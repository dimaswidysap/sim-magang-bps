<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TugasSubmissionController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AsnController;
//
use App\Http\Controllers\Admin\AdminMahasiswa;
use App\Http\Controllers\Admin\adminAsn;
// use App\Http\Controllers\Admin\AdminAsn as AdminAdminAsn;
use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Admin\AdminSkill;
//
use App\Http\Controllers\Asn\TugasController;
//
use App\Http\Controllers\Mahasiswa\MahasiswaTugas;
use App\Http\Controllers\Mahasiswa\TugasanggotacontrollerInvite;
use App\Http\Controllers\Mahasiswa\TugasAnggotaControllerRespond;
use App\Http\Controllers\Mahasiswa\TugasMahasiswaControllerAmbil;

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
        Route::get('/asn/update/{id}', [AdminAsn::class, 'formUpdateAsn'])->name('form-asn-edit'); //ini yang di edit sebelumnta
        Route::put('/asnUpdate/{id}', [AdminAsn::class, 'updateAsn'])->name('admin-asn-update');
        Route::get('/asn/create', [AdminAsn::class, 'showForm'])->name('asn.mahasiswa.create');
        Route::post('/asnCreate', [AdminAsn::class, 'storeAsn'])->name('admin-asn-store');
        Route::delete('/asn/{id}', [AdminAsn::class, 'destroyAsn'])->name('admin-asn-destroy');

        // management label skill
        Route::get('/skill', [AdminController::class, 'adminSkill'])->name('admin-skill');
        Route::get('/skill/create', [AdminSkill::class, 'createSkill'])->name('create-skill');
        Route::post('/skill', [AdminSkill::class, 'storeSkill'])->name('admin-skill-store');
        Route::get('/skill/edit/{id}', [AdminSkill::class, 'formSkillEdit'])->name('admin-skill-edit');
        Route::post('/skill/{id}', [AdminSkill::class, 'updateSkill'])->name('admin-skill-update');
        Route::delete('/skill/destroy/{id}', [AdminSkill::class, 'destroySkill'])->name('admin-skill-destroy');

        // management periode magang
        Route::get('/periode-magang', [AdminController::class, 'adminPeriodeMagang'])->name('admin-periode-magang');
        Route::get('/periode/create', [PeriodeMagangController::class, 'formPeriodeCreate'])->name('admin-periode-create');
        Route::post('/periode', [PeriodeMagangController::class, 'storePeriode'])->name('admin-periode-store');
        Route::get('/periode/edit/{id}', [PeriodeMagangController::class, 'formPeriodeEdit'])->name('admin-periode-edit');
        Route::put('/periode/{id}', [PeriodeMagangController::class, 'updatePeriode'])->name('admin-periode-update');
        Route::delete('/periode/destroy/{id}', [PeriodeMagangController::class, 'destroyPeriode'])->name('admin-periode-destroy');
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
        Route::get('/detail-tugas-saya/{id}', [MahasiswaController::class, 'detailTugasSaya'])->name('detail-tugas-saya');
        //
        route::get('/tugas/detail/{id}', [MahasiswaTugas::class, 'detailTugas'])->name('detail-tugas');
        //
        Route::get('/tugas/{id}/undang', [TugasanggotacontrollerInvite::class, 'formUndangAnggota'])->name('mahasiswa-tugas-undang-form');
        Route::post('/tugas/{id}/undang', [TugasanggotacontrollerInvite::class, 'undangAnggota'])->name('mahasiswa-tugas-undang');
        //
        Route::get('/undangan', [TugasAnggotaControllerRespond::class, 'daftarUndangan'])->name('mahasiswa-undangan');
        Route::post('/undangan/{id}/terima', [TugasAnggotaControllerRespond::class, 'terimaUndangan'])->name('mahasiswa-undangan-terima');
        Route::post('/undangan/{id}/tolak', [TugasAnggotaControllerRespond::class, 'tolakUndangan'])->name('mahasiswa-undangan-tolak');
        //
        Route::get('/tugas/tersedia', [TugasMahasiswaControllerAmbil::class, 'daftarTugasTersedia'])->name('mahasiswa-tugas-tersedia');
        Route::post('/tugas/{id}/ambil', [TugasMahasiswaControllerAmbil::class, 'ambilTugas'])->name('mahasiswa-tugas-ambil');
        //
        Route::get('/tugas/{id}/submit', [TugasSubmissionController::class, 'formSubmitTugas'])->name('mahasiswa-tugas-submit-form');
        Route::post('/tugas/{id}/submit', [TugasSubmissionController::class, 'storeSubmission'])->name('mahasiswa-tugas-submit');
    });

// ASN
Route::prefix('asn')
    ->middleware(['auth', 'role:asn'])
    ->group(function () {
        Route::get('/dashboard', [AsnController::class, 'asnIndex'])->name('asn-index');
        Route::delete('/tugas/{id}', [AsnController::class, 'destroyTugas'])->name('asn-tugas-destroy');
        Route::get('/profil', [AsnController::class, 'showFormProfil'])->name('asn-profil');
        Route::put('/profil', [AsnController::class, 'updateProfil'])->name('asn-profil-update');
        Route::get('/create-task', [AsnController::class, 'createTugasForm'])->name('asn-create-task-form');
        Route::get('/task-not-done', [AsnController::class, 'taskNotDone'])->name('task-not-done');
        Route::get('/task-done', [AsnController::class, 'taskDone'])->name('task-done');
        Route::get('/tugas-selesai-detail/{id}',[AsnController::class,'tugasSelesaiDetail'])->name('asn-tugas-selesai-detail');
        Route::get('/pengumpulan', [AsnController::class, 'pengumpulanTugas'])->name('pengumpulan-tugas-asn');
        //
        Route::post('/storeTugas', [TugasController::class, 'storeTugas'])->name('asn-store-tugas');
        Route::get('/update-tugas/{id}', [TugasController::class, 'editTugasForm'])->name('edit-tugas-form');
        Route::put('/tugas/{id}', [TugasController::class, 'updateTugas'])->name('asn-update-tugas');
        //
        Route::get('/submission', [TugasSubmissionController::class, 'daftarSubmissionMasuk'])->name('asn-submission-index');
        Route::get('/submission/{id}', [TugasSubmissionController::class, 'detailSubmission'])->name('asn-submission-detail');
        Route::post('/submission/{id}/setujui', [TugasSubmissionController::class, 'approveSubmission'])->name('asn-submission-approve');
        Route::post('/submission/{id}/revisi', [TugasSubmissionController::class, 'mintaRevisi'])->name('asn-submission-revisi');
    });
