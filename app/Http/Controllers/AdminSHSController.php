<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\ModelSHS;
use App\Exports\SHSExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminSHSController extends Controller
{
    /**
     * Menampilkan daftar SHS
     */
    public function index()
    {
        $shs = ModelSHS::with('referensiHarga')->orderByDesc('created_at')->paginate(25);

        return view('administrator-v2.shs.index', compact('shs'));
    }

    /**
     * Menampilkan detail SHS
     */
    public function show($uid)
    {
        $shs = ModelSHS::with('referensiHarga')->where('shs_uid', $uid)->firstOrFail();

        return response()->json($shs);
    }

    /**
     * Verifikasi SHS
     */
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
            'shs_verifikasi_nama' => session('admin_nama'),
            'shs_verifikasi_nip' => session('admin_nip'),
            'shs_verifikasi_jabatan' => session('admin_jabatan'),
            'shs_verifikasi_bidang' => session('admin_bidang'),
        ]);

        return back()->with('success', 'Usulan SHS berhasil diverifikasi.');
    }

    /**
     * Mengaktifkan kembali SHS
     */
    public function aktif($uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $shs->update([
            'shs_status' => 'Diajukan',
        ]);

        return back()->with('success', 'SHS berhasil diaktifkan.');
    }

    /**
     * Menonaktifkan SHS
     */
    public function nonaktif(Request $request, $uid)
    {
        $request->validate([
            'shs_catatan_admin' => 'nullable|string',
        ]);

        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $shs->update([
            'shs_status' => 'Tidak Diajukan',
            'shs_catatan_admin' => $request->shs_catatan_admin,
        ]);

        return back()->with('success', 'SHS berhasil dinonaktifkan.');
    }

    /**
     * Export SHS ke Excel
     */
    public function export(Request $request)
    {
        $field = $request->input('field', []);
        $status = $request->input('status');
        $tahun = $request->input('tahun');

        $namaFile = 'Usulan_SHS';

        if ($status) {
            $namaFile .= '_' . str_replace(' ', '_', $status);
        }

        if ($tahun) {
            $namaFile .= '_' . $tahun;
        }

        $namaFile .= '_' . now()->format('Ymd_His');

        $namaFile .= '.xlsx';

        return Excel::download(new SHSExport($field, $status, $tahun), $namaFile);
    }
}