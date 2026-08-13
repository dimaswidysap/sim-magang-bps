<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. table_mahasiswa_profiles
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

            $table->index('nim');
        });

        // 2. table_asn_profiles
        Schema::create('asn_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nip')->unique();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->timestamps();
        });

        // 3. table_mahasiswa_profile_skill
        Schema::create('mahasiswa_profile_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_profile_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['mahasiswa_profile_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_profile_skill');
        Schema::dropIfExists('asn_profiles');
        Schema::dropIfExists('mahasiswa_profiles');
    }
};
