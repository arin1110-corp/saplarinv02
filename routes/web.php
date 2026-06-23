<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanPWAController;
use App\Http\Controllers\UserBBMController;
use App\Http\Controllers\AdminBBMController;
use App\Http\Controllers\AdminDriveController;
use App\Http\Controllers\AdminKinerjaController;
use App\Http\Controllers\UserKinerjaController;
use App\Http\Controllers\UserLaporanAktivitasController;
use App\Http\Controllers\AdminLaporanAktivitasController;
use App\Http\Controllers\AdminPrioritasController;
use App\Http\Controllers\UserPrioritasController;
use App\Http\Controllers\AdminProgramPrioritasController;
use App\Http\Controllers\UserProgramPrioritasController;
use App\Http\Controllers\AdminSPJController;
use App\Http\Controllers\UserSPJController;
use App\Http\Controllers\AdminSPJRequestController;

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

    /*
        |--------------------------------------------------------------------------
        | Kinerja
        |--------------------------------------------------------------------------
        */
    Route::get('/kinerja', [AdminKinerjaController::class, 'index'])
        ->name('admin.kinerja.index');

    Route::post('/kinerja/store', [AdminKinerjaController::class, 'store'])
        ->name('admin.kinerja.store');

    Route::post('/kinerja/update', [AdminKinerjaController::class, 'update'])
        ->name('admin.kinerja.update');

    Route::post('/kinerja/{uid}/delete', [AdminKinerjaController::class, 'delete'])
        ->name('admin.kinerja.delete');

    /*
            |--------------------------------------------------------------------------
            | Laporan Aktivitas
            |--------------------------------------------------------------------------
            */
    Route::get('/laporan-aktivitas', [AdminLaporanAktivitasController::class, 'index'])
        ->name('admin.laporan-aktivitas.index');

    Route::post('/laporan-aktivitas/kegiatan/{uid}/nonaktif', [AdminLaporanAktivitasController::class, 'nonaktifKegiatan'])
        ->name('admin.laporan-aktivitas.kegiatan.nonaktif');

    Route::post('/laporan-aktivitas/kegiatan/{uid}/aktif', [AdminLaporanAktivitasController::class, 'aktifKegiatan'])
        ->name('admin.laporan-aktivitas.kegiatan.aktif');

    Route::post('/laporan-aktivitas/aktivitas/{uid}/nonaktif', [AdminLaporanAktivitasController::class, 'nonaktifAktivitas'])
        ->name('admin.laporan-aktivitas.aktivitas.nonaktif');

    Route::post('/laporan-aktivitas/aktivitas/{uid}/aktif', [AdminLaporanAktivitasController::class, 'aktifAktivitas'])
        ->name('admin.laporan-aktivitas.aktivitas.aktif');
    /*
            |--------------------------------------------------------------------------
            | Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/prioritas', [AdminPrioritasController::class, 'index'])
        ->name('admin.prioritas.index');

    Route::post('/prioritas/store', [AdminPrioritasController::class, 'store'])
        ->name('admin.prioritas.store');

    Route::post('/prioritas/update', [AdminPrioritasController::class, 'update'])
        ->name('admin.prioritas.update');

    Route::post('/prioritas/bukti/{uid}/nonaktif', [AdminPrioritasController::class, 'nonaktifBukti'])
        ->name('admin.prioritas.bukti.nonaktif');

    Route::post('/prioritas/bukti/{uid}/aktif', [AdminPrioritasController::class, 'aktifBukti'])
        ->name('admin.prioritas.bukti.aktif');

    /*
            |--------------------------------------------------------------------------
            | Program Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/program-prioritas', [AdminProgramPrioritasController::class, 'index'])
        ->name('admin.program-prioritas.index');

    Route::post('/program-prioritas/store', [AdminProgramPrioritasController::class, 'store'])
        ->name('admin.program-prioritas.store');

    Route::post('/program-prioritas/update', [AdminProgramPrioritasController::class, 'update'])
        ->name('admin.program-prioritas.update');

    Route::post('/program-prioritas/rencana/{uid}/nonaktif', [AdminProgramPrioritasController::class, 'nonaktifRencana'])
        ->name('admin.program-prioritas.rencana.nonaktif');

    Route::post('/program-prioritas/rencana/{uid}/aktif', [AdminProgramPrioritasController::class, 'aktifRencana'])
        ->name('admin.program-prioritas.rencana.aktif');

    Route::post('/program-prioritas/capaian/{uid}/nonaktif', [AdminProgramPrioritasController::class, 'nonaktifCapaian'])
        ->name('admin.program-prioritas.capaian.nonaktif');

    Route::post('/program-prioritas/capaian/{uid}/aktif', [AdminProgramPrioritasController::class, 'aktifCapaian'])
        ->name('admin.program-prioritas.capaian.aktif');

    // Export
    Route::get('/program-prioritas/export', [AdminProgramPrioritasController::class, 'export'])
        ->name('admin.program-prioritas.export');

    /*
            |--------------------------------------------------------------------------
            | SPJ
            |--------------------------------------------------------------------------
            */
    Route::get('/spj', [AdminSPJController::class, 'index'])
        ->name('admin.spj.index');

    Route::post('/spj/store', [AdminSPJController::class, 'store'])
        ->name('admin.spj.store');

    Route::post('/spj/{uid}/status', [AdminSPJController::class, 'toggleStatus'])
        ->name('admin.spj.status');
    Route::post('/spj/update', [AdminSPJController::class, 'update'])
        ->name('admin.spj.update');

    /*
            |--------------------------------------------------------------------------
            | Permintaan SPJ
            |--------------------------------------------------------------------------
            */
    Route::get('/spj/permintaan', [AdminSPJRequestController::class, 'index'])
        ->name('admin.spj.request');

    Route::post('/spj/permintaan/{uid}/toggle', [AdminSPJRequestController::class, 'toggle'])
        ->name('admin.spj.request.toggle');
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

    /*
        |--------------------------------------------------------------------------
        | Kinerja
        |--------------------------------------------------------------------------
        */
    Route::get('/kinerja', [UserKinerjaController::class, 'index'])
        ->name('kinerja.index');

    Route::post('/kinerja/{uid}/progress/store', [UserKinerjaController::class, 'storeProgress'])
        ->name('kinerja.progress.store');

    Route::post('/kinerja/progress/{uid}/update', [UserKinerjaController::class, 'updateProgress'])
        ->name('kinerja.progress.update');

    /*
        |--------------------------------------------------------------------------
        | Laporan Aktivitas
        |--------------------------------------------------------------------------
        */

    Route::get('/laporan-aktivitas', [UserLaporanAktivitasController::class, 'index'])
        ->name('laporan-aktivitas.index');

    Route::post('/laporan-aktivitas/kegiatan/store', [UserLaporanAktivitasController::class, 'storeKegiatan'])
        ->name('laporan-aktivitas.kegiatan.store');

    Route::post('/laporan-aktivitas/{uid}/aktivitas/store', [UserLaporanAktivitasController::class, 'storeAktivitas'])
        ->name('laporan-aktivitas.aktivitas.store');

    /*
            |--------------------------------------------------------------------------
            | Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/prioritas', [UserPrioritasController::class, 'index'])
        ->name('prioritas.index');

    Route::post('/prioritas/{uid}/bukti/store', [UserPrioritasController::class, 'storeBukti'])
        ->name('prioritas.bukti.store');

    /*
            |--------------------------------------------------------------------------
            | Program Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/program-prioritas', [UserProgramPrioritasController::class, 'index'])
        ->name('program-prioritas.index');

    Route::post('/program-prioritas/{uid}/rencana/store', [UserProgramPrioritasController::class, 'storeRencana'])
        ->name('program-prioritas.rencana.store');

    Route::post('/program-prioritas/rencana/{uid}/capaian/store', [UserProgramPrioritasController::class, 'storeCapaian'])
        ->name('program-prioritas.capaian.store');

    /*
            |--------------------------------------------------------------------------
            | SPJ
            |--------------------------------------------------------------------------
            */
    Route::get('/spj', [UserSPJController::class, 'index'])
        ->name('spj.index');

    Route::post('/spj/{uid}/store', [UserSPJController::class, 'store'])
        ->name('spj.store');
    });