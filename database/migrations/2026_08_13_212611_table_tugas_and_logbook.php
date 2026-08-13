<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. table_tugas
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asn_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('periode_magang_id')->nullable()->constrained('periode_magang')->nullOnDelete();
            $table->foreignId('mahasiswa_profile_id')->nullable()->constrained('mahasiswa_profiles')->nullOnDelete();

            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('deadline')->nullable(); // Ditambahkan langsung
            $table->enum('status', ['tersedia', 'diambil', 'menunggu_review', 'revisi', 'selesai'])->default('tersedia');
            $table->timestamp('diambil_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        // 2. table_tugas_skill
        Schema::create('tugas_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tugas_id', 'skill_id']);
        });

        // 3. table_tugas_anggota
        Schema::create('tugas_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_profile_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->enum('status', ['diundang', 'diterima', 'ditolak'])->default('diundang');

            // Kolom dimodifikasi langsung
            $table->foreignId('diundang_oleh')->nullable()->constrained('mahasiswa_profiles')->nullOnDelete();
            $table->enum('sumber', ['undangan_teman', 'ditugaskan_asn'])->default('undangan_teman');

            $table->timestamps();
            $table->unique(['tugas_id', 'mahasiswa_profile_id']);
        });

        // 4. table_tugas_submissions
        Schema::create('tugas_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();

            // Kolom file dijadikan nullable secara langsung
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();

            $table->text('catatan_mahasiswa')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'revisi'])->default('menunggu');
            $table->text('catatan_asn')->nullable();
            $table->foreignId('direview_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('direview_at')->nullable();
            $table->timestamps();

            $table->index(['tugas_id', 'status']);
        });

        // 5. table_tugas_attachments
        Schema::create('tugas_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type');
            $table->timestamps();
        });

        // 6. table_logbook (Menggantikan Absen)
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_profile_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tugas_id', 'mahasiswa_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook');
        Schema::dropIfExists('tugas_attachments');
        Schema::dropIfExists('tugas_submissions');
        Schema::dropIfExists('tugas_anggota');
        Schema::dropIfExists('tugas_skill');
        Schema::dropIfExists('tugas');
    }
};
