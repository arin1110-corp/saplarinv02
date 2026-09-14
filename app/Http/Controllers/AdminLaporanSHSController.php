<?php

namespace App\Http\Controllers;

use App\Models\ModelSHS;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SHSExport;

class AdminLaporanSHSController extends Controller
{
    /**
     * Menampilkan halaman laporan SHS
     */
    public function index(Request $request)
    {
        $search = trim($request->search);

        $shs = ModelSHS::query()

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query
                        ->where('shs_unit_nama', 'like', "%{$search}%")
                        ->orWhere('shs_barang', 'like', "%{$search}%")
                        ->orWhere('shs_satuan', 'like', "%{$search}%")
                        ->orWhere('shs_kelompok_barang', 'like', "%{$search}%")
                        ->orWhere('shs_operator_nama', 'like', "%{$search}%")
                        ->orWhere('shs_operator_nip', 'like', "%{$search}%")
                        ->orWhere('shs_status', 'like', "%{$search}%")
                        ->orWhere('shs_harga', 'like', "%{$search}%");
                });
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view('administrator-v2.laporan-shs.index', compact('shs'));
    }

    /**
     * Menampilkan detail SHS
     */
    public function show($uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        return response()->json($shs);
    }

    /**
     * Verifikasi usulan SHS
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

            'shs_verifikasi_oleh' => session('pegawai_nama'),
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
     * Export laporan SHS ke Excel
     */
    public function export(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil Filter
        |--------------------------------------------------------------------------
        */

        $field = $request->input('field', []);

        $status = $request->input('status');

        $tahun = $request->input('tahun');

        /*
        |--------------------------------------------------------------------------
        | Nama Status Untuk Nama File
        |--------------------------------------------------------------------------
        */

        $statusFile = $status ? str_replace(' ', '_', $status) : 'Semua';

        /*
        |--------------------------------------------------------------------------
        | Nama File
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | Usulan_SHS_Diajukan_2026_14-09-2026_14-30-25.xlsx
        |
        | Karena menggunakan jam, menit, dan detik,
        | nama file berbeda setiap kali export.
        |
        */

        $namaFile = 'Usulan_SHS_' . $statusFile;

        if ($tahun) {
            $namaFile .= '_' . $tahun;
        }

        $namaFile .= '_' . now()->format('d-m-Y_H-i-s');

        $namaFile .= '.xlsx';

        /*
        |--------------------------------------------------------------------------
        | Download Excel
        |--------------------------------------------------------------------------
        */

        return Excel::download(new SHSExport($field, $status, $tahun), $namaFile);
    }
}