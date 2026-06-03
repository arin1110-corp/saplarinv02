<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanPWAController;

Route::get('/', function () {
    return view('homepage/home');
})->name('homepage');

Route::post('/login-submit', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::get('/login-admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/set-role', [AuthController::class, 'setRole'])->name('set.role');

// Route::get('/laporan-pwa', [LaporanPWAController::class, 'laporanPWA'])->name('laporan.pwa');

Route::middleware(['admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('/users', [AdminController::class, 'manageUser'])->name('admin.users');

        Route::post('/store', [AdminController::class, 'storeUser']);

        Route::post('/update-role', [AdminController::class, 'updateRole']);

        Route::get('/program', [AdminController::class, 'program'])->name('admin.program');
        Route::post('/program/store', [AdminController::class, 'storeProgram'])->name('admin.program.store');
        Route::post('/program/update', [AdminController::class, 'updateProgram'])->name('admin.program.update');

        Route::get('/kegiatan', [AdminController::class, 'kegiatan'])->name('admin.kegiatan');
        Route::post('/kegiatan/store', [AdminController::class, 'storeKegiatan'])->name('admin.kegiatan.store');
        Route::post('/kegiatan/update', [AdminController::class, 'updateKegiatan'])->name('admin.kegiatan.update');

        Route::get('/sub-kegiatan', [AdminController::class, 'subkegiatan'])->name('admin.subkegiatan');
        Route::post('/sub-kegiatan/store', [AdminController::class, 'storeSubKegiatan'])->name('admin.subkegiatan.store');
        Route::post('/sub-kegiatan/update', [AdminController::class, 'updateSubKegiatan'])->name('admin.subkegiatan.update');

        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');

        Route::get('/laporan-spj', [AdminController::class, 'laporanSPJ'])->name('admin.laporan.spj');

        Route::get('/laporan-pwa', [LaporanPWAController::class, 'laporanPWA'])->name('admin.laporan.pwa');

        Route::get('/laporan-kegiatan', [AdminController::class, 'laporanKegiatan'])->name('admin.laporan.kegiatan');

        Route::get('/laporan-sub-kegiatan', [AdminController::class, 'laporanSubKegiatan'])->name('admin.laporan.subkegiatan');

        Route::get('/laporan-kak', [AdminController::class, 'laporanKAK'])->name('admin.laporan.kak');

        Route::get('/permintaan/permintaan-spj', [AdminController::class, 'permintaanSPJ'])->name('admin.permintaan.spj');
        Route::get('/permintaan/permintaan-kak', [AdminController::class, 'permintaanKAK'])->name('admin.permintaan.kak');
    });

Route::prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboardUser'])->name('dashboard');

        Route::get('/bbm', [AuthController::class, 'bbm'])->name('bbm.index');
        Route::get('/spj', [AuthController::class, 'spj'])->name('spj.index');
        Route::get('/kak', [AuthController::class, 'kak'])->name('kak.index');
        Route::get('/pwa', [AuthController::class, 'pwa'])->name('pwa.index');

        Route::get('/riwayat', [AuthController::class, 'riwayat'])->name('riwayat');
    });