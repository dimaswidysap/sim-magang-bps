<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangLogbook extends Model
{
    use HasFactory;

    protected $table = 'magang_logbook';

    protected $fillable = [
        'mahasiswa_profile_id',
        'tanggal_kegiatan',
        'judul_kegiatan',
        'deskripsi_kegiatan',
        'file_lampiran',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
            'file_lampiran'    => 'array', // <--- Ditambahkan agar otomatis dikonversi ke Array/JSON
        ];
    }

    public function mahasiswaProfile()
    {
        return $this->belongsTo(MahasiswaProfile::class);
    }

    /**
     * Tebak dari ekstensi file, BUKAN dari mime_type asli.
     * Sudah disesuaikan untuk mendukung multiple file (array) maupun data lama (string).
     */
    public function isGambar(): bool
    {
        if (empty($this->file_lampiran)) {
            return false;
        }

        // Jika data baru (array dari multiple file)
        if (is_array($this->file_lampiran)) {
            foreach ($this->file_lampiran as $file) {
                $ekstensi = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ekstensi, ['png', 'jpg', 'jpeg', 'webp'])) {
                    return true;
                }
            }
            return false;
        }

        // Fallback jika masih ada data lama yang tersimpan berupa string
        $ekstensi = strtolower(pathinfo($this->file_lampiran, PATHINFO_EXTENSION));

        return in_array($ekstensi, ['png', 'jpg', 'jpeg', 'webp']);
    }
}
