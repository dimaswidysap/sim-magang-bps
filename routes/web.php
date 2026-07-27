<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminMahasiswa;
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
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'adminIndex'])->name('admin-index');
    Route::get('/mahasiswa',[AdminController::class,'adminMahasiswa'])->name('admin-mahasiswa');

    //
    Route::get('/mahasiswa/create',[AdminMahasiswa::class,'showForm'])->name('admin.mahasiswa.create');
    Route::post('/mahasiswa', [AdminMahasiswa::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
    Route::get('/mahasiswa/detail/{id}',[AdminMahasiswa::class,'detailMahasiswa'])->name('admin-mahasiswa-detail');
    Route::get('/mahasiswa/update/{id}',[AdminMahasiswa::class,'formMahasiswaEdit'])->name('form-mahasiswa-edit');
    Route::put('/mahasiswa/{id}', [AdminMahasiswa::class, 'updateMahasiswa'])->name('admin-mahasiswa-update');
});

// MAHASISWA
Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/', [MahasiswaController::class, 'mahasiswaIndex'])->name('mahasiswa-index');
});

// ASN
Route::prefix('asn')->middleware(['auth', 'role:asn'])->group(function () {
    Route::get('/', [AsnController::class, 'asnIndex'])->name('asn-index');
});

// Nanti tambahkan pola yang sama untuk asn & mahasiswa:
// Route::prefix('asn')->middleware(['auth', 'role:asn'])->group(function () { ... });
// Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->group(function () { ... });
