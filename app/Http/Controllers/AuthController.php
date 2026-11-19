<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('login.index');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(
            [
                'login' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'login.required' => 'Email atau nama wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        $throttleKey = Str::transliterate(Str::lower($credentials['login'])) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'login' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Cari user dengan pencocokan case-sensitive pada kolom email atau name
        $user = User::findForCaseSensitiveLogin($credentials['login'], $credentials['password']);

        if (!$user) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login' => 'Email/nama atau password salah.',
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'login' => 'Akun Anda tidak aktif. Hubungi admin.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user);
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
            'asn' => redirect()->route('asn-index'),
            'mahasiswa' => redirect()->route('mahasiswa-index'),
            default => redirect()->route('login-form'),
        };
    }
}
