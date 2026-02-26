<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Notifikasi Berhasil Login
        $message = 'Selamat datang kembali, ' . $user->name . '!';

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard.index')->with('success', $message);
        }

        if ($user->role === 'customer') {
            return redirect()->route('customer.home')->with('success', $message);
        }

        return redirect('/')->with('success', $message);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke homepage dengan pesan logout
        return redirect('/')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}