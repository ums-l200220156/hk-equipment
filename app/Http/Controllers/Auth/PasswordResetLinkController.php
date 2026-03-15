<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request (EMAIL).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    public function storeWA(Request $request): RedirectResponse
{
    $request->validate([
        'phone' => ['required', 'string', 'min:10'],
    ]);

    // 1. Bersihkan input dari karakter aneh
    $input = preg_replace('/[^0-9]/', '', $request->phone);

    // 2. Buat dua versi nomor untuk pengecekan di Database
    $phoneWith0 = $input;
    $phoneWith62 = $input;

    if (str_starts_with($input, '0')) {
        $phoneWith62 = '62' . substr($input, 1);
    } elseif (str_starts_with($input, '62')) {
        $phoneWith0 = '0' . substr($input, 2);
    }

    // 3. Cari user (Cek versi 08 atau 62 di database)
    $user = User::where('phone', $phoneWith0)
                ->orWhere('phone', $phoneWith62)
                ->first();

    if (!$user) {
        return back()->withInput()->withErrors(['phone' => 'Nomor WhatsApp tidak terdaftar.']);
    }

    // 4. Generate & Simpan OTP
    $otpCode = rand(100000, 999999);
    DB::table('user_otps')->updateOrInsert(
        ['user_id' => $user->id],
        [
            'otp' => $otpCode,
            'expire_at' => now()->addMinutes(5),
            'updated_at' => now(),
        ]
    );

    // 5. KIRIM KE FONNTE (Wajib format 62)
    $targetFonnte = (str_starts_with($user->phone, '0')) 
                    ? '62' . substr($user->phone, 1) 
                    : $user->phone;

    $response = Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN'),
    ])->post('https://api.fonnte.com/send', [
        'target' => $targetFonnte,
        'message' => "KODE OTP HK EQUIPMENT: *{$otpCode}*.\n\nRahasiakan kode ini. Berlaku 5 menit.",
    ]);

    // Cek respon Fonnte di Log jika masih tidak masuk
    \Log::info("Respon Fonnte: " . $response->body());

    return redirect()->route('password.otp.view', ['phone' => $user->phone])
                     ->with('status', 'Kode OTP telah dikirim ke WhatsApp Anda.');
}
    /**
     * Verify the OTP provided by the user.
     */
    public function verifyOTP(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required|numeric',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['phone' => 'Terjadi kesalahan sistem.']);
        }

        // Cek kode OTP di database
        $otpData = DB::table('user_otps')
                    ->where('user_id', $user->id)
                    ->where('otp', $request->otp)
                    ->where('expire_at', '>', now())
                    ->first();

        if (!$otpData) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluwarsa.']);
        }

        // Jika benar, hapus OTP agar tidak bisa dipakai lagi
        DB::table('user_otps')->where('user_id', $user->id)->delete();

        // Redirect ke halaman ganti password baru dengan membawa token/phone
        // Kita gunakan email sebagai identifier standar Laravel
        return redirect()->route('password.reset', ['token' => 'wa-verification-success', 'email' => $user->email])
                        ->with('status', 'Verifikasi berhasil. Silakan atur password baru Anda.');
    }
}