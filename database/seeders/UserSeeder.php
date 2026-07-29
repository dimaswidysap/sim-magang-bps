<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AsnProfile;
use App\Models\MahasiswaProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // 1. Super Admin
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
        $asnUser2 = User::firstOrCreate(
            ['email' => 'wijiutami@gmail.com'],
            [
                'name' => 'Wiji Utami',
                'password' => Hash::make('password123'),
                'role' => 'asn',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        AsnProfile::firstOrCreate(
            ['user_id' => $asnUser2->id],
            [
                'nip' => '199002022015032002',
                'jabatan' => 'Statistisi Ahli Pertama',
                'unit_kerja' => 'BPS Kabupaten Madiun',
            ],
        );
        $asnUser3 = User::firstOrCreate(
            ['email' => 'Tarmadi@gmail.com'],
            [
                'name' => 'Tarmadi',
                'password' => Hash::make('password123'),
                'role' => 'asn',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        AsnProfile::firstOrCreate(
            ['user_id' => $asnUser3->id],
            [
                'nip' => '198803032012121003',
                'jabatan' => 'Pranata Komputer Ahli Muda',
                'unit_kerja' => 'BPS Kabupaten Madiun',
            ],
        );

        // 3. Mahasiswa
        $mahasiswaUser = User::firstOrCreate(
            ['email' => 'dimas@gmail.com'],
            [
                'name' => 'Dimas Widy Saputra',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        MahasiswaProfile::firstOrCreate(
            ['user_id' => $mahasiswaUser->id],
            [
                'nim' => '2305101038',
                'instansi_asal' => 'UNIVERSITAS PGRI MADIUN',
                'jenjang' => 'S1',
                'jurusan' => 'Teknik Informatika',
                'status' => 'aktif',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            ],
        );

        $mahasiswaUser2 = User::firstOrCreate(
            ['email' => 'ridho@gmail.com'],
            [
                'name' => 'Ridho',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        MahasiswaProfile::firstOrCreate(
            ['user_id' => $mahasiswaUser2->id],
            [
                'nim' => '2305101039',
                'instansi_asal' => 'UNIVERSITAS PGRI MADIUN',
                'jenjang' => 'S1',
                'jurusan' => 'Teknik Informatika',
                'status' => 'aktif',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            ],
        );

        // Mahasiswa - Zevina
        $mahasiswaUser3 = User::firstOrCreate(
            ['email' => 'zevina@gmail.com'],
            [
                'name' => 'Zevina',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        MahasiswaProfile::firstOrCreate(
            ['user_id' => $mahasiswaUser3->id],
            [
                'nim' => '2305101040',
                'instansi_asal' => 'UNIVERSITAS PGRI MADIUN',
                'jenjang' => 'S1',
                'jurusan' => 'Teknik Informatika',
                'status' => 'aktif',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            ],
        );

        // Mahasiswa - Gopal
        $mahasiswaUser4 = User::firstOrCreate(
            ['email' => 'gopal@gmail.com'],
            [
                'name' => 'Gopal',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        MahasiswaProfile::firstOrCreate(
            ['user_id' => $mahasiswaUser4->id],
            [
                'nim' => '2305101041',
                'instansi_asal' => 'UNIVERSITAS PGRI MADIUN',
                'jenjang' => 'S1',
                'jurusan' => 'Teknik Informatika',
                'status' => 'aktif',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            ],
        );
    }
}
