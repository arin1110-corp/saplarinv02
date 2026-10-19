<?php

namespace App\Http\Controllers;

use App\Models\ModelSHS;
use App\Services\SHSEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SHSExport;

class AdminLaporanSHSController extends Controller
{
    /**
     * Menampilkan halaman laporan SHS.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $tahun = $request->input('tahun');
        $unit = $request->input('unit');
        $status = $request->input('status');

        $query = ModelSHS::query()
            ->when($search !== '', function ($q) use ($search) {
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
            ->when($tahun, fn($q) => $q->where('shs_tahun', $tahun))
            ->when($unit, fn($q) => $q->where('shs_unit_nama', $unit))
            ->when($status, fn($q) => $q->where('shs_status', $status));

        $shs = (clone $query)->with('referensiHarga')->latest()->paginate(10)->withQueryString();

        $tahunOptions = ModelSHS::query()->whereNotNull('shs_tahun')->where('shs_tahun', '!=', '')->distinct()->orderByDesc('shs_tahun')->pluck('shs_tahun');

        $unitOptions = ModelSHS::query()->whereNotNull('shs_unit_nama')->where('shs_unit_nama', '!=', '')->distinct()->orderBy('shs_unit_nama')->pluck('shs_unit_nama');

        return view('administrator-v2.laporan-shs.index', compact('shs', 'tahunOptions', 'unitOptions'));
    }

    /**
     * Menampilkan detail SHS.
     */
    public function show($uid)
    {
        $shs = ModelSHS::with('referensiHarga')->where('shs_uid', $uid)->firstOrFail();

        return response()->json($shs);
    }

    /**
     * Verifikasi usulan SHS.
     */
    public function verifikasi(Request $request, $uid)
    {
        $request->validate([
            'shs_catatan_admin' => 'nullable|string|max:5000',
        ]);

        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $shs->update([
            'shs_status' => 'Diverifikasi',
            'shs_catatan_admin' => $request->input('shs_catatan_admin'),
            'shs_verifikasi_at' => now(),
            'shs_verifikasi_oleh' => session('pegawai_nama'),
        ]);

        return back()->with('success', 'Usulan SHS berhasil diverifikasi.');
    }

    /**
     * Mengembalikan usulan SHS ke Draft dan mengirim catatan perbaikan.
     *
     * Gunakan fungsi ini untuk usulan yang perlu diperbaiki operator,
     * termasuk usulan yang sebelumnya telah diajukan ke BPKAD.
     */
    public function mintaPerbaikan(Request $request, $uid, SHSEmailService $emailService)
    {
        $request->validate([
            'shs_catatan_admin' => 'required|string|max:5000',
        ]);

        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $catatan = trim($request->input('shs_catatan_admin'));

        if ($catatan === '') {
            return back()
                ->withErrors([
                    'shs_catatan_admin' => 'Catatan perbaikan wajib diisi.',
                ])
                ->withInput();
        }

        /*
         * Simpan status dan catatan terlebih dahulu.
         * Tidak membuat kolom status baru.
         */
        DB::transaction(function () use ($shs, $catatan) {
            $shs->update([
                'shs_status' => 'Draft',
                'shs_catatan_admin' => $catatan,
            ]);
        });

        $subject = 'Permintaan Perbaikan Usulan SHS - SAPLARIN';

        $body = "Yth. {$shs->shs_operator_nama},\n\n" . "Usulan Standar Harga Satuan (SHS) Anda memerlukan perbaikan.\n\n" . "Berikut informasi usulan:\n" . "Barang       : {$shs->shs_barang}\n" . "Tahun        : {$shs->shs_tahun}\n" . "Unit         : {$shs->shs_unit_nama}\n" . 'Harga Usulan : Rp ' . number_format((float) $shs->shs_harga, 0, ',', '.') . "\n\n" . "Catatan Admin:\n" . $catatan . "\n\n" . 'Status usulan telah dikembalikan menjadi Draft. ' . 'Silakan masuk ke SAPLARIN, perbaiki usulan sesuai catatan di atas, ' . "kemudian ajukan kembali setelah perbaikan selesai.\n\n" . 'Email ini dikirim secara otomatis oleh SAPLARIN.';

        try {
            $emailTerkirim = $emailService->kirimKeOperator($shs, $subject, $body);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('warning', 'Status SHS berhasil dikembalikan ke Draft, tetapi email gagal dikirim. Periksa konfigurasi email.');
        }

        if (!$emailTerkirim) {
            return back()->with('warning', 'Status SHS berhasil dikembalikan ke Draft dan catatan tersimpan, tetapi email operator tidak ditemukan atau API SADARIN tidak dapat diakses.');
        }

        return back()->with('success', 'Usulan SHS dikembalikan ke Draft. Catatan tersimpan dan email perbaikan berhasil dikirim ke operator.');
    }

    /**
     * Mengaktifkan kembali SHS.
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
     * Menonaktifkan SHS.
     */
    public function nonaktif(Request $request, $uid)
    {
        $request->validate([
            'shs_catatan_admin' => 'nullable|string|max:5000',
        ]);

        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $shs->update([
            'shs_status' => 'Tidak Diajukan',
            'shs_catatan_admin' => $request->input('shs_catatan_admin'),
        ]);

        return back()->with('success', 'SHS berhasil dinonaktifkan.');
    }

    /**
     * Export laporan SHS ke Excel.
     */
    public function export(Request $request)
    {
        $field = $request->input('field', []);
        $status = $request->input('status');
        $tahun = $request->input('tahun');

        $statusFile = $status ? str_replace(' ', '_', $status) : 'Semua';

        $namaFile = 'Usulan_SHS_' . $statusFile;

        if ($tahun) {
            $namaFile .= '_' . $tahun;
        }

        $namaFile .= '_' . now()->format('d-m-Y_H-i-s');
        $namaFile .= '.xlsx';

        return Excel::download(new SHSExport($field, $status, $tahun), $namaFile);
    }

    /**
     * Mengajukan SHS ke BPKAD.
     */
    public function ajukanBPKAD(string $uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        if ($shs->shs_status !== 'Diverifikasi') {
            return back()->with('error', 'Usulan SHS harus berstatus Diverifikasi sebelum diajukan ke BPKAD.');
        }

        $shs->update([
            'shs_status' => 'Diajukan ke BPKAD',
        ]);

        return back()->with('success', 'Usulan SHS berhasil diajukan ke BPKAD.');
    }
}