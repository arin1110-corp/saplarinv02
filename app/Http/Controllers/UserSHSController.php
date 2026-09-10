<?php

namespace App\Http\Controllers;

use App\Models\ModelSHS;
use App\Models\ModelSHSKelompok;
use App\Models\ModelSHSSatuan;
use App\Models\ModelSHSReferensiHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSHSController extends Controller
{
    /**
     * Daftar SHS
     */
    public function index()
    {
        $shs = ModelSHS::with('referensiHarga')->orderBy('shs_tahun', 'desc')->orderBy('shs_unit_nama')->orderBy('shs_barang')->get();

        return view('user.shs.index', compact('shs'));
    }

    /**
     * Form tambah SHS
     */
    public function create()
    {
        $kelompoks = ModelSHSKelompok::where('kelompok_status', 1)->orderBy('kelompok_nama')->get();

        $satuans = ModelSHSSatuan::where('satuan_status', 1)->orderBy('satuan_nama')->get();

        return view('user.shs.create', compact('kelompoks', 'satuans'));
    }

    /**
     * Simpan SHS baru
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Normalisasi harga sebelum validasi
        |--------------------------------------------------------------------------
        | Input dari form menggunakan format Rupiah:
        | 12.000.000
        |
        | Diubah menjadi:
        | 12000000
        |
        | sehingga rule numeric Laravel dapat bekerja.
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'shs_harga' => $this->normalizeHarga($request->input('shs_harga')),

            'shs_harga_referensi' => collect($request->input('shs_harga_referensi', []))
                ->map(function ($harga) {
                    return $this->normalizeHarga($harga);
                })
                ->toArray(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | Referensi harga
            |--------------------------------------------------------------------------
            */

            'shs_harga_referensi' => 'nullable|array',

            'shs_harga_referensi.*' => 'nullable|numeric|min:0',

            'shs_link_survei' => 'nullable|array',

            'shs_link_survei.*' => 'nullable|url',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan dalam transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($request) {
            /*
            |--------------------------------------------------------------------------
            | Simpan data utama SHS
            |--------------------------------------------------------------------------
            */

            $shs = ModelSHS::create([
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

                /*
                |--------------------------------------------------------------------------
                | Field lama tetap disimpan
                |--------------------------------------------------------------------------
                */

                'shs_link_survei' => implode("\n", $request->input('shs_link_survei', [])),

                'shs_kelompok' => $request->shs_kelompok,

                'shs_status' => 'Diajukan',

                'shs_operator_id' => session('pegawai_id'),

                'shs_operator_nama' => session('pegawai_nama'),

                'shs_operator_nip' => session('pegawai_nip'),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan referensi harga
            |--------------------------------------------------------------------------
            */

            $hargaReferensi = $request->input('shs_harga_referensi', []);

            $linkSurvei = $request->input('shs_link_survei', []);

            foreach ($linkSurvei as $index => $link) {
                $link = trim($link);

                if ($link === '') {
                    continue;
                }

                $harga = $hargaReferensi[$index] ?? null;

                if ($harga === null || $harga === '') {
                    continue;
                }

                ModelSHSReferensiHarga::create([
                    'shs_referensi_uid' => Str::uuid(),

                    'shs_id' => $shs->shs_id,

                    'shs_referensi_harga' => $harga,

                    'shs_referensi_link' => $link,
                ]);
            }
        });

        return redirect()->route('user.shs.index')->with('success', 'Usulan SHS berhasil disimpan.');
    }

    /**
     * Form edit SHS
     */
    public function edit($uid)
    {
        $shs = ModelSHS::with('referensiHarga')->where('shs_uid', $uid)->firstOrFail();

        $kelompoks = ModelSHSKelompok::where('kelompok_status', 1)->orderBy('kelompok_nama')->get();

        $satuans = ModelSHSSatuan::where('satuan_status', 1)->orderBy('satuan_nama')->get();

        return view('user.shs.edit', compact('shs', 'kelompoks', 'satuans'));
    }

    /**
     * Update SHS
     */
    public function update(Request $request, $uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Normalisasi harga sebelum validasi
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'shs_harga' => $this->normalizeHarga($request->input('shs_harga')),

            'shs_harga_referensi' => collect($request->input('shs_harga_referensi', []))
                ->map(function ($harga) {
                    return $this->normalizeHarga($harga);
                })
                ->toArray(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | Referensi harga
            |--------------------------------------------------------------------------
            */

            'shs_harga_referensi' => 'nullable|array',

            'shs_harga_referensi.*' => 'nullable|numeric|min:0',

            'shs_link_survei' => 'nullable|array',

            'shs_link_survei.*' => 'nullable|url',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update dalam transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($request, $shs) {
            /*
            |--------------------------------------------------------------------------
            | Update data utama
            |--------------------------------------------------------------------------
            */

            $shs->update([
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

                /*
                |--------------------------------------------------------------------------
                | Field lama tetap disimpan
                |--------------------------------------------------------------------------
                */

                'shs_link_survei' => implode("\n", $request->input('shs_link_survei', [])),

                'shs_kelompok' => $request->shs_kelompok,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Hapus referensi lama
            |--------------------------------------------------------------------------
            */

            $shs->referensiHarga()->delete();

            /*
            |--------------------------------------------------------------------------
            | Buat ulang referensi
            |--------------------------------------------------------------------------
            */

            $hargaReferensi = $request->input('shs_harga_referensi', []);

            $linkSurvei = $request->input('shs_link_survei', []);

            foreach ($linkSurvei as $index => $link) {
                $link = trim($link);

                if ($link === '') {
                    continue;
                }

                $harga = $hargaReferensi[$index] ?? null;

                if ($harga === null || $harga === '') {
                    continue;
                }

                ModelSHSReferensiHarga::create([
                    'shs_referensi_uid' => Str::uuid(),

                    'shs_id' => $shs->shs_id,

                    'shs_referensi_harga' => $harga,

                    'shs_referensi_link' => $link,
                ]);
            }
        });

        return redirect()->route('user.shs.index')->with('success', 'Usulan berhasil diperbarui.');
    }

    /**
     * Normalisasi format harga Rupiah
     *
     * Contoh:
     * 12.000.000 -> 12000000
     * 1.500.000  -> 1500000
     * 500000     -> 500000
     */
    private function normalizeHarga($harga)
    {
        if ($harga === null || $harga === '') {
            return $harga;
        }

        return str_replace('.', '', $harga);
    }
}