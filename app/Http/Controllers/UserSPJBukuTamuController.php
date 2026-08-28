<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJRealisasi;
use App\Models\ModelSPJBukuTamu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserSPJBukuTamuController extends Controller
{
    /**
     * Simpan buku tamu kemudian buka file SPJ.
     */
    public function store(Request $request, $uid)
    {
        $request->validate(
            [
                'buku_tamu_nama' => ['required', 'string', 'max:150'],

                'buku_tamu_nip' => ['nullable', 'string', 'max:50'],

                'buku_tamu_unit' => ['nullable', 'string', 'max:200'],

                'buku_tamu_tujuan' => ['required', 'string', 'max:255'],
            ],
            [
                'buku_tamu_nama.required' => 'Nama wajib diisi.',

                'buku_tamu_tujuan.required' => 'Tujuan kunjungan wajib dipilih.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | CARI DATA SPJ
        |--------------------------------------------------------------------------
        */

        $spj = ModelSPJRealisasi::where('spj_uid', $uid)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CEK FILE
        |--------------------------------------------------------------------------
        */

        if (!$spj->spj_file) {
            return back()->with('error', 'File SPJ belum tersedia.');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN BUKU TAMU
        |--------------------------------------------------------------------------
        */

        ModelSPJBukuTamu::create([
            'buku_tamu_uid' => (string) Str::uuid(),

            'spj_uid' => $spj->spj_uid,

            'buku_tamu_nama' => $request->buku_tamu_nama,

            'buku_tamu_nip' => $request->buku_tamu_nip,

            'buku_tamu_unit' => $request->buku_tamu_unit,

            'buku_tamu_tujuan' => $request->buku_tamu_tujuan,

            'buku_tamu_file' => $spj->spj_file,

            'buku_tamu_waktu' => now(),

            'buku_tamu_ip' => $request->ip(),

            'buku_tamu_user_agent' => $request->userAgent(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | BUKA GOOGLE DRIVE
        |--------------------------------------------------------------------------
        |
        | Jangan pakai asset().
        | URL yang tersimpan di spj_file langsung dibuka.
        |
        */

        return redirect()->away($spj->spj_file);
    }
}