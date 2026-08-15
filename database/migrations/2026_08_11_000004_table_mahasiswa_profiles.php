<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('periode_magang_id')->nullable()->constrained('periode_magang')->nullOnDelete();

            $table->string('nim');
            $table->string('instansi_asal');
            $table->enum('jenjang', ['SMA/SMK', 'D3', 'D4', 'S1', 'S2'])->nullable();
            $table->string('jurusan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['pending', 'aktif', 'selesai', 'dibatalkan'])->default('pending');
            $table->string('surat_pengantar_path')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // FIX: NIM unik PER KOMBINASI dengan instansi_asal, bukan unik
            // secara global. NIM ditentukan masing-masing kampus/sekolah,
            // jadi wajar kalau NIM yang sama muncul di 2 instansi berbeda -
            // itu BUKAN duplikat data, itu kebetulan format penomoran.
            $table->unique(['nim', 'instansi_asal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_profiles');
    }
};
