<?php

namespace App\Http\Controllers;

use App\Models\ModelAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\ModelUser;
use App\Models\ModelProgram;
use App\Models\ModelKegiatan;
use App\Models\ModelSubKegiatan;
use Carbon\Carbon;

class AdminController extends Controller
{
    //
    public function index()
    {
        $tahun = request('tahun', date('Y'));

        $totalUser = \App\Models\ModelUser::count();

        $totalPagu = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)
            ->where('spj_pagu_status', 1)
            ->sum('spj_pagu_final');

        $totalRealisasiSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->sum('spj_nominal');

        $sisaPagu = $totalPagu - $totalRealisasiSPJ;

        $persenSerapan = $totalPagu > 0 ? ($totalRealisasiSPJ / $totalPagu) * 100 : 0;
        $persenSerapan = min($persenSerapan, 100);

        $jumlahPaguSPJ = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)
            ->where('spj_pagu_status', 1)
            ->count();

        $jumlahInputSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->count();

        $jumlahBBM = \DB::table('saplarin_bbm_pengajuan')
            ->whereYear('created_at', $tahun)
            ->count();

        $bbmMenunggu = \DB::table('saplarin_bbm_pengajuan')
            ->whereYear('created_at', $tahun)
            ->where('bbm_status_pengajuan', 'Menunggu Verifikasi')
            ->count();

        $jumlahPrioritas = \DB::table('sadarin_program_prioritas')
            ->where('prioritas_tahun', $tahun)
            ->where('prioritas_status', 'Aktif')
            ->count();

        $jumlahAktivitas = \DB::table('saplarin_laporan_kegiatan')
            ->where('laporan_kegiatan_tahun', $tahun)
            ->where('laporan_kegiatan_status', 'Aktif')
            ->count();

        $paguTerbaru = \App\Models\ModelSPJPagu::with(['program', 'kegiatan', 'subKegiatan', 'realisasi'])
            ->where('spj_pagu_tahun', $tahun)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('administrator.admin', compact(
            'tahun',
            'totalUser',
            'totalPagu',
            'totalRealisasiSPJ',
            'sisaPagu',
            'persenSerapan',
            'jumlahPaguSPJ',
            'jumlahInputSPJ',
            'jumlahBBM',
            'bbmMenunggu',
            'jumlahPrioritas',
            'jumlahAktivitas',
            'paguTerbaru'
        ));
    }
    public function getUsers()
    {
        // ambil semua role user dari database saplarin
        $users = ModelUser::select('user_uid', 'user_role')->get()->groupBy('user_uid');

        // ambil semua pegawai dari SADARIN
        $response = Http::get(env('SADARIN_API') . '/pegawai');

        if (!$response->ok()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Gagal mengambil data pegawai dari SADARIN',
                ],
                500,
            );
        }

        $pegawai = collect($response->json()['data']);

        $result = [];

        foreach ($users as $uid => $roles) {
            $pegawaiData = $pegawai->firstWhere('id', (int) $uid);

            if ($pegawaiData) {
                $result[] = [
                    'id' => $pegawaiData['id'],
                    'nama' => $pegawaiData['nama'],
                    'nip' => $pegawaiData['nip'],
                    'jabatan' => $pegawaiData['jabatan'] ?? '-',
                    'bidang' => $pegawaiData['bidang'] ?? '-',

                    // multiple role
                    'roles' => $roles->pluck('user_role')->values(),
                ];
            }
        }

        return response()->json([
            'status' => true,
            'total' => count($result),
            'data' => $result,
        ]);
    }
    public function manageUser()
    {
        if (!session('logged_in') || session('active_role') !== 'Admin Full') {
            abort(403, 'Hanya Admin Full yang dapat mengakses Data User');
        }

        $roles = ModelUser::where('user_uid', session('pegawai_id'))
            ->pluck('user_role')
            ->toArray();

        $availableRoles = [
            'Admin Full',
            'Admin BBM',
            'Admin Arsiparis',
            'Pegawai',
            'Operator',
        ];

        $users = ModelUser::select('user_uid', 'user_role')->get()->groupBy('user_uid');

        $response = Http::get(env('SADARIN_API') . '/pegawai');

        $pegawai = [];
        $result = [];

        if ($response->ok()) {
            $pegawai = $response->json()['data'] ?? [];
            $pegawaiCollection = collect($pegawai);

            foreach ($users as $uid => $userRoles) {
                $pegawaiData = $pegawaiCollection->firstWhere('id', (int) $uid);

                if ($pegawaiData) {
                    $result[] = [
                        'id' => $pegawaiData['id'],
                        'nama' => $pegawaiData['nama'],
                        'nip' => $pegawaiData['nip'] ?? '-',
                        'nik' => $pegawaiData['nik'] ?? '-',
                        'jabatan' => $pegawaiData['jabatan'] ?? '-',
                        'bidang' => $pegawaiData['bidang'] ?? '-',
                        'jeniskerja' => $pegawaiData['jeniskerja'] ?? '-',
                        'roles' => $userRoles->pluck('user_role')->unique()->values()->toArray(),
                    ];
                }
            }
        }

        return view('administrator.users', [
            'users' => $result,
            'roles' => $roles,
            'pegawai' => $pegawai,
            'availableRoles' => $availableRoles,
        ]);
    }
    public function storeUser(Request $request)
    {
        $request->validate([
            'user_uid' => 'required',
            'roles' => 'required|array',
        ]);

        foreach ($request->roles as $role) {
            ModelUser::firstOrCreate([
                'user_uid' => $request->user_uid,
                'user_role' => $role,
            ]);
        }

        return back()->with('success', 'User berhasil ditambahkan');
    }
    public function updateRole(Request $request)
    {
        $request->validate([
            'user_uid' => 'required',
            'roles' => 'required|array',
        ]);

        // hapus role lama
        ModelUser::where('user_uid', $request->user_uid)->delete();

        // insert role baru
        foreach ($request->roles as $role) {
            ModelUser::create([
                'user_uid' => $request->user_uid,
                'user_role' => $role,
            ]);
        }

        return back()->with('success', 'Role user berhasil diperbarui');
    }

    //==================================== PROGRAM =====================================
    public function program()
    {
        // role user login (popup switch role)
        $roles = ModelUser::where('user_uid', session('pegawai_id'))->pluck('user_role')->toArray();
        $programs = ModelProgram::all();

        return view('administrator.program', compact('programs', 'roles'));
    }
    public function storeProgram(Request $request)
    {
        $request->validate([
            'program_kode' => 'required|string|max:255|unique:saplarin_program,program_kode',
            'program_nama' => 'required|string|max:255',
            'program_status' => 'required',
        ]);

        ModelProgram::create([
            'program_uid' => Str::uuid(),
            'program_kode' => $request->program_kode,
            'program_nama' => $request->program_nama,
            'program_status' => $request->program_status,
        ]);

        return back()->with('success', 'Program berhasil ditambahkan');
    }
    public function updateProgram(Request $request)
    {
        $request->validate([
            'program_id' => 'required',
            'program_kode' => 'required|string|max:255|unique:saplarin_program,program_kode',
            'program_nama' => 'required|string|max:255',
            'program_status' => 'required',
        ]);

        ModelProgram::where('program_id', $request->program_id)->update([
            'program_kode' => $request->program_kode,
            'program_nama' => $request->program_nama,
            'program_status' => $request->program_status,
        ]);

        return back()->with('success', 'Program berhasil diperbarui');
    }

    //==================================== KEGIATAN =====================================
    public function kegiatan()
    {
        // role user login (popup switch role)
        $roles = ModelUser::where('user_uid', session('pegawai_id'))->pluck('user_role')->toArray();
        $kegiatans = ModelKegiatan::join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')->select('saplarin_kegiatan.*', 'saplarin_program.program_nama', 'saplarin_program.program_kode')->get();

        // dropdown program
        $programs = ModelProgram::where('program_status', 1)->get();

        return view('administrator.kegiatan', compact('kegiatans', 'programs', 'roles'));
    }
    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan_program' => 'required',
            'kegiatan_kode' => 'required|string|max:255|unique:saplarin_kegiatan,kegiatan_kode',
            'kegiatan_nama' => 'required|string|max:255',
            'kegiatan_status' => 'required',
        ]);

        ModelKegiatan::create([
            'kegiatan_uid' => Str::uuid(),
            'kegiatan_program' => $request->kegiatan_program,
            'kegiatan_kode' => $request->kegiatan_kode,
            'kegiatan_nama' => $request->kegiatan_nama,
            'kegiatan_status' => $request->kegiatan_status,
        ]);

        return back()->with('success', 'Kegiatan berhasil ditambahkan');
    }
    public function updateKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required',
            'kegiatan_kode' => 'required|string|max:255|unique:saplarin_kegiatan,kegiatan_kode',
            'kegiatan_program' => 'required',
            'kegiatan_nama' => 'required|string|max:255',
            'kegiatan_status' => 'required',
        ]);

        ModelKegiatan::where('kegiatan_id', $request->kegiatan_id)->update([
            'kegiatan_kode' => $request->kegiatan_kode,
            'kegiatan_program' => $request->kegiatan_program,
            'kegiatan_nama' => $request->kegiatan_nama,
            'kegiatan_status' => $request->kegiatan_status,
        ]);

        return back()->with('success', 'Kegiatan berhasil diperbarui');
    }
    // ==================================== SUB KEGIATAN =====================================
    public function subkegiatan()
    {
        // role user login (popup switch role)
        $roles = ModelUser::where('user_uid', session('pegawai_id'))->pluck('user_role')->toArray();
        $subkegiatans = ModelSubKegiatan::join('saplarin_kegiatan', 'saplarin_sub_kegiatan.sub_kegiatan_kegiatan', '=', 'saplarin_kegiatan.kegiatan_id')->join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')->select('saplarin_sub_kegiatan.*', 'saplarin_kegiatan.kegiatan_nama', 'saplarin_program.program_nama', 'saplarin_program.program_kode', 'saplarin_kegiatan.kegiatan_kode')->get();

        // dropdown kegiatan
        $kegiatans = ModelKegiatan::where('kegiatan_status', 1)->get();

        return view('administrator.subkegiatan', compact('subkegiatans', 'kegiatans', 'roles'));
    }
    public function storeSubKegiatan(Request $request)
    {
        $request->validate([
            'sub_kegiatan_kode' => 'required|string|max:255|unique:saplarin_sub_kegiatan,sub_kegiatan_kode',
            'sub_kegiatan_kode_rekening' => 'nullable|string|max:255|unique:saplarin_sub_kegiatan,sub_kegiatan_kode_rekening',
            'sub_kegiatan_kegiatan' => 'required',
            'sub_kegiatan_nama' => 'required|string|max:255',
            'sub_kegiatan_status' => 'required',
        ]);

        ModelSubKegiatan::create([
            'sub_kegiatan_uid' => Str::uuid(),
            'sub_kegiatan_kode' => $request->sub_kegiatan_kode,
            'sub_kegiatan_kode_rekening' => $request->sub_kegiatan_kode_rekening,
            'sub_kegiatan_kegiatan' => $request->sub_kegiatan_kegiatan,
            'sub_kegiatan_nama' => $request->sub_kegiatan_nama,
            'sub_kegiatan_status' => $request->sub_kegiatan_status,
        ]);

        return back()->with('success', 'Sub Kegiatan berhasil ditambahkan');
    }
    public function updateSubKegiatan(Request $request)
    {
        $request->validate([
            'sub_kegiatan_id' => 'required|exists:saplarin_sub_kegiatan,sub_kegiatan_id',

            'sub_kegiatan_kode' => 'required|string|max:255|unique:saplarin_sub_kegiatan,sub_kegiatan_kode,' . $request->sub_kegiatan_id . ',sub_kegiatan_id',

            'sub_kegiatan_kode_rekening' => 'nullable|string|max:255|unique:saplarin_sub_kegiatan,sub_kegiatan_kode_rekening,' . $request->sub_kegiatan_id . ',sub_kegiatan_id',

            'sub_kegiatan_kegiatan' => 'required',
            'sub_kegiatan_nama' => 'required|string|max:255',
            'sub_kegiatan_status' => 'required',
        ]);

        ModelSubKegiatan::where('sub_kegiatan_id', $request->sub_kegiatan_id)->update([
            'sub_kegiatan_kode' => $request->sub_kegiatan_kode,
            'sub_kegiatan_kode_rekening' => $request->sub_kegiatan_kode_rekening,
            'sub_kegiatan_kegiatan' => $request->sub_kegiatan_kegiatan,
            'sub_kegiatan_nama' => $request->sub_kegiatan_nama,
            'sub_kegiatan_status' => $request->sub_kegiatan_status,
        ]);

        return back()->with('success', 'Sub Kegiatan berhasil diperbarui');
    }
}