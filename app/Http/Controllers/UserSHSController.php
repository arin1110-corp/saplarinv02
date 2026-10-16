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
        $shs = ModelSHS::with('referensiHarga')
            ->orderBy('shs_tahun', 'desc')
            ->orderBy('shs_unit_nama')
            ->orderBy('shs_barang')
            ->get();

        return view('user.shs.index', compact('shs'));
    }

    /**
     * Form tambah SHS
     */
    public function create()
    {
        $kelompoks = ModelSHSKelompok::where('kelompok_status', 1)
            ->orderBy('kelompok_nama')
            ->get();

        $satuans = ModelSHSSatuan::where('satuan_status', 1)
            ->orderBy('satuan_nama')
            ->get();

        return view('user.shs.create', compact('kelompoks', 'satuans'));
    }

    /**
     * Simpan SHS baru
     */
    public function store(Request $request)
    {
        $request->merge([
            'shs_harga' => $this->normalizeHarga(
                $request->input('shs_harga')
            ),

            'shs_harga_referensi' => collect(
                $request->input('shs_harga_referensi', [])
            )->map(function ($harga) {
                return $this->normalizeHarga($harga);
            })->toArray(),
        ]);

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
            'shs_harga_referensi' => 'nullable|array',
            'shs_harga_referensi.*' => 'nullable|numeric|min:0',
            'shs_link_survei' => 'nullable|array',
            'shs_link_survei.*' => 'nullable|url',
        ]);

        DB::transaction(function () use ($request) {
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
                'shs_link_survei' => implode(
                    "\n",
                    $request->input('shs_link_survei', [])
                ),
                'shs_kelompok' => $request->shs_kelompok,
                'shs_status' => 'Diajukan',
                'shs_operator_id' => session('pegawai_id'),
                'shs_operator_nama' => session('pegawai_nama'),
                'shs_operator_nip' => session('pegawai_nip'),
            ]);

            $hargaReferensi = $request->input(
                'shs_harga_referensi',
                []
            );

            $linkSurvei = $request->input(
                'shs_link_survei',
                []
            );

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

        return redirect()
            ->route('user.shs.index')
            ->with('success', 'Usulan SHS berhasil disimpan.');
    }

    /**
     * Form edit SHS
     */
    public function edit($uid)
    {
        $shs = ModelSHS::with('referensiHarga')
            ->where('shs_uid', $uid)
            ->firstOrFail();

        $kelompoks = ModelSHSKelompok::where('kelompok_status', 1)
            ->orderBy('kelompok_nama')
            ->get();

        $satuans = ModelSHSSatuan::where('satuan_status', 1)
            ->orderBy('satuan_nama')
            ->get();

        return view('user.shs.edit', compact(
            'shs',
            'kelompoks',
            'satuans'
        ));
    }

    /**
     * Update SHS
     */
    public function update(Request $request, $uid)
    {
        $shs = ModelSHS::where('shs_uid', $uid)
            ->firstOrFail();

        $request->merge([
            'shs_harga' => $this->normalizeHarga(
                $request->input('shs_harga')
            ),

            'shs_harga_referensi' => collect(
                $request->input('shs_harga_referensi', [])
            )->map(function ($harga) {
                return $this->normalizeHarga($harga);
            })->toArray(),
        ]);

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
            'shs_harga_referensi' => 'nullable|array',
            'shs_harga_referensi.*' => 'nullable|numeric|min:0',
            'shs_link_survei' => 'nullable|array',
            'shs_link_survei.*' => 'nullable|url',
        ]);

        DB::transaction(function () use ($request, $shs) {
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
                'shs_link_survei' => implode(
                    "\n",
                    $request->input('shs_link_survei', [])
                ),
                'shs_kelompok' => $request->shs_kelompok,
            ]);

            $shs->referensiHarga()->delete();

            $hargaReferensi = $request->input(
                'shs_harga_referensi',
                []
            );

            $linkSurvei = $request->input(
                'shs_link_survei',
                []
            );

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

        return redirect()
            ->route('user.shs.index')
            ->with('success', 'Usulan berhasil diperbarui.');
    }

    /**
     * Konfirmasi operator bahwa SHS sudah dimasukkan ke SIPD.
     *
     * Status hanya dapat diubah jika:
     * - SHS berstatus "Diajukan ke BPKAD".
     * - SHS merupakan milik operator yang sedang login.
     */
    public function konfirmasiSIPD(string $uid)
    {
        $pegawaiId = session('pegawai_id');

        if (!$pegawaiId) {
            return redirect()
                ->route('user.shs.index')
                ->with('error', 'Sesi operator tidak ditemukan. Silakan login kembali.');
        }

        $shs = ModelSHS::where('shs_uid', $uid)
            ->where('shs_operator_id', $pegawaiId)
            ->firstOrFail();

        if ($shs->shs_status !== 'Diajukan ke BPKAD') {
            return redirect()
                ->route('user.shs.index')
                ->with('error', 'Konfirmasi SIPD hanya dapat dilakukan setelah usulan diajukan ke BPKAD.');
        }

        $shs->shs_status = 'Sudah Ada di SIPD';
        $shs->save();

        return redirect()
            ->route('user.shs.index')
            ->with('success', 'Status SHS berhasil diperbarui menjadi Sudah Ada di SIPD.');
    }

    /**
     * Normalisasi format harga Rupiah.
     *
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