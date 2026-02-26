<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        /* =========================================
           AMBIL DATA
        ========================================= */
        $data = $request->only([
            'name',
            'email',
            'phone',
            'address'
        ]);

        /* =========================================
           HANDLE IMAGE UPLOAD
        ========================================= */
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $request->validate([
                'image' => 'image|mimes:jpg,jpeg,png|max:2048'
            ]);

            // HAPUS FILE LAMA
            if ($user->image && File::exists(public_path('uploads/users/' . $user->image))) {
                File::delete(public_path('uploads/users/' . $user->image));
            }

            // SIMPAN BARU
            $filename = time() . '_' . uniqid() . '.jpg';
            $file->move(public_path('uploads/users'), $filename);

            $data['image'] = $filename;
        }

        /* =========================================
           UPDATE USER
        ========================================= */
        $user->update($data);

        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
        }

        return Redirect::route('customer.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            // 🔥 CUSTOM ERROR MESSAGE
            'current_password.required' => 'Password saat ini wajib diisi',
            'current_password.current_password' => 'Password saat ini tidak sesuai',

            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru',
        ], [
            // 🔥 NAMA FIELD BIAR HUMAN FRIENDLY
            'current_password' => 'Password saat ini',
            'password' => 'Password baru',
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with([
            'success' => 'Password berhasil diperbarui!',
            'type' => 'password'
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Password wajib diisi untuk menghapus akun',
            'password.current_password' => 'Password yang dimasukkan salah',
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')
            ->with('success', 'Akun berhasil dihapus secara permanen');
    }
}