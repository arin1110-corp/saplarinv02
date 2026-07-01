<?php

namespace App\Http\Controllers;

use App\Models\ModelLaporanKegiatan;
use App\Models\ModelLaporanAktivitas;
use Illuminate\Http\Request;
use App\Models\ModelSubKegiatan;

class AdminLaporanAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = ModelLaporanKegiatan::with(['aktivitas.bukti']);

        if ($request->filled('tahun')) {
            $query->where('laporan_kegiatan_tahun', $request->tahun);
        }

        if ($request->filled('sub_kegiatan')) {
            $query->where('laporan_kegiatan_sub_kegiatan_id', $request->sub_kegiatan);
        }

        if ($request->filled('status')) {
            $query->where('laporan_kegiatan_status', $request->status);
        }

        $kegiatans = $query->orderBy('laporan_kegiatan_tahun', 'desc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $subKegiatans = \App\Models\ModelSubKegiatan::orderBy('sub_kegiatan_nama')->get();

        $tahun = ModelLaporanKegiatan::select('laporan_kegiatan_tahun')->distinct()->orderBy('laporan_kegiatan_tahun', 'desc')->pluck('laporan_kegiatan_tahun');

        return view('administrator.laporan-aktivitas.index', compact('kegiatans', 'subKegiatans', 'tahun'));
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