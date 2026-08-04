<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'periode_magang_id', 'nim', 'instansi_asal', 'jenjang',
        'jurusan', 'tanggal_mulai', 'tanggal_selesai', 'status',
        'surat_pengantar_path', 'catatan',
    ];

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
}
