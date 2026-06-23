<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJRealisasi;
use Illuminate\Support\Facades\DB;

class AdminLaporanSPJController extends Controller
{
    public function index()
    {
        $chartUnit = ModelSPJRealisasi::select(
                'spj_bidang_nama',
                DB::raw('SUM(spj_nominal) as total')
            )
            ->where('spj_status', 'Aktif')
            ->groupBy('spj_bidang_nama')
            ->get();

        return view('administrator.laporan.spj', compact(
            'chartUnit'
        ));
    }
}