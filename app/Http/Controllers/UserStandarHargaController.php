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

        ]);


        $tahun = $request->tahun;

        $jenis = $request->jenis;

        $nip = session('pegawai_nip');

        $nama = session('pegawai_nama');

        /*
        |--------------------------------------------------------------------------
        | UNIT OPERATOR
        |--------------------------------------------------------------------------
        |
        | Sesuaikan dengan session pegawai yang digunakan SAPLARIN.
        |
        */

        $unit = session('pegawai_bidang');


        /*
        |--------------------------------------------------------------------------
        | DATA CHECKBOX
        |--------------------------------------------------------------------------
        */

        $dipilih = $request->input(
            'standar_harga',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI AGAR TIDAK BISA MEMILIH STANDAR HARGA JENIS LAIN
        |--------------------------------------------------------------------------
        */

        $validIds = ModelStandarHarga::query()

            ->whereIn(
                'standar_harga_id',
                $dipilih
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

            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | HAPUS CHECKLIST OPERATOR SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        ModelStandarHargaPenggunaan::query()

            ->where(
                'penggunaan_tahun',
                $tahun
            )

            ->where(
                'penggunaan_input_nip',
                $nip
            )

            ->whereHas('standarHarga', function ($q) use ($jenis) {

                $q->where(
                    'standar_harga_jenis',
                    $jenis
                );

            })

            ->update([
                'penggunaan_status' => false,
            ]);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN CHECKLIST BARU
        |--------------------------------------------------------------------------
        */

        foreach ($validIds as $id) {

            /*
            | Cek apakah sebelumnya sudah pernah ada.
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


            if ($penggunaan) {

                $penggunaan->update([

                    'penggunaan_status' => true,

                    'penggunaan_input_nama' => $nama,

                    'penggunaan_unit' => $unit,

                ]);

            } else {

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


        return redirect()

            ->route(
                'user.standar-harga.index',
                [
                    'tahun' => $tahun,
                    'jenis' => $jenis,
                ]
            )

            ->with(
                'success',
                'Data penggunaan ' . $jenis . ' berhasil disimpan.'
            );
    }
}