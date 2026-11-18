<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {$table->string('kode_kelompok', 50)->nullable()->after('asn_profile_id')->index();
            $table->boolean('is_ketua_kelompok')->default(false)->after('kode_kelompok');$table->string('status_pengajuan', 30)->nullable()->after('status')->index();
            // Nilai status_pengajuan: 'belum_diajukan', 'menunggu_verifikasi', 'diterima', 'ditolak'
            $table->text('alasan_penolakan')->nullable()->after('status_pengajuan');
            $table->timestamp('diajukan_at')->nullable()->after('alasan_penolakan');$table->foreignId('diverifikasi_oleh')
                ->nullable()
                ->after('diajukan_at')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('diverifikasi_at')->nullable()->after('diverifikasi_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('diverifikasi_oleh');$table->dropColumn([
                'kode_kelompok',
                'is_ketua_kelompok',
                'status_pengajuan',
                'alasan_penolakan',
                'diajukan_at',
                'diverifikasi_at',
            ]);
        });
    }
};
