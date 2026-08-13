<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tidak ada kolom "tipe" (foto/file) terpisah - cukup pakai mime_type
     * untuk menentukan cara tampil (gambar tampil inline via <img>, file
     * lain tampil sebagai link download). Menghindari data redundan yang
     * bisa tidak sinkron dengan mime_type aslinya.
     */
    public function up(): void
    {
        Schema::create('berita_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')
                ->constrained('berita')
                ->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size'); // bytes
            $table->string('mime_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_attachments');
    }
};
