<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tugas_anggota', function (Blueprint $table) {
            $table->dropForeign(['diundang_oleh']);
        });

        Schema::table('tugas_anggota', function (Blueprint $table) {
            // Nullable karena kalau sumbernya 'ditugaskan_asn', tidak ada
            // mahasiswa yang "mengundang" - yang menugaskan itu ASN, bukan
            // mahasiswa, dan kolom ini cuma bisa merujuk ke mahasiswa_profiles.
            $table->foreignId('diundang_oleh')->nullable()->change();

            $table
                ->enum('sumber', ['undangan_teman', 'ditugaskan_asn'])
                ->default('undangan_teman')
                ->after('diundang_oleh');
        });

        Schema::table('tugas_anggota', function (Blueprint $table) {
            $table->foreign('diundang_oleh')->references('id')->on('mahasiswa_profiles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tugas_anggota', function (Blueprint $table) {
            $table->dropForeign(['diundang_oleh']);
            $table->dropColumn('sumber');
        });

        Schema::table('tugas_anggota', function (Blueprint $table) {
            $table->foreignId('diundang_oleh')->nullable(false)->change();
            $table->foreign('diundang_oleh')->references('id')->on('mahasiswa_profiles')->cascadeOnDelete();
        });
    }
};
