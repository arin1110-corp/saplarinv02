<?php

namespace App\Http\Controllers;

use App\Models\ModelKinerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdminKinerjaController extends Controller
{
    public function index()
    {
        $kinerjas = ModelKinerja::with(['progress.bukti', 'progressTerbaru'])
            ->orderBy('kinerja_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $bidangs = [];

        $response = Http::get(env('SADARIN_API') . '/bidang');

        if ($response->ok()) {
            $json = $response->json();
            $bidangs = $json['data'] ?? [];
        }

        return view('administrator.kinerja.index', compact('kinerjas', 'bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kinerja_tahun' => 'required|digits:4',
            'kinerja_bidang_id' => 'required',
            'kinerja_bidang_nama' => 'required|string|max:255',
            'kinerja_kegiatan' => 'required|string|max:255',
            'kinerja_deskripsi' => 'nullable|string',
            'kinerja_status' => 'required|in:Aktif,Nonaktif',
        ]);

        ModelKinerja::create([
            'kinerja_uid' => Str::uuid(),
            'kinerja_tahun' => $request->kinerja_tahun,
            'kinerja_bidang_id' => $request->kinerja_bidang_id,
            'kinerja_bidang_nama' => $request->kinerja_bidang_nama,
            'kinerja_kegiatan' => $request->kinerja_kegiatan,
            'kinerja_deskripsi' => $request->kinerja_deskripsi,
            'kinerja_status' => $request->kinerja_status,
            'kinerja_created_by' => session('pegawai_id'),
            'kinerja_created_by_nama' => session('pegawai_nama'),
        ]);

        return back()->with('success', 'Data kinerja berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'kinerja_id' => 'required|exists:saplarin_kinerja,kinerja_id',
            'kinerja_tahun' => 'required|digits:4',
            'kinerja_bidang_id' => 'required',
            'kinerja_bidang_nama' => 'required|string|max:255',
            'kinerja_kegiatan' => 'required|string|max:255',
            'kinerja_deskripsi' => 'nullable|string',
            'kinerja_status' => 'required|in:Aktif,Nonaktif',
        ]);

        $kinerja = ModelKinerja::findOrFail($request->kinerja_id);

        $kinerja->update([
            'kinerja_tahun' => $request->kinerja_tahun,
            'kinerja_bidang_id' => $request->kinerja_bidang_id,
            'kinerja_bidang_nama' => $request->kinerja_bidang_nama,
            'kinerja_kegiatan' => $request->kinerja_kegiatan,
            'kinerja_deskripsi' => $request->kinerja_deskripsi,
            'kinerja_status' => $request->kinerja_status,
        ]);

        return back()->with('success', 'Data kinerja berhasil diperbarui.');
    }

    public function delete($uid)
    {
        $kinerja = ModelKinerja::where('kinerja_uid', $uid)->firstOrFail();
        $kinerja->delete();

        return back()->with('success', 'Data kinerja berhasil dihapus.');
    }
}