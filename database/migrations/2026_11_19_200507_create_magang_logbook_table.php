<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magang_logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_profile_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->date('tanggal_kegiatan');
            $table->string('judul_kegiatan');
            $table->string('lokasi')->nullable();
            $table->text('deskripsi_kegiatan');
            $table->text('file_lampiran')->nullable();
            $table->boolean('is_validated')->default(false)->index();
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magang_logbook');
    }
};
