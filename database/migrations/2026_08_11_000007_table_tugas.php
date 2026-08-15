<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asn_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('periode_magang_id')->nullable()->constrained('periode_magang')->nullOnDelete();
            $table->foreignId('mahasiswa_profile_id')->nullable()->constrained('mahasiswa_profiles')->nullOnDelete();

            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('deadline')->nullable();
            $table->enum('status', ['tersedia', 'diambil', 'menunggu_review', 'revisi', 'selesai'])->default('tersedia');
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
