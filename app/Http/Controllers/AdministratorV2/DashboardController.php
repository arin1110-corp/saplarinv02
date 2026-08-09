<?php

namespace App\Http\Controllers\AdministratorV2;

use App\Http\Controllers\Controller;
use App\Models\ModelPadTarget;
use App\Models\ModelPadRealisasi;
use App\Models\ModelSPJPagu;
use App\Models\ModelSPJRealisasi;
use App\Models\ModelUser;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index2()
    {
        return view('administrator-v2.dashboard.index');
    }

    public function index()
    {
        $tahun = request('tahun', date('Y'));

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $totalUser = ModelUser::count();

        /*
        |--------------------------------------------------------------------------
        | SPJ
        |--------------------------------------------------------------------------
        */

        $totalPagu = ModelSPJPagu::where('spj_pagu_tahun', $tahun)->where('spj_pagu_status', 1)->sum('spj_pagu_final');

        $totalRealisasiSPJ = ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->sum('spj_nominal');

        $sisaPagu = max($totalPagu - $totalRealisasiSPJ, 0);

        $persenSerapan = $totalPagu > 0 ? ($totalRealisasiSPJ / $totalPagu) * 100 : 0;

        $persenSerapan = min($persenSerapan, 100);

        $jumlahPaguSPJ = ModelSPJPagu::where('spj_pagu_tahun', $tahun)->where('spj_pagu_status', 1)->count();

        $jumlahInputSPJ = ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PAD
        |--------------------------------------------------------------------------
        */

        $totalTargetPAD = ModelPadTarget::where('pad_target_tahun', $tahun)->where('pad_target_status', true)->sum('pad_target_nominal');

        $totalRealisasiPAD = ModelPadRealisasi::where('pad_realisasi_status', 'Diterima')
            ->whereHas('target', function ($q) use ($tahun) {
                $q->where('pad_target_tahun', $tahun)->where('pad_target_status', true);
            })
            ->sum('pad_realisasi_nominal');

        $sisaPAD = max($totalTargetPAD - $totalRealisasiPAD, 0);

        $persenPAD = $totalTargetPAD > 0 ? ($totalRealisasiPAD / $totalTargetPAD) * 100 : 0;

        /*
        |--------------------------------------------------------------------------
        | DATA LAINNYA
        |--------------------------------------------------------------------------
        */

        $jumlahBBM = DB::table('saplarin_bbm_pengajuan')->whereYear('created_at', $tahun)->count();

        $bbmMenunggu = DB::table('saplarin_bbm_pengajuan')->whereYear('created_at', $tahun)->where('bbm_status_pengajuan', 'Menunggu Verifikasi')->count();

        $jumlahPrioritas = DB::table('sadarin_program_prioritas')->where('prioritas_tahun', $tahun)->where('prioritas_status', 'Aktif')->count();

        $jumlahAktivitas = DB::table('saplarin_laporan_kegiatan')->where('laporan_kegiatan_tahun', $tahun)->where('laporan_kegiatan_status', 'Aktif')->count();

        /*
        |--------------------------------------------------------------------------
        | PAGU TERBARU
        |--------------------------------------------------------------------------
        */

        $paguTerbaru = ModelSPJPagu::with(['program', 'kegiatan', 'subKegiatan', 'realisasi'])
            ->where('spj_pagu_tahun', $tahun)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NAMA BULAN
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

        /*
        |--------------------------------------------------------------------------
        | REKAP SPJ BULANAN
        |--------------------------------------------------------------------------
        */

        $rekapBulanan = ModelSPJRealisasi::selectRaw(
            '
                MONTH(spj_tanggal) as bulan,
                COUNT(*) as jumlah_spj,
                SUM(spj_nominal) as total_nominal
            ',
        )
            ->where('spj_status', 'Aktif')
            ->whereHas('pagu', function ($q) use ($tahun) {
                $q->where('spj_pagu_tahun', $tahun);
            })
            ->groupBy(DB::raw('MONTH(spj_tanggal)'))
            ->orderBy(DB::raw('MONTH(spj_tanggal)'))
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

        /*
        |--------------------------------------------------------------------------
        | DETAIL SPJ
        |--------------------------------------------------------------------------
        */

        $bulan = request('bulan');

        $detailSPJ = collect();

        if ($bulan) {
            $detailSPJ = ModelSPJRealisasi::with(['pagu.unit', 'pagu.program', 'pagu.kegiatan', 'pagu.subKegiatan'])
                ->whereMonth('spj_tanggal', $bulan)
                ->where('spj_status', 'Aktif')
                ->whereHas('pagu', function ($q) use ($tahun) {
                    $q->where('spj_pagu_tahun', $tahun);
                })
                ->orderBy('spj_tanggal')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | REKAP PAD BULANAN
        |--------------------------------------------------------------------------
        */

        $rekapPAD = ModelPadRealisasi::selectRaw(
            '
                MONTH(pad_realisasi_tanggal) as bulan,
                COUNT(*) as jumlah_penerimaan,
                SUM(pad_realisasi_nominal) as total_nominal
            ',
        )
            ->where('pad_realisasi_status', 'Diterima')
            ->whereHas('target', function ($q) use ($tahun) {
                $q->where('pad_target_tahun', $tahun)->where('pad_target_status', true);
            })
            ->groupBy(DB::raw('MONTH(pad_realisasi_tanggal)'))
            ->orderBy(DB::raw('MONTH(pad_realisasi_tanggal)'))
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CHART PAD
        |--------------------------------------------------------------------------
        */

        $chartPAD = [];

        foreach (range(1, 12) as $i) {
            $row = $rekapPAD->firstWhere('bulan', $i);

            $chartPAD[] = [
                'bulan' => $namaBulan[$i],

                'jumlah' => $row->jumlah_penerimaan ?? 0,

                'nominal' => $row->total_nominal ?? 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DETAIL PAD
        |--------------------------------------------------------------------------
        */

        $bulanPAD = request('bulanPAD');

        $detailPAD = collect();

        if ($bulanPAD) {
            $detailPAD = ModelPadRealisasi::with(['target.jenis', 'target.komponen', 'subkomponen'])
                ->where('pad_realisasi_status', 'Diterima')
                ->whereMonth('pad_realisasi_tanggal', $bulanPAD)
                ->whereHas('target', function ($q) use ($tahun) {
                    $q->where('pad_target_tahun', $tahun)->where('pad_target_status', true);
                })
                ->orderBy('pad_realisasi_tanggal')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'administrator-v2.dashboard.index',
            compact(
                'tahun',

                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                'totalUser',

                /*
                |--------------------------------------------------------------------------
                | SPJ
                |--------------------------------------------------------------------------
                */

                'totalPagu',

                'totalRealisasiSPJ',

                'sisaPagu',

                'persenSerapan',

                'jumlahPaguSPJ',

                'jumlahInputSPJ',

                'paguTerbaru',

                'chartSPJ',

                'bulan',

                'detailSPJ',

                /*
                |--------------------------------------------------------------------------
                | PAD
                |--------------------------------------------------------------------------
                */

                'totalTargetPAD',

                'totalRealisasiPAD',

                'sisaPAD',

                'persenPAD',

                'chartPAD',

                'bulanPAD',

                'detailPAD',

                /*
                |--------------------------------------------------------------------------
                | DATA LAINNYA
                |--------------------------------------------------------------------------
                */

                'jumlahBBM',

                'bbmMenunggu',

                'jumlahPrioritas',

                'jumlahAktivitas',
            ),
        );
    }
}