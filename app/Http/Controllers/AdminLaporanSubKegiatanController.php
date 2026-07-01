<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatanLaporan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanSubKegiatanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminLaporanSubKegiatanController extends Controller
{
    public function index()
    {
        $laporan = SubKegiatanLaporan::with(['subKegiatan', 'indikator', 'permasalahan', 'solusi', 'tindakLanjut'])
            ->latest()
            ->get();

        return view('administrator.laporan-sub-kegiatan.index', compact('laporan'));
    }
    public function exportExcel()
    {
        return Excel::download(new LaporanSubKegiatanExport(), 'laporan-sub-kegiatan-' . now()->format('YmdHis') . '.xlsx');
    }
    public function pdf($uid)
    {
        $laporan = SubKegiatanLaporan::with(['subKegiatan.kegiatan.program', 'detail', 'permasalahan', 'solusi', 'tindakLanjut'])
            ->where('laporan_uid', $uid)
            ->firstOrFail();

        $pdf = Pdf::loadView('administrator.laporan-sub-kegiatan.pdf', compact('laporan'))->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-sub-kegiatan-' . $uid . '.pdf');
    }
}