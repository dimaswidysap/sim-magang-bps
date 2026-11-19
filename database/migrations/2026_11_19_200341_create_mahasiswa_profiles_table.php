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
            $table->foreignId('asn_profile_id')->nullable()->constrained('asn_profiles')->nullOnDelete();
            $table->foreignId('calon_asn_profile_id')->nullable()->constrained('asn_profiles')->nullOnDelete();

            // Informasi Kelompok Magang
            $table->string('kode_kelompok', 50)->nullable()->index();
            $table->boolean('is_ketua_kelompok')->default(false);

            // Biodata & Akademik Peserta
            $table->string('foto_profil_path')->nullable();
            $table->string('nim')->nullable();
            $table->string('instansi_asal')->nullable();
            $table->enum('jenjang', ['SMA/SMK', 'D3', 'D4', 'S1', 'S2'])->nullable();
            $table->string('jurusan')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('surat_pengantar_path')->nullable();
            $table->text('catatan')->nullable();

            // Periode & Status Magang
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['pending', 'aktif', 'selesai', 'dibatalkan'])->default('pending');

            // Alur Pengajuan & Verifikasi Pembimbing
            $table->string('status_pengajuan', 30)->nullable()->index();
            $table->text('alasan_penolakan')->nullable();
            $table->text('pesan_penolakan_asn')->nullable();
            $table->timestamp('diajukan_at')->nullable();
            $table->timestamp('dikirim_ke_asn_at')->nullable();
            $table->timestamp('direspon_asn_at')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diverifikasi_at')->nullable();

            $table->timestamps();

            $table->unique(['nim', 'instansi_asal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_profiles');
    }
};
