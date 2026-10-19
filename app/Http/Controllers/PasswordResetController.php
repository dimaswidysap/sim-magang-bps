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
     * Langkah 1 - dipanggil dari tombol "Ganti kata sandi akun" (setelah user
     * menekan "Tetap Lanjutkan" di modal). Mengirim tautan reset ke email
     * milik user yang sedang login.
     */
    public function sendLink(Request $request): RedirectResponse
    {
        $user = $request->user();

        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (Throwable $e) {
            // Biasanya: kredensial Mailtrap salah / SMTP tidak terjangkau.
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage());

            return back()->with('error', 'Email gagal dikirim. Periksa pengaturan MAIL di file .env, lalu coba lagi.');
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Tautan untuk membuat kata sandi baru telah dikirim ke ' . $user->email . '. Silakan cek email Anda.');
        }

        $pesan = match ($status) {
            Password::RESET_THROTTLED => 'Permintaan terlalu sering. Tunggu sekitar 1 menit sebelum meminta tautan baru.',
            default => 'Tautan reset tidak dapat dikirim. Silakan coba lagi.',
        };

        return back()->with('error', $pesan);
    }

    /**
     * Langkah 2 - halaman yang dibuka dari tautan di email.
     * Sengaja TANPA middleware 'guest': user biasanya masih login di browser
     * yang sama saat membuka tautan, dan 'guest' akan melemparnya ke dashboard.
     */
    public function showResetForm(Request $request, string $token): View|RedirectResponse
    {
        $email = (string) $request->query('email', '');
        $user = User::where('email', $email)->first();

        if (!$user || !Password::tokenExists($user, $token)) {
            return redirect()
                ->route('login-form')
                ->with('error', 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru dari halaman profil.');
        }

        return view('login.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Langkah 3 - simpan password baru + konfirmasi.
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

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                // Cast 'hashed' di model User otomatis meng-hash password.
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

        // Password sudah berubah -> akhiri sesi yang sedang berjalan (jika ada),
        // supaya user login ulang dengan password baru.
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
