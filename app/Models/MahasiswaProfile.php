<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'periode_magang_id', 'nim', 'instansi_asal', 'jenjang', 'jurusan', 'tanggal_mulai', 'tanggal_selesai', 'status', 'surat_pengantar_path', 'catatan'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMahasiswaAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function periodeMagang()
    {
        return $this->belongsTo(PeriodeMagang::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'mahasiswa_profile_skill');
    }

    public function tugasDiambil()
    {
        return $this->hasMany(Tugas::class);
    }

    // Semua undangan tugas yang PERNAH diterima mahasiswa ini (sebagai anggota, bukan ketua)
    public function undanganTugas()
    {
        return $this->hasMany(TugasAnggota::class);
    }

    // Cuma undangan yang masih menunggu jawaban (belum diterima/ditolak)
    public function undanganMenunggu()
    {
        return $this->hasMany(TugasAnggota::class)->where('status', 'diundang');
    }

    /**
     * Filter mahasiswa yang akunnya masih aktif (users.is_active = 1).
     * Mahasiswa yang dinonaktifkan admin tidak akan ikut terambil.
     */
    public function scopeAktif($query)
    {
        return $query
            ->where('status', 'aktif') // <-- Filter status di table mahasiswaProfile
            ->whereHas('user', function ($q) {
                $q->where('is_active', true); // <-- Filter is_active di table users
                // Opsional: $q->where('role', 'mahasiswa'); jika ingin memastikan rolenya
            });
    }

    /**
     * 1 query tunggal - tambahkan 2 kolom hitungan (jumlah_tugas_aktif dan
     * jumlah_tugas_selesai) ke setiap baris mahasiswa_profiles, mencakup
     * peran sebagai KETUA (tugas.mahasiswa_profile_id) maupun ANGGOTA
     * (tugas_anggota dengan status diterima).
     */
    public function scopeDenganStatistikTugas($query)
    {
        return $query->selectRaw("mahasiswa_profiles.*,
            (
                SELECT COUNT(*) FROM tugas
                WHERE tugas.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas.status != 'selesai'
            )
            +
            (
                SELECT COUNT(*) FROM tugas_anggota
                INNER JOIN tugas ON tugas.id = tugas_anggota.tugas_id
                WHERE tugas_anggota.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas_anggota.status = 'diterima'
                AND tugas.status != 'selesai'
            ) AS jumlah_tugas_aktif,
            (
                SELECT COUNT(*) FROM tugas
                WHERE tugas.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas.status = 'selesai'
            )
            +
            (
                SELECT COUNT(*) FROM tugas_anggota
                INNER JOIN tugas ON tugas.id = tugas_anggota.tugas_id
                WHERE tugas_anggota.mahasiswa_profile_id = mahasiswa_profiles.id
                AND tugas_anggota.status = 'diterima'
                AND tugas.status = 'selesai'
            ) AS jumlah_tugas_selesai
        ");
    }
}
