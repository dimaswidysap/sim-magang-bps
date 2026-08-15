<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_profile_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->enum('status', ['diundang', 'diterima', 'ditolak'])->default('diundang');

            // Nullable karena kalau sumber = 'ditugaskan_asn', tidak ada
            // mahasiswa yang "mengundang" - kolom ini cuma bisa merujuk
            // ke mahasiswa_profiles, sementara yang menugaskan itu ASN.
            $table->foreignId('diundang_oleh')->nullable()->constrained('mahasiswa_profiles')->nullOnDelete();
            $table->enum('sumber', ['undangan_teman', 'ditugaskan_asn'])->default('undangan_teman');

            $table->timestamps();

            $table->unique(['tugas_id', 'mahasiswa_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_anggota');
    }
};
