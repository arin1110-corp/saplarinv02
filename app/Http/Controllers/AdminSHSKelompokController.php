<?php

namespace App\Http\Controllers;

use App\Models\ModelSHSKelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSHSKelompokController extends Controller
{
    public function index()
    {
        $kelompoks = ModelSHSKelompok::withCount(['shs as jumlah_shs'])
            ->orderBy('kelompok_kode')
            ->get();

        return view('administrator.shs-kelompok.index', compact('kelompoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelompok_kode' => 'required|unique:saplarin_shs_kelompok,kelompok_kode',
            'kelompok_nama' => 'required',
            'kelompok_tipe' => 'required',
        ]);

        ModelSHSKelompok::create([
            'kelompok_uid' => Str::uuid(),

            'kelompok_kode' => $request->kelompok_kode,

            'kelompok_nama' => $request->kelompok_nama,

            'kelompok_tipe' => $request->kelompok_tipe,

            'kelompok_status' => 1,

            'created_by' => session('pegawai_nama'),
        ]);

        return back()->with('success', 'Kelompok barang berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'kelompok_id' => 'required',
            'kelompok_kode' => 'required',
            'kelompok_nama' => 'required',
            'kelompok_tipe' => 'required',
        ]);

        $data = ModelSHSKelompok::findOrFail($request->kelompok_id);

        $data->update([
            'kelompok_kode' => $request->kelompok_kode,

            'kelompok_nama' => $request->kelompok_nama,

            'kelompok_tipe' => $request->kelompok_tipe,

            'updated_by' => session('pegawai_nama'),
        ]);

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function status($uid)
    {
        $data = ModelSHSKelompok::where('kelompok_uid', $uid)->firstOrFail();

        $data->kelompok_status = !$data->kelompok_status;

        $data->save();

        return back()->with('success', 'Status berhasil diperbarui.');
    }
}