<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('magang_logbook', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel profil mahasiswa
            $table->foreignId('mahasiswa_profile_id')
                  ->constrained('mahasiswa_profiles')
                  ->cascadeOnDelete();

            // Field sesuai kriteria fitur
            $table->date('tanggal_kegiatan');
            $table->string('judul_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->string('file_lampiran')->nullable(); // Menyimpan path file foto (png, jpg, jpeg)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magang_logbook');
    }
};
