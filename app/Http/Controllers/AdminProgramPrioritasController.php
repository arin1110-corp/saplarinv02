<?php

namespace App\Http\Controllers;

use App\Models\ModelProgramPrioritas;
use App\Models\ModelProgramPrioritasRencana;
use App\Models\ModelProgramPrioritasCapaian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProgramPrioritasController extends Controller
{
    public function index()
    {
        $prioritas = ModelProgramPrioritas::with([
                'rencana.capaian.files',
            ])
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('administrator.program-prioritas.index', compact('prioritas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prioritas_tahun' => 'required|digits:4',
            'prioritas_judul' => 'required|string|max:255',
            'prioritas_deskripsi' => 'nullable|string',
            'prioritas_status' => 'required|in:Aktif,Nonaktif',
        ]);

        ModelProgramPrioritas::create([
            'prioritas_uid' => Str::uuid(),
            'prioritas_tahun' => $request->prioritas_tahun,
            'prioritas_judul' => $request->prioritas_judul,
            'prioritas_deskripsi' => $request->prioritas_deskripsi,
            'prioritas_status' => $request->prioritas_status,
            'prioritas_created_by' => session('pegawai_id'),
            'prioritas_created_by_nama' => session('pegawai_nama'),
        ]);

        return back()->with('success', 'Program prioritas berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'prioritas_id' => 'required|exists:sadarin_program_prioritas,prioritas_id',
            'prioritas_tahun' => 'required|digits:4',
            'prioritas_judul' => 'required|string|max:255',
            'prioritas_deskripsi' => 'nullable|string',
            'prioritas_status' => 'required|in:Aktif,Nonaktif',
        ]);

        $prioritas = ModelProgramPrioritas::findOrFail($request->prioritas_id);

        $prioritas->update([
            'prioritas_tahun' => $request->prioritas_tahun,
            'prioritas_judul' => $request->prioritas_judul,
            'prioritas_deskripsi' => $request->prioritas_deskripsi,
            'prioritas_status' => $request->prioritas_status,
        ]);

        return back()->with('success', 'Program prioritas berhasil diperbarui.');
    }

    public function nonaktifRencana($uid)
    {
        $rencana = ModelProgramPrioritasRencana::where('rencana_uid', $uid)->firstOrFail();

        $rencana->update([
            'rencana_status' => 'Nonaktif',
        ]);

        return back()->with('success', 'Rencana aksi berhasil dinonaktifkan.');
    }

    public function aktifRencana($uid)
    {
        $rencana = ModelProgramPrioritasRencana::where('rencana_uid', $uid)->firstOrFail();

        $rencana->update([
            'rencana_status' => 'Aktif',
        ]);

        return back()->with('success', 'Rencana aksi berhasil diaktifkan.');
    }

    public function nonaktifCapaian($uid)
    {
        $capaian = ModelProgramPrioritasCapaian::where('capaian_uid', $uid)->firstOrFail();

        $capaian->update([
            'capaian_status' => 'Nonaktif',
        ]);

        return back()->with('success', 'Capaian berhasil dinonaktifkan.');
    }

    public function aktifCapaian($uid)
    {
        $capaian = ModelProgramPrioritasCapaian::where('capaian_uid', $uid)->firstOrFail();

        $capaian->update([
            'capaian_status' => 'Aktif',
        ]);

        return back()->with('success', 'Capaian berhasil diaktifkan.');
    }
}