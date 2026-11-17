<?php

namespace Database\Seeders;

use App\Models\AsnProfile;
use App\Models\Berita;
use App\Models\Logbook;
use App\Models\MagangLogbook;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\Tugas;
use App\Models\TugasAnggota;
use App\Models\TugasSubmission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KPSeeder extends Seeder
{
    /**
     * Simulasi 1 tahun operasional SIM Magang untuk kebutuhan Seminar KP.
     * - Tidak menambah akun Admin dan ASN baru (menggunakan 3 Admin & 5 ASN yang sudah ada).
     * - Mengisi 16 peserta magang dalam 4 gelombang (1 tahun terakhir) beserta tugas, tim, submission, logbook, dan berita.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $defaultPassword = '12345678';
            $today = Carbon::today();

            // =========================================================================
            // 1. MASTER DATA SKILLS
            // =========================================================================
            $daftarSkill = [
                'Pengolahan Data Excel',
                'Analisis Statistik',
                'Python & Pandas',
                'Pengembangan Web Laravel',
                'Desain Grafis & Infografis',
                'Manajemen Basis Data SQL',
                'Visualisasi Data',
                'Administrasi Perkantoran',
            ];

            $skillMap = [];
            foreach ($daftarSkill as $namaSkill) {
                $skill = Skill::firstOrCreate(['nama_skill' => $namaSkill]);
                $skillMap[$namaSkill] = $skill->id;
            }

            // =========================================================================
            // 2. AKUN ADMIN (3 AKUN EKSISTING — TIDAK DITAMBAH)
            // =========================================================================
            $admins = [
                [
                    'name' => '1 Ahmad Fauzi, S.Kom.',
                    'email' => 'admin1@gmail.com',
                    'phone' => '081234567001',
                ],
                [
                    'name' => '2 Rina Kartika, S.E.',
                    'email' => 'admin2@gmail.com',
                    'phone' => '081234567002',
                ],
                [
                    'name' => '3 Dimas Prasetyo, S.Tr.Stat.',
                    'email' => 'admin3@gmail.com',
                    'phone' => '081234567003',
                ],
            ];

            $adminUsers = [];
            foreach ($admins as $idx => $admin) {
                $adminUsers[$idx + 1] = User::updateOrCreate(
                    ['email' => $admin['email']],
                    [
                        'name' => $admin['name'],
                        'email_verified_at' => $today->copy()->subYear(),
                        'password' => $defaultPassword,
                        'role' => 'admin',
                        'phone' => $admin['phone'],
                        'avatar' => null,
                        'is_active' => true,
                    ]
                );
            }

            // =========================================================================
            // 3. AKUN ASN PEMBIMBING (5 AKUN EKSISTING — TIDAK DITAMBAH)
            // =========================================================================
            $asns = [
                1 => [
                    'name' => '1 Budi Santoso, S.Si., M.Stat.',
                    'email' => 'asn1@gmail.com',
                    'phone' => '081234567101',
                    'profile' => [
                        'nip' => '198504122010011001',
                        'jabatan' => 'Statistisi Ahli Muda',
                        'unit_kerja' => 'Fungsi Statistik Sosial',
                        'tanggal_lahir' => '1985-04-12',
                        'alamat' => 'Jl. Pahlawan No. 14, Kel. Kartoharjo, Kota Madiun',
                    ],
                ],
                2 => [
                    'name' => '2 Siti Rahmawati, S.Kom., M.T.',
                    'email' => 'asn2@gmail.com',
                    'phone' => '081234567102',
                    'profile' => [
                        'nip' => '198809232012122002',
                        'jabatan' => 'Pranata Komputer Ahli Muda',
                        'unit_kerja' => 'Fungsi Integrasi Pengolahan dan Diseminasi Statistik',
                        'tanggal_lahir' => '1988-09-23',
                        'alamat' => 'Jl. Diponegoro No. 45, Kel. Oro-Oro Ombo, Kota Madiun',
                    ],
                ],
                3 => [
                    'name' => '3 Hendra Wijaya, S.E., M.M.',
                    'email' => 'asn3@gmail.com',
                    'phone' => '081234567103',
                    'profile' => [
                        'nip' => '198302152008011003',
                        'jabatan' => 'Analis Perencanaan dan Anggaran',
                        'unit_kerja' => 'Subbagian Umum',
                        'tanggal_lahir' => '1983-02-15',
                        'alamat' => 'Jl. Cokroaminoto No. 88, Kel. Kejuron, Kota Madiun',
                    ],
                ],
                4 => [
                    'name' => '4 Dewi Lestari, S.Tr.Stat.',
                    'email' => 'asn4@gmail.com',
                    'phone' => '081234567104',
                    'profile' => [
                        'nip' => '199207102015032004',
                        'jabatan' => 'Statistisi Ahli Pertama',
                        'unit_kerja' => 'Fungsi Statistik Produksi dan Distribusi',
                        'tanggal_lahir' => '1992-07-10',
                        'alamat' => 'Jl. Dr. Sutomo No. 27, Kel. Kartoharjo, Kota Madiun',
                    ],
                ],
                5 => [
                    'name' => '5 Eko Saputra, S.ST.',
                    'email' => 'asn5@gmail.com',
                    'phone' => '081234567105',
                    'profile' => [
                        'nip' => '199011052014021005',
                        'jabatan' => 'Pranata Komputer Ahli Pertama',
                        'unit_kerja' => 'Fungsi Neraca Wilayah dan Analisis Statistik',
                        'tanggal_lahir' => '1990-11-05',
                        'alamat' => 'Jl. Agus Salim No. 61, Kel. Nambangan Lor, Kota Madiun',
                    ],
                ],
            ];

            $asnUsers = [];
            $asnProfiles = [];

            foreach ($asns as $key => $asn) {
                $userAsn = User::updateOrCreate(
                    ['email' => $asn['email']],
                    [
                        'name' => $asn['name'],
                        'email_verified_at' => $today->copy()->subYear(),
                        'password' => $defaultPassword,
                        'role' => 'asn',
                        'phone' => $asn['phone'],
                        'avatar' => null,
                        'is_active' => true,
                    ]
                );

                $asnProfile = AsnProfile::updateOrCreate(
                    ['user_id' => $userAsn->id],
                    [
                        'nip' => $asn['profile']['nip'],
                        'jabatan' => $asn['profile']['jabatan'],
                        'unit_kerja' => $asn['profile']['unit_kerja'],
                        'tanggal_lahir' => $asn['profile']['tanggal_lahir'],
                        'alamat' => $asn['profile']['alamat'],
                        'avatar' => null,
                    ]
                );

                $asnUsers[$key] = $userAsn;
                $asnProfiles[$key] = $asnProfile;
            }

            // =========================================================================
            // 4. DATA PESERTA MAGANG 1 TAHUN (4 GELOMBANG: ALUMNI, AKTIF, PENDING, BATAL)
            // =========================================================================
            $gelombang1Mulai = $today->copy()->subMonths(12)->startOfMonth();
            $gelombang1Selesai = $today->copy()->subMonths(9)->endOfMonth();

            $gelombang2Mulai = $today->copy()->subMonths(8)->startOfMonth();
            $gelombang2Selesai = $today->copy()->subMonths(5)->endOfMonth();

            $gelombang3Mulai = $today->copy()->subMonths(4)->startOfMonth();
            $gelombang3Selesai = $today->copy()->subMonth()->endOfMonth();

            $gelombang4Mulai = $today->copy()->subDays(42);
            $gelombang4Selesai = $today->copy()->addDays(48);

            $pesertaMagangData = [
                // --- GELOMBANG 1 (12 - 9 Bulan Lalu | Status: Selesai) ---
                'magang1' => [
                    'name' => '1 Nabila Putri Maharani',
                    'email' => 'magang1@gmail.com',
                    'phone' => '085712345201',
                    'asn_key' => 1,
                    'created_at' => $gelombang1Mulai->copy()->subDays(7),
                    'profile' => [
                        'nim' => '164221015',
                        'instansi_asal' => 'Universitas Airlangga',
                        'jenjang' => 'S1',
                        'jurusan' => 'Teknologi Sains Data',
                        'tanggal_lahir' => '2004-03-18',
                        'tanggal_mulai' => $gelombang1Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang1Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Mulyorejo Tengah No. 19, Kota Surabaya',
                        'catatan' => 'Alumni Gelombang 1: Menyelesaikan pengolahan data Susenas dan visualisasi indikator kemiskinan.',
                    ],
                    'skills' => ['Python & Pandas', 'Analisis Statistik', 'Visualisasi Data'],
                ],
                'magang2' => [
                    'name' => '2 Muhammad Rizky Pratama',
                    'email' => 'magang2@gmail.com',
                    'phone' => '085712345202',
                    'asn_key' => 2,
                    'created_at' => $gelombang1Mulai->copy()->subDays(6),
                    'profile' => [
                        'nim' => '2241720048',
                        'instansi_asal' => 'Politeknik Negeri Malang',
                        'jenjang' => 'D4',
                        'jurusan' => 'Teknik Informatika',
                        'tanggal_lahir' => '2004-07-24',
                        'tanggal_mulai' => $gelombang1Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang1Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Soekarno Hatta No. 102, Kota Malang',
                        'catatan' => 'Alumni Gelombang 1: Membangun dashboard monitoring pengolahan survei berbasis Laravel.',
                    ],
                    'skills' => ['Pengembangan Web Laravel', 'Manajemen Basis Data SQL', 'Python & Pandas'],
                ],
                'magang3' => [
                    'name' => '3 Aulia Rahma Azzahra',
                    'email' => 'magang3@gmail.com',
                    'phone' => '085712345203',
                    'asn_key' => 4,
                    'created_at' => $gelombang1Mulai->copy()->subDays(5),
                    'profile' => [
                        'nim' => '5003221089',
                        'instansi_asal' => 'Institut Teknologi Sepuluh Nopember',
                        'jenjang' => 'S1',
                        'jurusan' => 'Statistika',
                        'tanggal_lahir' => '2004-11-09',
                        'tanggal_mulai' => $gelombang1Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang1Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Keputih Tegal Timur No. 31, Kota Surabaya',
                        'catatan' => 'Alumni Gelombang 1: Bertugas pada tabulasi statistik industri dan infografis harga produsen.',
                    ],
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel', 'Desain Grafis & Infografis'],
                ],

                // --- GELOMBANG 2 (8 - 5 Bulan Lalu | Status: Selesai) ---
                'magang4' => [
                    'name' => '4 Fajar Nugroho',
                    'email' => 'magang4@gmail.com',
                    'phone' => '085712345204',
                    'asn_key' => 3,
                    'created_at' => $gelombang2Mulai->copy()->subDays(8),
                    'profile' => [
                        'nim' => '233140701111032',
                        'instansi_asal' => 'Universitas Brawijaya',
                        'jenjang' => 'D3',
                        'jurusan' => 'Sistem Informasi',
                        'tanggal_lahir' => '2005-01-30',
                        'tanggal_mulai' => $gelombang2Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang2Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Veteran Dalam No. 12, Kota Malang',
                        'catatan' => 'Alumni Gelombang 2: Digitalisasi arsip surat menyurat dan inventarisasi BMN Subbagian Umum.',
                    ],
                    'skills' => ['Manajemen Basis Data SQL', 'Pengolahan Data Excel', 'Administrasi Perkantoran'],
                ],
                'magang5' => [
                    'name' => '5 Salsabila Khairunnisa',
                    'email' => 'magang5@gmail.com',
                    'phone' => '085712345205',
                    'asn_key' => 5,
                    'created_at' => $gelombang2Mulai->copy()->subDays(6),
                    'profile' => [
                        'nim' => '24105012',
                        'instansi_asal' => 'SMKN 1 Madiun',
                        'jenjang' => 'SMA/SMK',
                        'jurusan' => 'Rekayasa Perangkat Lunak',
                        'tanggal_lahir' => '2008-05-14',
                        'tanggal_mulai' => $gelombang2Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang2Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Thamrin No. 55, Kel. Klegen, Kota Madiun',
                        'catatan' => 'Alumni PKL Gelombang 2: Desain publikasi PDRB dan kompilasi tabel direktori.',
                    ],
                    'skills' => ['Desain Grafis & Infografis', 'Administrasi Perkantoran', 'Pengolahan Data Excel'],
                ],
                'magang6' => [
                    'name' => '6 Dimas Arya Saputra',
                    'email' => 'magang6@gmail.com',
                    'phone' => '085712345206',
                    'asn_key' => 5,
                    'created_at' => $gelombang2Mulai->copy()->subDays(5),
                    'profile' => [
                        'nim' => '220403010045',
                        'instansi_asal' => 'Universitas Negeri Surabaya',
                        'jenjang' => 'S1',
                        'jurusan' => 'Ilmu Ekonomi',
                        'tanggal_lahir' => '2004-02-11',
                        'tanggal_mulai' => $gelombang2Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang2Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Ketintang Baru No. 40, Kota Surabaya',
                        'catatan' => 'Alumni Gelombang 2: Penyusunan analisis pertumbuhan ekonomi triwulanan Kota Madiun.',
                    ],
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel', 'Visualisasi Data'],
                ],

                // --- GELOMBANG 3 (4 - 1 Bulan Lalu | Status: Selesai) ---
                'magang7' => [
                    'name' => '7 Citra Dewi Anggraini',
                    'email' => 'magang7@gmail.com',
                    'phone' => '085712345207',
                    'asn_key' => 1,
                    'created_at' => $gelombang3Mulai->copy()->subDays(6),
                    'profile' => [
                        'nim' => '222212589',
                        'instansi_asal' => 'Politeknik Statistika STIS',
                        'jenjang' => 'D4',
                        'jurusan' => 'Statistika Terapan',
                        'tanggal_lahir' => '2004-08-19',
                        'tanggal_mulai' => $gelombang3Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang3Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Otto Iskandardinata No. 64C, Jakarta Timur',
                        'catatan' => 'Alumni Gelombang 3: Evaluasi kualitas data Sakernas dan penyusunan profil ketenagakerjaan.',
                    ],
                    'skills' => ['Analisis Statistik', 'Python & Pandas', 'Visualisasi Data'],
                ],
                'magang8' => [
                    'name' => '8 Rafi Akbar Maulana',
                    'email' => 'magang8@gmail.com',
                    'phone' => '085712345208',
                    'asn_key' => 2,
                    'created_at' => $gelombang3Mulai->copy()->subDays(4),
                    'profile' => [
                        'nim' => '22081010142',
                        'instansi_asal' => 'UPN Veteran Jawa Timur',
                        'jenjang' => 'S1',
                        'jurusan' => 'Informatika',
                        'tanggal_lahir' => '2004-06-27',
                        'tanggal_mulai' => $gelombang3Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang3Selesai->toDateString(),
                        'status' => 'selesai',
                        'alamat' => 'Jl. Rungkut Madya No. 78, Kota Surabaya',
                        'catatan' => 'Alumni Gelombang 3: Optimasi query basis data direktori wilayah kerja statistik (Wilkerstat).',
                    ],
                    'skills' => ['Manajemen Basis Data SQL', 'Pengembangan Web Laravel', 'Python & Pandas'],
                ],

                // --- GELOMBANG 4 (Berjalan Saat Ini | Status: Aktif — 1 Peserta per Pembimbing ASN) ---
                'magang9' => [
                    'name' => '9 Anisa Fitriani',
                    'email' => 'magang9@gmail.com',
                    'phone' => '085712345209',
                    'asn_key' => 1,
                    'created_at' => $gelombang4Mulai->copy()->subDays(5),
                    'profile' => [
                        'nim' => '235090501111018',
                        'instansi_asal' => 'Universitas Brawijaya',
                        'jenjang' => 'S1',
                        'jurusan' => 'Statistika',
                        'tanggal_lahir' => '2005-04-03',
                        'tanggal_mulai' => $gelombang4Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang4Selesai->toDateString(),
                        'status' => 'aktif',
                        'alamat' => 'Jl. MT Haryono No. 167, Kota Malang',
                        'catatan' => 'Peserta aktif pada Fungsi Statistik Sosial (anomalicheck & tabulasi Indeks Pembangunan Manusia).',
                    ],
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel', 'Visualisasi Data'],
                ],
                'magang10' => [
                    'name' => '10 Bagas Setiawan',
                    'email' => 'magang10@gmail.com',
                    'phone' => '085712345210',
                    'asn_key' => 2,
                    'created_at' => $gelombang4Mulai->copy()->subDays(5),
                    'profile' => [
                        'nim' => '233307041',
                        'instansi_asal' => 'Politeknik Negeri Madiun',
                        'jenjang' => 'D4',
                        'jurusan' => 'Teknologi Rekayasa Perangkat Lunak',
                        'tanggal_lahir' => '2005-02-14',
                        'tanggal_mulai' => $gelombang4Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang4Selesai->toDateString(),
                        'status' => 'aktif',
                        'alamat' => 'Jl. Serayu No. 84, Kel. Pandean, Kota Madiun',
                        'catatan' => 'Peserta aktif pada Fungsi IPDS (pengembangan portal diseminasi data & otomatisasi script Python).',
                    ],
                    'skills' => ['Pengembangan Web Laravel', 'Python & Pandas', 'Manajemen Basis Data SQL'],
                ],
                'magang11' => [
                    'name' => '11 Nadia Kusuma Wardani',
                    'email' => 'magang11@gmail.com',
                    'phone' => '085712345211',
                    'asn_key' => 3,
                    'created_at' => $gelombang4Mulai->copy()->subDays(4),
                    'profile' => [
                        'nim' => '233101019',
                        'instansi_asal' => 'Politeknik Negeri Madiun',
                        'jenjang' => 'D3',
                        'jurusan' => 'Administrasi Bisnis',
                        'tanggal_lahir' => '2005-09-21',
                        'tanggal_mulai' => $gelombang4Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang4Selesai->toDateString(),
                        'status' => 'aktif',
                        'alamat' => 'Jl. Bali No. 23, Kel. Kartoharjo, Kota Madiun',
                        'catatan' => 'Peserta aktif pada Subbagian Umum (rekapitulasi SPJ, surat masuk/keluar, dan laporan bulanan).',
                    ],
                    'skills' => ['Administrasi Perkantoran', 'Pengolahan Data Excel'],
                ],
                'magang12' => [
                    'name' => '12 Hafizh Ramadhan',
                    'email' => 'magang12@gmail.com',
                    'phone' => '085712345212',
                    'asn_key' => 4,
                    'created_at' => $gelombang4Mulai->copy()->subDays(4),
                    'profile' => [
                        'nim' => 'H0823054',
                        'instansi_asal' => 'Universitas Sebelas Maret',
                        'jenjang' => 'S1',
                        'jurusan' => 'Agribisnis',
                        'tanggal_lahir' => '2004-12-05',
                        'tanggal_mulai' => $gelombang4Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang4Selesai->toDateString(),
                        'status' => 'aktif',
                        'alamat' => 'Jl. Ir. Sutami No. 36A, Kota Surakarta',
                        'catatan' => 'Peserta aktif pada Fungsi Statistik Produksi dan Distribusi (survei ubinan & harga gabah).',
                    ],
                    'skills' => ['Pengolahan Data Excel', 'Analisis Statistik', 'Visualisasi Data'],
                ],
                'magang13' => [
                    'name' => '13 Putri Ayu Lestari',
                    'email' => 'magang13@gmail.com',
                    'phone' => '085712345213',
                    'asn_key' => 5,
                    'created_at' => $gelombang4Mulai->copy()->subDays(3),
                    'profile' => [
                        'nim' => '230312604821',
                        'instansi_asal' => 'Universitas Negeri Malang',
                        'jenjang' => 'S1',
                        'jurusan' => 'Matematika',
                        'tanggal_lahir' => '2005-01-17',
                        'tanggal_mulai' => $gelombang4Mulai->toDateString(),
                        'tanggal_selesai' => $gelombang4Selesai->toDateString(),
                        'status' => 'aktif',
                        'alamat' => 'Jl. Semarang No. 5, Kota Malang',
                        'catatan' => 'Peserta aktif pada Fungsi Neraca Wilayah & Analisis Statistik (kompilasi PDRB lapangan usaha).',
                    ],
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel', 'Desain Grafis & Infografis'],
                ],

                // --- STATUS PENDING & DIBATALKAN (Untuk Kelengkapan Statistik Dashboard Admin) ---
                'magang14' => [
                    'name' => '14 Kevin Pratama Wijaya',
                    'email' => 'magang14@gmail.com',
                    'phone' => '085712345214',
                    'asn_key' => 2,
                    'created_at' => $today->copy()->subDays(3),
                    'profile' => [
                        'nim' => '232410102077',
                        'instansi_asal' => 'Universitas Jember',
                        'jenjang' => 'S1',
                        'jurusan' => 'Sistem Informasi',
                        'tanggal_lahir' => '2005-06-11',
                        'tanggal_mulai' => $today->copy()->addDays(14)->toDateString(),
                        'tanggal_selesai' => $today->copy()->addMonths(4)->toDateString(),
                        'status' => 'pending',
                        'alamat' => 'Jl. Kalimantan No. 37, Kab. Jember',
                        'catatan' => 'Terjadwal mulai magang 2 minggu lagi pada Fungsi IPDS.',
                    ],
                    'skills' => ['Pengembangan Web Laravel', 'Manajemen Basis Data SQL'],
                ],
                'magang15' => [
                    'name' => '15 Rizka Amalia Putri',
                    'email' => 'magang15@gmail.com',
                    'phone' => '085712345215',
                    'asn_key' => 3,
                    'created_at' => $today->copy()->subDays(12), // 12 hari lalu -> tersisa 18 hari sebelum terhapus otomatis
                    'profile' => [
                        'nim' => null,
                        'instansi_asal' => 'Politeknik Negeri Madiun',
                        'jenjang' => 'D3',
                        'jurusan' => 'Manajemen Informatika',
                        'tanggal_lahir' => null,
                        'tanggal_mulai' => null,
                        'tanggal_selesai' => null,
                        'status' => 'pending',
                        'alamat' => 'Jl. Ring Road Barat No. 11, Kota Madiun',
                        'catatan' => 'Akun dibuat tanpa tanggal mulai & selesai (demo peringatan otomatis terhapus dalam 1 bulan).',
                    ],
                    'skills' => ['Administrasi Perkantoran'],
                ],
                'magang16' => [
                    'name' => '16 Galih Permana',
                    'email' => 'magang16@gmail.com',
                    'phone' => '085712345216',
                    'asn_key' => 4,
                    'created_at' => $today->copy()->subMonths(3),
                    'profile' => [
                        'nim' => '1103223088',
                        'instansi_asal' => 'Telkom University',
                        'jenjang' => 'S1',
                        'jurusan' => 'Teknik Komputer',
                        'tanggal_lahir' => '2004-10-02',
                        'tanggal_mulai' => $today->copy()->subMonths(2)->toDateString(),
                        'tanggal_selesai' => $today->copy()->addMonth()->toDateString(),
                        'status' => 'dibatalkan',
                        'alamat' => 'Jl. Telekomunikasi No. 1, Kab. Bandung',
                        'catatan' => 'Dibatalkan atas permohonan peserta karena penyesuaian jadwal semester.',
                    ],
                    'skills' => ['Python & Pandas', 'Manajemen Basis Data SQL'],
                ],
            ];

            $mahasiswaProfiles = [];

            foreach ($pesertaMagangData as $key => $peserta) {
                $userMahasiswa = User::updateOrCreate(
                    ['email' => $peserta['email']],
                    [
                        'name' => $peserta['name'],
                        'email_verified_at' => $peserta['created_at'],
                        'password' => $defaultPassword,
                        'role' => 'mahasiswa',
                        'phone' => $peserta['phone'],
                        'avatar' => null,
                        'is_active' => $peserta['profile']['status'] !== 'dibatalkan',
                    ]
                );

                // Set created_at realistis agar perhitungan batas 1 bulan & riwayat pendaftaran akurat
                User::where('id', $userMahasiswa->id)->update([
                    'created_at' => $peserta['created_at'],
                    'updated_at' => $peserta['created_at'],
                ]);

                $mahasiswaProfile = MahasiswaProfile::updateOrCreate(
                    ['user_id' => $userMahasiswa->id],
                    [
                        'asn_profile_id' => $asnProfiles[$peserta['asn_key']]->id,
                        'foto_profil_path' => null,
                        'nim' => $peserta['profile']['nim'],
                        'instansi_asal' => $peserta['profile']['instansi_asal'],
                        'jenjang' => $peserta['profile']['jenjang'],
                        'jurusan' => $peserta['profile']['jurusan'],
                        'tanggal_lahir' => $peserta['profile']['tanggal_lahir'],
                        'tanggal_mulai' => $peserta['profile']['tanggal_mulai'],
                        'tanggal_selesai' => $peserta['profile']['tanggal_selesai'],
                        'status' => $peserta['profile']['status'],
                        'surat_pengantar_path' => null,
                        'catatan' => $peserta['profile']['catatan'],
                        'alamat' => $peserta['profile']['alamat'],
                    ]
                );

                MahasiswaProfile::where('id', $mahasiswaProfile->id)->update([
                    'created_at' => $peserta['created_at'],
                    'updated_at' => $peserta['created_at'],
                ]);

                $selectedSkillIds = collect($peserta['skills'])
                    ->map(fn (string $nama) => $skillMap[$nama] ?? null)
                    ->filter()
                    ->values()
                    ->all();

                $mahasiswaProfile->skills()->sync($selectedSkillIds);
                $mahasiswaProfiles[$key] = $mahasiswaProfile;
            }

            // Bersihkan data tugas & logbook lama milik peserta seeder agar aman dijalankan berulang (idempoten)
            $seededProfileIds = collect($mahasiswaProfiles)->pluck('id')->all();
            $seededAsnUserIds = collect($asnUsers)->pluck('id')->all();

            Tugas::whereIn('asn_id', $seededAsnUserIds)->delete();
            MagangLogbook::whereIn('mahasiswa_profile_id', $seededProfileIds)->delete();

            // =========================================================================
            // 5. DATA TUGAS (SELESAI, KOLABORASI TIM, REVIEW, REVISI, DIAMBIL, TERSEDIA)
            // =========================================================================
            $daftarTugas = [
                // --- Tugas Gelombang 1 (Alumni Selesai) ---
                [
                    'asn_key' => 1,
                    'ketua' => 'magang1',
                    'anggota' => [],
                    'judul' => 'Cleaning dan Validasi Raw Data Susenas Kor 2025',
                    'deskripsi' => 'Melakukan pemeriksaan konsistensi antarblok kuesioner Susenas Kor serta membuat rekap anomali variabel pendidikan dan kesehatan menggunakan Python Pandas.',
                    'status' => 'selesai',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $gelombang1Mulai->copy()->addDays(4),
                    'diambil_at' => $gelombang1Mulai->copy()->addDays(5),
                    'deadline' => $gelombang1Mulai->copy()->addDays(25),
                    'selesai_at' => $gelombang1Mulai->copy()->addDays(22),
                    'skills' => ['Python & Pandas', 'Analisis Statistik'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Seluruh 1.240 record raw data Susenas Kor sudah dibersihkan dan dilengkapi script validasi otomatis.',
                            'catatan_asn' => 'Hasil bersih, struktur variabel sudah sesuai pedoman tabulasi.',
                            'created_at' => $gelombang1Mulai->copy()->addDays(21),
                            'direview_at' => $gelombang1Mulai->copy()->addDays(22),
                        ],
                    ],
                ],
                [
                    'asn_key' => 1,
                    'ketua' => 'magang1',
                    'anggota' => [],
                    'judul' => 'Visualisasi Tren Indikator Kemiskinan Kota Madiun 5 Tahun Terakhir',
                    'deskripsi' => 'Menyusun grafik interaktif dan ringkasan eksekutif perkembangan Garis Kemiskinan, P0, P1, dan P2 untuk bahan rilis berita resmi statistik.',
                    'status' => 'selesai',
                    'sumber' => 'mandiri',
                    'created_at' => $gelombang1Mulai->copy()->addDays(35),
                    'diambil_at' => $gelombang1Mulai->copy()->addDays(36),
                    'deadline' => $gelombang1Mulai->copy()->addDays(60),
                    'selesai_at' => $gelombang1Mulai->copy()->addDays(55),
                    'skills' => ['Visualisasi Data', 'Analisis Statistik'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Dashboard grafik tren kemiskinan dan file tabel lampiran telah diunggah.',
                            'catatan_asn' => 'Penyajian grafik sangat informatif dan siap dipakai untuk paparan pimpinan.',
                            'created_at' => $gelombang1Mulai->copy()->addDays(54),
                            'direview_at' => $gelombang1Mulai->copy()->addDays(55),
                        ],
                    ],
                ],
                [
                    'asn_key' => 2,
                    'ketua' => 'magang2',
                    'anggota' => [],
                    'judul' => 'Pengembangan Modul Monitoring Progres Pengolahan Dokumen Survei',
                    'deskripsi' => 'Membangun halaman rekapitulasi progres batching, editing-coding, dan entri dokumen survei berbasis Laravel.',
                    'status' => 'selesai',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $gelombang1Mulai->copy()->addDays(6),
                    'diambil_at' => $gelombang1Mulai->copy()->addDays(6),
                    'deadline' => $gelombang1Mulai->copy()->addDays(45),
                    'selesai_at' => $gelombang1Mulai->copy()->addDays(42),
                    'skills' => ['Pengembangan Web Laravel', 'Manajemen Basis Data SQL'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Modul monitoring progres pengolahan beserta fitur ekspor Excel telah selesai diuji.',
                            'catatan_asn' => 'Fitur berjalan lancar tanpa bug, siap digunakan tim IPDS.',
                            'created_at' => $gelombang1Mulai->copy()->addDays(41),
                            'direview_at' => $gelombang1Mulai->copy()->addDays(42),
                        ],
                    ],
                ],
                [
                    'asn_key' => 4,
                    'ketua' => 'magang3',
                    'anggota' => [],
                    'judul' => 'Tabulasi & Infografis Statistik Industri Mikro Kecil (IMK)',
                    'deskripsi' => 'Mengolah tabel direktori usaha IMK per kecamatan dan membuat 4 desain infografis ringkasan hasil survei.',
                    'status' => 'selesai',
                    'sumber' => 'mandiri',
                    'created_at' => $gelombang1Mulai->copy()->addDays(10),
                    'diambil_at' => $gelombang1Mulai->copy()->addDays(11),
                    'deadline' => $gelombang1Mulai->copy()->addDays(38),
                    'selesai_at' => $gelombang1Mulai->copy()->addDays(35),
                    'skills' => ['Pengolahan Data Excel', 'Desain Grafis & Infografis'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'File master Excel dan 4 aset infografis resolusi tinggi sudah diserahkan.',
                            'catatan_asn' => 'Layout infografis rapi dan angka konsisten dengan tabulasi.',
                            'created_at' => $gelombang1Mulai->copy()->addDays(34),
                            'direview_at' => $gelombang1Mulai->copy()->addDays(35),
                        ],
                    ],
                ],

                // --- Tugas Gelombang 2 (Alumni Selesai + Kolaborasi Tim) ---
                [
                    'asn_key' => 3,
                    'ketua' => 'magang4',
                    'anggota' => [],
                    'judul' => 'Rekapitulasi Digital Arsip Surat Masuk dan Keluar Triwulan II',
                    'deskripsi' => 'Melakukan klasifikasi kode arsip, pemindaian dokumen administrasi, serta penginputan indeks arsip ke database.',
                    'status' => 'selesai',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $gelombang2Mulai->copy()->addDays(5),
                    'diambil_at' => $gelombang2Mulai->copy()->addDays(5),
                    'deadline' => $gelombang2Mulai->copy()->addDays(30),
                    'selesai_at' => $gelombang2Mulai->copy()->addDays(28),
                    'skills' => ['Administrasi Perkantoran', 'Manajemen Basis Data SQL'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Seluruh 340 berkas surat triwulan II sudah terindeks dan tersimpan rapi.',
                            'catatan_asn' => 'Pencarian nomor surat sekarang jauh lebih cepat, terima kasih.',
                            'created_at' => $gelombang2Mulai->copy()->addDays(27),
                            'direview_at' => $gelombang2Mulai->copy()->addDays(28),
                        ],
                    ],
                ],
                [
                    'asn_key' => 5,
                    'ketua' => 'magang6',
                    'anggota' => [
                        ['key' => 'magang5', 'status' => 'diterima', 'sumber' => 'ditugaskan_asn'],
                    ],
                    'judul' => 'Kompilasi Tabel & Publikasi PDRB Menurut Lapangan Usaha',
                    'deskripsi' => 'Kolaborasi penyusunan seri data PDRB atas dasar harga berlaku & konstan serta desain tata letak publikasi tahunan.',
                    'status' => 'selesai',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $gelombang2Mulai->copy()->addDays(8),
                    'diambil_at' => $gelombang2Mulai->copy()->addDays(8),
                    'deadline' => $gelombang2Mulai->copy()->addDays(50),
                    'selesai_at' => $gelombang2Mulai->copy()->addDays(47),
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel', 'Desain Grafis & Infografis'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Tabel seri 17 lapangan usaha beserta layout publikasi siap cetak telah selesai disusun.',
                            'catatan_asn' => 'Kerja sama tim sangat baik, seluruh tabel konsisten 100%.',
                            'created_at' => $gelombang2Mulai->copy()->addDays(46),
                            'direview_at' => $gelombang2Mulai->copy()->addDays(47),
                        ],
                    ],
                ],

                // --- Tugas Gelombang 3 (Alumni Selesai) ---
                [
                    'asn_key' => 1,
                    'ketua' => 'magang7',
                    'anggota' => [],
                    'judul' => 'Analisis Tingkat Pengangguran Terbuka (TPT) dan Struktur Ketenagakerjaan',
                    'deskripsi' => 'Mengolah mikrodata Sakernas untuk memetakan distribusi tenaga kerja menurut lapangan pekerjaan utama dan tingkat pendidikan.',
                    'status' => 'selesai',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $gelombang3Mulai->copy()->addDays(5),
                    'diambil_at' => $gelombang3Mulai->copy()->addDays(6),
                    'deadline' => $gelombang3Mulai->copy()->addDays(35),
                    'selesai_at' => $gelombang3Mulai->copy()->addDays(32),
                    'skills' => ['Analisis Statistik', 'Python & Pandas'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Naskah analisis singkat dan tabel distribusi Sakernas telah selesai diverifikasi.',
                            'catatan_asn' => 'Metodologi penimbang survei diterapkan dengan tepat.',
                            'created_at' => $gelombang3Mulai->copy()->addDays(31),
                            'direview_at' => $gelombang3Mulai->copy()->addDays(32),
                        ],
                    ],
                ],
                [
                    'asn_key' => 2,
                    'ketua' => 'magang8',
                    'anggota' => [],
                    'judul' => 'Normalisasi & Validasi Basis Data Wilayah Kerja Statistik (Wilkerstat)',
                    'deskripsi' => 'Melakukan pengecekan duplikasi kode SLS (Satuan Lingkungan Setempat) dan menyusun script SQL validasi master wilayah.',
                    'status' => 'selesai',
                    'sumber' => 'mandiri',
                    'created_at' => $gelombang3Mulai->copy()->addDays(7),
                    'diambil_at' => $gelombang3Mulai->copy()->addDays(8),
                    'deadline' => $gelombang3Mulai->copy()->addDays(40),
                    'selesai_at' => $gelombang3Mulai->copy()->addDays(37),
                    'skills' => ['Manajemen Basis Data SQL', 'Pengembangan Web Laravel'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Seluruh 27 kelurahan sudah tervalidasi tanpa duplikasi ID SLS.',
                            'catatan_asn' => 'Query validasi sangat membantu proses pemutakhiran Wilkerstat.',
                            'created_at' => $gelombang3Mulai->copy()->addDays(36),
                            'direview_at' => $gelombang3Mulai->copy()->addDays(37),
                        ],
                    ],
                ],

                // --- Tugas Gelombang 4 (Peserta Aktif Saat Ini — Beragam Status untuk Demo Seminar) ---
                // 1) Tugas Selesai pada Peserta Aktif
                [
                    'asn_key' => 1,
                    'ketua' => 'magang9',
                    'anggota' => [],
                    'judul' => 'Kompilasi Data Indeks Pembangunan Manusia (IPM) Kota Madiun',
                    'deskripsi' => 'Menghimpun komponen umur harapan hidup, harapan lama sekolah, rata-rata lama sekolah, dan pengeluaran per kapita disesuaikan.',
                    'status' => 'selesai',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $gelombang4Mulai->copy()->addDays(3),
                    'diambil_at' => $gelombang4Mulai->copy()->addDays(4),
                    'deadline' => $gelombang4Mulai->copy()->addDays(20),
                    'selesai_at' => $gelombang4Mulai->copy()->addDays(18),
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Tabel kompilasi 4 komponen IPM tahun 2021–2025 telah selesai disusun.',
                            'catatan_asn' => 'Data lengkap dan siap dijadikan bahan rilis.',
                            'created_at' => $gelombang4Mulai->copy()->addDays(17),
                            'direview_at' => $gelombang4Mulai->copy()->addDays(18),
                        ],
                    ],
                ],
                [
                    'asn_key' => 2,
                    'ketua' => 'magang10',
                    'anggota' => [],
                    'judul' => 'Otomasi Konversi Tabel Publikasi PDF ke Format Excel Terstruktur',
                    'deskripsi' => 'Membuat script Python untuk mengekstrak puluhan tabel statistik tahunan menjadi sheet Excel siap olah.',
                    'status' => 'selesai',
                    'sumber' => 'mandiri',
                    'created_at' => $gelombang4Mulai->copy()->addDays(5),
                    'diambil_at' => $gelombang4Mulai->copy()->addDays(6),
                    'deadline' => $gelombang4Mulai->copy()->addDays(24),
                    'selesai_at' => $gelombang4Mulai->copy()->addDays(22),
                    'skills' => ['Python & Pandas', 'Pengolahan Data Excel'],
                    'submissions' => [
                        [
                            'status' => 'disetujui',
                            'catatan_mahasiswa' => 'Script konversi batch beserta 45 file hasil ekstraksi telah diunggah.',
                            'catatan_asn' => 'Sangat menghemat waktu tim diseminasi, mantap!',
                            'created_at' => $gelombang4Mulai->copy()->addDays(21),
                            'direview_at' => $gelombang4Mulai->copy()->addDays(22),
                        ],
                    ],
                ],

                // 2) Tugas Menunggu Review ASN (Siap Didemokan Approval-nya!)
                [
                    'asn_key' => 2,
                    'ketua' => 'magang10',
                    'anggota' => [
                        ['key' => 'magang9', 'status' => 'diterima', 'sumber' => 'undangan_teman'],
                    ],
                    'judul' => 'Pembuatan Dashboard Ringkasan Indikator Strategis Kota Madiun',
                    'deskripsi' => 'Kolaborasi pembuatan tampilan visualisasi indikator makro (Inflasi, Pertumbuhan Ekonomi, Kemiskinan, TPT) untuk layanan Pojok Statistik.',
                    'status' => 'menunggu_review',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $today->copy()->subDays(14),
                    'diambil_at' => $today->copy()->subDays(13),
                    'deadline' => $today->copy()->addDays(5),
                    'selesai_at' => null,
                    'skills' => ['Pengembangan Web Laravel', 'Visualisasi Data'],
                    'submissions' => [
                        [
                            'status' => 'menunggu',
                            'catatan_mahasiswa' => 'Prototipe halaman indikator strategis beserta grafik interaktif sudah selesai dikerjakan bersama tim. Mohon review dari pembimbing.',
                            'catatan_asn' => null,
                            'created_at' => $today->copy()->subDay(),
                            'direview_at' => null,
                        ],
                    ],
                ],

                // 3) Tugas Status Revisi (Siap Didemokan Alur Revisi & Submit Ulang!)
                [
                    'asn_key' => 4,
                    'ketua' => 'magang12',
                    'anggota' => [],
                    'judul' => 'Validasi Data Survei Ubinan Padi dan Palawija Subround III',
                    'deskripsi' => 'Melakukan pengecekan kewajaran angka produktivitas per hektar hasil ubinan di seluruh kecamatan Kota Madiun.',
                    'status' => 'revisi',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $today->copy()->subDays(12),
                    'diambil_at' => $today->copy()->subDays(11),
                    'deadline' => $today->copy()->addDays(6),
                    'selesai_at' => null,
                    'skills' => ['Pengolahan Data Excel', 'Analisis Statistik'],
                    'submissions' => [
                        [
                            'status' => 'revisi',
                            'catatan_mahasiswa' => 'Rekapitulasi produktivitas ubinan padi dan palawija Subround III terlampir.',
                            'catatan_asn' => 'Mohon tambahkan kolom perbandingan dengan angka Subround III tahun lalu serta tandai sampel dengan deviasi di atas 15%.',
                            'created_at' => $today->copy()->subDays(3),
                            'direview_at' => $today->copy()->subDays(2),
                        ],
                    ],
                ],

                // 4) Tugas Sedang Dikerjakan (Status: Diambil)
                [
                    'asn_key' => 3,
                    'ketua' => 'magang11',
                    'anggota' => [],
                    'judul' => 'Penyusunan Laporan Evaluasi Realisasi Anggaran & Kinerja Bulanan',
                    'deskripsi' => 'Membantu rekapitulasi capaian realisasi anggaran per kegiatan dan kelengkapan dokumen pertanggungjawaban.',
                    'status' => 'diambil',
                    'sumber' => 'ditugaskan_asn',
                    'created_at' => $today->copy()->subDays(7),
                    'diambil_at' => $today->copy()->subDays(6),
                    'deadline' => $today->copy()->addDays(8),
                    'selesai_at' => null,
                    'skills' => ['Administrasi Perkantoran', 'Pengolahan Data Excel'],
                    'submissions' => [],
                ],
                [
                    'asn_key' => 5,
                    'ketua' => 'magang13',
                    'anggota' => [],
                    'judul' => 'Penghitungan Indeks Implisit dan Laju Pertumbuhan PDRB Triwulan III',
                    'deskripsi' => 'Menyusun kertas kerja penghitungan laju pertumbuhan q-to-q, y-on-y, dan c-to-c untuk 17 kategori lapangan usaha.',
                    'status' => 'diambil',
                    'sumber' => 'mandiri',
                    'created_at' => $today->copy()->subDays(6),
                    'diambil_at' => $today->copy()->subDays(5),
                    'deadline' => $today->copy()->addDays(10),
                    'selesai_at' => null,
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel'],
                    'submissions' => [],
                ],

                // 5) Tugas Tersedia (Belum Diambil — Siap Didemokan Klaim Tugas!)
                [
                    'asn_key' => 1,
                    'ketua' => null,
                    'anggota' => [],
                    'judul' => 'Pemetaan Sebaran Fasilitas Pendidikan dan Kesehatan Berbasis Desa/Kelurahan',
                    'deskripsi' => 'Mengolah data Potensi Desa (Podes) untuk menyusun tabel direktori fasilitas pendidikan dan kesehatan per kelurahan.',
                    'status' => 'tersedia',
                    'sumber' => 'mandiri',
                    'created_at' => $today->copy()->subDays(2),
                    'diambil_at' => null,
                    'deadline' => $today->copy()->addDays(14),
                    'selesai_at' => null,
                    'skills' => ['Pengolahan Data Excel', 'Visualisasi Data'],
                    'submissions' => [],
                ],
                [
                    'asn_key' => 2,
                    'ketua' => null,
                    'anggota' => [],
                    'judul' => 'Pembuatan Infografis Panduan Layanan Konsultasi Statistik Online',
                    'deskripsi' => 'Mendesain alur layanan permintaan data dan konsultasi statistik melalui website serta PST (Pelayanan Statistik Terpadu).',
                    'status' => 'tersedia',
                    'sumber' => 'mandiri',
                    'created_at' => $today->copy()->subDay(),
                    'diambil_at' => null,
                    'deadline' => $today->copy()->addDays(15),
                    'selesai_at' => null,
                    'skills' => ['Desain Grafis & Infografis'],
                    'submissions' => [],
                ],
                [
                    'asn_key' => 3,
                    'ketua' => null,
                    'anggota' => [],
                    'judul' => 'Inventarisasi dan Pelabelan Ulang Aset Perangkat Komputer Kantor',
                    'deskripsi' => 'Mendata nomor seri, spesifikasi perangkat keras, serta lokasi penempatan PC/laptop di seluruh ruang fungsi.',
                    'status' => 'tersedia',
                    'sumber' => 'mandiri',
                    'created_at' => $today->copy()->subDay(),
                    'diambil_at' => null,
                    'deadline' => $today->copy()->addDays(12),
                    'selesai_at' => null,
                    'skills' => ['Administrasi Perkantoran', 'Pengolahan Data Excel'],
                    'submissions' => [],
                ],
                [
                    'asn_key' => 4,
                    'ketua' => null,
                    'anggota' => [],
                    'judul' => 'Kompilasi Direktori Perusahaan Konstruksi dan Pertambangan',
                    'deskripsi' => 'Memutakhirkan status aktif/tutup perusahaan konstruksi berdasarkan hasil pencacahan lapangan.',
                    'status' => 'tersedia',
                    'sumber' => 'mandiri',
                    'created_at' => $today->copy()->subDays(2),
                    'diambil_at' => null,
                    'deadline' => $today->copy()->addDays(16),
                    'selesai_at' => null,
                    'skills' => ['Pengolahan Data Excel', 'Manajemen Basis Data SQL'],
                    'submissions' => [],
                ],
                [
                    'asn_key' => 5,
                    'ketua' => null,
                    'anggota' => [],
                    'judul' => 'Visualisasi Perbandingan Struktur Ekonomi Antar Kota di Jawa Timur',
                    'deskripsi' => 'Membuat grafik perbandingan kontribusi sektor perdagangan, industri pengolahan, dan jasa antar pemerintah kota.',
                    'status' => 'tersedia',
                    'sumber' => 'mandiri',
                    'created_at' => $today->copy()->subDay(),
                    'diambil_at' => null,
                    'deadline' => $today->copy()->addDays(18),
                    'selesai_at' => null,
                    'skills' => ['Visualisasi Data', 'Analisis Statistik'],
                    'submissions' => [],
                ],
            ];

            foreach ($daftarTugas as $item) {
                $asnUser = $asnUsers[$item['asn_key']];
                $ketuaProfile = $item['ketua'] ? $mahasiswaProfiles[$item['ketua']] : null;

                $tugas = Tugas::create([
                    'asn_id' => $asnUser->id,
                    'mahasiswa_profile_id' => $ketuaProfile?->id,
                    'judul' => $item['judul'],
                    'deskripsi' => $item['deskripsi'],
                    'deadline' => $item['deadline'],
                    'status' => $item['status'],
                    'sumber' => $item['sumber'],
                    'diambil_at' => $item['diambil_at'],
                    'selesai_at' => $item['selesai_at'],
                ]);

                Tugas::where('id', $tugas->id)->update([
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['selesai_at'] ?? $item['diambil_at'] ?? $item['created_at'],
                ]);

                // Attach skills tugas
                $tugasSkillIds = collect($item['skills'])
                    ->map(fn (string $nama) => $skillMap[$nama] ?? null)
                    ->filter()
                    ->values()
                    ->all();
                $tugas->skills()->sync($tugasSkillIds);

                // Anggota tim (jika ada)
                foreach ($item['anggota'] as $anggota) {
                    $anggotaProfile = $mahasiswaProfiles[$anggota['key']];
                    TugasAnggota::create([
                        'tugas_id' => $tugas->id,
                        'mahasiswa_profile_id' => $anggotaProfile->id,
                        'status' => $anggota['status'],
                        'diundang_oleh' => $anggota['sumber'] === 'undangan_teman' ? $ketuaProfile?->id : null,
                        'sumber' => $anggota['sumber'],
                    ]);
                }

                // Riwayat submission tugas
                foreach ($item['submissions'] as $sub) {
                    $submission = TugasSubmission::create([
                        'tugas_id' => $tugas->id,
                        'file_path' => null,
                        'file_name' => null,
                        'file_size' => null,
                        'mime_type' => null,
                        'catatan_mahasiswa' => $sub['catatan_mahasiswa'],
                        'status' => $sub['status'],
                        'catatan_asn' => $sub['catatan_asn'],
                        'direview_oleh' => $sub['direview_at'] ? $asnUser->id : null,
                        'direview_at' => $sub['direview_at'],
                    ]);

                    TugasSubmission::where('id', $submission->id)->update([
                        'created_at' => $sub['created_at'],
                        'updated_at' => $sub['direview_at'] ?? $sub['created_at'],
                    ]);
                }

                // Jika tugas sudah selesai -> catat otomatis ke tabel `logbook` untuk ketua & seluruh anggota diterima
                if ($item['status'] === 'selesai' && $ketuaProfile) {
                    $waktuLogbook = $item['selesai_at'] ?? $item['created_at'];

                    $logKetua = Logbook::create([
                        'tugas_id' => $tugas->id,
                        'mahasiswa_profile_id' => $ketuaProfile->id,
                    ]);
                    Logbook::where('id', $logKetua->id)->update([
                        'created_at' => $waktuLogbook,
                        'updated_at' => $waktuLogbook,
                    ]);

                    foreach ($item['anggota'] as $anggota) {
                        if ($anggota['status'] === 'diterima') {
                            $anggotaProfile = $mahasiswaProfiles[$anggota['key']];
                            $logAnggota = Logbook::create([
                                'tugas_id' => $tugas->id,
                                'mahasiswa_profile_id' => $anggotaProfile->id,
                            ]);
                            Logbook::where('id', $logAnggota->id)->update([
                                'created_at' => $waktuLogbook,
                                'updated_at' => $waktuLogbook,
                            ]);
                        }
                    }
                }
            }

            // =========================================================================
            // 6. LOGBOOK MANDIRI HARIAN (`magang_logbook`) SEPANJANG 1 TAHUN
            // =========================================================================
            $templateKegiatanPerFungsi = [
                1 => [ // Statistik Sosial
                    ['Apel Pagi & Briefing Survei Sosial', 'Mengikuti pengarahan pembimbing terkait target pengolahan kuesioner Susenas dan Sakernas.'],
                    ['Editing & Coding Kuesioner Rumah Tangga', 'Memeriksa kelengkapan isian blok anggota rumah tangga, pendidikan, dan ketenagakerjaan.'],
                    ['Tabulasi Indikator Kesejahteraan Rakyat', 'Menyusun tabel silang tingkat partisipasi sekolah dan akses sanitasi layak per kecamatan.'],
                    ['Pengecekan Anomali Data Survei Sosial', 'Melakukan verifikasi ulang isian outlier pendapatan dan pengeluaran rumah tangga.'],
                    ['Penyusunan Bahan Infografis Kemiskinan', 'Menyiapkan ringkasan angka indikator sosial untuk publikasi media sosial resmi.'],
                ],
                2 => [ // IPDS
                    ['Sinkronisasi & Backup Basis Data Survei', 'Melakukan pengecekan integritas tabel hasil entri pengolahan survei harian.'],
                    ['Pengembangan Fitur Monitoring Internal', 'Menulis kode modul rekapitulasi progres pengolahan dan melakukan pengujian form input.'],
                    ['Pemutakhiran Master Wilayah Kerja Statistik', 'Memvalidasi kode desa/kelurahan dan batas Satuan Lingkungan Setempat (SLS).'],
                    ['Otomasi Pembersihan Tabel Publikasi', 'Menjalankan script pengolahan format tabel agar sesuai standar publikasi Daerah Dalam Angka.'],
                    ['Dukungan Teknis Layanan Diseminasi Data', 'Membantu penyiapan dataset permintaan mahasiswa dan instansi pemerintah daerah.'],
                ],
                3 => [ // Subbagian Umum
                    ['Registrasi & Disposisi Surat Masuk', 'Mencatat surat masuk kedinasan pada buku agenda digital dan mendistribusikan ke fungsi terkait.'],
                    ['Pengarsipan Dokumen Pertanggungjawaban', 'Menyusun dan mengelompokkan berkas administrasi kegiatan bulanan secara kronologis.'],
                    ['Rekapitulasi Daftar Hadir & Kegiatan', 'Membantu kompilasi laporan administrasi kepegawaian dan jadwal rapat koordinasi.'],
                    ['Inventarisasi Barang Milik Negara (BMN)', 'Melakukan pengecekan fisik kondisi peralatan kantor dan pembaruan daftar inventaris ruangan.'],
                    ['Penyiapan Administrasi Rapat Evaluasi', 'Menyiapkan daftar hadir, notulensi, dan bahan paparan evaluasi kinerja bulanan.'],
                ],
                4 => [ // Statistik Produksi & Distribusi
                    ['Verifikasi Kuesioner Survei Harga Konsumen', 'Memeriksa kewajaran perkembangan harga komoditas bahan pokok mingguan di pasar pencacahan.'],
                    ['Tabulasi Hasil Survei Ubinan Tanaman Pangan', 'Merekap hasil pengukuran ubinan padi dan palawija untuk penghitungan produktivitas.'],
                    ['Pemutakhiran Direktori Industri & Konstruksi', 'Mengonfirmasi status operasional perusahaan responden survei triwulanan.'],
                    ['Pengolahan Data Statistik Transportasi & Pariwisata', 'Menginput dan memvalidasi data tingkat penghunian kamar hotel bulanan.'],
                    ['Penyusunan Grafik Perkembangan Inflasi Bulanan', 'Membuat visualisasi andil komoditas penyumbang inflasi dan deflasi daerah.'],
                ],
                5 => [ // Neraca Wilayah & Analisis Statistik
                    ['Kompilasi Data Pendukung PDRB Triwulanan', 'Menghimpun data realisasi produksi dan indikator sektoral dari dinas teknis terkait.'],
                    ['Pengecekan Konsistensi Seri Data PDRB', 'Membandingkan nilai PDRB ADHB dan ADHK antar triwulan untuk menjaga kewajaran indeks implisit.'],
                    ['Penghitungan Struktur & Laju Pertumbuhan Ekonomi', 'Menyusun tabel distribusi persentase 17 kategori lapangan usaha Kota Madiun.'],
                    ['Penyusunan Narasi Analisis Ekonomi Daerah', 'Menulis ringkasan faktor pendorong pertumbuhan ekonomi pada sektor perdagangan dan jasa.'],
                    ['Finalisasi Tabel Publikasi PDRB Tahunan', 'Melakukan proofreading angka dan tata letak tabel sebelum tahap publikasi.'],
                ],
            ];

            // Isi logbook mandiri untuk semua peserta alumni (`selesai`) dan peserta berjalan (`aktif`)
            foreach ($pesertaMagangData as $key => $peserta) {
                $statusPeserta = $peserta['profile']['status'];
                if (! in_array($statusPeserta, ['selesai', 'aktif'], true)) {
                    continue;
                }

                $profile = $mahasiswaProfiles[$key];
                $asnKey = $peserta['asn_key'];
                $asnUser = $asnUsers[$asnKey];
                $templates = $templateKegiatanPerFungsi[$asnKey];

                $mulai = Carbon::parse($peserta['profile']['tanggal_mulai']);
                $batasAkhir = $statusPeserta === 'selesai'
                    ? Carbon::parse($peserta['profile']['tanggal_selesai'])
                    : $today->copy();

                // Kumpulkan hari kerja (Senin - Jumat) dalam rentang tersebut
                $hariKerja = [];
                $cursor = $mulai->copy();
                while ($cursor->lte($batasAkhir)) {
                    if (! $cursor->isWeekend()) {
                        $hariKerja[] = $cursor->copy();
                    }
                    $cursor->addDay();
                }

                // Ambil sampel hari kerja yang representatif (maksimal 24 hari untuk alumni, 20 hari untuk aktif)
                $totalHariDiambil = $statusPeserta === 'selesai'
                    ? min(24, count($hariKerja))
                    : min(20, count($hariKerja));

                if ($totalHariDiambil === 0) {
                    continue;
                }

                $step = max(1, (int) floor(count($hariKerja) / $totalHariDiambil));
                $tanggalTerpilih = [];
                for ($i = 0; $i < count($hariKerja) && count($tanggalTerpilih) < $totalHariDiambil; $i += $step) {
                    $tanggalTerpilih[] = $hariKerja[$i];
                }

                // Pastikan 2 hari kerja terakhir sebelum hari ini ikut masuk untuk peserta aktif
                // (akan dijadikan logbook "Menunggu Validasi" saat demo seminar KP)
                if ($statusPeserta === 'aktif' && count($hariKerja) >= 2) {
                    $duaHariTerakhir = array_slice($hariKerja, -2);
                    foreach ($duaHariTerakhir as $tglAkhir) {
                        $sudahAda = collect($tanggalTerpilih)->contains(
                            fn (Carbon $d) => $d->toDateString() === $tglAkhir->toDateString()
                        );
                        if (! $sudahAda) {
                            $tanggalTerpilih[] = $tglAkhir;
                        }
                    }
                    usort($tanggalTerpilih, fn (Carbon $a, Carbon $b) => $a->timestamp <=> $b->timestamp);
                }

                $jumlahTanggal = count($tanggalTerpilih);
                foreach ($tanggalTerpilih as $idx => $tglKegiatan) {
                    $tpl = $templates[$idx % count($templates)];

                    // Untuk peserta aktif, 2 entri paling akhir diset belum divalidasi agar siap didemokan validasinya
                    $menungguValidasi = ($statusPeserta === 'aktif') && ($idx >= $jumlahTanggal - 2);

                    $logbookMandiri = MagangLogbook::create([
                        'mahasiswa_profile_id' => $profile->id,
                        'tanggal_kegiatan' => $tglKegiatan->toDateString(),
                        'judul_kegiatan' => $tpl[0],
                        'deskripsi_kegiatan' => $tpl[1] . ' (' . $tglKegiatan->translatedFormat('d F Y') . ').',
                        'file_lampiran' => null,
                        'is_validated' => ! $menungguValidasi,
                        'validated_at' => $menungguValidasi ? null : $tglKegiatan->copy()->setTime(16, 15),
                        'validated_by' => $menungguValidasi ? null : $asnUser->id,
                    ]);

                    MagangLogbook::where('id', $logbookMandiri->id)->update([
                        'created_at' => $tglKegiatan->copy()->setTime(15, 30),
                        'updated_at' => $menungguValidasi
                            ? $tglKegiatan->copy()->setTime(15, 30)
                            : $tglKegiatan->copy()->setTime(16, 15),
                    ]);
                }
            }

            // =========================================================================
            // 7. DATA BERITA & PENGUMUMAN INTERNAL (`berita`)
            // =========================================================================
            $daftarBerita = [
                [
                    'user_id' => $adminUsers[1]->id,
                    'judul' => 'Pedoman Pengisian Logbook Harian dan Validasi Pembimbing',
                    'konten' => "Seluruh peserta magang/PKL diwajibkan mengisi logbook kegiatan harian setiap selesai jam kerja. Logbook mandiri yang telah diisi akan diverifikasi oleh pembimbing ASN masing-masing dan otomatis terhitung dalam rekap Total Hari Masuk peserta.",
                    'created_at' => $today->copy()->subMonths(11),
                ],
                [
                    'user_id' => $asnUsers[2]->id,
                    'judul' => 'Standar Penamaan File Hasil Pengolahan dan Publikasi',
                    'konten' => "Untuk memudahkan pengarsipan pada server internal IPDS, setiap file hasil pengolahan data atau desain infografis yang dikumpulkan pada modul Tugas agar menggunakan format penamaan: [KodeFungsi]_[NamaKegiatan]_[Tanggal].",
                    'created_at' => $today->copy()->subMonths(6),
                ],
                [
                    'user_id' => $adminUsers[2]->id,
                    'judul' => 'Ketentuan Kelengkapan Tanggal Mulai dan Selesai Magang pada Profil',
                    'konten' => "Bagi calon peserta magang yang akunnya dibuat sebelum surat penetapan tanggal terbit, harap segera melengkapi Tanggal Mulai dan Tanggal Selesai magang pada halaman Profil maksimal 1 bulan sejak akun dibuat agar akun tetap aktif.",
                    'created_at' => $today->copy()->subMonths(2),
                ],
                [
                    'user_id' => $asnUsers[1]->id,
                    'judul' => 'Jadwal Evaluasi Tengah Periode Peserta Magang Gelombang Berjalan',
                    'konten' => "Diberitahukan kepada seluruh peserta magang aktif agar memastikan seluruh progres tugas dan logbook kegiatan minggu ini sudah terbarui sebelum sesi evaluasi bersama pembimbing masing-masing.",
                    'created_at' => $today->copy()->subDays(4),
                ],
            ];

            foreach ($daftarBerita as $beritaItem) {
                $existing = Berita::where('judul', $beritaItem['judul'])->first();
                if (! $existing) {
                    $berita = Berita::create([
                        'user_id' => $beritaItem['user_id'],
                        'judul' => $beritaItem['judul'],
                        'konten' => $beritaItem['konten'],
                    ]);

                    Berita::where('id', $berita->id)->update([
                        'created_at' => $beritaItem['created_at'],
                        'updated_at' => $beritaItem['created_at'],
                    ]);
                }
            }
        });
    }
}
