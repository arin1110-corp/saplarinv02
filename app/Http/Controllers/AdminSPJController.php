<?php

namespace App\Http\Controllers;

use App\Models\ModelProgram;
use App\Models\ModelKegiatan;
use App\Models\ModelSubKegiatan;
use App\Models\ModelSPJPagu;
use App\Models\ModelSPJPaguDetail;
use App\Models\ModelSPJUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSPJController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->search);

        $pagus = ModelSPJPagu::with(['unit', 'program', 'kegiatan', 'subKegiatan', 'detail', 'realisasi'])

            ->when($search, function ($q) use ($search) {
                $q->whereHas('unit', function ($query) use ($search) {
                    $query->where('unit_nama', 'like', "%{$search}%")->orWhere('unit_kode', 'like', "%{$search}%");
                })

                    ->orWhereHas('program', function ($query) use ($search) {
                        $query->where('program_nama', 'like', "%{$search}%")->orWhere('program_kode', 'like', "%{$search}%");
                    })

                    ->orWhereHas('kegiatan', function ($query) use ($search) {
                        $query->where('kegiatan_nama', 'like', "%{$search}%")->orWhere('kegiatan_kode', 'like', "%{$search}%");
                    })

                    ->orWhereHas('subKegiatan', function ($query) use ($search) {
                        $query->where('sub_kegiatan_nama', 'like', "%{$search}%")->orWhere('sub_kegiatan_kode', 'like', "%{$search}%");
                    })

                    ->orWhere('spj_pagu_tahun', 'like', "%{$search}%");
            })

            ->orderByDesc('spj_pagu_tahun')
            ->orderBy('created_at')

            ->paginate(10)

            ->withQueryString();

        $units = ModelSPJUnit::where('unit_status', 1)->orderBy('unit_kode')->get();

        $programs = ModelProgram::where('program_status', 1)->orderBy('program_kode')->get();

        $kegiatans = ModelKegiatan::where('kegiatan_status', 1)->orderBy('kegiatan_kode')->get();

        $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_status', 1)->orderBy('sub_kegiatan_kode')->get();

        return view('administrator-v2.spj.index', compact('pagus', 'units', 'programs', 'kegiatans', 'subKegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'spj_pagu_unit_id' => 'required|exists:saplarin_spj_unit,unit_id',
            'spj_pagu_program_id' => 'required',
            'spj_pagu_kegiatan_id' => 'required',
            'spj_pagu_sub_kegiatan_id' => 'required',
            'spj_pagu_tahun' => 'required|digits:4',

            'pagu_jenis' => 'required|array|min:1',
            'pagu_jenis.*' => 'required|string|max:255',

            'pagu_nominal' => 'required|array|min:1',
            'pagu_nominal.*' => 'required',

            'pagu_tanggal' => 'nullable|array',
            'pagu_tanggal.*' => 'nullable|date',

            'pagu_keterangan' => 'nullable|array',
            'pagu_keterangan.*' => 'nullable|string',
        ]);

        $sudahAda = ModelSPJPagu::where('spj_pagu_tahun', $request->spj_pagu_tahun)->where('spj_pagu_unit_id', $request->spj_pagu_unit_id)->where('spj_pagu_sub_kegiatan_id', $request->spj_pagu_sub_kegiatan_id)->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'Pagu untuk tahun, unit, dan sub kegiatan tersebut sudah ada.');
        }

        $paguJenis = $request->input('pagu_jenis', []);
        $paguNominal = $request->input('pagu_nominal', []);
        $paguTanggal = $request->input('pagu_tanggal', []);
        $paguKeterangan = $request->input('pagu_keterangan', []);

        $paguNominalBersih = $this->bersihkanNominalArray($paguNominal);

        $lastNominal = end($paguNominalBersih);

        $pagu = ModelSPJPagu::create([
            'spj_pagu_uid' => Str::uuid(),

            'spj_pagu_unit_id' => $request->spj_pagu_unit_id,

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

    public function update(Request $request)
    {
        $request->validate([
            'spj_pagu_id' => 'required|exists:saplarin_spj_pagu,spj_pagu_id',

            'spj_pagu_unit_id' => 'required|exists:saplarin_spj_unit,unit_id',
            'spj_pagu_program_id' => 'required',
            'spj_pagu_kegiatan_id' => 'required',
            'spj_pagu_sub_kegiatan_id' => 'required',
            'spj_pagu_tahun' => 'required|digits:4',
            'spj_pagu_status' => 'required|in:0,1',

            'pagu_jenis' => 'required|array|min:1',
            'pagu_jenis.*' => 'required|string|max:255',

            'pagu_nominal' => 'required|array|min:1',
            'pagu_nominal.*' => 'required',

            'pagu_tanggal' => 'nullable|array',
            'pagu_tanggal.*' => 'nullable|date',

            'pagu_keterangan' => 'nullable|array',
            'pagu_keterangan.*' => 'nullable|string',
        ]);

        $pagu = ModelSPJPagu::findOrFail($request->spj_pagu_id);

        $sudahAda = ModelSPJPagu::where('spj_pagu_tahun', $request->spj_pagu_tahun)->where('spj_pagu_unit_id', $request->spj_pagu_unit_id)->where('spj_pagu_sub_kegiatan_id', $request->spj_pagu_sub_kegiatan_id)->where('spj_pagu_id', '!=', $pagu->spj_pagu_id)->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'Pagu untuk tahun, unit, dan sub kegiatan tersebut sudah ada.');
        }

        $paguJenis = $request->input('pagu_jenis', []);
        $paguNominal = $request->input('pagu_nominal', []);
        $paguTanggal = $request->input('pagu_tanggal', []);
        $paguKeterangan = $request->input('pagu_keterangan', []);

        $paguNominalBersih = $this->bersihkanNominalArray($paguNominal);

        $lastNominal = end($paguNominalBersih);

        $pagu->update([
            'spj_pagu_unit_id' => $request->spj_pagu_unit_id,

            'spj_pagu_program_id' => $request->spj_pagu_program_id,
            'spj_pagu_kegiatan_id' => $request->spj_pagu_kegiatan_id,
            'spj_pagu_sub_kegiatan_id' => $request->spj_pagu_sub_kegiatan_id,
            'spj_pagu_tahun' => $request->spj_pagu_tahun,

            'spj_pagu_final' => $lastNominal ?: 0,
            'spj_pagu_status' => $request->spj_pagu_status,
        ]);

        ModelSPJPaguDetail::where('spj_pagu_detail_pagu_id', $pagu->spj_pagu_id)->delete();

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

        return back()->with('success', 'Data pagu SPJ berhasil diperbarui.');
    }

    public function toggleStatus($uid)
    {
        $pagu = ModelSPJPagu::where('spj_pagu_uid', $uid)->firstOrFail();

        $pagu->update([
            'spj_pagu_status' => $pagu->spj_pagu_status == 1 ? 0 : 1,
        ]);

        return back()->with('success', 'Status pagu SPJ berhasil diperbarui.');
    }

    private function bersihkanNominalArray(array $paguNominal): array
    {
        $hasil = [];

        foreach ($paguNominal as $index => $nominal) {
            $nominal = (string) $nominal;

            $nominal = str_replace('Rp', '', $nominal);
            $nominal = str_replace(' ', '', $nominal);
            $nominal = str_replace('.', '', $nominal);
            $nominal = str_replace(',', '.', $nominal);

            $hasil[$index] = (float) $nominal;
        }

        return $hasil;
    }
}