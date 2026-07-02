<?php

namespace App\Http\Controllers;

use App\Models\ModelSHS;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ModelSHSKelompok;
use App\Models\ModelSHSSatuan;

class UserSHSController extends Controller
{
    public function index()
    {
        $shs = ModelSHS::orderBy('shs_tahun', 'desc')->orderBy('shs_unit_nama')->orderBy('shs_barang')->get();

        return view('user.shs.index', compact('shs'));
    }

    public function create()
    {
        $kelompoks = ModelSHSKelompok::where('kelompok_status', 1)->orderBy('kelompok_nama')->get();
        $satuans = ModelSHSSatuan::where('satuan_status', 1)->orderBy('satuan_nama')->get();

        return view('user.shs.create', compact('kelompoks', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shs_tahun' => 'required|digits:4',

            'shs_unit_kode' => 'required',

            'shs_unit_nama' => 'required',

            'shs_kode_kelompok' => 'required|max:100',

            'shs_kelompok_barang' => 'required|max:255',

            'shs_barang' => 'required|max:255',

            'shs_spesifikasi' => 'required',

            'shs_satuan' => 'required|max:100',

            'shs_harga' => 'required|numeric|min:1',

            'shs_merek' => 'nullable|max:255',

            'shs_tipe' => 'nullable|max:255',

            'shs_dasar_usulan' => 'nullable|max:255',

            'shs_keterangan' => 'nullable',

            'shs_tkdn' => 'nullable|numeric',

            'shs_kelompok' => 'required',

            'shs_link_survei' => 'nullable|array',

            'shs_link_survei.*' => 'nullable|url',
        ]);

        ModelSHS::create([
            'shs_uid' => Str::uuid(),

            'shs_tahun' => $request->shs_tahun,

            'shs_unit_kode' => $request->shs_unit_kode,

            'shs_unit_nama' => $request->shs_unit_nama,

            'shs_kode_kelompok' => $request->shs_kode_kelompok,

            'shs_kelompok_barang' => $request->shs_kelompok_barang,

            'shs_barang' => $request->shs_barang,

            'shs_spesifikasi' => $request->shs_spesifikasi,

            'shs_satuan' => $request->shs_satuan,

            'shs_harga' => $request->shs_harga,
            
            'shs_merek' => $request->shs_merek,

            'shs_tipe' => $request->shs_tipe,

            'shs_dasar_usulan' => $request->shs_dasar_usulan,

            'shs_keterangan' => $request->shs_keterangan,

            'shs_tkdn' => $request->shs_tkdn,

            'shs_link_survei' => implode("\n", $request->shs_link_survei ?? []),

            'shs_kelompok' => $request->shs_kelompok,

            'shs_status' => 'Diajukan',

            'shs_operator_id' => session('pegawai_id'),

            'shs_operator_nama' => session('pegawai_nama'),

            'shs_operator_nip' => session('pegawai_nip'),
        ]);

        return redirect()->route('user.shs.index')->with('success', 'Usulan SHS berhasil disimpan.');
    }
    public function edit($uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $kelompoks = ModelSHSKelompok::where('kelompok_status', 1)->orderBy('kelompok_nama')->get();

        $satuans = ModelSHSSatuan::where('satuan_status', 1)->orderBy('satuan_nama')->get();

        return view('user.shs.edit', compact('shs', 'kelompoks', 'satuans'));
    }
    public function update(Request $request, $uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        $harga = str_replace('.', '', $request->shs_harga);

        $shs->update([
            'shs_tahun' => $request->shs_tahun,

            'shs_unit_kode' => $request->shs_unit_kode,

            'shs_unit_nama' => $request->shs_unit_nama,

            'shs_kode_kelompok' => $request->shs_kode_kelompok,

            'shs_kelompok_barang' => $request->shs_kelompok_barang,

            'shs_barang' => $request->shs_barang,

            'shs_spesifikasi' => $request->shs_spesifikasi,

            'shs_satuan' => $request->shs_satuan,

            'shs_harga' => $harga,

            'shs_merek' => $request->shs_merek,

            'shs_tipe' => $request->shs_tipe,

            'shs_dasar_usulan' => $request->shs_dasar_usulan,

            'shs_keterangan' => $request->shs_keterangan,

            'shs_tkdn' => $request->shs_tkdn,

            'shs_kelompok' => $request->shs_kelompok,

            'shs_merek' => $request->shs_merek,

            'shs_tipe' => $request->shs_tipe,

            'shs_dasar_usulan' => $request->shs_dasar_usulan,

            'shs_keterangan' => $request->shs_keterangan,

            'shs_link_survei' => implode("\n", $request->shs_link_survei ?? []),

            // setelah diedit kembali Draft

        ]);

        return redirect()->route('user.shs.index')->with('success', 'Usulan berhasil diperbarui.');
    }
}