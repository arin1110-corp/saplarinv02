<?php

namespace App\Http\Controllers;

use App\Models\ModelPadTarget;
use App\Models\ModelPadRealisasi;
use App\Models\ModelPadSubkomponen;
use App\Services\ArinDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserPadController extends Controller
{
    /**
     * Daftar target PAD + riwayat penerimaan
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $search = trim($request->get('search', ''));

        /*
        |--------------------------------------------------------------------------
        | TARGET PAD
        |--------------------------------------------------------------------------
        */

        $targets = ModelPadTarget::with(['jenis', 'komponen', 'realisasi.subkomponen'])
            ->where('pad_target_tahun', $tahun)
            ->where('pad_target_status', true)
            ->orderBy('pad_target_unit_nama')
            ->orderBy('pad_target_komponen')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENERIMAAN
        |--------------------------------------------------------------------------
        */

        $riwayat = ModelPadRealisasi::with(['target.jenis', 'target.komponen', 'subkomponen'])
            ->whereHas('target', function ($query) use ($tahun) {
                $query->where('pad_target_tahun', $tahun);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    /*
                        |--------------------------------------------------------------------------
                        | TANGGAL
                        |--------------------------------------------------------------------------
                        */

                    $q->where('pad_realisasi_tanggal', 'like', '%' . $search . '%');

                    /*
                        |--------------------------------------------------------------------------
                        | KETERANGAN
                        |--------------------------------------------------------------------------
                        */

                    $q->orWhere('pad_realisasi_keterangan', 'like', '%' . $search . '%');

                    /*
                        |--------------------------------------------------------------------------
                        | NOMINAL
                        |--------------------------------------------------------------------------
                        */

                    $q->orWhere('pad_realisasi_nominal', 'like', '%' . $search . '%');

                    /*
                        |--------------------------------------------------------------------------
                        | SUBKOMPONEN
                        |--------------------------------------------------------------------------
                        */

                    $q->orWhereHas('subkomponen', function ($subkomponenQuery) use ($search) {
                        $subkomponenQuery->where('pad_subkomponen_nama', 'like', '%' . $search . '%')->orWhere('pad_subkomponen_kode', 'like', '%' . $search . '%');
                    });

                    /*
                        |--------------------------------------------------------------------------
                        | TARGET
                        |--------------------------------------------------------------------------
                        */

                    $q->orWhereHas('target', function ($targetQuery) use ($search) {
                        /*
                                |--------------------------------------------------------------------------
                                | UNIT
                                |--------------------------------------------------------------------------
                                */

                        $targetQuery->where('pad_target_unit_nama', 'like', '%' . $search . '%');

                        /*
                                |--------------------------------------------------------------------------
                                | JENIS
                                |--------------------------------------------------------------------------
                                */

                        $targetQuery->orWhereHas('jenis', function ($jenisQuery) use ($search) {
                            $jenisQuery->where('pad_jenis_nama', 'like', '%' . $search . '%');
                        });

                        /*
                                |--------------------------------------------------------------------------
                                | KOMPONEN
                                |--------------------------------------------------------------------------
                                */

                        $targetQuery->orWhereHas('komponen', function ($komponenQuery) use ($search) {
                            $komponenQuery->where('pad_komponen_nama', 'like', '%' . $search . '%')->orWhere('pad_komponen_kode', 'like', '%' . $search . '%');
                        });
                    });
                });
            })
            ->orderBy('pad_realisasi_tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        $unit = 'Seluruh Unit';

        return view('user.pad.index', compact('targets', 'riwayat', 'tahun', 'unit', 'search'));
    }

    /**
     * Form input realisasi PAD
     *
     * Operator memilih SUBKOMPONEN.
     */
    public function input($uid)
    {
        $target = ModelPadTarget::with(['jenis', 'komponen.subkomponen'])
            ->where('pad_target_uid', $uid)
            ->where('pad_target_status', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | SUBKOMPONEN
        |--------------------------------------------------------------------------
        */

        $subkomponen = $target->komponen->subkomponen()->where('pad_subkomponen_status', true)->orderBy('pad_subkomponen_nama')->get();

        return view('user.pad.input', compact('target', 'subkomponen'));
    }

    /**
     * Simpan realisasi PAD
     */
    public function store(Request $request, ArinDriveService $arinDrive)
    {
        /*
        |--------------------------------------------------------------------------
        | NORMALISASI NOMINAL
        |--------------------------------------------------------------------------
        */

        $nominal = $request->pad_realisasi_nominal;

        if (is_string($nominal)) {
            $nominal = str_replace('.', '', $nominal);

            $nominal = str_replace(',', '', $nominal);
        }

        $request->merge([
            'pad_realisasi_nominal' => $nominal,
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'pad_realisasi_target' => 'required|exists:saplarin_pad_target,pad_target_id',

            'pad_realisasi_subkomponen' => 'required|exists:saplarin_pad_subkomponen,pad_subkomponen_id',

            'pad_realisasi_tanggal' => 'required|date',

            'pad_realisasi_nominal' => 'required|numeric|min:0.01',

            'pad_realisasi_keterangan' => 'nullable|string',

            'pad_realisasi_dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TARGET
        |--------------------------------------------------------------------------
        */

        $target = ModelPadTarget::with(['jenis', 'komponen'])
            ->where('pad_target_id', $request->pad_realisasi_target)
            ->where('pad_target_status', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | SUBKOMPONEN
        |--------------------------------------------------------------------------
        |
        | Pastikan subkomponen memang milik komponen
        | dari target yang dipilih.
        |
        */

        $subkomponen = ModelPadSubkomponen::where('pad_subkomponen_id', $request->pad_realisasi_subkomponen)->where('pad_subkomponen_komponen', $target->pad_target_komponen)->where('pad_subkomponen_status', true)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | UID
        |--------------------------------------------------------------------------
        */

        $uid = (string) Str::uuid();

        /*
        |--------------------------------------------------------------------------
        | UPLOAD DOKUMEN KE ARINDRIVE
        |--------------------------------------------------------------------------
        */

        $dokumen = null;

        if ($request->hasFile('pad_realisasi_dokumen')) {
            $file = $request->file('pad_realisasi_dokumen');

            $extension = $file->getClientOriginalExtension();

            $namaFile = $uid . '_PAD_' . Str::slug($subkomponen->pad_subkomponen_nama) . '.' . $extension;

            $dokumen = $arinDrive->upload(
                $file,

                'pad_realisasi',

                $namaFile,

                $uid,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN REALISASI
        |--------------------------------------------------------------------------
        */

        ModelPadRealisasi::create([
            'pad_realisasi_uid' => $uid,

            /*
            | Target Komponen
            */

            'pad_realisasi_target' => $target->pad_target_id,

            /*
            | Subkomponen yang dipilih operator
            */

            'pad_realisasi_subkomponen' => $subkomponen->pad_subkomponen_id,

            /*
            | Tanggal
            */

            'pad_realisasi_tanggal' => $request->pad_realisasi_tanggal,

            /*
            | Nominal
            */

            'pad_realisasi_nominal' => $request->pad_realisasi_nominal,

            /*
            | Keterangan
            */

            'pad_realisasi_keterangan' => $request->pad_realisasi_keterangan,

            /*
            | Dokumen
            */

            'pad_realisasi_dokumen' => $dokumen,

            /*
            | Input Operator
            */

            'pad_realisasi_input' => session('pegawai_nip'),

            'pad_realisasi_input_nama' => session('pegawai_nama'),

            /*
            | Unit
            */

            'pad_realisasi_input_unit' => $target->pad_target_unit_nama,

            /*
            | Status
            */

            'pad_realisasi_status' => 'Diterima',
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()->route('user.pad.index')->with('success', 'Penerimaan PAD berhasil disimpan.');
    }
}