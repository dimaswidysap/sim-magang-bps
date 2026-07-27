<?php

namespace Database\Seeders;

use App\Models\PeriodeMagang;
use Illuminate\Database\Seeder;

class PeriodeMagangSeeder extends Seeder
{
    /**
     * Pakai firstOrCreate berdasarkan nama_periode, aman dijalankan berkali-kali.
     * Status disesuaikan manual dengan tanggalnya - Laravel tidak otomatis
     * update status berdasarkan tanggal hari ini, jadi kalau nanti sudah
     * lewat tanggal_selesai, status 'berlangsung' harus di-update manual
     * (atau nanti dibuatkan scheduled command kalau perlu).
     */
    public function run(): void
    {
        $periodes = [
            [
                'nama_periode' => 'Periode Januari - Maret 2026',
                'tanggal_mulai' => '2026-01-05',
                'tanggal_selesai' => '2026-03-31',
                'kuota' => 15,
                'status' => 'selesai',
                'keterangan' => 'Periode magang semester genap 2025/2026.',
            ],
            [
                'nama_periode' => 'Periode April - Juni 2026',
                'tanggal_mulai' => '2026-04-01',
                'tanggal_selesai' => '2026-06-30',
                'kuota' => 20,
                'status' => 'selesai',
                'keterangan' => null,
            ],
            [
                'nama_periode' => 'Periode Juli - September 2026',
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2026-09-30',
                'kuota' => 25,
                'status' => 'berlangsung',
                'keterangan' => 'Periode magang semester ganjil 2026/2027 - sedang berjalan.',
            ],
            [
                'nama_periode' => 'Periode Oktober - Desember 2026',
                'tanggal_mulai' => '2026-10-01',
                'tanggal_selesai' => '2026-12-31',
                'kuota' => 20,
                'status' => 'akan_datang',
                'keterangan' => null,
            ],
        ];

        foreach ($periodes as $periode) {
            PeriodeMagang::firstOrCreate(
                ['nama_periode' => $periode['nama_periode']],
                $periode
            );
        }

        $this->command->info('4 periode magang berhasil dibuat.');
    }
}
