<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_profile_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tugas_id', 'mahasiswa_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook');
    }
};
