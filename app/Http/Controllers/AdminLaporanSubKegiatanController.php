<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatanLaporan;

class AdminLaporanSubKegiatanController extends Controller
{
    public function index()
    {
        $laporan = SubKegiatanLaporan::with([
            'subKegiatan',
            'indikator',
            'permasalahan',
            'solusi',
            'tindakLanjut'
        ])
        ->latest()
        ->get();

        return view(
            'administrator.laporan-sub-kegiatan.index',
            compact('laporan')
        );
    }
}