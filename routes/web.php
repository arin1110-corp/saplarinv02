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
use App\Http\Controllers\AdminLaporanSPJController;
use App\Http\Controllers\AdminSubKegiatanIndikatorController;
use App\Http\Controllers\UserSubKegiatanLaporanController;
use App\Http\Controllers\AdminLaporanSubKegiatanController;
use App\Http\Controllers\UserSHSController;
use App\Http\Controllers\AdminLaporanSHSController;
use App\Http\Controllers\AdminSHSKelompokController;
use App\Http\Controllers\Administrator\AdminSHSSatuanController;

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

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

    Route::get('/users', [AdminController::class, 'manageUser'])->name('admin.users');

    Route::post('/store', [AdminController::class, 'storeUser'])->name('admin.users.store');

    Route::post('/update-role', [AdminController::class, 'updateRole'])->name('admin.users.updateRole');

    /*
        |--------------------------------------------------------------------------
        | Master Data
        |--------------------------------------------------------------------------
        */

    Route::get('/program', [AdminController::class, 'program'])->name('admin.program');

    Route::post('/program/store', [AdminController::class, 'storeProgram'])->name('admin.program.store');

    Route::post('/program/update', [AdminController::class, 'updateProgram'])->name('admin.program.update');

    Route::get('/kegiatan', [AdminController::class, 'kegiatan'])->name('admin.kegiatan');

    Route::post('/kegiatan/store', [AdminController::class, 'storeKegiatan'])->name('admin.kegiatan.store');

    Route::post('/kegiatan/update', [AdminController::class, 'updateKegiatan'])->name('admin.kegiatan.update');

    Route::get('/sub-kegiatan', [AdminController::class, 'subkegiatan'])->name('admin.subkegiatan');

    Route::post('/sub-kegiatan/store', [AdminController::class, 'storeSubKegiatan'])->name('admin.subkegiatan.store');

    Route::post('/sub-kegiatan/update', [AdminController::class, 'updateSubKegiatan'])->name('admin.subkegiatan.update');

    /*
        |--------------------------------------------------------------------------
        | Data Permintaan
        |--------------------------------------------------------------------------
        */

    Route::get('/permintaan/permintaan-spj', [AdminController::class, 'permintaanSPJ'])->name('admin.permintaan.spj');

    Route::get('/permintaan/permintaan-kak', [AdminController::class, 'permintaanKAK'])->name('admin.permintaan.kak');

    /*
        |--------------------------------------------------------------------------
        | Admin BBM
        |--------------------------------------------------------------------------
        */

    Route::get('/bbm', [AdminBBMController::class, 'index'])->name('admin.bbm.index');

    Route::post('/bbm/{uid}/terima-pengajuan', [AdminBBMController::class, 'terimaPengajuan'])->name('admin.bbm.terimaPengajuan');

    Route::post('/bbm/{uid}/tolak-pengajuan', [AdminBBMController::class, 'tolakPengajuan'])->name('admin.bbm.tolakPengajuan');

    Route::post('/bbm/{uid}/terima-laporan', [AdminBBMController::class, 'terimaLaporan'])->name('admin.bbm.terimaLaporan');

    Route::post('/bbm/{uid}/tolak-laporan', [AdminBBMController::class, 'tolakLaporan'])->name('admin.bbm.tolakLaporan');

    Route::post('/bbm/{uid}/sinkron', [AdminBBMController::class, 'sinkronPengajuan'])->name('admin.bbm.sinkron');

    /*
        |--------------------------------------------------------------------------
        | Drive Management
        |--------------------------------------------------------------------------
        */

    Route::get('/drive/json', [AdminDriveController::class, 'json'])->name('admin.drive.json');

    Route::post('/drive/json/store', [AdminDriveController::class, 'storeJson'])->name('admin.drive.json.store');

    Route::post('/drive/json/update', [AdminDriveController::class, 'updateJson'])->name('admin.drive.json.update');

    Route::get('/drive/folder', [AdminDriveController::class, 'folder'])->name('admin.drive.folder');

    Route::post('/drive/folder/store', [AdminDriveController::class, 'storeFolder'])->name('admin.drive.folder.store');

    Route::post('/drive/folder/update', [AdminDriveController::class, 'updateFolder'])->name('admin.drive.folder.update');

    /*
        |--------------------------------------------------------------------------
        | Laporan
        |--------------------------------------------------------------------------
        */

    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');

    Route::get('/laporan-spj', [AdminLaporanSPJController::class, 'index'])->name('admin.laporan.spj');

    Route::get('/laporan-pwa', [LaporanPWAController::class, 'laporanPWA'])->name('admin.laporan.pwa');

    Route::get('/laporan-kegiatan', [AdminController::class, 'laporanKegiatan'])->name('admin.laporan.kegiatan');

    Route::get('/laporan-sub-kegiatan', [AdminController::class, 'laporanSubKegiatan'])->name('admin.laporan.subkegiatan');

    Route::get('/laporan-kak', [AdminController::class, 'laporanKAK'])->name('admin.laporan.kak');

    /*
        |--------------------------------------------------------------------------
        | Kinerja
        |--------------------------------------------------------------------------
        */
    Route::get('/kinerja', [AdminKinerjaController::class, 'index'])->name('admin.kinerja.index');

    Route::post('/kinerja/store', [AdminKinerjaController::class, 'store'])->name('admin.kinerja.store');

    Route::post('/kinerja/update', [AdminKinerjaController::class, 'update'])->name('admin.kinerja.update');

    Route::post('/kinerja/{uid}/delete', [AdminKinerjaController::class, 'delete'])->name('admin.kinerja.delete');

    /*
            |--------------------------------------------------------------------------
            | Laporan Aktivitas
            |--------------------------------------------------------------------------
            */
    Route::get('/laporan-aktivitas', [AdminLaporanAktivitasController::class, 'index'])->name('admin.laporan-aktivitas.index');

    Route::post('/laporan-aktivitas/kegiatan/{uid}/nonaktif', [AdminLaporanAktivitasController::class, 'nonaktifKegiatan'])->name('admin.laporan-aktivitas.kegiatan.nonaktif');

    Route::post('/laporan-aktivitas/kegiatan/{uid}/aktif', [AdminLaporanAktivitasController::class, 'aktifKegiatan'])->name('admin.laporan-aktivitas.kegiatan.aktif');

    Route::post('/laporan-aktivitas/aktivitas/{uid}/nonaktif', [AdminLaporanAktivitasController::class, 'nonaktifAktivitas'])->name('admin.laporan-aktivitas.aktivitas.nonaktif');

    Route::post('/laporan-aktivitas/aktivitas/{uid}/aktif', [AdminLaporanAktivitasController::class, 'aktifAktivitas'])->name('admin.laporan-aktivitas.aktivitas.aktif');
    /*
            |--------------------------------------------------------------------------
            | Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/prioritas', [AdminPrioritasController::class, 'index'])->name('admin.prioritas.index');

    Route::post('/prioritas/store', [AdminPrioritasController::class, 'store'])->name('admin.prioritas.store');

    Route::post('/prioritas/update', [AdminPrioritasController::class, 'update'])->name('admin.prioritas.update');

    Route::post('/prioritas/bukti/{uid}/nonaktif', [AdminPrioritasController::class, 'nonaktifBukti'])->name('admin.prioritas.bukti.nonaktif');

    Route::post('/prioritas/bukti/{uid}/aktif', [AdminPrioritasController::class, 'aktifBukti'])->name('admin.prioritas.bukti.aktif');

    /*
            |--------------------------------------------------------------------------
            | Program Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/program-prioritas', [AdminProgramPrioritasController::class, 'index'])->name('admin.program-prioritas.index');

    Route::post('/program-prioritas/store', [AdminProgramPrioritasController::class, 'store'])->name('admin.program-prioritas.store');

    Route::post('/program-prioritas/update', [AdminProgramPrioritasController::class, 'update'])->name('admin.program-prioritas.update');

    Route::post('/program-prioritas/rencana/{uid}/nonaktif', [AdminProgramPrioritasController::class, 'nonaktifRencana'])->name('admin.program-prioritas.rencana.nonaktif');

    Route::post('/program-prioritas/rencana/{uid}/aktif', [AdminProgramPrioritasController::class, 'aktifRencana'])->name('admin.program-prioritas.rencana.aktif');

    Route::post('/program-prioritas/capaian/{uid}/nonaktif', [AdminProgramPrioritasController::class, 'nonaktifCapaian'])->name('admin.program-prioritas.capaian.nonaktif');

    Route::post('/program-prioritas/capaian/{uid}/aktif', [AdminProgramPrioritasController::class, 'aktifCapaian'])->name('admin.program-prioritas.capaian.aktif');

    // Export
    Route::get('/program-prioritas/export', [AdminProgramPrioritasController::class, 'export'])->name('admin.program-prioritas.export');

    /*
            |--------------------------------------------------------------------------
            | SPJ
            |--------------------------------------------------------------------------
            */
    Route::get('/spj', [AdminSPJController::class, 'index'])->name('admin.spj.index');

    Route::post('/spj/store', [AdminSPJController::class, 'store'])->name('admin.spj.store');

    Route::post('/spj/{uid}/status', [AdminSPJController::class, 'toggleStatus'])->name('admin.spj.status');
    Route::post('/spj/update', [AdminSPJController::class, 'update'])->name('admin.spj.update');

    /*
            |--------------------------------------------------------------------------
            | Permintaan SPJ
            |--------------------------------------------------------------------------
            */
    Route::get('/spj/permintaan', [AdminSPJRequestController::class, 'index'])->name('admin.permintaan.spj');

    Route::post('/spj/permintaan/{uid}/toggle', [AdminSPJRequestController::class, 'toggle'])->name('admin.permintaan.spj.toggle');

    /*
            |--------------------------------------------------------------------------
            | Permintaan Sub Kegiatan
            |--------------------------------------------------------------------------
            */
    Route::get('/sub-kegiatan-indikator', [AdminSubKegiatanIndikatorController::class, 'index'])->name('admin.sub-kegiatan-indikator.index');

    Route::post('/sub-kegiatan-indikator/store', [AdminSubKegiatanIndikatorController::class, 'store'])->name('admin.sub-kegiatan-indikator.store');

    Route::post('/sub-kegiatan-indikator/update', [AdminSubKegiatanIndikatorController::class, 'update'])->name('admin.sub-kegiatan-indikator.update');

    Route::post('/sub-kegiatan-indikator/{uid}/delete', [AdminSubKegiatanIndikatorController::class, 'delete'])->name('admin.sub-kegiatan-indikator.delete');
    Route::get('/laporan-sub-kegiatan', [AdminLaporanSubKegiatanController::class, 'index'])->name('admin.laporan.subkegiatan');

    /*
            |--------------------------------------------------------------------------
            | SHS
            |--------------------------------------------------------------------------
            */
    Route::get('/shs', [AdminLaporanSHSController::class, 'index'])->name('admin.shs.index');

    Route::post('/shs/{uid}/aktif', [AdminLaporanSHSController::class, 'aktif'])->name('admin.shs.aktif');

    Route::post('/shs/{uid}/nonaktif', [AdminLaporanSHSController::class, 'nonaktif'])->name('admin.shs.nonaktif');
    Route::get('/laporan-shs', [AdminLaporanSHSController::class, 'index'])->name('admin.laporan.shs');

    Route::get('/laporan-shs/export', [AdminLaporanSHSController::class, 'export'])->name('admin.laporan.shs.export');

    Route::get('/laporan-shs/{uid}', [AdminLaporanSHSController::class, 'show'])->name('admin.laporan.shs.show');

    Route::post('/laporan-shs/{uid}/verifikasi', [AdminLaporanSHSController::class, 'verifikasi'])->name('admin.laporan.shs.verifikasi');

    Route::post('/laporan-shs/{uid}/aktif', [AdminLaporanSHSController::class, 'aktif'])->name('admin.laporan.shs.aktif');

    Route::post('/laporan-shs/{uid}/nonaktif', [AdminLaporanSHSController::class, 'nonaktif'])->name('admin.laporan.shs.nonaktif');
    /*
        |--------------------------------------------------------------------------
        | Master Kelompok Barang SHS
        |--------------------------------------------------------------------------
        */

    Route::get('/shs-kelompok', [AdminSHSKelompokController::class, 'index'])->name('admin.shs-kelompok.index');

    Route::post('/shs-kelompok/store', [AdminSHSKelompokController::class, 'store'])->name('admin.shs-kelompok.store');

    Route::post('/shs-kelompok/update', [AdminSHSKelompokController::class, 'update'])->name('admin.shs-kelompok.update');

    Route::post('/shs-kelompok/{uid}/status', [AdminSHSKelompokController::class, 'status'])->name('admin.shs-kelompok.status');

    /*
            |--------------------------------------------------------------------------
            | Satuan SHS
            |--------------------------------------------------------------------------
            */
    Route::get('/shs-satuan', [AdminSHSSatuanController::class, 'index'])->name('admin.shs.satuan');

    Route::post('/shs-satuan', [AdminSHSSatuanController::class, 'store'])->name('admin.shs.satuan.store');

    Route::put('/shs-satuan/{uid}', [AdminSHSSatuanController::class, 'update'])->name('admin.shs.satuan.update');

    Route::post('/shs-satuan/{uid}/status', [AdminSHSSatuanController::class, 'status'])->name('admin.shs.satuan.status');
    });

Route::prefix('user')
    ->name('user.')
    ->group(function () {
    /*
        |--------------------------------------------------------------------------
        | Dashboard User
        |--------------------------------------------------------------------------
        */

    Route::get('/dashboard', [AuthController::class, 'dashboardUser'])->name('dashboard');

    /*
        |--------------------------------------------------------------------------
        | Pengajuan User
        |--------------------------------------------------------------------------
        */

    Route::get('/bbm', [UserBBMController::class, 'index'])->name('bbm.index');

    Route::get('/bbm/create', [UserBBMController::class, 'create'])->name('bbm.create');

    Route::post('/bbm/store', [UserBBMController::class, 'store'])->name('bbm.store');

    Route::get('/bbm/{uid}', [UserBBMController::class, 'show'])->name('bbm.show');

    Route::post('/bbm/{uid}/laporan', [UserBBMController::class, 'uploadLaporan'])->name('bbm.laporan');

    Route::get('/spj', [AuthController::class, 'spj'])->name('spj.index');

    Route::get('/kak', [AuthController::class, 'kak'])->name('kak.index');

    Route::get('/pwa', [AuthController::class, 'pwa'])->name('pwa.index');

    /*
        |--------------------------------------------------------------------------
        | Riwayat
        |--------------------------------------------------------------------------
        */

    Route::get('/riwayat', [AuthController::class, 'riwayat'])->name('riwayat');

    /*
        |--------------------------------------------------------------------------
        | Kinerja
        |--------------------------------------------------------------------------
        */
    Route::get('/kinerja', [UserKinerjaController::class, 'index'])->name('kinerja.index');

    Route::post('/kinerja/{uid}/progress/store', [UserKinerjaController::class, 'storeProgress'])->name('kinerja.progress.store');

    Route::post('/kinerja/progress/{uid}/update', [UserKinerjaController::class, 'updateProgress'])->name('kinerja.progress.update');

    /*
        |--------------------------------------------------------------------------
        | Laporan Aktivitas
        |--------------------------------------------------------------------------
        */

    Route::get('/laporan-aktivitas', [UserLaporanAktivitasController::class, 'index'])->name('laporan-aktivitas.index');

    Route::post('/laporan-aktivitas/kegiatan/store', [UserLaporanAktivitasController::class, 'storeKegiatan'])->name('laporan-aktivitas.kegiatan.store');

    Route::post('/laporan-aktivitas/{uid}/aktivitas/store', [UserLaporanAktivitasController::class, 'storeAktivitas'])->name('laporan-aktivitas.aktivitas.store');

    /*
            |--------------------------------------------------------------------------
            | Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/prioritas', [UserPrioritasController::class, 'index'])->name('prioritas.index');

    Route::post('/prioritas/{uid}/bukti/store', [UserPrioritasController::class, 'storeBukti'])->name('prioritas.bukti.store');

    /*
            |--------------------------------------------------------------------------
            | Program Prioritas
            |--------------------------------------------------------------------------
            */
    Route::get('/program-prioritas', [UserProgramPrioritasController::class, 'index'])->name('program-prioritas.index');

    Route::post('/program-prioritas/{uid}/rencana/store', [UserProgramPrioritasController::class, 'storeRencana'])->name('program-prioritas.rencana.store');

    Route::post('/program-prioritas/rencana/{uid}/capaian/store', [UserProgramPrioritasController::class, 'storeCapaian'])->name('program-prioritas.capaian.store');

    /*
            |--------------------------------------------------------------------------
            | SPJ
            |--------------------------------------------------------------------------
            */
    Route::get('/spj', [UserSPJController::class, 'index'])->name('spj.index');

    Route::post('/spj/{uid}/store', [UserSPJController::class, 'store'])->name('spj.store');

    /*
            |--------------------------------------------------------------------------
            | Permintaan Sub Kegiatan
            |--------------------------------------------------------------------------
            */
    Route::get('/laporan-sub-kegiatan', [UserSubKegiatanLaporanController::class, 'index'])->name('laporan-sub-kegiatan.index');

    Route::get('/laporan-sub-kegiatan/create', [UserSubKegiatanLaporanController::class, 'create'])->name('laporan-sub-kegiatan.create');

    Route::get('/laporan-sub-kegiatan/indikator/{subKegiatanId}', [UserSubKegiatanLaporanController::class, 'getIndikator'])->name('laporan-sub-kegiatan.indikator');

    Route::post('/laporan-sub-kegiatan/store', [UserSubKegiatanLaporanController::class, 'store'])->name('laporan-sub-kegiatan.store');
    Route::get('/laporan-sub-kegiatan', [UserSubKegiatanLaporanController::class, 'index'])->name('laporan-sub-kegiatan.index');

    Route::get('/laporan-sub-kegiatan/create', [UserSubKegiatanLaporanController::class, 'create'])->name('laporan-sub-kegiatan.create');

    Route::get('/laporan-sub-kegiatan/indikator', [UserSubKegiatanLaporanController::class, 'getIndikator'])->name('laporan-sub-kegiatan.indikator');

    Route::post('/laporan-sub-kegiatan/store', [UserSubKegiatanLaporanController::class, 'store'])->name('laporan-sub-kegiatan.store');
    Route::get('/laporan-sub-kegiatan/sub-kegiatan-by-unit', [UserSubKegiatanLaporanController::class, 'getSubKegiatanByUnit'])->name('laporan-sub-kegiatan.sub-by-unit');
    Route::get('/laporan-sub-kegiatan/sub-kegiatan', [UserSubKegiatanLaporanController::class, 'getSubKegiatanByUnit'])->name('laporan-sub-kegiatan.sub-kegiatan');

    Route::get('/laporan-sub-kegiatan/indikator', [UserSubKegiatanLaporanController::class, 'getIndikator'])->name('laporan-sub-kegiatan.indikator');
    Route::get(
        '/laporan-sub-kegiatan/{uid}/edit',
        [UserSubKegiatanLaporanController::class, 'edit']
    )->name('laporan-sub-kegiatan.edit');

    Route::put(
        '/laporan-sub-kegiatan/{uid}',
        [UserSubKegiatanLaporanController::class, 'update']
    )->name('laporan-sub-kegiatan.update');

    /*
            |--------------------------------------------------------------------------
            | SHS
            |--------------------------------------------------------------------------
            */
    Route::get('/shs', [UserSHSController::class, 'index'])->name('shs.index');

    Route::get('/shs/create', [UserSHSController::class, 'create'])->name('shs.create');

    Route::post('/shs/store', [UserSHSController::class, 'store'])->name('shs.store');
    Route::get('/shs/{uid}/edit', [UserSHSController::class, 'edit'])->name('shs.edit');

    Route::put('/shs/{uid}', [UserSHSController::class, 'update'])->name('shs.update');

    Route::post('/shs/{uid}/delete', [UserSHSController::class, 'delete'])->name('shs.delete');
    });