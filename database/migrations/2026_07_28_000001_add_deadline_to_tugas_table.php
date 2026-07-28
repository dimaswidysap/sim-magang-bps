<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            // Nullable supaya migration aman dijalankan meski sudah ada
            // data tugas lama tanpa deadline. Validasi "wajib diisi" untuk
            // tugas BARU cukup diatur di layer aplikasi (controller), tidak
            // perlu dipaksa NOT NULL di database.
            $table->dateTime('deadline')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });
    }
};
