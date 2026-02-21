<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    // 1. Validasi Input Lengkap
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'phone' => ['required', 'string', 'max:20'],
        'address' => ['required', 'string'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ], [
        // --- PESAN ERROR ---
        'email.lowercase' => 'Alamat email harus menggunakan huruf kecil semua.',
        'email.unique'    => 'Email ini sudah terdaftar di sistem kami.',
        'image.max'       => 'Ukuran foto maksimal adalah 2MB.',
        // -------------------------------------------
    ]);

    // 2. Logika Unggah Foto Profil
    $imageName = null;
    if ($request->hasFile('image')) {
        // Memberi nama unik berdasarkan waktu agar tidak bentrok
        $imageName = time() . '_' . $request->image->getClientOriginalName();
        // Simpan file ke folder public/uploads/users
        $request->image->move(public_path('uploads/users'), $imageName);
    }

    // 3. Simpan Data ke Database
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'image' => $imageName, // Simpan nama file foto
        'role' => 'customer',   // Default role
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk ke akun Anda.');
}

};