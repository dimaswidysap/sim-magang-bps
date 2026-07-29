<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminMahasiswa;
use App\Http\Controllers\Admin\adminAsn;
use App\Http\Controllers\Admin\AdminAsn as AdminAdminAsn;
use App\Http\Controllers\Asn\TugasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AsnController;

Route::get('/', function () {
    return view('home.index');
})->name('landing-page');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login-form');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// SUPER ADMIN
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [AdminController::class, 'adminIndex'])->name('admin-index');

        // Management Mahasiswa
        Route::get('/mahasiswa', [AdminController::class, 'adminMahasiswa'])->name('admin-mahasiswa');
        Route::get('/mahasiswa/create', [AdminMahasiswa::class, 'showForm'])->name('admin.mahasiswa.create');
        Route::post('/mahasiswa', [AdminMahasiswa::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
        Route::get('/mahasiswa/detail/{id}', [AdminMahasiswa::class, 'detailMahasiswa'])->name('admin-mahasiswa-detail');
        Route::get('/mahasiswa/update/{id}', [AdminMahasiswa::class, 'formMahasiswaEdit'])->name('form-mahasiswa-edit');
        Route::put('/mahasiswa/{id}', [AdminMahasiswa::class, 'updateMahasiswa'])->name('admin-mahasiswa-update');
        Route::delete('/destroyMahasiswa/{id}', [AdminMahasiswa::class, 'destroyMahasiswa'])->name('admin-mahasiswa-destroy');

        // Management ASN
        Route::get('/asn', [AdminController::class, 'adminAsn'])->name('admin-asn');
        Route::get('/asn/detail/{id}', [AdminAsn::class, 'detailAsn'])->name('admin-asn-detail');
        Route::get('/asn/update/{id}', [AdminAdminAsn::class, 'formUpdateAsn'])->name('form-asn-edit');
        Route::put('/asnUpdate/{id}', [AdminAsn::class, 'updateAsn'])->name('admin-asn-update');
        Route::get('/asn/create', [AdminAsn::class, 'showForm'])->name('asn.mahasiswa.create');
        Route::post('/asnCreate', [AdminAsn::class, 'storeAsn'])->name('admin-asn-store');
        Route::delete('/asn/{id}', [AdminAsn::class, 'destroyAsn'])->name('admin-asn-destroy');

        // management label skill
        Route::get('/skill',[AdminController::class,'adminSkill'])->name('admin-skill');


        // management periode magang
        Route::get('/periode-magang',[AdminController::class,'adminPeriodeMagang'])->name('admin-periode-magang');

    });

// MAHASISWA
Route::prefix('mahasiswa')
    ->middleware(['auth', 'role:mahasiswa'])
    ->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'mahasiswaIndex'])->name('mahasiswa-index');

        Route::get('/profil', [MahasiswaController::class, 'showFormProfil'])->name('mahasiswa-profil');
        Route::put('/profil', [MahasiswaController::class, 'updateProfil'])->name('mahasiswa-profil-update');

        Route::get('/tugas', [MahasiswaController::class, 'tugas'])->name('tugas');
        Route::get('/tugas-saya', [MahasiswaController::class, 'tugasSaya'])->name('tugas-saya');
    });

// ASN
Route::prefix('asn')
    ->middleware(['auth', 'role:asn'])
    ->group(function () {
        Route::get('/dashboard', [AsnController::class, 'asnIndex'])->name('asn-index');

        Route::get('/profil', [AsnController::class, 'showFormProfil'])->name('asn-profil');
        Route::put('/profil', [AsnController::class, 'updateProfil'])->name('asn-profil-update');

        Route::get('/create-task', [AsnController::class, 'createTugasForm'])->name('asn-create-task-form');
        Route::post('/storeTugas', [TugasController::class, 'storeTugas'])->name('asn-store-tugas');
        Route::get('/task-not-done', [AsnController::class, 'taskNotDone'])->name('task-not-done');
        Route::get('/task-done', [AsnController::class, 'taskDone'])->name('task-done');
    });
