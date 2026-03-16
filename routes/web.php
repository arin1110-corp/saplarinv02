<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanPWAController;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// ========== LOGIN USER ==========
Route::get('/login-user', [AuthController::class, 'formUser'])->name('login.user');
Route::post('/login-user', [AuthController::class, 'loginUser']);

Route::get('/daftarakunuser', function () {
    return view('daftaruser');
})->name('user.register');

// ========== LOGIN VERIFIKATOR ==========
Route::get('/login-verifikator', [AuthController::class, 'formVerifikator'])->name('login.verifikator');
Route::post('/login-verifikator', [AuthController::class, 'loginVerifikator']);

// ========== LOGIN ADMIN ==========
Route::get('/login-admin', [AuthController::class, 'formAdmin'])->name('login.admin');
Route::post('/login-admin', [AuthController::class, 'loginAdmin']);

// ========== LOGOUT ==========
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== DASHBOARD ==========
Route::middleware('auth:user')->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboarduser');
    })->name('user.dashboard');
});

Route::middleware('auth:verifikator')->group(function () {
    Route::get('/verifikator/dashboard', function () {
        return view('verifikator.dashboard');
    })->name('verifikator.dashboard');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('administrator.dashboardadmin');
    })->name('admin.dashboard');
});

Route::post('/import-laporan-pwa', [LaporanPWAController::class, 'importPWA'])->name('import.pwa');
Route::get('/import-pwa', function () {
    return view('homepage_import_pwa');
})->name('import.pwa.form');
Route::get('/data-pwa', [LaporanPWAController::class, 'inputDataPWA'])->name('data.pwa.form');
Route::post('/data-pwa', [LaporanPWAController::class, 'simpanDataPWA'])->name('data.pwa.store');
Route::get('/laporan-pwa', [LaporanPWAController::class, 'laporanPWA'])->name('laporan.pwa');