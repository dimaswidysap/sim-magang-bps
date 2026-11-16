<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('magang_logbook', function (Blueprint $table) {
            $table->boolean('is_validated')
                ->default(false)
                ->after('file_lampiran')
                ->index();
            $table->timestamp('validated_at')
                ->nullable()
                ->after('is_validated');
            $table->foreignId('validated_by')
                ->nullable()
                ->after('validated_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('magang_logbook', function (Blueprint $table) {
            $table->dropConstrainedForeignId('validated_by');
            $table->dropColumn(['is_validated', 'validated_at']);
        });
    }
};
