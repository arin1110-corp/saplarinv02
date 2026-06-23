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

        $query = ModelSPJPagu::with([
            'unit',
            'program',
            'kegiatan',
            'subKegiatan',
            'realisasi',
        ])
            ->where('spj_pagu_status', 1)
            ->where('spj_pagu_tahun', $tahun);

        if ($unitId) {
            $query->where('spj_pagu_unit_id', $unitId);
        }

        $pagus = $query->get();

        $totalPagu = $pagus->sum('spj_pagu_final');

        $totalRealisasi = $pagus->sum(function ($pagu) {
            return $pagu->realisasi
                ->where('spj_status', 'Aktif')
                ->sum('spj_nominal');
        });

        $sisaPagu = max($totalPagu - $totalRealisasi, 0);

        $persenSerapan = $totalPagu > 0
            ? ($totalRealisasi / $totalPagu) * 100
            : 0;

        if ($persenSerapan > 100) {
            $persenSerapan = 100;
        }

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

        $chartUnit = $pagus
            ->groupBy('spj_pagu_unit_id')
            ->map(function ($items) {
                $unit = $items->first()->unit;

                $totalPaguUnit = $items->sum('spj_pagu_final');

                $totalRealisasiUnit = $items->sum(function ($pagu) {
                    return $pagu->realisasi
                        ->where('spj_status', 'Aktif')
                        ->sum('spj_nominal');
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

        $chartSubKegiatan = $pagus
            ->map(function ($pagu) {
                $realisasi = $pagu->realisasi
                    ->where('spj_status', 'Aktif')
                    ->sum('spj_nominal');

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

        $tahunList = ModelSPJPagu::select('spj_pagu_tahun')
            ->distinct()
            ->orderBy('spj_pagu_tahun', 'desc')
            ->pluck('spj_pagu_tahun');

        $units = ModelSPJUnit::where('unit_status', 1)
            ->orderBy('unit_kode')
            ->get();

        return view('administrator.laporan.spj', compact(
            'tahun',
            'unitId',
            'tahunList',
            'units',
            'totalPagu',
            'totalRealisasi',
            'sisaPagu',
            'persenSerapan',
            'chartSerapan',
            'chartUnit',
            'chartSubKegiatan'
        ));
    }
}