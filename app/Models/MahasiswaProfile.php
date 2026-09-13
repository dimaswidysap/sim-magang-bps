<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MahasiswaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'foto_profil_path',
        'nim',
        'instansi_asal',
        'alamat',
        'jenjang',
        'jurusan',
        'tanggal_lahir',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'surat_pengantar_path',
        'catatan'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Accessor untuk URL foto profil / avatar default
    public function getFotoProfilUrlAttribute(): string
    {
        if ($this->foto_profil_path && Storage::disk('public')->exists($this->foto_profil_path)) {
            return Storage::url($this->foto_profil_path);
        }

        // Fallback jika belum upload foto
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name ?? 'User') . '&color=7F9CF5&background=EBF4FF';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS & SCOPES
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMahasiswaAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'mahasiswa_profile_skill');
    }

    public function tugasDiambil()
    {
        return $this->hasMany(Tugas::class);
    }

    public function undanganTugas()
    {
        return $this->hasMany(TugasAnggota::class);
    }

    public function undanganMenunggu()
    {
        return $this->hasMany(TugasAnggota::class)->where('status', 'diundang');
    }

    public function scopeAktif($query)
    {
        return $query
            ->where('status', 'aktif')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            });
    }

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
