<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJPagu;
use App\Models\ModelSPJRealisasi;
use App\Models\ModelSPJUnit;
use App\Services\ArinDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSPJController extends Controller
{
    public function index()
    {
        $pagus = ModelSPJPagu::with([
            'unit',
                'program',
                'kegiatan',
                'subKegiatan',
                'detail',
                'realisasi',
            ])
            ->where('spj_pagu_status', 1)
            ->orderBy('spj_pagu_tahun', 'desc')
            ->orderBy('spj_pagu_unit_id', 'asc')
            ->get();

        $units = ModelSPJUnit::where('unit_status', 1)
            ->orderBy('unit_kode', 'asc')
            ->get();

        return view('user.spj.index', compact('pagus', 'units'));
    }

    public function store(Request $request, $uid, ArinDriveService $arinDrive)
    {
        $request->validate([
            'spj_uraian' => 'required|string',
            'spj_nominal' => 'required|numeric|min:1',
            'spj_tanggal' => 'required|date',
            'spj_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $pagu = ModelSPJPagu::with(['unit', 'realisasi'])
            ->where('spj_pagu_uid', $uid)
            ->where('spj_pagu_status', 1)
            ->firstOrFail();

        $totalRealisasi = $pagu->realisasi()
            ->where('spj_status', 'Aktif')
            ->sum('spj_nominal');

        $sisaPagu = $pagu->spj_pagu_final - $totalRealisasi;

        if ((float) $request->spj_nominal > (float) $sisaPagu) {
            return back()->with(
                'error',
                'Nominal SPJ melebihi sisa pagu ' .
                    ($pagu->unit->unit_nama ?? '-') .
                    '. Sisa pagu: Rp ' . number_format($sisaPagu, 0, ',', '.')
            );
        }

        $spjUid = (string) Str::uuid();

        $file = $request->file('spj_file');

        $filename = $spjUid
            . '_SPJ_'
            . date('Ymd_His')
            . '.'
            . $file->getClientOriginalExtension();

        $spjFile = $arinDrive->upload(
            $file,
            'spj',
            $filename,
            $spjUid
        );

        ModelSPJRealisasi::create([
            'spj_uid' => $spjUid,
            'spj_pagu_id' => $pagu->spj_pagu_id,
            'spj_uraian' => $request->spj_uraian,
            'spj_nominal' => $request->spj_nominal,
            'spj_tanggal' => $request->spj_tanggal,
            'spj_tanggal_input' => Carbon::now(),
            'spj_file' => $spjFile,

            'spj_operator_id' => session('pegawai_id'),
            'spj_operator_nama' => session('pegawai_nama'),
            'spj_operator_nip' => session('pegawai_nip'),

            'spj_bidang_id' => session('pegawai_bidang_id'),
            'spj_bidang_nama' => session('pegawai_bidang'),

            'spj_status' => 'Aktif',
        ]);

        return back()->with('success', 'SPJ berhasil diinput.');
    }
}