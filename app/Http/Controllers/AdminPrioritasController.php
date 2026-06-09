<?php

namespace App\Http\Controllers;

use App\Models\ModelPrioritas;
use App\Models\ModelPrioritasBukti;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPrioritasController extends Controller
{
    public function index()
    {
        $prioritas = ModelPrioritas::with(['bukti.files'])
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrator.prioritas.index', compact('prioritas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prioritas_tahun' => 'required|digits:4',
            'prioritas_judul' => 'required|string|max:255',
            'prioritas_deskripsi' => 'nullable|string',
            'prioritas_status' => 'required|in:Aktif,Nonaktif',
        ]);

        ModelPrioritas::create([
            'prioritas_uid' => Str::uuid(),
            'prioritas_tahun' => $request->prioritas_tahun,
            'prioritas_judul' => $request->prioritas_judul,
            'prioritas_deskripsi' => $request->prioritas_deskripsi,
            'prioritas_status' => $request->prioritas_status,
            'prioritas_created_by' => session('pegawai_id'),
            'prioritas_created_by_nama' => session('pegawai_nama'),
        ]);

        return back()->with('success', 'Data prioritas berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'prioritas_id' => 'required|exists:saplarin_prioritas,prioritas_id',
            'prioritas_tahun' => 'required|digits:4',
            'prioritas_judul' => 'required|string|max:255',
            'prioritas_deskripsi' => 'nullable|string',
            'prioritas_status' => 'required|in:Aktif,Nonaktif',
        ]);

        $prioritas = ModelPrioritas::findOrFail($request->prioritas_id);

        $prioritas->update([
            'prioritas_tahun' => $request->prioritas_tahun,
            'prioritas_judul' => $request->prioritas_judul,
            'prioritas_deskripsi' => $request->prioritas_deskripsi,
            'prioritas_status' => $request->prioritas_status,
        ]);

        return back()->with('success', 'Data prioritas berhasil diperbarui.');
    }

    public function nonaktifBukti($uid)
    {
        $bukti = ModelPrioritasBukti::where('bukti_uid', $uid)->firstOrFail();

        $bukti->update([
            'bukti_status' => 'Nonaktif',
        ]);

        return back()->with('success', 'Bukti prioritas berhasil dinonaktifkan.');
    }

    public function aktifBukti($uid)
    {
        $bukti = ModelPrioritasBukti::where('bukti_uid', $uid)->firstOrFail();

        $bukti->update([
            'bukti_status' => 'Aktif',
        ]);

        return back()->with('success', 'Bukti prioritas berhasil diaktifkan.');
    }
}