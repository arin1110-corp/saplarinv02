<?php

namespace App\Http\Controllers;

use App\Models\ModelBBM;
use App\Services\BBMEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserBBMController extends Controller
{
    public function index()
    {
        $bbms = ModelBBM::where('bbm_pengaju_id', session('pegawai_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.bbm.index', compact('bbms'));
    }

    public function create()
    {
        return view('user.bbm.create');
    }

    public function store(Request $request, BBMEmailService $emailService)
    {
        $request->validate([
            'bbm_no_plat' => 'required|string|max:50',
            'bbm_uraian_kegiatan' => 'required|string',
            'bbm_liter' => 'required|numeric|min:0.01',
            'bbm_spt_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $uid = (string) Str::uuid();

        $sptFile = $this->uploadFileToPublic(
            $request->file('bbm_spt_file'),
            $uid,
            'spt'
        );

        $bbm = ModelBBM::create([
            'bbm_uid' => $uid,

            'bbm_pengaju_id' => session('pegawai_id'),
            'bbm_pengaju_nama' => session('pegawai_nama'),
            'bbm_pengaju_nip' => session('pegawai_nip'),
            'bbm_pengaju_email' => session('pegawai_email'),
            'bbm_bidang_nama' => session('pegawai_bidang') ?? session('pegawai_bidang_nama') ?? '-',

            'bbm_no_plat' => strtoupper($request->bbm_no_plat),
            'bbm_uraian_kegiatan' => $request->bbm_uraian_kegiatan,
            'bbm_liter' => $request->bbm_liter,

            'bbm_spt_file' => $sptFile,
            'bbm_spt_sync' => false,

            'bbm_status_pengajuan' => 'Menunggu Verifikasi',
            'bbm_status_laporan' => 'Belum Upload',
        ]);

        $emailService->kirimKeAdminBBM(
            'Pengajuan BBM Baru - ' . $bbm->bbm_pengaju_nama,
            "Yth. Admin BBM,\n\n" .
                "Terdapat pengajuan BBM baru dengan data berikut:\n\n" .
                "Nama Pengaju : {$bbm->bbm_pengaju_nama}\n" .
                "NIP          : {$bbm->bbm_pengaju_nip}\n" .
                "Bidang       : {$bbm->bbm_bidang_nama}\n" .
                "No Plat      : {$bbm->bbm_no_plat}\n" .
                "Jumlah BBM   : {$bbm->bbm_liter} Liter\n" .
                "Status       : {$bbm->bbm_status_pengajuan}\n\n" .
                "Silakan login ke SAPLARIN untuk melakukan verifikasi.\n\n" .
                "SAPLARIN"
        );

        $emailService->kirimKePengaju(
            $bbm,
            'Pengajuan BBM Berhasil Dikirim',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
                "Pengajuan BBM Anda berhasil dikirim.\n\n" .
                "No Plat    : {$bbm->bbm_no_plat}\n" .
                "Jumlah BBM : {$bbm->bbm_liter} Liter\n" .
                "Status     : {$bbm->bbm_status_pengajuan}\n\n" .
                "Mohon menunggu proses verifikasi admin.\n\n" .
                "SAPLARIN"
        );

        return redirect()
            ->route('user.bbm.index')
            ->with('success', 'Pengajuan BBM berhasil dikirim');
    }

    public function show($uid)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)
            ->where('bbm_pengaju_id', session('pegawai_id'))
            ->firstOrFail();

        return view('user.bbm.show', compact('bbm'));
    }

    public function uploadLaporan(Request $request, $uid, BBMEmailService $emailService)
    {
        $request->validate([
            'bbm_tanggal_nota' => 'required|date',
            'bbm_laporan_nota_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $bbm = ModelBBM::where('bbm_uid', $uid)
            ->where('bbm_pengaju_id', session('pegawai_id'))
            ->firstOrFail();

        if ($bbm->bbm_status_pengajuan !== 'Pengajuan Diterima') {
            return back()->with('error', 'Upload nota hanya bisa dilakukan setelah pengajuan diterima.');
        }

        if ($bbm->bbm_status_laporan === 'Laporan Nota Diterima') {
            return back()->with('error', 'Laporan nota sudah diterima dan tidak bisa diubah.');
        }

        $notaFile = $this->uploadFileToPublic(
            $request->file('bbm_laporan_nota_file'),
            $bbm->bbm_uid,
            'nota',
            $request->bbm_tanggal_nota
        );

        $bbm->update([
            'bbm_tanggal_nota' => $request->bbm_tanggal_nota,
            'bbm_laporan_nota_file' => $notaFile,
            'bbm_laporan_nota_sync' => false,
            'bbm_status_laporan' => 'Menunggu Verifikasi',
        ]);

        $emailService->kirimKeAdminBBM(
            'Laporan Nota BBM Baru - ' . $bbm->bbm_pengaju_nama,
            "Yth. Admin BBM,\n\n" .
                "Pegawai telah mengupload laporan nota BBM.\n\n" .
                "Nama Pengaju : {$bbm->bbm_pengaju_nama}\n" .
                "NIP          : {$bbm->bbm_pengaju_nip}\n" .
                "Bidang       : {$bbm->bbm_bidang_nama}\n" .
                "No Plat      : {$bbm->bbm_no_plat}\n" .
                "Jumlah BBM   : {$bbm->bbm_liter} Liter\n" .
                "Tanggal Nota : {$request->bbm_tanggal_nota}\n" .
                "Status Nota  : Menunggu Verifikasi\n\n" .
                "Silakan login ke SAPLARIN untuk melakukan verifikasi laporan nota.\n\n" .
                "SAPLARIN"
        );

        return back()->with('success', 'Laporan nota berhasil diupload.');
    }

    private function uploadFileToPublic($file, $uid, $jenis, $tanggalNota = null)
    {
        $folder = public_path('assets/notabbm');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $tanggal = $tanggalNota
            ? date('Ymd', strtotime($tanggalNota))
            : date('Ymd');

        $extension = $file->getClientOriginalExtension();

        $filename = $uid . '-' . $jenis . '-' . $tanggal . '-' . time() . '.' . $extension;

        $file->move($folder, $filename);

        return 'assets/notabbm/' . $filename;
    }
}