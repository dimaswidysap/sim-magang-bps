<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tools:cleanup-tmp', function () {
    $baseTmpDir = storage_path('app/tmp');

    if (!File::isDirectory($baseTmpDir)) {
        $this->info('Direktori storage/app/tmp belum ada.');
        return;
    }

    $threshold = now()->subHours(2)->timestamp;
    $deleted = 0;

    foreach (File::directories($baseTmpDir) as $dir) {
        if (File::lastModified($dir) < $threshold) {
            File::deleteDirectory($dir);
            $deleted++;
        }
    }

    $this->info("Pembersihan selesai: {$deleted} folder sementara dihapus.");
})->purpose('Membersihkan folder sementara Tools FASIH di storage/app/tmp yang berusia lebih dari 2 jam');

Schedule::command('tools:cleanup-tmp')->hourly();
