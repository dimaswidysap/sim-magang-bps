<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TugasSubmission extends Model
{
    use HasFactory;

    protected $table = 'tugas_submissions';

    protected $fillable = [
        'tugas_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'catatan_mahasiswa',
        'status',
        'catatan_asn',
        'direview_oleh',
        'direview_at',
    ];

    protected function casts(): array
    {
        return [
            'direview_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & VIRTUAL ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    // Mengambil URL lengkap file dari Storage
    public function getFileUrlAttribute(): string
    {
        return $this->file_path ? Storage::url($this->file_path) : '#';
    }

    // Mengubah ukuran file (byte) menjadi KB / MB yang siap tampil di view
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '0 KB';
        }

        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 2) . ' MB';
        }

        return number_format($this->file_size / 1024, 0) . ' KB';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'direview_oleh');
    }
}
