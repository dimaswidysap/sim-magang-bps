<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 1 tugas hanya untuk 1 mahasiswa (rebutan / first-come-first-served),
     * jadi mahasiswa_profile_id ditaruh langsung di tabel ini (bukan pivot).
     * Begitu diambil (mahasiswa_profile_id terisi), tugas otomatis hilang
     * dari daftar "tersedia" untuk mahasiswa lain (dicek via kolom status).
     *
     * Alur status:
     * tersedia -> diambil -> menunggu_review -> selesai
     *                              |-> revisi -> menunggu_review (lagi)
     */
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asn_id') // ASN pembuat tugas
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('periode_magang_id')
                ->nullable()
                ->constrained('periode_magang')
                ->nullOnDelete();
            $table->foreignId('mahasiswa_profile_id') // yang mengambil, null = belum diambil
                ->nullable()
                ->constrained('mahasiswa_profiles')
                ->nullOnDelete();

            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('status', ['tersedia', 'diambil', 'menunggu_review', 'revisi', 'selesai'])
                ->default('tersedia');
            $table->timestamp('diambil_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
