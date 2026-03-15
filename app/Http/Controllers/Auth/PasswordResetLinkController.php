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
        ], [
            'phone.required' => 'Nomor WhatsApp wajib diisi',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['phone' => 'Nomor WhatsApp tidak terdaftar.']);
        }

        // 1. Generate OTP 6 Digit
        $otpCode = rand(100000, 999999);

        // 2. Simpan/Update OTP ke database (berlaku 5 menit)
        DB::table('user_otps')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'otp' => $otpCode,
                'expire_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Integrasi WA Gateway (Simulasi atau Real API)
        // Jika pakai Fonnte, un-comment kode di bawah ini:
        /*
        Http::withHeaders([
            'Authorization' => 'YOUR_FONNTE_TOKEN',
        ])->post('https://api.fonnte.com/send', [
            'target' => $request->phone,
            'message' => "KODE OTP HK SYSTEM: *{$otpCode}*. Rahasiakan kode ini. Berlaku 5 menit.",
        ]);
        */

        // Log kode ke laravel.log agar Cah Bagus bisa testing tanpa API WA
       \Log::info("OTP WA HK SYSTEM untuk {$phone}: {$otpCode}");

        // 4. Redirect ke halaman verifikasi OTP
        return redirect()->route('password.otp.view', ['phone' => $phone])
                        ->with('status', 'Kode OTP telah dikirim ke nomor WhatsApp Anda.');
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