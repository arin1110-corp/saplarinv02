<?php

namespace App\Http\Controllers;

use App\Models\ModelLaporanKegiatan;
use App\Models\ModelLaporanAktivitas;
use App\Models\ModelLaporanAktivitasBukti;
use App\Models\ModelProgram;
use App\Models\ModelKegiatan;
use App\Models\ModelSubKegiatan;
use App\Services\ArinDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserLaporanAktivitasController extends Controller
{
    public function index()
    {
        $kegiatans = ModelLaporanKegiatan::with(['aktivitas.bukti'])
            ->where('laporan_kegiatan_status', 'Aktif')
            ->where('laporan_kegiatan_bidang_id', session('pegawai_bidang_id'))
            ->orderBy('laporan_kegiatan_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $programs = ModelProgram::where('program_status', 1)
            ->orderBy('program_nama', 'asc')
            ->get();

        $masterKegiatans = ModelKegiatan::where('kegiatan_status', 1)
            ->orderBy('kegiatan_nama', 'asc')
            ->get();

        $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_status', 1)
            ->orderBy('sub_kegiatan_nama', 'asc')
            ->get();

        return view('user.laporan-aktivitas.index', compact(
            'kegiatans',
            'programs',
            'masterKegiatans',
            'subKegiatans'
        ));
    }

    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'laporan_kegiatan_tahun' => 'required|digits:4',
            'laporan_kegiatan_sub_kegiatan_id' => 'required',
            'laporan_kegiatan_deskripsi' => 'nullable|string',
        ]);

        $subKegiatan = ModelSubKegiatan::where('sub_kegiatan_id', $request->laporan_kegiatan_sub_kegiatan_id)
            ->where('sub_kegiatan_status', 1)
            ->firstOrFail();

        ModelLaporanKegiatan::create([
            'laporan_kegiatan_uid' => Str::uuid(),
            'laporan_kegiatan_tahun' => $request->laporan_kegiatan_tahun,

            'laporan_kegiatan_nama' => $subKegiatan->sub_kegiatan_nama,
            'laporan_kegiatan_sub_kegiatan_id' => $subKegiatan->sub_kegiatan_id,
            'laporan_kegiatan_sub_kegiatan_nama' => $subKegiatan->sub_kegiatan_nama,

            'laporan_kegiatan_deskripsi' => $request->laporan_kegiatan_deskripsi,

            'laporan_kegiatan_bidang_id' => session('pegawai_bidang_id'),
            'laporan_kegiatan_bidang_nama' => session('pegawai_bidang'),

            'laporan_kegiatan_user_id' => session('pegawai_id'),
            'laporan_kegiatan_user_nama' => session('pegawai_nama'),
            'laporan_kegiatan_user_nip' => session('pegawai_nip'),

            'laporan_kegiatan_status' => 'Aktif',
        ]);

        return back()->with('success', 'Kegiatan berdasarkan sub kegiatan berhasil ditambahkan.');
    }

    public function storeAktivitas(Request $request, $uid, ArinDriveService $arinDrive)
    {
        $request->validate([
            'aktivitas_nama' => 'required|string|max:255',
            'aktivitas_uraian' => 'nullable|string',
            'aktivitas_tanggal_mulai' => 'required|date',
            'aktivitas_tanggal_selesai' => 'required|date|after_or_equal:aktivitas_tanggal_mulai',
            'bukti_file' => 'required|array|max:5',
            'bukti_file.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:204800',
        ]);

        $kegiatan = ModelLaporanKegiatan::where('laporan_kegiatan_uid', $uid)
            ->where('laporan_kegiatan_status', 'Aktif')
            ->where('laporan_kegiatan_bidang_id', session('pegawai_bidang_id'))
            ->firstOrFail();

        $triwulan = $this->getTriwulan($request->aktivitas_tanggal_selesai);

        $aktivitasUid = (string) Str::uuid();

        $aktivitas = ModelLaporanAktivitas::create([
            'aktivitas_uid' => $aktivitasUid,
            'aktivitas_kegiatan_id' => $kegiatan->laporan_kegiatan_id,

            'aktivitas_nama' => $request->aktivitas_nama,
            'aktivitas_uraian' => $request->aktivitas_uraian,

            'aktivitas_tanggal_mulai' => $request->aktivitas_tanggal_mulai,
            'aktivitas_tanggal_selesai' => $request->aktivitas_tanggal_selesai,
            'aktivitas_triwulan' => $triwulan,

            'aktivitas_user_id' => session('pegawai_id'),
            'aktivitas_user_nama' => session('pegawai_nama'),
            'aktivitas_user_nip' => session('pegawai_nip'),

            'aktivitas_bidang_id' => session('pegawai_bidang_id'),
            'aktivitas_bidang_nama' => session('pegawai_bidang'),

            'aktivitas_status' => 'Aktif',
        ]);

        foreach ($request->file('bukti_file') as $file) {
            $filename = $aktivitas->aktivitas_uid
                . '_BUKTI_AKTIVITAS_'
                . date('Ymd_His')
                . '_'
                . rand(100, 999)
                . '.'
                . $file->getClientOriginalExtension();

            $path = $arinDrive->upload(
                $file,
                'laporan_aktivitas',
                $filename,
                $aktivitas->aktivitas_uid
            );

            ModelLaporanAktivitasBukti::create([
                'bukti_aktivitas_id' => $aktivitas->aktivitas_id,
                'bukti_file' => $path,
                'bukti_nama_file' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('success', 'Aktivitas berhasil ditambahkan dan otomatis masuk ' . $triwulan . '.');
    }

    private function getTriwulan($tanggal)
    {
        $bulan = date('n', strtotime($tanggal));

        if ($bulan >= 1 && $bulan <= 3) {
            return 'TW I';
        }

        if ($bulan >= 4 && $bulan <= 6) {
            return 'TW II';
        }

        if ($bulan >= 7 && $bulan <= 9) {
            return 'TW III';
        }

        return 'TW IV';
    }
}