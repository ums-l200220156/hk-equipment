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

        // 1. Normalisasi nomor: hilangkan karakter non-angka
        $inputPhone = preg_replace('/[^0-9]/', '', $request->phone);

        // 2. Cari user. Kita coba cari yang cocok dengan input user
        $user = User::where('phone', $inputPhone)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['phone' => 'Nomor WhatsApp tidak terdaftar di sistem kami.']);
        }

        // 3. Generate OTP 6 Digit
        $otpCode = rand(100000, 999999);

        // 4. Simpan/Update OTP ke database
        DB::table('user_otps')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'otp' => $otpCode,
                'expire_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. PROSES KIRIM WA REAL (FONNTE)
        try {
            // Pastikan format nomor diawali 62 untuk Fonnte
            $targetPhone = $inputPhone;
            if (str_starts_with($targetPhone, '0')) {
                $targetPhone = '62' . substr($targetPhone, 1);
            }

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'), // Mengambil token dari .env
            ])->post('https://api.fonnte.com/send', [
                'target' => $targetPhone,
                'message' => "KODE OTP HK EQUIPMENT: *{$otpCode}*.\n\nRahasiakan kode ini. Berlaku 5 menit.",
            ]);

            // Log untuk debug jika gagal di server Fonnte
            if ($response->failed()) {
                \Log::error("Fonnte Error: " . $response->body());
            }

        } catch (\Exception $e) {
            \Log::error("WA Gateway Error: " . $e->getMessage());
        }

        // 6. Redirect ke halaman verifikasi OTP
        return redirect()->route('password.otp.view', ['phone' => $inputPhone])
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