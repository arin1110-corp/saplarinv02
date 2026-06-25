<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelSubKegiatan;
use App\Models\SubKegiatanIndikator;
use Illuminate\Http\Request;

class SubKegiatanIndikatorController extends Controller
{
    public function index()
    {
        $subKegiatan = ModelSubKegiatan::where('sub_kegiatan_status', 1)
            ->orderBy('sub_kegiatan_nama')
            ->get();

        $indikator = SubKegiatanIndikator::with('subKegiatan')
            ->orderBy('tahun', 'desc')
            ->latest()
            ->get();

        return view('admin.sub-kegiatan-indikator.index', compact('subKegiatan', 'indikator'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_kegiatan_id' => 'required',
            'indikator' => 'required|string',
            'target' => 'required|numeric',
            'satuan' => 'required|string|max:100',
            'tahun' => 'required|numeric',
        ]);

        SubKegiatanIndikator::create([
            'sub_kegiatan_id' => $request->sub_kegiatan_id,
            'indikator' => $request->indikator,
            'target' => $request->target,
            'satuan' => $request->satuan,
            'tahun' => $request->tahun,
            'status' => 1,
        ]);

        return back()->with('success', 'Indikator target sub kegiatan berhasil disimpan.');
    }

    public function destroy($id)
    {
        $indikator = SubKegiatanIndikator::findOrFail($id);
        $indikator->delete();

        return back()->with('success', 'Indikator berhasil dihapus.');
    }
}