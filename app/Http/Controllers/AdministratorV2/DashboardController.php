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

        $totalPagu = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)->where('spj_pagu_status', 1)->sum('spj_pagu_final');

        $totalRealisasiSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->sum('spj_nominal');

        $sisaPagu = $totalPagu - $totalRealisasiSPJ;

        $persenSerapan = $totalPagu > 0 ? ($totalRealisasiSPJ / $totalPagu) * 100 : 0;
        $persenSerapan = min($persenSerapan, 100);

        $jumlahPaguSPJ = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)->where('spj_pagu_status', 1)->count();

        $jumlahInputSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->count();

        $jumlahBBM = \DB::table('saplarin_bbm_pengajuan')->whereYear('created_at', $tahun)->count();

        $bbmMenunggu = \DB::table('saplarin_bbm_pengajuan')->whereYear('created_at', $tahun)->where('bbm_status_pengajuan', 'Menunggu Verifikasi')->count();

        $jumlahPrioritas = \DB::table('sadarin_program_prioritas')->where('prioritas_tahun', $tahun)->where('prioritas_status', 'Aktif')->count();

        $jumlahAktivitas = \DB::table('saplarin_laporan_kegiatan')->where('laporan_kegiatan_tahun', $tahun)->where('laporan_kegiatan_status', 'Aktif')->count();

        $paguTerbaru = \App\Models\ModelSPJPagu::with(['program', 'kegiatan', 'subKegiatan', 'realisasi'])
            ->where('spj_pagu_tahun', $tahun)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        /*
|--------------------------------------------------------------------------
| REKAP SPJ BULANAN
|--------------------------------------------------------------------------
*/

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $rekapBulanan = \App\Models\ModelSPJRealisasi::selectRaw(
            "
        MONTH(spj_tanggal) as bulan,
        COUNT(*) as jumlah_spj,
        SUM(spj_nominal) as total_nominal
    ",
        )

            ->where('spj_status', 'Aktif')

            ->whereHas('pagu', function ($q) use ($tahun) {
                $q->where('spj_pagu_tahun', $tahun);
            })

            ->groupBy(\DB::raw('MONTH(spj_tanggal)'))

            ->orderBy(\DB::raw('MONTH(spj_tanggal)'))

            ->get();

        $chartSPJ = [];

        foreach (range(1, 12) as $i) {
            $row = $rekapBulanan->firstWhere('bulan', $i);

            $chartSPJ[] = [
                'bulan' => $namaBulan[$i],

                'jumlah' => $row->jumlah_spj ?? 0,

                'nominal' => $row->total_nominal ?? 0,
            ];
        }

        $bulan = request('bulan');

        $detailSPJ = collect();

        if ($bulan) {
            $detailSPJ = \App\Models\ModelSPJRealisasi::with(['pagu.unit', 'pagu.program', 'pagu.kegiatan', 'pagu.subKegiatan'])

                ->whereMonth('spj_tanggal', $bulan)

                ->where('spj_status', 'Aktif')

                ->whereHas('pagu', function ($q) use ($tahun) {
                    $q->where('spj_pagu_tahun', $tahun);
                })

                ->orderBy('spj_tanggal')

                ->get();
        }

        return view(
            'administrator-v2.dashboard.index',
            compact(
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

                'paguTerbaru',

                'chartSPJ',

                'bulan',

                'detailSPJ',
            ),
        );
    }
}