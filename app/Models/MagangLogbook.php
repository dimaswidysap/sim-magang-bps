<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangLogbook extends Model
{
    use HasFactory;

    protected $table = 'magang_logbook';

    protected $fillable = [
        'mahasiswa_profile_id', 'tanggal_kegiatan', 'judul_kegiatan',
        'deskripsi_kegiatan', 'file_lampiran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
        ];
    }

    public function mahasiswaProfile()
    {
        return $this->belongsTo(MahasiswaProfile::class);
    }

    /**
     * Tebak dari ekstensi file, BUKAN dari mime_type asli - karena tabel
     * ini tidak menyimpan mime_type. Kurang akurat dibanding isGambar()
     * di BeritaAttachment, tapi cukup untuk kebutuhan tampilan sekarang.
     */
    public function isGambar(): bool
    {
        if (! $this->file_lampiran) {
            return false;
        }

        $ekstensi = strtolower(pathinfo($this->file_lampiran, PATHINFO_EXTENSION));

        return in_array($ekstensi, ['png', 'jpg', 'jpeg']);
    }
}
