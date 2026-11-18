<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->foreignId('calon_asn_profile_id')
                ->nullable()
                ->after('asn_profile_id')
                ->constrained('asn_profiles')
                ->nullOnDelete();
            $table->text('pesan_penolakan_asn')->nullable()->after('alasan_penolakan');
            $table->timestamp('dikirim_ke_asn_at')->nullable()->after('diajukan_at');
            $table->timestamp('direspon_asn_at')->nullable()->after('dikirim_ke_asn_at');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('calon_asn_profile_id');
            $table->dropColumn([
                'pesan_penolakan_asn',
                'dikirim_ke_asn_at',
                'direspon_asn_at',
            ]);
        });
    }
};
