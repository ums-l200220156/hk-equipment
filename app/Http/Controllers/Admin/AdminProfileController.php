<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
    $user = Auth::user();

    $request->validate([
        'name'  => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'phone' => ['nullable', 'string', 'max:20'], // Pastikan validasi phone ada
        'password' => ['nullable', 'confirmed', Password::defaults()],
    ]);

    $data = [
        'name'  => $request->name,
        'email' => $request->email,
        'phone' => $request->phone, // Ini akan menyimpan nomor HP baru ke database
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}