<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id', 'periode_magang_id', 'tanggal', 'jam_masuk', 'jam_pulang',
        'latitude_masuk', 'longitude_masuk', 'latitude_pulang', 'longitude_pulang',
        'status', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'latitude_masuk' => 'decimal:7',
            'longitude_masuk' => 'decimal:7',
            'latitude_pulang' => 'decimal:7',
            'longitude_pulang' => 'decimal:7',
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
}
