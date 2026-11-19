<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Penerimaan Magang BPS</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: Arial, Helvetica, sans-serif; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb;">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1d4ed8; padding: 24px 32px; text-align: center;">
                            <p style="margin: 0 0 6px 0; font-size: 11px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #dbeafe;">
                                Badan Pusat Statistik (BPS)
                            </p>
                            <h1 style="margin: 0; font-size: 20px; font-weight: bold; color: #ffffff;">
                                Pemberitahuan Penerimaan Magang
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px; font-size: 14px; line-height: 1.65; color: #374151;">
                            <p style="margin: 0 0 16px 0;">
                                Yth. <strong>{{ $namaLengkap }}</strong>,
                            </p>

                            <p style="margin: 0 0 16px 0;">
                                <strong>Selamat!</strong> Anda dinyatakan <strong>DITERIMA</strong> untuk melaksanakan program Magang / Praktik Kerja Lapangan (PKL) di <strong>Badan Pusat Statistik (BPS)</strong>.
                            </p>

                            <p style="margin: 0 0 20px 0;">
                                Untuk mendukung administrasi, pengisian logbook harian, serta pemantauan kegiatan selama magang, silakan masuk (login) ke aplikasi <strong>SIM Magang</strong> menggunakan <strong>Nama Lengkap</strong> Anda (atau alamat email yang terdaftar) beserta <strong>Password</strong> berikut:
                            </p>

                            <!-- Kotak Kredensial Login (Hanya Nama Lengkap & Password) -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #1d4ed8;">
                                            Informasi Akun SIM Magang
                                        </p>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
                                            <tr>
                                                <td style="padding: 5px 0; width: 140px; color: #64748b;">Nama Lengkap</td>
                                                <td style="padding: 5px 0; width: 15px; color: #64748b;">:</td>
                                                <td style="padding: 5px 0; font-weight: bold; color: #0f172a;">{{ $namaLengkap }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; color: #64748b;">Password</td>
                                                <td style="padding: 5px 0; color: #64748b;">:</td>
                                                <td style="padding: 5px 0; font-family: 'Courier New', Courier, monospace; font-weight: bold; color: #0f172a; font-size: 15px;">
                                                    {{ $password }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tombol Login -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('login-form') }}"
                                            style="display: inline-block; background-color: #1d4ed8; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 14px; padding: 12px 28px; border-radius: 8px;">
                                            Masuk ke Aplikasi SIM Magang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 8px 0; font-size: 13px; color: #4b5563;">
                                <strong>Catatan Penting:</strong>
                            </p>
                            <ul style="margin: 0 0 20px 0; padding-left: 20px; font-size: 13px; color: #4b5563;">
                                <li style="margin-bottom: 6px;">Penulisan <strong>Nama Lengkap</strong> saat login memperhatikan huruf besar dan huruf kecil sesuai yang tercantum di atas.</li>
                                <li style="margin-bottom: 6px;">Setelah berhasil masuk, segera lengkapi biodata profil Anda dan ajukan pembimbing magang.</li>
                                <li>Demi keamanan akun, disarankan untuk segera mengganti password bawaan setelah login pertama kali.</li>
                            </ul>

                            <p style="margin: 0; font-size: 13px; color: #6b7280;">
                                Hormat kami,<br>
                                <strong style="color: #1f2937;">Admin SIM Magang — Badan Pusat Statistik (BPS)</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; text-align: center; font-size: 11px; color: #9ca3af;">
                            Email ini dikirim secara otomatis oleh sistem SIM Magang BPS. Mohon tidak membalas email ini.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
