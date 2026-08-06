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
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    //
    public function index()
    {
        $tahun = request('tahun', date('Y'));

        $totalUser = \App\Models\ModelUser::count();

        $totalPagu = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)->where('spj_pagu_status', 1)->sum('spj_pagu_final');

        $totalRealisasiSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->sum('spj_nominal');

        $sisaPagu = $totalPagu - $totalRealisasiSPJ;

        $persenSerapan = $totalPagu > 0 ? ($totalRealisasiSPJ / $totalPagu) * 100 : 0;
        $persenSerapan = min($persenSerapan, 100);

        $jumlahPaguSPJ = \App\Models\ModelSPJPagu::where('spj_pagu_tahun', $tahun)->where('spj_pagu_status', 1)->count();

        $jumlahInputSPJ = \App\Models\ModelSPJRealisasi::whereHas('pagu', function ($q) use ($tahun) {
            $q->where('spj_pagu_tahun', $tahun);
        })
            ->where('spj_status', 'Aktif')
            ->count();

        $jumlahBBM = \DB::table('saplarin_bbm_pengajuan')->whereYear('created_at', $tahun)->count();

        $bbmMenunggu = \DB::table('saplarin_bbm_pengajuan')->whereYear('created_at', $tahun)->where('bbm_status_pengajuan', 'Menunggu Verifikasi')->count();

        $jumlahPrioritas = \DB::table('sadarin_program_prioritas')->where('prioritas_tahun', $tahun)->where('prioritas_status', 'Aktif')->count();

        $jumlahAktivitas = \DB::table('saplarin_laporan_kegiatan')->where('laporan_kegiatan_tahun', $tahun)->where('laporan_kegiatan_status', 'Aktif')->count();

        $paguTerbaru = \App\Models\ModelSPJPagu::with(['program', 'kegiatan', 'subKegiatan', 'realisasi'])
            ->where('spj_pagu_tahun', $tahun)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('administrator.admin', compact('tahun', 'totalUser', 'totalPagu', 'totalRealisasiSPJ', 'sisaPagu', 'persenSerapan', 'jumlahPaguSPJ', 'jumlahInputSPJ', 'jumlahBBM', 'bbmMenunggu', 'jumlahPrioritas', 'jumlahAktivitas', 'paguTerbaru'));
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

        $roles = ModelUser::where('user_uid', session('pegawai_id'))->pluck('user_role')->toArray();

        $availableRoles = ['Admin Full', 'Admin BBM', 'Admin Arsiparis', 'Pegawai', 'Operator', 'Operator SPJ'];

        $search = strtolower(trim(request('search', '')));

        $users = ModelUser::select('user_uid', 'user_role')->get()->groupBy('user_uid');

        $response = Http::get(env('SADARIN_API') . '/pegawai');

        $pegawai = [];
        $result = [];

        if ($response->ok()) {
            $pegawai = collect($response->json()['data'] ?? [])->keyBy('id');

            foreach ($users as $uid => $userRoles) {
                $pegawaiData = $pegawai->get((int) $uid);

                if (!$pegawaiData) {
                    continue;
                }

                if ($search !== '') {
                    $text = strtolower(($pegawaiData['nama'] ?? '') . ' ' . ($pegawaiData['nip'] ?? '') . ' ' . ($pegawaiData['nik'] ?? '') . ' ' . ($pegawaiData['jabatan'] ?? '') . ' ' . ($pegawaiData['bidang'] ?? ''));

                    if (!str_contains($text, $search)) {
                        continue;
                    }
                }

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

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 10;

        $collection = collect($result);

        $users = new LengthAwarePaginator(
            $collection->slice(($page - 1) * $perPage, $perPage)->values(),

            $collection->count(),

            $perPage,

            $page,

            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),

                'query' => request()->query(),
            ],
        );
        return view('administrator-v2.user.index', [
            'users' => $users,

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
    public function program(Request $request)
    {
        // Role user login (popup switch role)
        $roles = ModelUser::where('user_uid', session('pegawai_id'))->pluck('user_role')->toArray();

        $search = trim($request->search);

        $programs = ModelProgram::query()

            ->when($search, function ($q) use ($search) {
                $q->where('program_kode', 'like', "%{$search}%")->orWhere('program_nama', 'like', "%{$search}%");
            })

            ->orderBy('program_kode')

            ->paginate(10)

            ->withQueryString();

        return view('administrator-v2.program.index', compact('programs', 'roles'));
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
            'program_kode' => 'required|string|max:255',
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
    public function kegiatan(Request $request)
    {
        // Role user login (popup switch role)
        $roles = ModelUser::where('user_uid', session('pegawai_id'))->pluck('user_role')->toArray();

        $search = trim($request->search);

        $kegiatans = ModelKegiatan::query()

            ->join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')

            ->select('saplarin_kegiatan.*', 'saplarin_program.program_nama', 'saplarin_program.program_kode')

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query
                        ->where('saplarin_kegiatan.kegiatan_kode', 'like', "%{$search}%")
                        ->orWhere('saplarin_kegiatan.kegiatan_nama', 'like', "%{$search}%")
                        ->orWhere('saplarin_program.program_nama', 'like', "%{$search}%")
                        ->orWhere('saplarin_program.program_kode', 'like', "%{$search}%");
                });
            })

            ->orderBy('saplarin_program.program_kode')
            ->orderBy('saplarin_kegiatan.kegiatan_kode')

            ->paginate(10)

            ->withQueryString();

        // Dropdown Program
        $programs = ModelProgram::where('program_status', 1)->orderBy('program_kode')->get();

        return view('administrator-v2.kegiatan.index', compact('kegiatans', 'programs', 'roles'));
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
            'kegiatan_kode' => 'required|string|max:255',
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
    public function subkegiatan(Request $request)
    {
        // Role user login (popup switch role)
        $roles = ModelUser::where('user_uid', session('pegawai_id'))->pluck('user_role')->toArray();

        $search = trim($request->search);

        $subkegiatans = ModelSubKegiatan::query()

            ->join('saplarin_kegiatan', 'saplarin_sub_kegiatan.sub_kegiatan_kegiatan', '=', 'saplarin_kegiatan.kegiatan_id')

            ->join('saplarin_program', 'saplarin_kegiatan.kegiatan_program', '=', 'saplarin_program.program_id')

            ->select('saplarin_sub_kegiatan.*', 'saplarin_kegiatan.kegiatan_nama', 'saplarin_kegiatan.kegiatan_kode', 'saplarin_program.program_nama', 'saplarin_program.program_kode')

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query
                        ->where('saplarin_sub_kegiatan.sub_kegiatan_kode', 'like', "%{$search}%")
                        ->orWhere('saplarin_sub_kegiatan.sub_kegiatan_nama', 'like', "%{$search}%")
                        ->orWhere('saplarin_kegiatan.kegiatan_nama', 'like', "%{$search}%")
                        ->orWhere('saplarin_kegiatan.kegiatan_kode', 'like', "%{$search}%")
                        ->orWhere('saplarin_program.program_nama', 'like', "%{$search}%")
                        ->orWhere('saplarin_program.program_kode', 'like', "%{$search}%");
                });
            })

            ->orderBy('saplarin_program.program_kode')
            ->orderBy('saplarin_kegiatan.kegiatan_kode')
            ->orderBy('saplarin_sub_kegiatan.sub_kegiatan_kode')

            ->paginate(10)

            ->withQueryString();

        // Dropdown Kegiatan
        $kegiatans = ModelKegiatan::where('kegiatan_status', 1)->orderBy('kegiatan_kode')->get();

        return view('administrator-v2.subkegiatan.index', compact('subkegiatans', 'kegiatans', 'roles'));
    }
    public function storeSubKegiatan(Request $request)
    {
        $request->validate([
            'sub_kegiatan_kode' => 'required|string|max:255',
            'sub_kegiatan_kode_rekening' => 'nullable|string|max:255|',
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