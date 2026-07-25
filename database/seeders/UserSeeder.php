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
            ['email' => 'admin@bps.go.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        // 2. ASN
        $asnUser = User::firstOrCreate(
            ['email' => 'asn@bps.go.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'asn',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        AsnProfile::firstOrCreate(
            ['user_id' => $asnUser->id],
            [
                'nip' => '198501012010011001',
                'jabatan' => 'Statistisi Ahli Muda',
                'unit_kerja' => 'BPS Kabupaten Madiun',
            ],
        );

        // 3. Mahasiswa
        $mahasiswaUser = User::firstOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Andi Wijaya',
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
                'jenjang' => 'D4',
                'jurusan' => 'Teknik Informatika',
                'status' => 'aktif',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            ],
        );

        $this->command->info('3 user berhasil dibuat: admin@bps.go.id, asn@bps.go.id, mahasiswa@example.com (password: password123)');
    }
}
