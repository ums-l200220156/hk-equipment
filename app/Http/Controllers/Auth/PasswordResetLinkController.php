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

    // 1. Bersihkan input dari karakter non-angka (spasi, strip, plus)
    $input = preg_replace('/[^0-9]/', '', $request->phone);

    // 2. Normalisasi input agar kita punya dua versi (versi 08 dan versi 62)
    // Ini gunanya supaya kalau di DB isinya 08 tapi user ngetik 62, tetep ketemu.
    if (str_starts_with($input, '62')) {
        $phoneWith62 = $input;
        $phoneWith0 = '0' . substr($input, 2);
    } elseif (str_starts_with($input, '0')) {
        $phoneWith0 = $input;
        $phoneWith62 = '62' . substr($input, 1);
    } else {
        // Jika user ngetik 89... (langsung angka 8)
        $phoneWith0 = '0' . $input;
        $phoneWith62 = '62' . $input;
    }

    // 3. Cari user di database (Cek kedua versi tersebut)
    $user = User::where('phone', $phoneWith0)
                ->orWhere('phone', $phoneWith62)
                ->first();

    if (!$user) {
        return back()->withInput()->withErrors(['phone' => 'Nomor WhatsApp tidak terdaftar.']);
    }

    // 4. Generate & Simpan OTP ke DB
    $otpCode = rand(100000, 999999);
    DB::table('user_otps')->updateOrInsert(
        ['user_id' => $user->id],
        [
            'otp' => $otpCode,
            'expire_at' => now()->addMinutes(5),
            'updated_at' => now(),
        ]
    );

    // 5. KIRIM KE FONNTE (Fonnte WAJIB 62 agar tidak "Invalid Target")
    $response = Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN'),
    ])->post('https://api.fonnte.com/send', [
        'target' => $phoneWith62, // Apapun inputnya, kita kirim ke Fonnte versi 62
        'message' => "KODE OTP HK EQUIPMENT: *{$otpCode}*.\n\nRahasiakan kode ini. Berlaku 5 menit.",
        'countryCode' => '62',
    ]);

    return redirect()->route('password.otp.view', ['phone' => $input])
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