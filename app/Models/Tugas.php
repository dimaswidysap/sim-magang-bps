<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'asn_id', 'periode_magang_id', 'mahasiswa_profile_id',
        'judul', 'deskripsi', 'deadline', 'status', 'diambil_at', 'selesai_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'diambil_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    public function asn()
    {
        return $this->belongsTo(User::class, 'asn_id');
    }

    public function periodeMagang()
    {
        return $this->belongsTo(PeriodeMagang::class);
    }

    public function mahasiswaProfile()
    {
        return $this->belongsTo(MahasiswaProfile::class);
    }

    public function submissions()
    {
        return $this->hasMany(TugasSubmission::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'tugas_skill');
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeBelumSelesai($query)
    {
        return $query->where('status', '!=', 'selesai');
    }

    public function scopeMilikAsn($query, $asnId)
    {
        return $query->where('asn_id', $asnId);
    }
}
