<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJPagu;
use App\Models\ModelSPJRealisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSPJController extends Controller
{
    public function index()
    {
        $pagus = ModelSPJPagu::with([
                'program',
                'kegiatan',
                'subKegiatan',
                'detail',
                'realisasi',
            ])
            ->where('spj_pagu_status', 1)
            ->orderBy('spj_pagu_tahun', 'desc')
            ->get();

        return view('user.spj.index', compact('pagus'));
    }

    public function store(Request $request, $uid)
    {
        $request->validate([
            'spj_uraian' => 'required|string',
            'spj_nominal' => 'required|numeric|min:1',
            'spj_tanggal' => 'required|date',
            'spj_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $pagu = ModelSPJPagu::where('spj_pagu_uid', $uid)
            ->where('spj_pagu_status', 1)
            ->firstOrFail();

        $folder = public_path('assets/spj');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $file = $request->file('spj_file');
        $filename = Str::uuid() . '-spj.' . $file->getClientOriginalExtension();

        $file->move($folder, $filename);

        ModelSPJRealisasi::create([
            'spj_uid' => Str::uuid(),
            'spj_pagu_id' => $pagu->spj_pagu_id,
            'spj_uraian' => $request->spj_uraian,
            'spj_nominal' => $request->spj_nominal,
            'spj_tanggal' => $request->spj_tanggal,
            'spj_tanggal_input' => Carbon::now(),
            'spj_file' => 'assets/spj/' . $filename,
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