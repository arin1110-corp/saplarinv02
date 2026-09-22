<?php

namespace App\Http\Controllers;

use App\Models\ModelPermintaanKAK;

class AdminKAKController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     *
     * Menampilkan seluruh Permintaan KAK yang telah diupload
     * oleh seluruh pegawai/user.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | DATA PERMINTAAN KAK
        |--------------------------------------------------------------------------
        |
        | Relasi:
        |
        | Program
        |   └── Kegiatan
        |        └── Sub Kegiatan
        |             └── Permintaan KAK
        |
        | Di tabel KAK hanya menyimpan:
        |
        | kak_sub_kegiatan_id
        |
        | Program dan Kegiatan diperoleh melalui relasi.
        |
        */

        $kaks = ModelPermintaanKAK::query()

            ->join('saplarin_sub_kegiatan', 'saplarin_permintaan_kak.kak_sub_kegiatan_id', '=', 'saplarin_sub_kegiatan.sub_kegiatan_id')

            ->join('saplarin_kegiatan', 'saplarin_sub_kegiatan.sub_kegiatan_kegiatan', '=', 'saplarin_kegiatan.kegiatan_id')

            ->join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')

            ->select(
                /*
                |--------------------------------------------------------------------------
                | DATA KAK
                |--------------------------------------------------------------------------
                */

                'saplarin_permintaan_kak.*',

                /*
                |--------------------------------------------------------------------------
                | DATA SUB KEGIATAN
                |--------------------------------------------------------------------------
                */

                'saplarin_sub_kegiatan.sub_kegiatan_kode',

                'saplarin_sub_kegiatan.sub_kegiatan_nama',

                /*
                |--------------------------------------------------------------------------
                | DATA KEGIATAN
                |--------------------------------------------------------------------------
                */

                'saplarin_kegiatan.kegiatan_kode',

                'saplarin_kegiatan.kegiatan_nama',

                /*
                |--------------------------------------------------------------------------
                | DATA PROGRAM
                |--------------------------------------------------------------------------
                */

                'saplarin_program.program_kode',

                'saplarin_program.program_nama',
            )

            ->orderByDesc('saplarin_permintaan_kak.created_at')

            ->get();

        /*
        |--------------------------------------------------------------------------
        | VIEW ADMIN
        |--------------------------------------------------------------------------
        */

        return view('administrator-v2.permintaan-kak.index', compact('kaks'));
    }
}