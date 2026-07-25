<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat pengumpulan hasil tugas DIPISAH dari tabel `tugas` (bukan kolom
     * tunggal) karena ada alur revisi: 1 tugas bisa punya lebih dari 1 kali
     * submission (submit -> revisi -> submit ulang), dan tiap submission
     * perlu menyimpan feedback ASN masing-masing agar riwayatnya tidak hilang.
     */
    public function up(): void
    {
        Schema::create('tugas_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')
                ->constrained('tugas')
                ->cascadeOnDelete();

            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size'); // dalam bytes
            $table->string('mime_type');
            $table->text('catatan_mahasiswa')->nullable();

            $table->enum('status', ['menunggu', 'disetujui', 'revisi'])
                ->default('menunggu');
            $table->text('catatan_asn')->nullable();
            $table->foreignId('direview_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
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
