<?php

namespace App\Http\Controllers;

use App\Models\ModelSHS;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SHSExport;

class AdminLaporanSHSController extends Controller
{
    public function index()
    {
        $shs = ModelSHS::orderByDesc('created_at')->get();

        return view('administrator.laporan-shs.index', compact('shs'));
    }
    public function show($uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        return response()->json($shs);
    }
    public function verifikasi(Request $request, $uid)
    {
        $request->validate([
            'shs_catatan_admin' => 'nullable|string',
        ]);

        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $shs->update([
            'shs_status' => 'Diverifikasi',

            'shs_catatan_admin' => $request->shs_catatan_admin,

            'shs_verifikasi_at' => now(),

            'shs_verifikasi_oleh' => session('pegawai_nama'),
        ]);

        return back()->with('success', 'Usulan SHS berhasil diverifikasi.');
    }
    public function aktif($uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $shs->update([
            'shs_status' => 'Diajukan',
        ]);

        return back()->with('success', 'SHS berhasil diaktifkan.');
    }
    public function nonaktif(Request $request, $uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $shs->update([
            'shs_status' => 'Tidak Diajukan',

            'shs_catatan_admin' => $request->shs_catatan_admin,
        ]);

        return back()->with('success', 'SHS berhasil dinonaktifkan.');
    }

    public function export(Request $request)
    {
        $status = $request->status ? str_replace(' ', '_', $request->status) : 'Semua';

        $namaFile = 'Usulan_SHS_' . $status . '_' . now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new SHSExport($request->field, $request->status), $namaFile);
    }
}