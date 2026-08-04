<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Anggota TAMBAHAN di luar ketua (tugas.mahasiswa_profile_id).
     * Ketua = yang pertama kali ambil tugas, tetap tercatat di kolom lama.
     * Tabel ini cuma untuk anggota yang diundang ketua setelahnya.
     */
    public function up(): void
    {
        Schema::create('tugas_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')
                ->constrained('tugas')
                ->cascadeOnDelete();
            $table->foreignId('mahasiswa_profile_id')
                ->constrained('mahasiswa_profiles')
                ->cascadeOnDelete();
            $table->enum('status', ['diundang', 'diterima', 'ditolak'])
                ->default('diundang');
            $table->foreignId('diundang_oleh')
                ->constrained('mahasiswa_profiles')
                ->cascadeOnDelete();
            $table->timestamps();

            // 1 mahasiswa cuma bisa diundang 1x untuk tugas yang sama
            // (mencegah undangan duplikat ke orang yang sama)
            $table->unique(['tugas_id', 'mahasiswa_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_anggota');
    }
};

