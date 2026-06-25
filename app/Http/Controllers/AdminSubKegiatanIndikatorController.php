<?php

namespace App\Http\Controllers;

use App\Models\ModelSubKegiatan;
use App\Models\SubKegiatanIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSubKegiatanIndikatorController extends Controller
{
    public function index()
    {
        $indikators = SubKegiatanIndikator::with('subKegiatan')
            ->orderBy('created_at', 'desc')
            ->get();

        $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_status', 1)
            ->orderBy('sub_kegiatan_nama')
            ->get();

        return view('administrator.sub-kegiatan-indikator.index', compact(
            'indikators',
            'subKegiatans'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'indikator_sub_kegiatan_id' => 'required|exists:saplarin_sub_kegiatan,sub_kegiatan_id',
            'indikator_nama' => 'required|string',
            'indikator_target' => 'required|numeric|min:0',
            'indikator_satuan' => 'required|string|max:100',
        ]);

        SubKegiatanIndikator::create([
            'indikator_uid' => Str::uuid(),
            'indikator_sub_kegiatan_id' => $request->indikator_sub_kegiatan_id,
            'indikator_nama' => $request->indikator_nama,
            'indikator_target' => $request->indikator_target,
            'indikator_satuan' => $request->indikator_satuan,
            'indikator_status' => 1,
        ]);

        return back()->with('success', 'Indikator sub kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'indikator_id' => 'required|exists:saplarin_sub_kegiatan_indikator,indikator_id',
            'indikator_sub_kegiatan_id' => 'required|exists:saplarin_sub_kegiatan,sub_kegiatan_id',
            'indikator_nama' => 'required|string',
            'indikator_target' => 'required|numeric|min:0',
            'indikator_satuan' => 'required|string|max:100',
            'indikator_status' => 'required|in:0,1',
        ]);

        $indikator = SubKegiatanIndikator::findOrFail($request->indikator_id);

        $indikator->update([
            'indikator_sub_kegiatan_id' => $request->indikator_sub_kegiatan_id,
            'indikator_nama' => $request->indikator_nama,
            'indikator_target' => $request->indikator_target,
            'indikator_satuan' => $request->indikator_satuan,
            'indikator_status' => $request->indikator_status,
        ]);

        return back()->with('success', 'Indikator sub kegiatan berhasil diperbarui.');
    }

    public function delete($uid)
    {
        $indikator = SubKegiatanIndikator::where('indikator_uid', $uid)->firstOrFail();
        $indikator->delete();

        return back()->with('success', 'Indikator berhasil dihapus.');
    }
}