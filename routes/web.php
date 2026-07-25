<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('home.index');
})->name('landing-page');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login-form');

