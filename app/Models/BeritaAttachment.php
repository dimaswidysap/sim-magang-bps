<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAttachment extends Model
{
    use HasFactory;

    protected $table = 'berita_attachments';

    protected $fillable = [
        'berita_id', 'file_path', 'file_name', 'file_size', 'mime_type',
    ];

    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }

    public function isGambar(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}
