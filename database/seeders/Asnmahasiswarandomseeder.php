<?php

namespace Database\Seeders;

use App\Models\AsnProfile;
use App\Models\MahasiswaProfile;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AsnMahasiswaRandomSeeder extends Seeder
{
    /**
     * Email dibuat deterministik (asn1@gmail.com, mahasiswa1@gmail.com, dst)
     * supaya dijamin unik tanpa risiko tabrakan dari random generator,
     * dan aman dijalankan berkali-kali (firstOrCreate).
     */
    public function run(): void
    {
        $this->seedAsn();
        $this->seedMahasiswa();
    }

    private function seedAsn(): void
    {
        $jabatanList = ['Statistisi Ahli Pertama', 'Statistisi Ahli Muda', 'Statistisi Ahli Madya', 'Analis Data', 'Pranata Komputer'];
        $unitKerjaList = ['BPS Kabupaten Madiun', 'BPS Kota Madiun', 'BPS Provinsi Jawa Timur', 'BPS Kabupaten Ponorogo', 'BPS Kabupaten Magetan'];

        for ($i = 1; $i <= 5; $i++) {
            $nama = fake('id_ID')->name();
            $email = "asn{$i}@gmail.com";

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => Hash::make('password123'),
                    'role' => 'asn',
                    'phone' => fake('id_ID')->phoneNumber(),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            AsnProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => fake()->unique()->numerify('19########0#1##1'), // format mirip NIP 18 digit
                    'jabatan' => fake()->randomElement($jabatanList),
                    'unit_kerja' => fake()->randomElement($unitKerjaList),
                ]
            );
        }

        $this->command->info('5 ASN berhasil dibuat.');
    }

    private function seedMahasiswa(): void
    {
        $instansiList = [
            'Universitas PGRI Madiun', 'Politeknik Negeri Malang', 'Universitas Brawijaya',
            'Universitas Negeri Malang', 'Institut Teknologi Sepuluh Nopember',
            'SMK Negeri 1 Madiun', 'Universitas Airlangga',
        ];
        $jurusanList = ['Teknik Informatika', 'Sistem Informasi', 'Statistika', 'Ilmu Komputer', 'Manajemen Informatika'];
        $jenjangList = ['SMA/SMK', 'D3', 'D4', 'S1'];

        $periodeIds = PeriodeMagang::pluck('id');

        for ($i = 1; $i <= 10; $i++) {
            $nama = fake('id_ID')->name();
            $email = "mahasiswa{$i}@gmail.com";

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => Hash::make('password123'),
                    'role' => 'mahasiswa',
                    'phone' => fake('id_ID')->phoneNumber(),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $tanggalMulai = fake()->dateTimeBetween('-1 month', 'now');
            $tanggalSelesai = (clone $tanggalMulai)->modify('+3 months');

            MahasiswaProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'periode_magang_id' => $periodeIds->isNotEmpty() ? $periodeIds->random() : null,
                    'nim' => fake()->unique()->numerify('##########'), // 10 digit
                    'instansi_asal' => fake()->randomElement($instansiList),
                    'jenjang' => fake()->randomElement($jenjangList),
                    'jurusan' => fake()->randomElement($jurusanList),
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'status' => 'aktif',
                ]
            );
        }

        $this->command->info('10 mahasiswa berhasil dibuat.');
    }
}
