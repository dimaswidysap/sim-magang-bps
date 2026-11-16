<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->foreignId('asn_profile_id')
                ->nullable()
                ->after('user_id')
                ->constrained('asn_profiles')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('asn_profile_id');
        });
    }
};
