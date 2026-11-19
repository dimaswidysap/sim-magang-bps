<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_liburs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_profile_id')
                ->constrained('mahasiswa_profiles')
                ->cascadeOnDelete();
            $table->foreignId('asn_profile_id')
                ->nullable()
                ->constrained('asn_profiles')
                ->nullOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->string('file_lampiran');
            $table->string('nama_file_asli')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_asn')->nullable();
            $table->timestamp('direspon_at')->nullable();
            $table->timestamps();

            $table->index(['mahasiswa_profile_id', 'status']);
            $table->index(['asn_profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_liburs');
    }
};
