<?php

namespace App\Http\Controllers\AdministratorV2;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index2()
    {
        return view('administrator-v2.dashboard.index');
    }
    public function index()
    {
        $tahun = request('tahun', date('Y'));

        $totalUser = \App\Models\ModelUser::count();

        $totalPagu = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)
            ->where('spj_pagu_status', 1)
            ->sum('spj_pagu_final');

        $totalRealisasiSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->sum('spj_nominal');

        $sisaPagu = $totalPagu - $totalRealisasiSPJ;

        $persenSerapan = $totalPagu > 0 ? ($totalRealisasiSPJ / $totalPagu) * 100 : 0;
        $persenSerapan = min($persenSerapan, 100);

        $jumlahPaguSPJ = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)
            ->where('spj_pagu_status', 1)
            ->count();

        $jumlahInputSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->count();

        $jumlahBBM = \DB::table('saplarin_bbm_pengajuan')
            ->whereYear('created_at', $tahun)
            ->count();

        $bbmMenunggu = \DB::table('saplarin_bbm_pengajuan')
            ->whereYear('created_at', $tahun)
            ->where('bbm_status_pengajuan', 'Menunggu Verifikasi')
            ->count();

        $jumlahPrioritas = \DB::table('sadarin_program_prioritas')
            ->where('prioritas_tahun', $tahun)
            ->where('prioritas_status', 'Aktif')
            ->count();

        $jumlahAktivitas = \DB::table('saplarin_laporan_kegiatan')
            ->where('laporan_kegiatan_tahun', $tahun)
            ->where('laporan_kegiatan_status', 'Aktif')
            ->count();

        $paguTerbaru = \App\Models\ModelSPJPagu::with(['program', 'kegiatan', 'subKegiatan', 'realisasi'])
            ->where('spj_pagu_tahun', $tahun)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('administrator-v2.dashboard.index', compact(
            'tahun',
            'totalUser',
            'totalPagu',
            'totalRealisasiSPJ',
            'sisaPagu',
            'persenSerapan',
            'jumlahPaguSPJ',
            'jumlahInputSPJ',
            'jumlahBBM',
            'bbmMenunggu',
            'jumlahPrioritas',
            'jumlahAktivitas',
            'paguTerbaru'
        ));
    }
}