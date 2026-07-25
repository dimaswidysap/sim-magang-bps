<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeMagang extends Model
{
    use HasFactory;

    protected $table = 'periode_magang';

    protected $fillable = [
        'nama_periode', 'tanggal_mulai', 'tanggal_selesai', 'kuota', 'status', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function mahasiswaProfiles()
    {
        return $this->hasMany(MahasiswaProfile::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
