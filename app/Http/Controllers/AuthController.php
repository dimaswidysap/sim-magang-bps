<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('login.index');
    }

    /**
     * Login pakai email untuk semua role (admin, mahasiswa, asn).
     * Role yang membedakan tujuan redirect setelah login berhasil.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak aktif. Hubungi admin.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-form');
    }

    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin-index'),
            'asn' => redirect()->route('asn-index'),       // ganti setelah route ASN dibuat
            'mahasiswa' => redirect()->route('mahasiswa-index'), // ganti setelah route mahasiswa dibuat
            default => redirect()->route('login-form'),
        };
    }
}
