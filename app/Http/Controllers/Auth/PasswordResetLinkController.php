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

    /**
     * Handle an incoming password reset link request (WHATSAPP).
     */
    public function storeWA(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:10'],
        ]);

        // 1. Bersihkan input dari karakter non-angka
        $input = preg_replace('/[^0-9]/', '', $request->phone);

        // 2. Normalisasi input untuk pencarian Database (08 vs 62)
        $phoneWith0 = $input;
        $phoneWith62 = $input;

        if (str_starts_with($input, '62')) {
            $phoneWith0 = '0' . substr($input, 2);
        } elseif (str_starts_with($input, '0')) {
            $phoneWith62 = '62' . substr($input, 1);
        } else {
            $phoneWith0 = '0' . $input;
            $phoneWith62 = '62' . $input;
        }

        // 3. Cari user di database menggunakan kedua versi nomor
        $user = User::where('phone', $phoneWith0)
                    ->orWhere('phone', $phoneWith62)
                    ->first();

        if (!$user) {
            return back()->withInput()->withErrors(['phone' => 'Nomor WhatsApp tidak terdaftar.']);
        }

        // 4. Generate & Simpan OTP ke DB (Berlaku 5 menit)
        $otpCode = rand(100000, 999999);
        DB::table('user_otps')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'otp' => $otpCode,
                'expire_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. KIRIM KE FONNTE (Wajib 62 agar tidak Invalid Target)
        try {
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $phoneWith62,
                'message' => "KODE OTP HK EQUIPMENT: *{$otpCode}*.\n\nRahasiakan kode ini. Berlaku 5 menit.\n(Ref: ".time().")",
                'countryCode' => '62',
            ]);
        } catch (\Exception $e) {
            \Log::error("Fonnte Error: " . $e->getMessage());
        }

        // 6. Redirect ke halaman verifikasi OTP dengan membawa input asli user
        return redirect()->route('password.otp.view', ['phone' => $request->phone])
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

        // 1. Bersihkan input nomor
        $input = preg_replace('/[^0-9]/', '', $request->phone);

        // 2. Normalisasi pencarian (sama seperti storeWA agar sinkron)
        $phoneWith0 = $input;
        $phoneWith62 = $input;

        if (str_starts_with($input, '62')) {
            $phoneWith0 = '0' . substr($input, 2);
        } elseif (str_starts_with($input, '0')) {
            $phoneWith62 = '62' . substr($input, 1);
        } else {
            $phoneWith0 = '0' . $input;
            $phoneWith62 = '62' . $input;
        }

        // 3. Cari user di database
        $user = User::where('phone', $phoneWith0)
                    ->orWhere('phone', $phoneWith62)
                    ->first();

        if (!$user) {
            return redirect()->route('password.request')
                             ->withErrors(['phone' => 'Sesi berakhir atau user tidak ditemukan.']);
        }

        // 4. Cek kode OTP di database (pastikan belum expire)
        $otpData = DB::table('user_otps')
                    ->where('user_id', $user->id)
                    ->where('otp', $request->otp)
                    ->where('expire_at', '>', now())
                    ->first();

        if (!$otpData) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluwarsa.']);
        }

        // 5. Jika benar, hapus OTP agar tidak bisa dipakai lagi
        DB::table('user_otps')->where('user_id', $user->id)->delete();

        // 6. Redirect ke halaman ganti password standar Laravel
        return redirect()->route('password.reset', [
            'token' => 'wa-verification-success', 
            'email' => $user->email
        ])->with('status', 'Verifikasi berhasil. Silakan atur password baru Anda.');
    }
}