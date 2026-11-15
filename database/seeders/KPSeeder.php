<?php

namespace Database\Seeders;

use App\Models\AsnProfile;
use App\Models\MahasiswaProfile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $defaultPassword = '12345678';

            // 1. Seed Master Data Skills
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

            // 2. Seed 3 Data Admin
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

            foreach ($admins as $admin) {
                User::updateOrCreate(
                    ['email' => $admin['email']],
                    [
                        'name' => $admin['name'],
                        'email_verified_at' => now(),
                        'password' => $defaultPassword,
                        'role' => 'admin',
                        'phone' => $admin['phone'],
                        'avatar' => null,
                        'is_active' => true,
                    ]
                );
            }

            // 3. Seed 5 Data ASN + AsnProfile Lengkap
            $asns = [
                [
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
                [
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
                [
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
                [
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
                [
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

            foreach ($asns as $asn) {
                $userAsn = User::updateOrCreate(
                    ['email' => $asn['email']],
                    [
                        'name' => $asn['name'],
                        'email_verified_at' => now(),
                        'password' => $defaultPassword,
                        'role' => 'asn',
                        'phone' => $asn['phone'],
                        'avatar' => null,
                        'is_active' => true,
                    ]
                );

                AsnProfile::updateOrCreate(
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
            }

            // 4. Seed 5 Data Peserta Magang + MahasiswaProfile Lengkap + Skills
            $pesertaMagang = [
                [
                    'name' => '1 Nabila Putri Maharani',
                    'email' => 'magang1@gmail.com',
                    'phone' => '085712345201',
                    'profile' => [
                        'nim' => '164221015',
                        'instansi_asal' => 'Universitas Airlangga',
                        'jenjang' => 'S1',
                        'jurusan' => 'Teknologi Sains Data',
                        'tanggal_lahir' => '2004-03-18',
                        'tanggal_mulai' => '2026-09-01',
                        'tanggal_selesai' => '2026-12-31',
                        'status' => 'aktif',
                        'alamat' => 'Jl. Mulyorejo Tengah No. 19, Kota Surabaya',
                        'catatan' => 'Fokus penugasan pada pembersihan data survei, validasi kuesioner FASIH, dan visualisasi indikator sosial.',
                    ],
                    'skills' => ['Python & Pandas', 'Analisis Statistik', 'Visualisasi Data'],
                ],
                [
                    'name' => '2 Muhammad Rizky Pratama',
                    'email' => 'magang2@gmail.com',
                    'phone' => '085712345202',
                    'profile' => [
                        'nim' => '2241720048',
                        'instansi_asal' => 'Politeknik Negeri Malang',
                        'jenjang' => 'D4',
                        'jurusan' => 'Teknik Informatika',
                        'tanggal_lahir' => '2004-07-24',
                        'tanggal_mulai' => '2026-09-01',
                        'tanggal_selesai' => '2026-12-31',
                        'status' => 'aktif',
                        'alamat' => 'Jl. Soekarno Hatta No. 102, Kota Malang',
                        'catatan' => 'Membantu pengembangan modul internal berbasis Laravel dan otomasi pengolahan tabel.',
                    ],
                    'skills' => ['Pengembangan Web Laravel', 'Manajemen Basis Data SQL', 'Python & Pandas'],
                ],
                [
                    'name' => '3 Aulia Rahma Azzahra',
                    'email' => 'magang3@gmail.com',
                    'phone' => '085712345203',
                    'profile' => [
                        'nim' => '5003221089',
                        'instansi_asal' => 'Institut Teknologi Sepuluh Nopember',
                        'jenjang' => 'S1',
                        'jurusan' => 'Statistika',
                        'tanggal_lahir' => '2004-11-09',
                        'tanggal_mulai' => '2026-09-15',
                        'tanggal_selesai' => '2027-01-15',
                        'status' => 'aktif',
                        'alamat' => 'Jl. Keputih Tegal Timur No. 31, Kota Surabaya',
                        'catatan' => 'Bertugas pada tabulasi data statistik produksi dan penyusunan publikasi infografis bulanan.',
                    ],
                    'skills' => ['Analisis Statistik', 'Pengolahan Data Excel', 'Desain Grafis & Infografis'],
                ],
                [
                    'name' => '4 Fajar Nugroho',
                    'email' => 'magang4@gmail.com',
                    'phone' => '085712345204',
                    'profile' => [
                        'nim' => '233140701111032',
                        'instansi_asal' => 'Universitas Brawijaya',
                        'jenjang' => 'D3',
                        'jurusan' => 'Sistem Informasi',
                        'tanggal_lahir' => '2005-01-30',
                        'tanggal_mulai' => '2026-10-01',
                        'tanggal_selesai' => '2027-01-31',
                        'status' => 'aktif',
                        'alamat' => 'Jl. Veteran Dalam No. 12, Kota Malang',
                        'catatan' => 'Mendukung pengelolaan database direktori usaha dan rekapitulasi laporan kegiatan.',
                    ],
                    'skills' => ['Manajemen Basis Data SQL', 'Pengolahan Data Excel', 'Administrasi Perkantoran'],
                ],
                [
                    'name' => '5 Salsabila Khairunnisa',
                    'email' => 'magang5@gmail.com',
                    'phone' => '085712345205',
                    'profile' => [
                        'nim' => '24105012',
                        'instansi_asal' => 'SMKN 1 Madiun',
                        'jenjang' => 'SMA/SMK',
                        'jurusan' => 'Rekayasa Perangkat Lunak',
                        'tanggal_lahir' => '2008-05-14',
                        'tanggal_mulai' => '2026-10-01',
                        'tanggal_selesai' => '2027-01-31',
                        'status' => 'aktif',
                        'alamat' => 'Jl. Thamrin No. 55, Kel. Klegen, Kota Madiun',
                        'catatan' => 'Siswa PKL dengan penugasan desain publikasi media sosial, pengarsipan digital, dan entri data.',
                    ],
                    'skills' => ['Desain Grafis & Infografis', 'Administrasi Perkantoran', 'Pengolahan Data Excel'],
                ],
            ];

            foreach ($pesertaMagang as $peserta) {
                $userMahasiswa = User::updateOrCreate(
                    ['email' => $peserta['email']],
                    [
                        'name' => $peserta['name'],
                        'email_verified_at' => now(),
                        'password' => $defaultPassword,
                        'role' => 'mahasiswa',
                        'phone' => $peserta['phone'],
                        'avatar' => null,
                        'is_active' => true,
                    ]
                );

                $mahasiswaProfile = MahasiswaProfile::updateOrCreate(
                    ['user_id' => $userMahasiswa->id],
                    [
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

                $selectedSkillIds = collect($peserta['skills'])
                    ->map(fn (string $nama) => $skillMap[$nama] ?? null)
                    ->filter()
                    ->values()
                    ->all();

                $mahasiswaProfile->skills()->sync($selectedSkillIds);
            }
        });
    }
}
