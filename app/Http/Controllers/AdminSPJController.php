<?php

namespace App\Http\Controllers;

use App\Models\ModelProgram;
use App\Models\ModelKegiatan;
use App\Models\ModelSubKegiatan;
use App\Models\ModelSPJPagu;
use App\Models\ModelSPJPaguDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSPJController extends Controller
{
    public function index()
    {
        $pagus = ModelSPJPagu::with(['program', 'kegiatan', 'subKegiatan', 'detail', 'realisasi'])
            ->orderBy('spj_pagu_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $programs = ModelProgram::where('program_status', 1)->get();
        $kegiatans = ModelKegiatan::where('kegiatan_status', 1)->get();
        $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_status', 1)->get();

        return view('administrator.spj.index', compact('pagus', 'programs', 'kegiatans', 'subKegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'spj_pagu_program_id' => 'required',
            'spj_pagu_kegiatan_id' => 'required',
            'spj_pagu_sub_kegiatan_id' => 'required',
            'spj_pagu_tahun' => 'required|digits:4',

            'pagu_jenis' => 'required|array|min:1',
            'pagu_jenis.*' => 'required|string|max:255',

            'pagu_nominal' => 'required|array|min:1',
            'pagu_nominal.*' => 'required|numeric|min:0',

            'pagu_tanggal' => 'nullable|array',
            'pagu_tanggal.*' => 'nullable|date',

            'pagu_keterangan' => 'nullable|array',
            'pagu_keterangan.*' => 'nullable|string',
        ]);

        $paguJenis = $request->input('pagu_jenis', []);
        $paguNominal = $request->input('pagu_nominal', []);
        $paguTanggal = $request->input('pagu_tanggal', []);
        $paguKeterangan = $request->input('pagu_keterangan', []);

        $paguNominalBersih = [];

        foreach ($paguNominal as $index => $nominal) {
            $nominalBersih = str_replace(['.', ','], ['', '.'], $nominal);
            $paguNominalBersih[$index] = (float) $nominalBersih;
        }

        $lastNominal = end($paguNominalBersih);

        $pagu = ModelSPJPagu::create([
            'spj_pagu_uid' => Str::uuid(),
            'spj_pagu_program_id' => $request->spj_pagu_program_id,
            'spj_pagu_kegiatan_id' => $request->spj_pagu_kegiatan_id,
            'spj_pagu_sub_kegiatan_id' => $request->spj_pagu_sub_kegiatan_id,
            'spj_pagu_tahun' => $request->spj_pagu_tahun,
            'spj_pagu_final' => $lastNominal ?: 0,
            'spj_pagu_status' => 1,
            'spj_pagu_created_by' => session('pegawai_id'),
            'spj_pagu_created_by_nama' => session('pegawai_nama'),
        ]);

        foreach ($paguNominalBersih as $index => $nominal) {
            ModelSPJPaguDetail::create([
                'spj_pagu_detail_pagu_id' => $pagu->spj_pagu_id,
                'spj_pagu_detail_jenis' => $paguJenis[$index] ?? 'Pagu',
                'spj_pagu_detail_urutan' => $index + 1,
                'spj_pagu_detail_nominal' => $nominal,
                'spj_pagu_detail_tanggal' => $paguTanggal[$index] ?? null,
                'spj_pagu_detail_keterangan' => $paguKeterangan[$index] ?? null,
            ]);
        }

        return back()->with('success', 'Data pagu SPJ berhasil ditambahkan.');
    }

    public function toggleStatus($uid)
    {
        $pagu = ModelSPJPagu::where('spj_pagu_uid', $uid)->firstOrFail();

        $pagu->update([
            'spj_pagu_status' => $pagu->spj_pagu_status == 1 ? 0 : 1,
        ]);

        return back()->with('success', 'Status pagu SPJ berhasil diperbarui.');
    }
}