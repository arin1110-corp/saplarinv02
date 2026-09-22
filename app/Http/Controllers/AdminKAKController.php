<?php

namespace App\Http\Controllers;

use App\Models\ModelPermintaanKAK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\KAKEmailService;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminKAKController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     *
     * Menampilkan seluruh Permintaan KAK.
     */
    public function index(Request $request)
    {
        $query = ModelPermintaanKAK::query()

            ->join('saplarin_sub_kegiatan', 'saplarin_permintaan_kak.kak_sub_kegiatan_id', '=', 'saplarin_sub_kegiatan.sub_kegiatan_id')

            ->join('saplarin_kegiatan', 'saplarin_sub_kegiatan.sub_kegiatan_kegiatan', '=', 'saplarin_kegiatan.kegiatan_id')

            ->join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')

            ->select(
                'saplarin_permintaan_kak.*',

                'saplarin_sub_kegiatan.sub_kegiatan_kode',
                'saplarin_sub_kegiatan.sub_kegiatan_nama',

                'saplarin_kegiatan.kegiatan_kode',
                'saplarin_kegiatan.kegiatan_nama',

                'saplarin_program.program_kode',
                'saplarin_program.program_nama',
            );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('saplarin_permintaan_kak.kak_unit', 'like', "%{$search}%")

                    ->orWhere('saplarin_permintaan_kak.kak_created_by_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_permintaan_kak.kak_tahapan', 'like', "%{$search}%")

                    ->orWhere('saplarin_sub_kegiatan.sub_kegiatan_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_sub_kegiatan.sub_kegiatan_kode', 'like', "%{$search}%")

                    ->orWhere('saplarin_kegiatan.kegiatan_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_program.program_nama', 'like', "%{$search}%")

                    ->orWhere('saplarin_permintaan_kak.kak_tahun', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $kaks = $query

            ->orderByDesc('saplarin_permintaan_kak.created_at')

            ->paginate(10)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('administrator-v2.permintaan-kak.index', compact('kaks'));
    }

    /**
     * ============================================================
     * TOGGLE STATUS
     * ============================================================
     *
     * Mengaktifkan / menonaktifkan KAK.
     */
    public function toggleStatus($id)
    {
        $kak = ModelPermintaanKAK::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | TOGGLE
        |--------------------------------------------------------------------------
        */

        $kak->kak_status = (int) $kak->kak_status === 1 ? 0 : 1;

        $kak->updated_at = now();

        $kak->save();

        /*
        |--------------------------------------------------------------------------
        | PESAN
        |--------------------------------------------------------------------------
        */

        $message = (int) $kak->kak_status === 1 ? 'Permintaan KAK berhasil diaktifkan.' : 'Permintaan KAK berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }

    /**
     * ============================================================
     * CATATAN ADMIN
     * ============================================================
     *
     * Menyimpan catatan admin dan mengirimkan email
     * kepada user yang mengajukan KAK.
     *
     * kak_created_by = user_id SAMPERIN
     */
    public function catatan(Request $request, $id, KAKEmailService $emailService)
    {
        $request->validate([
            'kak_catatan_admin' => ['nullable', 'string', 'max:5000'],
        ]);

        $kak = ModelPermintaanKAK::findOrFail($id);

        /*
    |--------------------------------------------------------------------------
    | SIMPAN CATATAN
    |--------------------------------------------------------------------------
    */

        $kak->kak_catatan_admin = $request->kak_catatan_admin;
        $kak->updated_at = now();
        $kak->save();

        /*
    |--------------------------------------------------------------------------
    | KIRIM EMAIL KE USER SAMPERIN
    |--------------------------------------------------------------------------
    |
    | KAK tidak menyimpan email.
    | Email dicari berdasarkan:
    |
    | kak_created_by
    |       ↓
    | samperin_user.user_id
    |       ↓
    | samperin_user.user_email
    |
    */

        try {
            $emailService->kirimKePengaju($kak);
        } catch (Throwable $e) {
            return back()->with('error', 'Catatan berhasil disimpan, tetapi email gagal dikirim: ' . $e->getMessage());
        }

        return back()->with('success', 'Catatan admin berhasil disimpan dan dikirim ke email pengaju.');
    }
}