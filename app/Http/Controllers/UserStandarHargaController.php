<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ModelStandarHarga;
use App\Models\ModelStandarHargaPenggunaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserStandarHargaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN UTAMA
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $tahun = $request->get(
            'tahun',
            now()->year
        );

        $jenis = $request->get(
            'jenis',
            'SSH'
        );

        $search = trim(
            $request->get('search', '')
        );

        /*
        |--------------------------------------------------------------------------
        | MASTER STANDAR HARGA
        |--------------------------------------------------------------------------
        */

        $data = ModelStandarHarga::query()

            ->where(
                'standar_harga_tahun',
                $tahun
            )

            ->where(
                'standar_harga_jenis',
                $jenis
            )

            ->where(
                'standar_harga_status',
                true
            )

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'standar_harga_kode_barang',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'standar_harga_uraian_barang',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'standar_harga_kode_kelompok',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'standar_harga_uraian_kelompok',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'standar_harga_id_standar',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'standar_harga_kode_rekening',
                        'like',
                        '%' . $search . '%'
                    );

                });

            })

            ->orderBy(
                'standar_harga_uraian_barang'
            )

            ->paginate(20)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | DATA YANG SUDAH DIPILIH OPERATOR
        |--------------------------------------------------------------------------
        */

        $nip = session('pegawai_nip');

        $penggunaan = ModelStandarHargaPenggunaan::query()

            ->where(
                'penggunaan_tahun',
                $tahun
            )

            ->where(
                'penggunaan_input_nip',
                $nip
            )

            ->where(
                'penggunaan_status',
                true
            )

            ->whereHas('standarHarga', function ($q) use ($jenis) {

                $q->where(
                    'standar_harga_jenis',
                    $jenis
                );
            })

            ->pluck(
                'penggunaan_standar_harga'
            )

            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | TOTAL MASTER
        |--------------------------------------------------------------------------
        */

        $totalMaster = ModelStandarHarga::query()

            ->where(
                'standar_harga_tahun',
                $tahun
            )

            ->where(
                'standar_harga_jenis',
                $jenis
            )

            ->where(
                'standar_harga_status',
                true
            )

            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL YANG DIGUNAKAN OPERATOR
        |--------------------------------------------------------------------------
        */

        $totalDigunakan = ModelStandarHargaPenggunaan::query()

            ->where(
                'penggunaan_tahun',
                $tahun
            )

            ->where(
                'penggunaan_input_nip',
                $nip
            )

            ->where(
                'penggunaan_status',
                true
            )

            ->whereHas('standarHarga', function ($q) use ($jenis) {

                $q->where(
                    'standar_harga_jenis',
                    $jenis
                );

            })

            ->count();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'user.standar-harga.index',
            compact(
                'data',
                'tahun',
                'jenis',
                'search',
                'penggunaan',
                'totalMaster',
                'totalDigunakan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PENGGUNAAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'jenis' => [
                'required',
                'in:ASB,SSH',
            ],

            'standar_harga' => [
                'nullable',
                'array',
            ],

            'standar_harga.*' => [
                'integer',
                'exists:saplarin_standar_harga,standar_harga_id',
            ],

            /*
            |--------------------------------------------------------------------------
            | ID YANG SEDANG DITAMPILKAN DI HALAMAN
            |--------------------------------------------------------------------------
            |
            | Ini penting karena checkbox yang tidak dicentang tidak
            | dikirim oleh browser.
            |
            */

            'visible_standar_harga' => [
                'nullable',
                'array',
            ],

            'visible_standar_harga.*' => [
                'integer',
                'exists:saplarin_standar_harga,standar_harga_id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | DATA SESSION
        |--------------------------------------------------------------------------
        */

        $tahun = $request->tahun;

        $jenis = $request->jenis;

        $nip = session('pegawai_nip');

        $nama = session('pegawai_nama');

        $unit = session('pegawai_bidang');


        /*
        |--------------------------------------------------------------------------
        | CHECKBOX YANG DICENTANG
        |--------------------------------------------------------------------------
        */

        $dipilih = $request->input(
            'standar_harga',
            []
        );

        /*
        |--------------------------------------------------------------------------
        | ID YANG TAMPIL PADA HALAMAN SAAT INI
        |--------------------------------------------------------------------------
        */

        $visibleIds = $request->input(
            'visible_standar_harga',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI ID
        |--------------------------------------------------------------------------
        */

        $dipilih = array_map(
            'intval',
            $dipilih
        );

        $visibleIds = array_map(
            'intval',
            $visibleIds
        );


        /*
        |--------------------------------------------------------------------------
        | HANYA PROSES ID YANG ADA DI HALAMAN INI
        |--------------------------------------------------------------------------
        */

        $validVisibleIds = ModelStandarHarga::query()

            ->whereIn(
                'standar_harga_id',
            $visibleIds
            )

            ->where(
                'standar_harga_tahun',
                $tahun
            )

            ->where(
                'standar_harga_jenis',
                $jenis
            )

            ->where(
                'standar_harga_status',
                true
            )

            ->pluck(
                'standar_harga_id'
            )

            ->map(
                fn($id) => (int) $id
            )

            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | CHECKBOX YANG DICENTANG HARUS BERADA DI HALAMAN INI
        |--------------------------------------------------------------------------
        */

        $validDipilihIds = array_values(
            array_intersect(
                $dipilih,
                $validVisibleIds
            )
        );


        /*
        |--------------------------------------------------------------------------
        | SINKRONISASI CHECKLIST HALAMAN SAAT INI
        |--------------------------------------------------------------------------
        |
        | Yang dicentang:
        |     -> true
        |
        | Yang tidak dicentang:
        |     -> false
        |
        | Data halaman lain:
        |     -> tidak disentuh
        |
        */

        foreach ($validVisibleIds as $id) {

            $isChecked = in_array(
                $id,
                $validDipilihIds
            );


            /*
            |--------------------------------------------------------------------------
            | CARI DATA YANG SUDAH ADA
            |--------------------------------------------------------------------------
            */

            $penggunaan = ModelStandarHargaPenggunaan::query()

                ->where(
                    'penggunaan_standar_harga',
                    $id
                )

                ->where(
                    'penggunaan_tahun',
                    $tahun
                )

                ->where(
                    'penggunaan_input_nip',
                    $nip
                )

                ->first();


            /*
            |--------------------------------------------------------------------------
            | JIKA SUDAH ADA
            |--------------------------------------------------------------------------
            */

            if ($penggunaan) {

                $penggunaan->update([

                    'penggunaan_status' => $isChecked,

                    'penggunaan_input_nama' => $nama,

                    'penggunaan_unit' => $unit,

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | JIKA BELUM ADA
            |--------------------------------------------------------------------------
            |
            | Hanya buat record kalau checkbox memang dicentang.
            |
            */ elseif ($isChecked) {

                ModelStandarHargaPenggunaan::create([

                    'penggunaan_uid'
                        => (string) Str::uuid(),

                    'penggunaan_standar_harga'
                        => $id,

                    'penggunaan_tahun'
                        => $tahun,

                    'penggunaan_input_nip'
                        => $nip,

                    'penggunaan_input_nama'
                        => $nama,

                    'penggunaan_unit'
                        => $unit,

                    'penggunaan_status'
                        => true,

                ]);

            }
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        |
        | Kembalikan ke halaman yang sama.
        | Search dan pagination tetap dipertahankan.
        |
        */

        return redirect()

            ->route(
                'user.standar-harga.index',
                [
                    'tahun' => $tahun,
                    'jenis' => $jenis,
                'search' => $request->get('search'),
                'page' => $request->get('page'),
                ]
            )

            ->with(
                'success',
                'Data penggunaan ' . $jenis . ' berhasil disimpan.'
            );
    }
}