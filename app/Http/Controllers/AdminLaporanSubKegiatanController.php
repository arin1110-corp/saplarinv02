<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubKegiatanLaporan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanSubKegiatanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PegawaiApiService;
use App\Services\SubKegiatanEmailService;

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
    public function catatan(Request $request, $uid, PegawaiApiService $pegawaiApi, SubKegiatanEmailService $emailService)
    {
        $request->validate([
            'catatan' => 'required',
        ]);

        $laporan = SubKegiatanLaporan::with('subKegiatan')->where('laporan_uid', $uid)->firstOrFail();

        $laporan->update([
            'laporan_catatan_admin' => $request->catatan,

            'laporan_catatan_at' => now(),

            'laporan_catatan_by' => session('pegawai_nama'),
        ]);

        $pegawai = $pegawaiApi->getPegawai($laporan->laporan_created_by);

        if ($pegawai && !empty($pegawai['user_email'])) {
            try {
                $emailService->kirimCatatan($laporan, $pegawai['user_email'], $pegawai['user_nama'], $request->catatan);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email catatan: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Catatan berhasil disimpan dan email berhasil dikirim.');
    }
}