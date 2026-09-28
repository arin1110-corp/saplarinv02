<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJRealisasi;
use App\Models\ModelSPJPagu;
use App\Models\ModelSPJBukuTamu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminSPJRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PERMINTAAN SPJ
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $tahun = $request->input('tahun');
        $unitId = $request->input('unit_id');
        $status = $request->input('status');

        /*
        |--------------------------------------------------------------------------
        | DATA UNIT
        |--------------------------------------------------------------------------
        */

        $units = ModelSPJPagu::with('unit')->get()->pluck('unit')->filter()->unique('unit_id')->sortBy('unit_nama')->values();

        /*
        |--------------------------------------------------------------------------
        | DAFTAR TAHUN
        |--------------------------------------------------------------------------
        */

        $tahunList = ModelSPJPagu::query()->select('spj_pagu_tahun')->whereNotNull('spj_pagu_tahun')->distinct()->orderByDesc('spj_pagu_tahun')->pluck('spj_pagu_tahun');

        /*
        |--------------------------------------------------------------------------
        | QUERY SPJ
        |--------------------------------------------------------------------------
        */

        $spjs = ModelSPJRealisasi::with(['pagu.unit', 'pagu.program', 'pagu.kegiatan', 'pagu.subKegiatan'])

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query

                    // SPJ
                    ->where('spj_operator_nama', 'like', "%{$search}%")
                        ->orWhere('spj_operator_nip', 'like', "%{$search}%")
                        ->orWhere('spj_bidang_nama', 'like', "%{$search}%")
                        ->orWhere('spj_uraian', 'like', "%{$search}%")
                        ->orWhere('spj_nominal', 'like', "%{$search}%")
                    ->orWhere('spj_status', 'like', "%{$search}%")

                    // UNIT
                    ->orWhereHas('pagu.unit', function ($query) use ($search) {
                        $query->where('unit_kode', 'like', "%{$search}%")->orWhere('unit_nama', 'like', "%{$search}%");
                    })

                    // PROGRAM
                    ->orWhereHas('pagu.program', function ($query) use ($search) {
                        $query->where('program_kode', 'like', "%{$search}%")->orWhere('program_nama', 'like', "%{$search}%");
                    })

                    // KEGIATAN
                    ->orWhereHas('pagu.kegiatan', function ($query) use ($search) {
                        $query->where('kegiatan_kode', 'like', "%{$search}%")->orWhere('kegiatan_nama', 'like', "%{$search}%");
                    })

                    // SUB KEGIATAN
                    ->orWhereHas('pagu.subKegiatan', function ($query) use ($search) {
                        $query->where('sub_kegiatan_kode', 'like', "%{$search}%")->orWhere('sub_kegiatan_nama', 'like', "%{$search}%");
                    });
                });
            })

            /*
            |--------------------------------------------------------------------------
            | FILTER TAHUN
            |--------------------------------------------------------------------------
            */

            ->when($tahun, function ($q) use ($tahun) {
                $q->whereHas('pagu', function ($query) use ($tahun) {
                    $query->where('spj_pagu_tahun', $tahun);
                });
            })

            /*
            |--------------------------------------------------------------------------
            | FILTER UNIT
            |--------------------------------------------------------------------------
            */

            ->when($unitId, function ($q) use ($unitId) {
                $q->whereHas('pagu', function ($query) use ($unitId) {
                    $query->where('unit_id', $unitId);
                });
            })

            /*
            |--------------------------------------------------------------------------
            | FILTER STATUS
            |--------------------------------------------------------------------------
            */

            ->when($status, function ($q) use ($status) {
                $q->where('spj_status', $status);
            })

            /*
            |--------------------------------------------------------------------------
            | SORTING
            |--------------------------------------------------------------------------
            */

            ->latest()

            /*
            |--------------------------------------------------------------------------
            | PAGINATION
            |--------------------------------------------------------------------------
            */

            ->paginate(10)

            ->withQueryString();

        return view('administrator-v2.permintaan-spj.index', compact('spjs', 'units', 'tahunList'));
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS SPJ
    |--------------------------------------------------------------------------
    */

    public function toggle(Request $request, $uid)
    {
        $request->validate([
            'spj_catatan_admin' => 'nullable|string',
        ]);

        $spj = ModelSPJRealisasi::where('spj_uid', $uid)->firstOrFail();

        $statusBaru = $spj->spj_status === 'Aktif' ? 'Nonaktif' : 'Aktif';

        $spj->update([
            'spj_status' => $statusBaru,
            'spj_catatan_admin' => $request->spj_catatan_admin,
            'spj_status_by' => session('pegawai_id'),
            'spj_status_by_nama' => session('pegawai_nama'),
            'spj_status_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Status SPJ berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | BUKU TAMU SPJ
    |--------------------------------------------------------------------------
    */

    public function bukuTamuIndex(Request $request)
    {
        $search = trim($request->input('search', ''));

        $tamu = ModelSPJBukuTamu::with(['spj'])

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query
                        ->where('buku_tamu_nama', 'like', "%{$search}%")
                        ->orWhere('buku_tamu_nip', 'like', "%{$search}%")
                        ->orWhere('buku_tamu_unit', 'like', "%{$search}%")
                        ->orWhere('buku_tamu_tujuan', 'like', "%{$search}%")
                        ->orWhere('spj_uid', 'like', "%{$search}%")

                        ->orWhereHas('spj', function ($spjQuery) use ($search) {
                            $spjQuery
                                ->where('spj_uraian', 'like', "%{$search}%")
                                ->orWhere('spj_operator_nama', 'like', "%{$search}%")
                                ->orWhere('spj_operator_nip', 'like', "%{$search}%");
                        });
                });
            })

            ->latest('buku_tamu_waktu')

            ->paginate(15)

            ->withQueryString();

        return view('administrator-v2.permintaan-spj.buku-tamu', compact('tamu'));
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BUKU TAMU
    |--------------------------------------------------------------------------
    */

    public function bukuTamu($uid)
    {
        $spj = ModelSPJRealisasi::with(['pagu.unit', 'pagu.program', 'pagu.kegiatan', 'pagu.subKegiatan'])
            ->where('spj_uid', $uid)
            ->firstOrFail();

        $tamu = ModelSPJBukuTamu::where('spj_uid', $uid)->latest('buku_tamu_waktu')->get();

        return view('administrator-v2.permintaan-spj.buku-tamu-detail', compact('spj', 'tamu'));
    }
}