<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class PasswordResetController extends Controller
{
    /**
     * Menampilkan halaman input email untuk permintaan reset password dari halaman login.
     */
    public function showForgotForm(): View
    {
        return view('login.forgot-password');
    }

    /**
     * Langkah 1 - Mengirim tautan reset password ke email pengguna (semua role).
     * Mendukung permintaan dari halaman Lupa Password (guest) maupun dari halaman Profil (auth).
     */
    public function sendLink(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $email = $request->user()->email;
        } else {
            $validated = $request->validate(
                [
                    'email' => 'required|email',
                ],
                [
                    'email.required' => 'Alamat email wajib diisi.',
                    'email.email' => 'Format alamat email tidak valid.',
                ]
            );

            $email = $validated['email'];
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun dengan alamat email tersebut tidak ditemukan.']);
        }

        if (! $user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda berstatus nonaktif. Silakan hubungi Admin untuk mengaktifkan akun terlebih dahulu.']);
        }

        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (Throwable $e) {
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage());

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email gagal dikirim. Periksa pengaturan MAIL Mailtrap di file .env, lalu coba lagi.');
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Tautan untuk membuat password baru telah dikirim ke ' . $user->email . '. Silakan cek inbox Mailtrap Anda.');
        }

        $pesan = match ($status) {
            Password::RESET_THROTTLED => 'Permintaan terlalu sering. Tunggu sekitar 1 menit sebelum meminta tautan baru.',
            default => 'Tautan reset tidak dapat dikirim. Silakan coba lagi.',
        };

        return back()
            ->withInput($request->only('email'))
            ->with('error', $pesan);
    }

    /**
     * Langkah 2 - Halaman yang dibuka dari tautan di email.
     */
    public function showResetForm(Request $request, string $token): View|RedirectResponse
    {
        $email = (string) $request->query('email', '');
        $user = User::where('email', $email)->first();

        if (! $user || $user->email !== $email || ! Password::tokenExists($user, $token)) {
            return redirect()
                ->route('login-form')
                ->with('error', 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan minta tautan reset password baru.');
        }

        return view('login.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Langkah 3 - Simpan password baru + konfirmasi.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'token' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|min:8|confirmed',
            ],
            [
                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password baru minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sama dengan password baru.',
            ],
        );

        $email = (string) $request->input('email', '');
        $targetUser = User::where('email', $email)->first();

        if (! $targetUser || $targetUser->email !== $email) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun dengan email tersebut tidak ditemukan.']);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            $pesan = match ($status) {
                Password::INVALID_TOKEN => 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru.',
                Password::INVALID_USER => 'Akun dengan email tersebut tidak ditemukan.',
                Password::RESET_THROTTLED => 'Terlalu banyak percobaan. Coba lagi beberapa saat lagi.',
                default => 'Password gagal diubah. Silakan coba lagi.',
            };

            return back()->withInput($request->only('email'))->withErrors(['email' => $pesan]);
        }

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()
            ->route('login-form')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}