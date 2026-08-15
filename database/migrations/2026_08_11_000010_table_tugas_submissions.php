<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();

            // Nullable - mahasiswa boleh submit dengan pesan teks saja
            // tanpa file, atau file saja tanpa pesan (minimal salah satu).
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
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_submissions');
    }
};
