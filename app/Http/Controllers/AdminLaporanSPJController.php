<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJPagu;
use App\Models\ModelSPJUnit;
use Illuminate\Http\Request;

class AdminLaporanSPJController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $unitId = $request->unit_id;
        $search = trim($request->search);

        $query = ModelSPJPagu::with(['unit', 'program', 'kegiatan', 'subKegiatan', 'realisasi'])
            ->where('spj_pagu_status', 1)
            ->where('spj_pagu_tahun', $tahun)

            ->when($unitId, function ($q) use ($unitId) {
                $q->where('spj_pagu_unit_id', $unitId);
            })

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query
                        ->whereHas('unit', function ($unit) use ($search) {
                            $unit->where('unit_kode', 'like', "%{$search}%")->orWhere('unit_nama', 'like', "%{$search}%");
                        })

                        ->orWhereHas('program', function ($program) use ($search) {
                            $program->where('program_kode', 'like', "%{$search}%")->orWhere('program_nama', 'like', "%{$search}%");
                        })

                        ->orWhereHas('kegiatan', function ($kegiatan) use ($search) {
                            $kegiatan->where('kegiatan_kode', 'like', "%{$search}%")->orWhere('kegiatan_nama', 'like', "%{$search}%");
                        })

                        ->orWhereHas('subKegiatan', function ($sub) use ($search) {
                            $sub->where('sub_kegiatan_kode', 'like', "%{$search}%")->orWhere('sub_kegiatan_nama', 'like', "%{$search}%");
                        });
                });
            });

        /*
    |--------------------------------------------------------------------------
    | Ambil semua data untuk Dashboard
    |--------------------------------------------------------------------------
    */

        $allPagus = (clone $query)->get();

        /*
    |--------------------------------------------------------------------------
    | Ambil data tabel (Pagination)
    |--------------------------------------------------------------------------
    */

        $pagus = (clone $query)->orderByDesc('spj_pagu_tahun')->orderBy('spj_pagu_unit_id')->paginate(10)->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | Ringkasan
    |--------------------------------------------------------------------------
    */

        $totalPagu = $allPagus->sum('spj_pagu_final');

        $totalRealisasi = $allPagus->sum(function ($pagu) {
            return $pagu->realisasi->where('spj_status', 'Aktif')->sum('spj_nominal');
        });

        $sisaPagu = max($totalPagu - $totalRealisasi, 0);

        $persenSerapan = $totalPagu > 0 ? ($totalRealisasi / $totalPagu) * 100 : 0;

        if ($persenSerapan > 100) {
            $persenSerapan = 100;
        }

        /*
    |--------------------------------------------------------------------------
    | Chart Serapan
    |--------------------------------------------------------------------------
    */

        $chartSerapan = collect([
            [
                'label' => 'Realisasi',

                'total' => $totalRealisasi,
            ],

            [
                'label' => 'Sisa Pagu',

                'total' => $sisaPagu,
            ],
        ]);

        /*
    |--------------------------------------------------------------------------
    | Chart Unit
    |--------------------------------------------------------------------------
    */

        $chartUnit = $allPagus
            ->groupBy('spj_pagu_unit_id')
            ->map(function ($items) {
                $unit = $items->first()->unit;

                $totalPaguUnit = $items->sum('spj_pagu_final');

                $totalRealisasiUnit = $items->sum(function ($pagu) {
                return $pagu->realisasi->where('spj_status', 'Aktif')->sum('spj_nominal');
                });

                return [
                    'label' => ($unit->unit_kode ?? '-') . ' - ' . ($unit->unit_nama ?? '-'),

                'pagu' => $totalPaguUnit,

                'realisasi' => $totalRealisasiUnit,

                'sisa' => max($totalPaguUnit - $totalRealisasiUnit, 0),

                'serapan' => $totalPaguUnit > 0 ? ($totalRealisasiUnit / $totalPaguUnit) * 100 : 0,
                ];
            })

            ->values();

        /*
    |--------------------------------------------------------------------------
    | Chart Sub Kegiatan
    |--------------------------------------------------------------------------
    */

        $chartSubKegiatan = $allPagus
            ->map(function ($pagu) {
            $realisasi = $pagu->realisasi->where('spj_status', 'Aktif')->sum('spj_nominal');

                return [
                    'label' => $pagu->subKegiatan->sub_kegiatan_nama ?? '-',

                'unit' => $pagu->unit->unit_kode ?? '-',

                'pagu' => $pagu->spj_pagu_final,

                'realisasi' => $realisasi,

                'sisa' => max($pagu->spj_pagu_final - $realisasi, 0),

                'serapan' => $pagu->spj_pagu_final > 0 ? ($realisasi / $pagu->spj_pagu_final) * 100 : 0,
                ];
            })

            ->filter(fn($item) => $item['pagu'] > 0)

            ->values();

        /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

        $tahunList = ModelSPJPagu::select('spj_pagu_tahun')->distinct()->orderByDesc('spj_pagu_tahun')->pluck('spj_pagu_tahun');

        $units = ModelSPJUnit::where('unit_status', 1)->orderBy('unit_kode')->get();

        return view(
            'administrator-v2.laporan-spj.index',
            compact(
                'tahun',
                'unitId',
                'pagus',
                'tahunList',
                'units',

                'totalPagu',
                'totalRealisasi',
                'sisaPagu',
                'persenSerapan',

                'chartSerapan',
                'chartUnit',
                'chartSubKegiatan',
            ),
        );
    }
}