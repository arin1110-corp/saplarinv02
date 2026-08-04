<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubKegiatanLaporan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanSubKegiatanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PegawaiApiService;
use App\Services\SubKegiatanEmailService;
use App\Models\SubKegiatanIndikator;
use App\Models\ModelProgram;
use App\Models\ModelKegiatan;
use App\Models\ModelSubKegiatan;

class AdminLaporanSubKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = SubKegiatanLaporan::with(['subKegiatan.kegiatan.program', 'detail', 'permasalahan', 'solusi', 'tindakLanjut']);

        if ($request->filled('unit')) {
            $query->where('laporan_unit_kode', $request->unit);
        }

        if ($request->filled('program')) {
            $query->whereHas('subKegiatan.kegiatan.program', function ($q) use ($request) {
                $q->where('program_id', $request->program);
            });
        }

        if ($request->filled('kegiatan')) {
            $query->whereHas('subKegiatan.kegiatan', function ($q) use ($request) {
                $q->where('kegiatan_id', $request->kegiatan);
            });
        }

        if ($request->filled('sub_kegiatan')) {
            $query->where('laporan_sub_kegiatan_id', $request->sub_kegiatan);
        }

        if ($request->filled('status')) {
            $query->where('laporan_status', $request->status);
        }

        $laporan = $query->latest()->get();

        $units = SubKegiatanIndikator::select('indikator_unit_kode', 'indikator_unit_nama')->distinct()->orderBy('indikator_unit_nama')->get();

        $programs = ModelProgram::orderBy('program_nama')->get();

        $kegiatans = collect();

        if ($request->filled('program')) {
            $kegiatans = ModelKegiatan::where('kegiatan_program', $request->program)->orderBy('kegiatan_nama')->get();
        }

        $subKegiatans = collect();

        if ($request->filled('kegiatan')) {
            $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_kegiatan', $request->kegiatan)->orderBy('sub_kegiatan_nama')->get();
        }

        return view('administrator-v2.laporan-sub-kegiatan.index', compact('laporan', 'units', 'programs', 'kegiatans', 'subKegiatans'));
    }
    public function getKegiatan(Request $request)
    {
        $kegiatan = ModelKegiatan::where('kegiatan_program', $request->program_id)->orderBy('kegiatan_nama')->get();

        return response()->json($kegiatan);
    }
    public function getSubKegiatan(Request $request)
    {
        $sub = ModelSubKegiatan::where('sub_kegiatan_kegiatan', $request->kegiatan_id)->orderBy('sub_kegiatan_nama')->get();

        return response()->json($sub);
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
    public function nonaktif($uid)
    {
        $laporan = SubKegiatanLaporan::where('laporan_uid', $uid)->firstOrFail();

        $laporan->update([
            'laporan_status' => 'Nonaktif',
        ]);

        return back()->with('success', 'Laporan berhasil dinonaktifkan.');
    }

    public function aktif($uid)
    {
        $laporan = SubKegiatanLaporan::where('laporan_uid', $uid)->firstOrFail();

        $laporan->update([
            'laporan_status' => 'Aktif',
        ]);

        return back()->with('success', 'Laporan berhasil diaktifkan.');
    }
}