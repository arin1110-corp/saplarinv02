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
            'kak_program_id' => ['required', 'integer'],

            'kak_kegiatan_id' => ['required', 'integer'],

            'kak_sub_kegiatan_id' => ['required', 'integer'],

            'kak_tahun' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2100'],

            /*
            |--------------------------------------------------------------------------
            | JENIS / TAHAPAN KAK
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | - Induk
            | - Perubahan
            | - Pergeseran
            |
            */
            'kak_tahapan' => ['required', 'string', 'max:100'],

            /*
            |--------------------------------------------------------------------------
            | FILE KAK
            |--------------------------------------------------------------------------
            |
            | Fokus KAK PDF.
            | Maksimal 200 MB.
            |
            */
            'kak_file' => ['required', 'file', 'mimes:pdf', 'max:204800'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK PROGRAM
        |--------------------------------------------------------------------------
        */
        $program = ModelProgram::where('program_id', $request->kak_program_id)->where('program_status', 1)->first();

        if (!$program) {
            return back()->withInput()->with('error', 'Program tidak ditemukan atau sudah tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK KEGIATAN
        |--------------------------------------------------------------------------
        |
        | Penting:
        | kegiatan harus benar-benar milik program yang dipilih.
        |
        */
        $kegiatan = ModelKegiatan::where('kegiatan_id', $request->kak_kegiatan_id)->where('kegiatan_program', $program->program_id)->where('kegiatan_status', 1)->first();

        if (!$kegiatan) {
            return back()->withInput()->with('error', 'Kegiatan tidak sesuai dengan program yang dipilih.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK SUB KEGIATAN
        |--------------------------------------------------------------------------
        |
        | Penting:
        | sub kegiatan harus benar-benar milik kegiatan yang dipilih.
        |
        */
        $subKegiatan = ModelSubKegiatan::where('sub_kegiatan_id', $request->kak_sub_kegiatan_id)->where('sub_kegiatan_kegiatan', $kegiatan->kegiatan_id)->where('sub_kegiatan_status', 1)->first();

        if (!$subKegiatan) {
            return back()->withInput()->with('error', 'Sub Kegiatan tidak sesuai dengan Kegiatan yang dipilih.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        |
        | Satu Sub Kegiatan + Tahun + Tahapan
        | tidak boleh memiliki dua KAK aktif.
        |
        */
        $sudahAda = ModelPermintaanKAK::where('kak_sub_kegiatan_id', $subKegiatan->sub_kegiatan_id)->where('kak_tahun', $request->kak_tahun)->where('kak_tahapan', $request->kak_tahapan)->where('kak_status', 1)->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'KAK untuk Sub Kegiatan, tahun, dan tahapan tersebut sudah tersedia.');
        }

        /*
        |--------------------------------------------------------------------------
        | UID KAK
        |--------------------------------------------------------------------------
        */
        $kakUid = (string) Str::uuid();

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */
        $file = $request->file('kak_file');

        $extension = strtolower($file->getClientOriginalExtension());

        /*
        |--------------------------------------------------------------------------
        | BERSIHKAN TAHAPAN UNTUK NAMA FILE
        |--------------------------------------------------------------------------
        */
        $tahapanFile = preg_replace('/[^A-Za-z0-9_-]/', '_', $request->kak_tahapan);

        /*
        |--------------------------------------------------------------------------
        | BERSIHKAN KODE SUB KEGIATAN
        |--------------------------------------------------------------------------
        */
        $subKodeFile = preg_replace('/[^A-Za-z0-9_-]/', '_', $subKegiatan->sub_kegiatan_kode ?: $subKegiatan->sub_kegiatan_id);

        /*
        |--------------------------------------------------------------------------
        | NAMA FILE ARINDRIVE
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | KAK_2026_5.02.01.01.0001_PERUBAHAN_<UID>.pdf
        |
        */
        $filename = 'KAK_' . $request->kak_tahun . '_' . $subKodeFile . '_' . $tahapanFile . '_' . $kakUid . '.' . $extension;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD KE ARINDRIVE
        |--------------------------------------------------------------------------
        |
        | Folder:
        | kak
        |
        | Reference:
        | kakUid
        |
        */
        try {
            $kakFile = $arinDrive->upload($file, 'kak_2026_perubahan', $filename, $kakUid);
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
        | TIDAK menyimpan:
        | - program_id
        | - kegiatan_id
        |
        | Karena Sub Kegiatan sudah terhubung:
        |
        | Sub Kegiatan
        |      ↓
        | Kegiatan
        |      ↓
        | Program
        |
        */
        try {
            DB::beginTransaction();

            $kak = ModelPermintaanKAK::create([
                'kak_uid' => $kakUid,

                'kak_sub_kegiatan_id' => $subKegiatan->sub_kegiatan_id,

                'kak_tahun' => $request->kak_tahun,

                /*
                |--------------------------------------------------------------------------
                | Keterangan
                |--------------------------------------------------------------------------
                |
                | Karena jenis KAK disimpan pada kak_tahapan,
                | keterangan kita isi sebagai identitas dokumen.
                |
                */
                'kak_keterangan' => 'KAK',

                /*
                |--------------------------------------------------------------------------
                | JENIS KAK
                |--------------------------------------------------------------------------
                */
                'kak_tahapan' => trim($request->kak_tahapan),

                /*
                |--------------------------------------------------------------------------
                | FILE ARINDRIVE
                |--------------------------------------------------------------------------
                */
                'kak_file' => $kakFile,

                /*
                |--------------------------------------------------------------------------
                | STATUS AKTIF
                |--------------------------------------------------------------------------
                */
                'kak_status' => 1,

                /*
                |--------------------------------------------------------------------------
                | CREATED BY
                |--------------------------------------------------------------------------
                */
                'kak_created_by' => $createdBy,

                'kak_created_by_nama' => $createdByNama,

                'created_at' => now(),

                'updated_at' => now(),
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            /*
            |--------------------------------------------------------------------------
            | CATATAN
            |--------------------------------------------------------------------------
            |
            | File ArinDrive sudah terupload.
            | Untuk sekarang kita tidak menghapus file Drive secara
            | otomatis karena method delete ArinDrive belum kita
            | pastikan dari service.
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
        return redirect()->route('user.kak.index')->with('success', 'KAK berhasil diupload.');
    }
}