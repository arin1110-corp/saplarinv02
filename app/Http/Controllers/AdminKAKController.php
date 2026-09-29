<?php

namespace App\Http\Controllers;

use App\Models\ModelPermintaanKAK;
use Illuminate\Http\Request;
use App\Services\KAKEmailService;
use Throwable;

class AdminKAKController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY UTAMA
        |--------------------------------------------------------------------------
        */

        $query = ModelPermintaanKAK::query()

            ->join('saplarin_sub_kegiatan', 'saplarin_permintaan_kak.kak_sub_kegiatan_id', '=', 'saplarin_sub_kegiatan.sub_kegiatan_id')

            ->join('saplarin_kegiatan', 'saplarin_sub_kegiatan.sub_kegiatan_kegiatan', '=', 'saplarin_kegiatan.kegiatan_id')

            ->join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')

            ->select(
                'saplarin_permintaan_kak.*',

                'saplarin_sub_kegiatan.sub_kegiatan_kode',
                'saplarin_sub_kegiatan.sub_kegiatan_nama',

            'saplarin_kegiatan.kegiatan_id',
                'saplarin_kegiatan.kegiatan_kode',
                'saplarin_kegiatan.kegiatan_nama',

            'saplarin_program.program_id',
                'saplarin_program.program_kode',
                'saplarin_program.program_nama',
            );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('saplarin_permintaan_kak.kak_unit', 'like', "%{$search}%")

                    ->orWhere('saplarin_permintaan_kak.kak_created_by_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_permintaan_kak.kak_created_by', 'like', "%{$search}%")

                    ->orWhere('saplarin_permintaan_kak.kak_tahapan', 'like', "%{$search}%")

                    ->orWhere('saplarin_permintaan_kak.kak_tahun', 'like', "%{$search}%")

                    ->orWhere('saplarin_sub_kegiatan.sub_kegiatan_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_sub_kegiatan.sub_kegiatan_kode', 'like', "%{$search}%")

                    ->orWhere('saplarin_kegiatan.kegiatan_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_kegiatan.kegiatan_kode', 'like', "%{$search}%")

                    ->orWhere('saplarin_program.program_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_program.program_kode', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {
            $query->where('saplarin_permintaan_kak.kak_tahun', $request->tahun);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER UNIT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('unit')) {
            $query->where('saplarin_permintaan_kak.kak_unit', $request->unit);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER TAHAPAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahapan')) {
            $query->where('saplarin_permintaan_kak.kak_tahapan', $request->tahapan);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('saplarin_permintaan_kak.kak_status', (int) $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER PROGRAM
        |--------------------------------------------------------------------------
        */

        if ($request->filled('program')) {
            $query->where('saplarin_program.program_id', $request->program);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER KEGIATAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kegiatan')) {
            $query->where('saplarin_kegiatan.kegiatan_id', $request->kegiatan);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER SUB KEGIATAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('sub_kegiatan')) {
            $query->where('saplarin_sub_kegiatan.sub_kegiatan_id', $request->sub_kegiatan);
        }

        /*
        |--------------------------------------------------------------------------
        | DATA FILTER
        |--------------------------------------------------------------------------
        */

        $tahunOptions = ModelPermintaanKAK::query()

            ->select('kak_tahun')
            ->whereNotNull('kak_tahun')
            ->where('kak_tahun', '!=', '')
            ->distinct()
            ->orderByDesc('kak_tahun')
            ->pluck('kak_tahun');

        $unitOptions = ModelPermintaanKAK::query()

            ->select('kak_unit')
            ->whereNotNull('kak_unit')
            ->where('kak_unit', '!=', '')
            ->distinct()
            ->orderBy('kak_unit')
            ->pluck('kak_unit');

        $tahapanOptions = ModelPermintaanKAK::query()

            ->select('kak_tahapan')
            ->whereNotNull('kak_tahapan')
            ->where('kak_tahapan', '!=', '')
            ->distinct()
            ->orderBy('kak_tahapan')
            ->pluck('kak_tahapan');

        $programOptions = \DB::table('saplarin_program')->select('program_id', 'program_kode', 'program_nama')->orderBy('program_kode')->get();

        $kegiatanOptions = \DB::table('saplarin_kegiatan')->select('kegiatan_id', 'kegiatan_kode', 'kegiatan_nama')->orderBy('kegiatan_kode')->get();

        $subKegiatanOptions = \DB::table('saplarin_sub_kegiatan')->select('sub_kegiatan_id', 'sub_kegiatan_kode', 'sub_kegiatan_nama')->orderBy('sub_kegiatan_kode')->get();

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $kaks = $query

            ->orderByDesc('saplarin_permintaan_kak.created_at')

            ->paginate(10)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('administrator-v2.permintaan-kak.index', compact('kaks', 'tahunOptions', 'unitOptions', 'tahapanOptions', 'programOptions', 'kegiatanOptions', 'subKegiatanOptions'));
    }

    /**
     * ============================================================
     * TOGGLE STATUS
     * ============================================================
     */
    public function toggleStatus($id)
    {
        $kak = ModelPermintaanKAK::findOrFail($id);

        $kak->kak_status = (int) $kak->kak_status === 1 ? 0 : 1;

        $kak->updated_at = now();

        $kak->save();

        $message = (int) $kak->kak_status === 1 ? 'Permintaan KAK berhasil diaktifkan.' : 'Permintaan KAK berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }

    /**
     * ============================================================
     * CATATAN ADMIN
     * ============================================================
     */
    public function catatan(Request $request, $id, KAKEmailService $emailService)
    {
        $request->validate([
            'kak_catatan_admin' => ['nullable', 'string', 'max:5000'],
        ]);

        $kak = ModelPermintaanKAK::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN CATATAN
        |--------------------------------------------------------------------------
        */

        $kak->kak_catatan_admin = $request->kak_catatan_admin;

        $kak->updated_at = now();

        $kak->save();

        /*
        |--------------------------------------------------------------------------
        | KIRIM EMAIL
        |--------------------------------------------------------------------------
        */

        try {
            $emailService->kirimKePengaju($kak);
        } catch (Throwable $e) {
            return back()->with('error', 'Catatan berhasil disimpan, tetapi email gagal dikirim: ' . $e->getMessage());
        }

        return back()->with('success', 'Catatan admin berhasil disimpan dan dikirim ke email pengaju.');
    }
}