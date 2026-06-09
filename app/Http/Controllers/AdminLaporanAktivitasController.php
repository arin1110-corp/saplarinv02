<?php

namespace App\Http\Controllers;

use App\Models\ModelLaporanKegiatan;
use App\Models\ModelLaporanAktivitas;
use Illuminate\Http\Request;

class AdminLaporanAktivitasController extends Controller
{
    public function index()
    {
        $kegiatans = ModelLaporanKegiatan::with(['aktivitas.bukti'])
            ->orderBy('laporan_kegiatan_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrator.laporan-aktivitas.index', compact('kegiatans'));
    }

    public function nonaktifKegiatan($uid)
    {
        $kegiatan = ModelLaporanKegiatan::where('laporan_kegiatan_uid', $uid)->firstOrFail();

        $kegiatan->update([
            'laporan_kegiatan_status' => 'Nonaktif',
        ]);

        return back()->with('success', 'Kegiatan berhasil dinonaktifkan.');
    }

    public function aktifKegiatan($uid)
    {
        $kegiatan = ModelLaporanKegiatan::where('laporan_kegiatan_uid', $uid)->firstOrFail();

        $kegiatan->update([
            'laporan_kegiatan_status' => 'Aktif',
        ]);

        return back()->with('success', 'Kegiatan berhasil diaktifkan.');
    }

    public function nonaktifAktivitas($uid)
    {
        $aktivitas = ModelLaporanAktivitas::where('aktivitas_uid', $uid)->firstOrFail();

        $aktivitas->update([
            'aktivitas_status' => 'Nonaktif',
        ]);

        return back()->with('success', 'Aktivitas berhasil dinonaktifkan.');
    }

    public function aktifAktivitas($uid)
    {
        $aktivitas = ModelLaporanAktivitas::where('aktivitas_uid', $uid)->firstOrFail();

        $aktivitas->update([
            'aktivitas_status' => 'Aktif',
        ]);

        return back()->with('success', 'Aktivitas berhasil diaktifkan.');
    }
}