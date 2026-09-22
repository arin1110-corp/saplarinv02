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
     * Daftar unit yang tersedia.
     */
    private function unitList(): array
    {
        return ['Sekretariat', 'Bidang Kesenian', 'Bidang Tradisi dan Warisan Budaya', 'Bidang Sejarah dan Dokumentasi Kebudayaan', 'Bidang Cagar Budaya dan Permuseuman', 'UPTD Taman Budaya', 'UPTD Monumen Perjuangan Rakyat Bali', 'UPTD Museum Bali'];
    }

    /**
     * INDEX
     */
    public function index()
    {
        $programs = ModelProgram::where('program_status', 1)
            ->with([
                'kegiatan' => function ($query) {
                    $query->where('kegiatan_status', 1)->orderBy('kegiatan_kode')->orderBy('kegiatan_nama');
                },
                'kegiatan.subKegiatan' => function ($query) {
                    $query->where('sub_kegiatan_status', 1)->orderBy('sub_kegiatan_kode')->orderBy('sub_kegiatan_nama');
                },
            ])
            ->orderBy('program_kode')
            ->orderBy('program_nama')
            ->get();

        $kaks = ModelPermintaanKAK::query()->with('subKegiatan')->where('kak_created_by', session('pegawai_id'))->orderByDesc('created_at')->get();

        $unitList = $this->unitList();

        return view('user.kak.index', compact('programs', 'kaks', 'unitList'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        return $this->index();
    }

    /**
     * GET KEGIATAN
     */
    public function kegiatan(Request $request)
    {
        $request->validate([
            'program_id' => ['required', 'integer'],
        ]);

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

        $kegiatans = ModelKegiatan::where('kegiatan_program', $program->program_id)
            ->where('kegiatan_status', 1)
            ->orderBy('kegiatan_kode')
            ->orderBy('kegiatan_nama')
            ->get(['kegiatan_id', 'kegiatan_uid', 'kegiatan_kode', 'kegiatan_nama']);

        return response()->json([
            'success' => true,
            'data' => $kegiatans,
        ]);
    }

    /**
     * GET SUB KEGIATAN
     */
    public function subKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan_id' => ['required', 'integer'],
        ]);

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

        $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_kegiatan', $kegiatan->kegiatan_id)
            ->where('sub_kegiatan_status', 1)
            ->orderBy('sub_kegiatan_kode')
            ->orderBy('sub_kegiatan_nama')
            ->get(['sub_kegiatan_id', 'sub_kegiatan_uid', 'sub_kegiatan_kode', 'sub_kegiatan_kode_rekening', 'sub_kegiatan_nama']);

        return response()->json([
            'success' => true,
            'data' => $subKegiatans,
        ]);
    }

    /**
     * STORE
     */
    public function store(Request $request, ArinDriveService $arinDrive)
    {
        $request->validate([
            'kak_sub_kegiatan_id' => ['required', 'integer'],
            'kak_tahun' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2100'],
            'kak_tahapan' => ['required', 'string', 'max:100'],
            'kak_unit' => ['required', 'string', 'max:255', 'in:' . implode(',', $this->unitList())],
            'kak_file' => ['required', 'file', 'mimes:pdf', 'max:204800'],
        ]);

        $subKegiatan = ModelSubKegiatan::where('sub_kegiatan_id', $request->kak_sub_kegiatan_id)->where('sub_kegiatan_status', 1)->first();

        if (!$subKegiatan) {
            return back()->withInput()->with('error', 'Sub Kegiatan tidak ditemukan atau sudah tidak aktif.');
        }

        $tahapan = trim($request->kak_tahapan);

        $sudahAda = ModelPermintaanKAK::where('kak_sub_kegiatan_id', $subKegiatan->sub_kegiatan_id)->where('kak_tahun', $request->kak_tahun)->where('kak_tahapan', $tahapan)->where('kak_status', 1)->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'KAK untuk Sub Kegiatan, tahun, dan tahapan tersebut sudah tersedia.');
        }

        $kakUid = (string) Str::uuid();

        $file = $request->file('kak_file');

        $extension = strtolower($file->getClientOriginalExtension());

        $tahapanFile = preg_replace('/[^A-Za-z0-9_-]/', '_', $tahapan);

        $subKodeFile = preg_replace('/[^A-Za-z0-9_.-]/', '_', $subKegiatan->sub_kegiatan_kode ?: $subKegiatan->sub_kegiatan_id);

        $filename = 'KAK_' . $request->kak_tahun . '_' . $subKodeFile . '_' . $tahapanFile . '_' . $kakUid . '.' . $extension;

        $folder = 'kak_2026_perubahan';

        try {
            $kakFile = $arinDrive->upload($file, $folder, $filename, $kakUid);
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Upload KAK ke ArinDrive gagal: ' . $e->getMessage());
        }

        try {
            DB::beginTransaction();

            ModelPermintaanKAK::create([
                'kak_uid' => $kakUid,

                'kak_sub_kegiatan_id' => $subKegiatan->sub_kegiatan_id,

                'kak_tahun' => $request->kak_tahun,

                'kak_keterangan' => 'KAK',

                'kak_tahapan' => $tahapan,

                'kak_unit' => $request->kak_unit,

                'kak_file' => $kakFile,

                'kak_status' => 1,

                'kak_created_by' => session('pegawai_id'),

                'kak_created_by_nama' => session('pegawai_nama'),

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data KAK: ' . $e->getMessage());
        }

        return redirect()->route('user.permintaan-kak.index')->with('success', 'KAK berhasil diupload.');
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $uid, ArinDriveService $arinDrive)
    {
        $request->validate([
            'kak_sub_kegiatan_id' => ['required', 'integer'],

            'kak_tahun' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2100'],

            'kak_tahapan' => ['required', 'string', 'max:100'],

            'kak_unit' => ['required', 'string', 'max:255', 'in:' . implode(',', $this->unitList())],

            'kak_file' => ['nullable', 'file', 'mimes:pdf', 'max:204800'],
        ]);

        $kak = ModelPermintaanKAK::where('kak_uid', $uid)->where('kak_created_by', session('pegawai_id'))->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | KAK NONAKTIF
        |--------------------------------------------------------------------------
        */
        if ((int) $kak->kak_status !== 1) {
            return back()->with('error', 'KAK yang sudah dinonaktifkan tidak dapat diedit.');
        }

        $subKegiatan = ModelSubKegiatan::where('sub_kegiatan_id', $request->kak_sub_kegiatan_id)->where('sub_kegiatan_status', 1)->first();

        if (!$subKegiatan) {
            return back()->withInput()->with('error', 'Sub Kegiatan tidak ditemukan atau sudah tidak aktif.');
        }

        $tahapan = trim($request->kak_tahapan);

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */
        $duplikat = ModelPermintaanKAK::where('kak_sub_kegiatan_id', $subKegiatan->sub_kegiatan_id)->where('kak_tahun', $request->kak_tahun)->where('kak_tahapan', $tahapan)->where('kak_status', 1)->where('kak_uid', '!=', $uid)->exists();

        if ($duplikat) {
            return back()->withInput()->with('error', 'KAK untuk Sub Kegiatan, tahun, dan tahapan tersebut sudah tersedia.');
        }

        $data = [
            'kak_sub_kegiatan_id' => $subKegiatan->sub_kegiatan_id,

            'kak_tahun' => $request->kak_tahun,

            'kak_tahapan' => $tahapan,

            'kak_unit' => $request->kak_unit,

            'updated_at' => now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | JIKA FILE DIGANTI
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('kak_file')) {
            $file = $request->file('kak_file');

            $extension = strtolower($file->getClientOriginalExtension());

            $tahapanFile = preg_replace('/[^A-Za-z0-9_-]/', '_', $tahapan);

            $subKodeFile = preg_replace('/[^A-Za-z0-9_.-]/', '_', $subKegiatan->sub_kegiatan_kode ?: $subKegiatan->sub_kegiatan_id);

            $filename = 'KAK_' . $request->kak_tahun . '_' . $subKodeFile . '_' . $tahapanFile . '_' . $kak->kak_uid . '.' . $extension;

            try {
                $kakFile = $arinDrive->upload($file, 'kak_2026_perubahan', $filename, $kak->kak_uid);

                $data['kak_file'] = $kakFile;
            } catch (Throwable $e) {
                return back()
                    ->withInput()
                    ->with('error', 'Upload file KAK baru gagal: ' . $e->getMessage());
            }
        }

        try {
            $kak->update($data);
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui KAK: ' . $e->getMessage());
        }

        return redirect()->route('user.permintaan-kak.index')->with('success', 'Data KAK berhasil diperbarui.');
    }
    /**
     * ============================================================
     * EDIT
     * ============================================================
     *
     * Mengambil data KAK milik user untuk form edit.
     */
    public function edit($uid)
    {
        $kak = ModelPermintaanKAK::query()->where('kak_uid', $uid)->where('kak_created_by', session('pegawai_id'))->where('kak_status', 1)->first();

        if (!$kak) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data KAK tidak ditemukan atau bukan milik Anda.',
                ],
                404,
            );
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kak_uid' => $kak->kak_uid,
                'kak_sub_kegiatan_id' => $kak->kak_sub_kegiatan_id,
                'kak_tahun' => $kak->kak_tahun,
                'kak_tahapan' => $kak->kak_tahapan,
                'kak_file' => $kak->kak_file,
                'kak_unit' => $kak->kak_unit,
                'kak_catatan_admin' => $kak->kak_catatan_admin,
            ],
        ]);
    }
}