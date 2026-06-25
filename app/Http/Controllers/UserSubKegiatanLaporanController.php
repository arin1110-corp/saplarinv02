<?php

namespace App\Http\Controllers;

use App\Models\ModelSubKegiatan;
use App\Models\SubKegiatanIndikator;
use App\Models\SubKegiatanLaporan;
use App\Models\SubKegiatanLaporanDetail;
use App\Models\SubKegiatanPermasalahan;
use App\Models\SubKegiatanSolusi;
use App\Models\SubKegiatanTindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSubKegiatanLaporanController extends Controller
{
    public function index()
    {
        $laporans = SubKegiatanLaporan::with([
                'subKegiatan',
                'detail',
                'permasalahan',
                'solusi',
                'tindakLanjut',
            ])
            ->latest('laporan_tahun')
            ->latest('laporan_bulan')
            ->get();

        return view(
            'user.laporan-sub-kegiatan.index',
            compact('laporans')
        );
    }

    public function create()
    {
        return view('user.laporan-sub-kegiatan.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil sub kegiatan berdasarkan unit
    |--------------------------------------------------------------------------
    */

    public function getSubKegiatanByUnit(Request $request)
    {
        $request->validate([
            'unit' => 'required'
        ]);

        $subKegiatans = SubKegiatanIndikator::with('subKegiatan')
            ->where('indikator_unit_kode', $request->unit)
            ->where('indikator_status', 1)
            ->get()
            ->pluck('subKegiatan')
            ->filter()
            ->unique('sub_kegiatan_id')
            ->values();

        return response()->json($subKegiatans);
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil indikator berdasarkan unit + sub kegiatan
    |--------------------------------------------------------------------------
    */

    public function getIndikator(Request $request)
    {
        $request->validate([
            'unit' => 'required',
            'sub_kegiatan_id' => 'required'
        ]);

        $indikators = SubKegiatanIndikator::where(
            'indikator_unit_kode',
            $request->unit
        )
            ->where(
                'indikator_sub_kegiatan_id',
                $request->sub_kegiatan_id
            )
            ->where('indikator_status', 1)
            ->orderBy('indikator_id')
            ->get();

        return response()->json($indikators);
    }

    /*
    |--------------------------------------------------------------------------
    | Simpan laporan
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'laporan_unit_kode' => 'required',
            'laporan_unit_nama' => 'required',

            'laporan_sub_kegiatan_id' =>
            'required|exists:saplarin_sub_kegiatan,sub_kegiatan_id',

            'laporan_bulan' =>
            'required|integer|min:1|max:12',

            'laporan_tahun' =>
            'required|digits:4',

            'realisasi' =>
            'required|array|min:1',

            'realisasi.*' =>
            'required|numeric|min:0',

            'permasalahan' =>
            'nullable|array',

            'solusi' =>
            'nullable|array',

            'tindak_lanjut' =>
            'nullable|array',
        ]);

        $sudahAda = SubKegiatanLaporan::where(
            'laporan_unit_kode',
            $request->laporan_unit_kode
        )
            ->where(
                'laporan_sub_kegiatan_id',
                $request->laporan_sub_kegiatan_id
            )
            ->where(
                'laporan_bulan',
                $request->laporan_bulan
            )
            ->where(
                'laporan_tahun',
                $request->laporan_tahun
            )
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Laporan bulan tersebut sudah diinput.'
                );
        }

        DB::transaction(function () use ($request) {

            $laporan = SubKegiatanLaporan::create([
                'laporan_uid' => Str::uuid(),

                'laporan_unit_kode' =>
                $request->laporan_unit_kode,

                'laporan_unit_nama' =>
                $request->laporan_unit_nama,

                'laporan_sub_kegiatan_id' =>
                $request->laporan_sub_kegiatan_id,

                'laporan_bulan' =>
                $request->laporan_bulan,

                'laporan_tahun' =>
                $request->laporan_tahun,

                'laporan_status' =>
                'Aktif',

                'laporan_created_by' =>
                session('pegawai_id'),

                'laporan_created_by_nama' =>
                session('pegawai_nama'),

                'laporan_created_by_nip' =>
                session('pegawai_nip'),
            ]);

            foreach ($request->realisasi as $indikatorId => $nilai) {

                $indikator = SubKegiatanIndikator::findOrFail(
                    $indikatorId
                );

                SubKegiatanLaporanDetail::create([
                    'detail_laporan_id' =>
                    $laporan->laporan_id,

                    'detail_indikator_id' =>
                    $indikator->indikator_id,

                    'detail_indikator_nama' =>
                    $indikator->indikator_nama,

                    'detail_target' =>
                    $indikator->indikator_target,

                    'detail_realisasi' =>
                    $nilai,

                    'detail_satuan' =>
                    $indikator->indikator_satuan,
                ]);
            }

            foreach ($request->permasalahan ?? [] as $item) {

                if ($item) {
                    SubKegiatanPermasalahan::create([
                        'permasalahan_laporan_id' =>
                        $laporan->laporan_id,

                        'permasalahan_uraian' =>
                        $item,
                    ]);
                }
            }

            foreach ($request->solusi ?? [] as $item) {

                if ($item) {
                    SubKegiatanSolusi::create([
                        'solusi_laporan_id' =>
                        $laporan->laporan_id,

                        'solusi_uraian' =>
                        $item,
                    ]);
                }
            }

            foreach ($request->tindak_lanjut ?? [] as $item) {

                if ($item) {
                    SubKegiatanTindakLanjut::create([
                        'tindak_lanjut_laporan_id' =>
                        $laporan->laporan_id,

                        'tindak_lanjut_uraian' =>
                        $item,
                    ]);
                }
            }
        });

        return redirect()
            ->route('user.laporan-sub-kegiatan.index')
            ->with(
                'success',
                'Laporan sub kegiatan berhasil disimpan.'
            );
    }
}