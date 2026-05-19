<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanPWAController;

Route::get('/', function () {
    return view('homepage/home');
})->name('homepage');

Route::post('/login-submit', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::get('/login-admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');