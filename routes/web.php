<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanPWAController;
use App\Http\Controllers\UserBBMController;
use App\Http\Controllers\AdminBBMController;
use App\Http\Controllers\AdminDriveController;

Route::get('/', function () {
    return view('homepage/home');
})->name('homepage');

Route::post('/login-submit', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::get('/login-admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/set-role', [AuthController::class, 'setRole'])->name('set.role');

Route::middleware(['admin'])
    ->prefix('admin')
    ->group(function () {

    /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

    Route::get('/users', [AdminController::class, 'manageUser'])
        ->name('admin.users');

    Route::post('/store', [AdminController::class, 'storeUser'])
        ->name('admin.users.store');

    Route::post('/update-role', [AdminController::class, 'updateRole'])
        ->name('admin.users.updateRole');

    /*
        |--------------------------------------------------------------------------
        | Master Data
        |--------------------------------------------------------------------------
        */

    Route::get('/program', [AdminController::class, 'program'])
        ->name('admin.program');

    Route::post('/program/store', [AdminController::class, 'storeProgram'])
        ->name('admin.program.store');

    Route::post('/program/update', [AdminController::class, 'updateProgram'])
        ->name('admin.program.update');

    Route::get('/kegiatan', [AdminController::class, 'kegiatan'])
        ->name('admin.kegiatan');

    Route::post('/kegiatan/store', [AdminController::class, 'storeKegiatan'])
        ->name('admin.kegiatan.store');

    Route::post('/kegiatan/update', [AdminController::class, 'updateKegiatan'])
        ->name('admin.kegiatan.update');

    Route::get('/sub-kegiatan', [AdminController::class, 'subkegiatan'])
        ->name('admin.subkegiatan');

    Route::post('/sub-kegiatan/store', [AdminController::class, 'storeSubKegiatan'])
        ->name('admin.subkegiatan.store');

    Route::post('/sub-kegiatan/update', [AdminController::class, 'updateSubKegiatan'])
        ->name('admin.subkegiatan.update');

    /*
        |--------------------------------------------------------------------------
        | Data Permintaan
        |--------------------------------------------------------------------------
        */

    Route::get('/permintaan/permintaan-spj', [AdminController::class, 'permintaanSPJ'])
        ->name('admin.permintaan.spj');

    Route::get('/permintaan/permintaan-kak', [AdminController::class, 'permintaanKAK'])
        ->name('admin.permintaan.kak');

    /*
        |--------------------------------------------------------------------------
        | Admin BBM
        |--------------------------------------------------------------------------
        */

    Route::get('/bbm', [AdminBBMController::class, 'index'])
        ->name('admin.bbm.index');

    Route::post('/bbm/{uid}/terima-pengajuan', [AdminBBMController::class, 'terimaPengajuan'])
        ->name('admin.bbm.terimaPengajuan');

    Route::post('/bbm/{uid}/tolak-pengajuan', [AdminBBMController::class, 'tolakPengajuan'])
        ->name('admin.bbm.tolakPengajuan');

    Route::post('/bbm/{uid}/terima-laporan', [AdminBBMController::class, 'terimaLaporan'])
        ->name('admin.bbm.terimaLaporan');

    Route::post('/bbm/{uid}/tolak-laporan', [AdminBBMController::class, 'tolakLaporan'])
        ->name('admin.bbm.tolakLaporan');

    Route::post('/bbm/{uid}/sinkron', [AdminBBMController::class, 'sinkronPengajuan'])
        ->name('admin.bbm.sinkron');

    /*
        |--------------------------------------------------------------------------
        | Drive Management
        |--------------------------------------------------------------------------
        */

    Route::get('/drive/json', [AdminDriveController::class, 'json'])
        ->name('admin.drive.json');

    Route::post('/drive/json/store', [AdminDriveController::class, 'storeJson'])
        ->name('admin.drive.json.store');

    Route::post('/drive/json/update', [AdminDriveController::class, 'updateJson'])
        ->name('admin.drive.json.update');

    Route::get('/drive/folder', [AdminDriveController::class, 'folder'])
        ->name('admin.drive.folder');

    Route::post('/drive/folder/store', [AdminDriveController::class, 'storeFolder'])
        ->name('admin.drive.folder.store');

    Route::post('/drive/folder/update', [AdminDriveController::class, 'updateFolder'])
        ->name('admin.drive.folder.update');

    /*
        |--------------------------------------------------------------------------
        | Laporan
        |--------------------------------------------------------------------------
        */

    Route::get('/laporan', [AdminController::class, 'laporan'])
        ->name('admin.laporan');

    Route::get('/laporan-spj', [AdminController::class, 'laporanSPJ'])
        ->name('admin.laporan.spj');

    Route::get('/laporan-pwa', [LaporanPWAController::class, 'laporanPWA'])
        ->name('admin.laporan.pwa');

    Route::get('/laporan-kegiatan', [AdminController::class, 'laporanKegiatan'])
        ->name('admin.laporan.kegiatan');

    Route::get('/laporan-sub-kegiatan', [AdminController::class, 'laporanSubKegiatan'])
        ->name('admin.laporan.subkegiatan');

    Route::get('/laporan-kak', [AdminController::class, 'laporanKAK'])
        ->name('admin.laporan.kak');
    });

Route::prefix('user')
    ->name('user.')
    ->group(function () {

    /*
        |--------------------------------------------------------------------------
        | Dashboard User
        |--------------------------------------------------------------------------
        */

    Route::get('/dashboard', [AuthController::class, 'dashboardUser'])
        ->name('dashboard');

    /*
        |--------------------------------------------------------------------------
        | Pengajuan User
        |--------------------------------------------------------------------------
        */

    Route::get('/bbm', [UserBBMController::class, 'index'])
        ->name('bbm.index');

    Route::get('/bbm/create', [UserBBMController::class, 'create'])
        ->name('bbm.create');

    Route::post('/bbm/store', [UserBBMController::class, 'store'])
        ->name('bbm.store');

    Route::get('/bbm/{uid}', [UserBBMController::class, 'show'])
        ->name('bbm.show');

    Route::post('/bbm/{uid}/laporan', [UserBBMController::class, 'uploadLaporan'])
        ->name('bbm.laporan');

    Route::get('/spj', [AuthController::class, 'spj'])
        ->name('spj.index');

    Route::get('/kak', [AuthController::class, 'kak'])
        ->name('kak.index');

    Route::get('/pwa', [AuthController::class, 'pwa'])
        ->name('pwa.index');

    /*
        |--------------------------------------------------------------------------
        | Riwayat
        |--------------------------------------------------------------------------
        */

    Route::get('/riwayat', [AuthController::class, 'riwayat'])
        ->name('riwayat');
    });