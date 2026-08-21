<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\StandarHargaImport;
use App\Models\ModelStandarHarga;
use App\Models\ModelStandarHargaPenggunaan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminStandarHargaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
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
            ''
        );

        $search = trim(
            $request->get('search', '')
        );

        $standarHarga = ModelStandarHarga::query()

            ->where(
                'standar_harga_tahun',
                $tahun
            )

            ->when(
                $jenis,
                function ($query) use ($jenis) {

                    $query->where(
                        'standar_harga_jenis',
                        $jenis
                    );

                }
            )

            ->when(
                $search,
                function ($query) use ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->where(
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
                            'standar_harga_spesifikasi',
                            'like',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'standar_harga_kode_rekening',
                            'like',
                            '%' . $search . '%'
                        );

                    });

                }
            )

            ->orderBy(
                'standar_harga_jenis'
            )

            ->orderBy(
                'standar_harga_id'
            )

            ->orderBy(
                'standar_harga_id'
            )

            ->paginate(25)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | REKAP
        |--------------------------------------------------------------------------
        */

        $totalSSH = ModelStandarHarga::where(
            'standar_harga_tahun',
            $tahun
        )
            ->where(
                'standar_harga_jenis',
                'SSH'
            )
            ->count();


        $totalASB = ModelStandarHarga::where(
            'standar_harga_tahun',
            $tahun
        )
            ->where(
                'standar_harga_jenis',
                'ASB'
            )
            ->count();


        $aktif = ModelStandarHarga::where(
            'standar_harga_tahun',
            $tahun
        )
            ->where(
                'standar_harga_status',
                true
            )
            ->count();


        $nonaktif = ModelStandarHarga::where(
            'standar_harga_tahun',
            $tahun
        )
            ->where(
                'standar_harga_status',
                false
            )
            ->count();


        return view(
            'administrator-v2.standar-harga.index',
            compact(
                'standarHarga',
                'tahun',
                'jenis',
                'search',
                'totalSSH',
                'totalASB',
                'aktif',
                'nonaktif'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORT FORM
    |--------------------------------------------------------------------------
    */

    public function importForm()
    {
        return view(
            'administrator-v2.standar-harga.import'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES IMPORT
    |--------------------------------------------------------------------------
    */

    public function importStore(
        Request $request
    ) {

        $request->validate([

            'standar_harga_tahun'
                => 'required|integer|min:2000|max:2100',

            'standar_harga_jenis'
                => 'required|in:SSH,ASB',

            'file'
                => 'required|file|mimes:xlsx,xls|max:20480',

        ], [

            'file.mimes'
                => 'File harus berupa Excel (.xlsx / .xls).',

            'file.max'
                => 'Ukuran file maksimal 20 MB.',

        ]);


        Excel::import(

            new StandarHargaImport(

                $request->standar_harga_tahun,

                $request->standar_harga_jenis

            ),

            $request->file('file')

        );


        return redirect()

            ->route(
                'admin.standar-harga.index'
            )

            ->with(
                'success',
                'Data ' .
                    $request->standar_harga_jenis .
                    ' tahun ' .
                    $request->standar_harga_tahun .
                    ' berhasil diimport.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function status($id)
    {
        $data = ModelStandarHarga::findOrFail(
            $id
        );

        $data->standar_harga_status =
            !$data->standar_harga_status;

        $data->save();


        return back()->with(
            'success',
            'Status data berhasil diperbarui.'
        );
    }
    /*
    |--------------------------------------------------------------------------
    | DAFTAR PERMINTAAN
    |--------------------------------------------------------------------------
    |
    | Yang ditampilkan hanya standar harga yang sudah
    | dipilih/digunakan oleh operator.
    |
    */

    public function permintaan(Request $request)
    {
        $tahun = $request->get(
            'tahun',
            now()->year
        );

        $jenis = strtoupper(
            $request->get('jenis', 'SSH')
        );

        $search = trim(
            $request->get('search', '')
        );


        /*
        |--------------------------------------------------------------------------
        | DATA YANG DIGUNAKAN
        |--------------------------------------------------------------------------
        */

        $permintaan = ModelStandarHargaPenggunaan::with([
            'standarHarga'
        ])

            ->where(
                'penggunaan_tahun',
                $tahun
            )

            ->whereHas('standarHarga', function ($query) use ($jenis) {

                $query->where(
                    'standar_harga_jenis',
                    $jenis
                );

                $query->where(
                    'standar_harga_status',
                    true
                );

            })


            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            ->when($search, function ($query) use ($search) {

                $query->whereHas(
                    'standarHarga',
                    function ($q) use ($search) {

                        $q->where(function ($x) use ($search) {

                            $x->where(
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
                                'standar_harga_spesifikasi',
                                'like',
                                '%' . $search . '%'
                            )

                            ->orWhere(
                                'standar_harga_kode_rekening',
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
                            );

                        });

                    }
                );

            })


            /*
            |--------------------------------------------------------------------------
            | TERBARU
            |--------------------------------------------------------------------------
            */

            ->orderByDesc(
                'penggunaan_id'
            )

            ->paginate(20)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TOTAL DIGUNAKAN
        |--------------------------------------------------------------------------
        */

        $totalDigunakan =
            ModelStandarHargaPenggunaan::where(
                'penggunaan_tahun',
                $tahun
            )

            ->whereHas('standarHarga', function ($query) use ($jenis) {

                $query->where(
                    'standar_harga_jenis',
                    $jenis
                );

                $query->where(
                    'standar_harga_status',
                    true
                );

            })

            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL NOMINAL
        |--------------------------------------------------------------------------
        */

        $totalNominal =
            ModelStandarHargaPenggunaan::where(
                'penggunaan_tahun',
                $tahun
            )

            ->whereHas('standarHarga', function ($query) use ($jenis) {

                $query->where(
                    'standar_harga_jenis',
                    $jenis
                );

                $query->where(
                    'standar_harga_status',
                    true
                );

            })

            ->join(
                'saplarin_standar_harga',
                'saplarin_standar_harga.standar_harga_id',
                '=',
                'saplarin_standar_harga_penggunaan.penggunaan_standar_harga'
            )

            ->sum(
                'saplarin_standar_harga.standar_harga_satuan_harga'
            );


        return view(
            'administrator-v2.standar-harga.permintaan',
            compact(
                'permintaan',
                'tahun',
                'jenis',
                'search',
                'totalDigunakan',
                'totalNominal'
            )
        );
    }
}