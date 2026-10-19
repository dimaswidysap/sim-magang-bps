<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Email reset password dalam Bahasa Indonesia.
        // URL tujuan memakai route bernama 'password.reset' (lihat web.php).
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            $menit = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject('Reset Password Akun SIM Magang')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Kami menerima permintaan untuk mengganti kata sandi akun Anda.')
                ->line('Klik tombol di bawah, lalu masukkan password baru dan konfirmasi password pada halaman yang terbuka.')
                ->action('Buat Password Baru', $url)
                ->line("Tautan ini hanya berlaku selama {$menit} menit.")
                ->line('Jika Anda tidak merasa meminta penggantian password, abaikan email ini. Password Anda tidak akan berubah.')
                ->salutation('Salam, SIM Magang');
        });
    }
}
