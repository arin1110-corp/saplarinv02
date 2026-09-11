<?php

namespace App\Http\Controllers;

use App\Models\ModelProgram;
use App\Models\ModelKegiatan;
use App\Models\ModelSubKegiatan;
use App\Models\ModelPermintaanKAK;
use App\Services\ArinDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserKAKController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     *
     * Halaman utama upload KAK user.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | DATA PROGRAM
        |--------------------------------------------------------------------------
        */
        $programs = ModelProgram::where('program_status', 1)->orderBy('program_kode')->orderBy('program_nama')->get();

        /*
        |--------------------------------------------------------------------------
        | DATA KAK MILIK USER
        |--------------------------------------------------------------------------
        |
        | Relasi:
        | Program
        |   └── Kegiatan
        |        └── Sub Kegiatan
        |             └── KAK
        |
        | Di database KAK hanya menyimpan sub kegiatan.
        |
        */
        $kaks = ModelPermintaanKAK::query()
            ->join('saplarin_sub_kegiatan', 'saplarin_permintaan_kak.kak_sub_kegiatan_id', '=', 'saplarin_sub_kegiatan.sub_kegiatan_id')
            ->join('saplarin_kegiatan', 'saplarin_sub_kegiatan.sub_kegiatan_kegiatan', '=', 'saplarin_kegiatan.kegiatan_id')
            ->join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')
            ->where('saplarin_permintaan_kak.kak_created_by', session('pegawai_id'))
            ->where('saplarin_permintaan_kak.kak_status', 1)
            ->select(
                'saplarin_permintaan_kak.*',

                'saplarin_sub_kegiatan.sub_kegiatan_kode',
                'saplarin_sub_kegiatan.sub_kegiatan_nama',

                'saplarin_kegiatan.kegiatan_kode',
                'saplarin_kegiatan.kegiatan_nama',

                'saplarin_program.program_kode',
                'saplarin_program.program_nama',
            )
            ->orderByDesc('saplarin_permintaan_kak.created_at')
            ->get();

        return view('user.kak.index', compact('programs', 'kaks'));
    }

    /**
     * ============================================================
     * CREATE
     * ============================================================
     *
     * Disediakan jika route menggunakan:
     * Route::get('/user/kak/create', ...);
     */
    public function create()
    {
        return $this->index();
    }

    /**
     * ============================================================
     * GET KEGIATAN
     * ============================================================
     *
     * AJAX:
     * Program -> Kegiatan
     *
     * Request:
     * program_id
     */
    public function kegiatan(Request $request)
    {
        $request->validate([
            'program_id' => ['required', 'integer'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan program aktif
        |--------------------------------------------------------------------------
        */
        $program = ModelProgram::where('program_id', $request->program_id)->where('program_status', 1)->first();

        if (!$program) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Program tidak ditemukan.',
                    'data' => [],
                ],
                404,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil kegiatan milik program
        |--------------------------------------------------------------------------
        */
        $kegiatans = ModelKegiatan::where('kegiatan_program', $program->program_id)
            ->where('kegiatan_status', 1)
            ->orderBy('kegiatan_kode')
            ->orderBy('kegiatan_nama')
            ->get(['kegiatan_id', 'kegiatan_uid', 'kegiatan_kode', 'kegiatan_nama']);

        return response()->json([
            'success' => true,
            'message' => 'Data kegiatan berhasil diambil.',
            'data' => $kegiatans,
        ]);
    }

    /**
     * ============================================================
     * GET SUB KEGIATAN
     * ============================================================
     *
     * AJAX:
     * Kegiatan -> Sub Kegiatan
     *
     * Request:
     * kegiatan_id
     */
    public function subKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan_id' => ['required', 'integer'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan kegiatan aktif
        |--------------------------------------------------------------------------
        */
        $kegiatan = ModelKegiatan::where('kegiatan_id', $request->kegiatan_id)->where('kegiatan_status', 1)->first();

        if (!$kegiatan) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Kegiatan tidak ditemukan.',
                    'data' => [],
                ],
                404,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil sub kegiatan
        |--------------------------------------------------------------------------
        */
        $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_kegiatan', $kegiatan->kegiatan_id)
            ->where('sub_kegiatan_status', 1)
            ->orderBy('sub_kegiatan_kode')
            ->orderBy('sub_kegiatan_nama')
            ->get(['sub_kegiatan_id', 'sub_kegiatan_uid', 'sub_kegiatan_kode', 'sub_kegiatan_kode_rekening', 'sub_kegiatan_nama']);

        return response()->json([
            'success' => true,
            'message' => 'Data sub kegiatan berhasil diambil.',
            'data' => $subKegiatans,
        ]);
    }

    /**
     * ============================================================
     * STORE
     * ============================================================
     *
     * Upload KAK oleh user.
     */
    public function store(Request $request, ArinDriveService $arinDrive)
    {
        /*
    |--------------------------------------------------------------------------
    | VALIDASI REQUEST
    |--------------------------------------------------------------------------
    */
        $request->validate([
            /*
        |----------------------------------------------------------------------
        | SUB KEGIATAN
        |----------------------------------------------------------------------
        */
            'kak_sub_kegiatan_id' => ['required', 'integer'],

            /*
        |----------------------------------------------------------------------
        | TAHUN
        |----------------------------------------------------------------------
        */
            'kak_tahun' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2100'],

            /*
        |----------------------------------------------------------------------
        | TAHAPAN KAK
        |----------------------------------------------------------------------
        |
        | Contoh:
        | - Induk
        | - Perubahan
        | - Pergeseran
        |
        */
            'kak_tahapan' => ['required', 'string', 'max:100'],

            /*
        |----------------------------------------------------------------------
        | FILE KAK
        |----------------------------------------------------------------------
        |
        | PDF maksimal 200 MB.
        |
        */
            'kak_file' => ['required', 'file', 'mimes:pdf', 'max:204800'],
        ]);

        /*
    |--------------------------------------------------------------------------
    | AMBIL SUB KEGIATAN
    |--------------------------------------------------------------------------
    |
    | Program dan Kegiatan tidak disimpan di tabel KAK.
    |
    | Program dan Kegiatan hanya digunakan di form untuk membantu user
    | memilih Sub Kegiatan.
    |
    | Setelah user memilih Sub Kegiatan, yang dikirim ke server hanya:
    |
    | kak_sub_kegiatan_id
    |
    */
        $subKegiatan = ModelSubKegiatan::where('sub_kegiatan_id', $request->kak_sub_kegiatan_id)->where('sub_kegiatan_status', 1)->first();

        /*
    |--------------------------------------------------------------------------
    | CEK SUB KEGIATAN
    |--------------------------------------------------------------------------
    */
        if (!$subKegiatan) {
            return back()->withInput()->with('error', 'Sub Kegiatan tidak ditemukan atau sudah tidak aktif.');
        }

        /*
    |--------------------------------------------------------------------------
    | NORMALISASI TAHAPAN
    |--------------------------------------------------------------------------
    */
        $tahapan = trim($request->kak_tahapan);

        /*
    |--------------------------------------------------------------------------
    | CEK DUPLIKAT
    |--------------------------------------------------------------------------
    |
    | Satu Sub Kegiatan + Tahun + Tahapan
    | hanya boleh mempunyai satu KAK aktif.
    |
    */
        $sudahAda = ModelPermintaanKAK::where('kak_sub_kegiatan_id', $subKegiatan->sub_kegiatan_id)->where('kak_tahun', $request->kak_tahun)->where('kak_tahapan', $tahapan)->where('kak_status', 1)->exists();

        /*
    |--------------------------------------------------------------------------
    | JIKA SUDAH ADA
    |--------------------------------------------------------------------------
    */
        if ($sudahAda) {
            return back()->withInput()->with('error', 'KAK untuk Sub Kegiatan, tahun, dan tahapan tersebut sudah tersedia.');
        }

        /*
    |--------------------------------------------------------------------------
    | GENERATE UID KAK
    |--------------------------------------------------------------------------
    */
        $kakUid = (string) Str::uuid();

        /*
    |--------------------------------------------------------------------------
    | AMBIL FILE
    |--------------------------------------------------------------------------
    */
        $file = $request->file('kak_file');

        /*
    |--------------------------------------------------------------------------
    | EXTENSION FILE
    |--------------------------------------------------------------------------
    */
        $extension = strtolower($file->getClientOriginalExtension());

        /*
    |--------------------------------------------------------------------------
    | BERSIHKAN TAHAPAN UNTUK NAMA FILE
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | "Perubahan APBD" menjadi:
    |
    | Perubahan_APBD
    |
    */
        $tahapanFile = preg_replace('/[^A-Za-z0-9_-]/', '_', $tahapan);

        /*
    |--------------------------------------------------------------------------
    | BERSIHKAN KODE SUB KEGIATAN
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | 5.02.01.01.0001
    |
    | karakter titik tetap dipertahankan.
    |
    */
        $subKodeFile = preg_replace('/[^A-Za-z0-9_.-]/', '_', $subKegiatan->sub_kegiatan_kode ?: $subKegiatan->sub_kegiatan_id);

        /*
    |--------------------------------------------------------------------------
    | NAMA FILE ARINDRIVE
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | KAK_2026_5.02.01.01.0001_PERUBAHAN_UUID.pdf
    |
    */
        $filename = 'KAK_' . $request->kak_tahun . '_' . $subKodeFile . '_' . $tahapanFile . '_' . $kakUid . '.' . $extension;

        /*
    |--------------------------------------------------------------------------
    | FOLDER ARINDRIVE
    |--------------------------------------------------------------------------
    |
    | SESUAIKAN DENGAN STRUKTUR ARINDRIVE ANDA.
    |
    | Untuk sementara saya pertahankan folder yang Anda gunakan
    | sebelumnya agar tidak mengubah struktur penyimpanan yang sudah ada.
    |
    */
        $folder = 'kak_2026_perubahan';

        /*
    |--------------------------------------------------------------------------
    | UPLOAD KE ARINDRIVE
    |--------------------------------------------------------------------------
    */
        try {
            $kakFile = $arinDrive->upload($file, $folder, $filename, $kakUid);
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Upload KAK ke ArinDrive gagal: ' . $e->getMessage());
        }

        /*
    |--------------------------------------------------------------------------
    | USER LOGIN
    |--------------------------------------------------------------------------
    */
        $createdBy = session('pegawai_id');

        $createdByNama = session('pegawai_nama');

        /*
    |--------------------------------------------------------------------------
    | SIMPAN DATABASE
    |--------------------------------------------------------------------------
    |
    | PERHATIKAN:
    |
    | Tidak ada:
    |
    | - program_id
    | - kegiatan_id
    |
    | Karena hubungan sudah diturunkan melalui:
    |
    | KAK
    |   ↓
    | Sub Kegiatan
    |   ↓
    | Kegiatan
    |   ↓
    | Program
    |
    */
        try {
            DB::beginTransaction();

            $kak = ModelPermintaanKAK::create([
                /*
            |------------------------------------------------------------------
            | UID
            |------------------------------------------------------------------
            */
                'kak_uid' => $kakUid,

                /*
            |------------------------------------------------------------------
            | SUB KEGIATAN
            |------------------------------------------------------------------
            */
                'kak_sub_kegiatan_id' => $subKegiatan->sub_kegiatan_id,

                /*
            |------------------------------------------------------------------
            | TAHUN
            |------------------------------------------------------------------
            */
                'kak_tahun' => $request->kak_tahun,

                /*
            |------------------------------------------------------------------
            | KETERANGAN
            |------------------------------------------------------------------
            */
                'kak_keterangan' => 'KAK',

                /*
            |------------------------------------------------------------------
            | TAHAPAN
            |------------------------------------------------------------------
            */
                'kak_tahapan' => $tahapan,

                /*
            |------------------------------------------------------------------
            | FILE ARINDRIVE
            |------------------------------------------------------------------
            */
                'kak_file' => $kakFile,

                /*
            |------------------------------------------------------------------
            | STATUS
            |------------------------------------------------------------------
            */
                'kak_status' => 1,

                /*
            |------------------------------------------------------------------
            | CREATED BY
            |------------------------------------------------------------------
            */
                'kak_created_by' => $createdBy,

                'kak_created_by_nama' => $createdByNama,

                /*
            |------------------------------------------------------------------
            | TIMESTAMP
            |------------------------------------------------------------------
            */
                'created_at' => now(),

                'updated_at' => now(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */
            DB::commit();
        } catch (Throwable $e) {
            /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */
            DB::rollBack();

            /*
        |--------------------------------------------------------------------------
        | CATATAN
        |--------------------------------------------------------------------------
        |
        | File sudah terupload ke ArinDrive.
        |
        | Karena method delete ArinDrive belum dipastikan tersedia,
        | file tidak dihapus otomatis di sini.
        |
        */
            return back()
                ->withInput()
                ->with('error', 'File berhasil diupload tetapi gagal menyimpan data KAK: ' . $e->getMessage());
        }

        /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */
        return redirect()->route('user.permintaan-kak.index')->with('success', 'KAK berhasil diupload.');
    }
}