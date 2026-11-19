<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLibur extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_liburs';

    protected $fillable = [
        'mahasiswa_profile_id',
        'asn_profile_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'file_lampiran',
        'nama_file_asli',
        'status',
        'catatan_asn',
        'direspon_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'direspon_at' => 'datetime',
        ];
    }

    public function mahasiswaProfile()
    {
        return $this->belongsTo(MahasiswaProfile::class, 'mahasiswa_profile_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(AsnProfile::class, 'asn_profile_id');
    }

    /**
     * Menghitung total hari libur (inklusif).
     */
    public function getJumlahHariAttribute(): int
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return 0;
        }

        return (int) $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    /**
     * Ekstensi file lampiran dalam huruf kecil.
     */
    public function getFileExtensionAttribute(): string
    {
        return strtolower(pathinfo((string) $this->file_lampiran, PATHINFO_EXTENSION));
    }

    public function isImage(): bool
    {
        return in_array($this->file_extension, [
            'jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tif', 'tiff', 'ico', 'avif', 'heic', 'heif',
        ], true);
    }

    public function isPdf(): bool
    {
        return $this->file_extension === 'pdf';
    }

    public function isWord(): bool
    {
        return in_array($this->file_extension, ['doc', 'docx'], true);
    }

    public function bisaDieditAtauDihapus(): bool
    {
        return $this->status === 'pending';
    }
}
