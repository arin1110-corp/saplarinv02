<?php

namespace App\Http\Controllers;

use App\Models\ModelBBM;
use App\Services\BBMEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminBBMController extends Controller
{
    public function index()
    {
        $bbms = ModelBBM::with('subKegiatan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrator.bbm.index', compact('bbms'));
    }

    public function terimaPengajuan(Request $request, $uid, BBMEmailService $emailService)
    {
        $request->validate([
            'bbm_acc_pimpinan_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $bbm = ModelBBM::with('subKegiatan')
            ->where('bbm_uid', $uid)
            ->firstOrFail();

        $accFile = $this->uploadFileToPublic(
            $request->file('bbm_acc_pimpinan_file'),
            $bbm->bbm_uid,
            'acc-pimpinan'
        );

        $bbm->update([
            'bbm_acc_pimpinan_file' => $accFile,
            'bbm_status_pengajuan' => 'Pengajuan Diterima',
            'bbm_catatan_admin' => null,
        ]);

        $emailService->kirimKePengaju(
            $bbm,
            'Pengajuan BBM Diterima',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
            "Pengajuan BBM Anda telah diterima.\n\n" .
            "Sub Kegiatan : " . ($bbm->subKegiatan->sub_kegiatan_nama ?? '-') . "\n" .
            "Status       : Pengajuan Diterima\n\n" .
            "Silakan upload laporan nota setelah pencairan atau setelah nota tersedia.\n\n" .
            "SAPLARIN"
        );

        return back()->with('success', 'Pengajuan BBM diterima dan dokumen ACC pimpinan berhasil diupload.');
    }

    public function tolakPengajuan(Request $request, $uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::with('subKegiatan')
            ->where('bbm_uid', $uid)
            ->firstOrFail();

        $bbm->update([
            'bbm_status_pengajuan' => 'Pengajuan Ditolak',
            'bbm_catatan_admin' => $request->catatan,
        ]);

        $emailService->kirimKePengaju(
            $bbm,
            'Pengajuan BBM Ditolak',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
            "Pengajuan BBM Anda ditolak.\n\n" .
            "Sub Kegiatan : " . ($bbm->subKegiatan->sub_kegiatan_nama ?? '-') . "\n" .
            "Catatan      : " . ($request->catatan ?? '-') . "\n\n" .
            "SAPLARIN"
        );

        return back()->with('success', 'Pengajuan BBM ditolak.');
    }

    public function terimaLaporan($uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::with('subKegiatan')
            ->where('bbm_uid', $uid)
            ->firstOrFail();

        if (!$bbm->bbm_laporan_nota_file) {
            return back()->with('error', 'User belum upload laporan nota.');
        }

        $bbm->update([
            'bbm_status_laporan' => 'Laporan Nota Diterima',
            'bbm_catatan_admin' => null,
        ]);

        $emailService->kirimKePengaju(
            $bbm,
            'Laporan Nota BBM Diterima',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
            "Laporan nota BBM Anda telah diterima.\n\n" .
            "Sub Kegiatan : " . ($bbm->subKegiatan->sub_kegiatan_nama ?? '-') . "\n" .
            "Status Nota  : Laporan Nota Diterima\n\n" .
            "Proses pengajuan BBM telah selesai.\n\n" .
            "SAPLARIN"
        );

        return back()->with('success', 'Laporan nota diterima.');
    }

    public function tolakLaporan(Request $request, $uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::with('subKegiatan')
            ->where('bbm_uid', $uid)
            ->firstOrFail();

        $bbm->update([
            'bbm_status_laporan' => 'Laporan Nota Ditolak',
            'bbm_catatan_admin' => $request->catatan,
        ]);

        $emailService->kirimKePengaju(
            $bbm,
            'Laporan Nota BBM Ditolak',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
            "Laporan nota BBM Anda ditolak.\n\n" .
            "Sub Kegiatan : " . ($bbm->subKegiatan->sub_kegiatan_nama ?? '-') . "\n" .
            "Catatan      : " . ($request->catatan ?? '-') . "\n\n" .
            "Silakan perbaiki dan upload kembali laporan nota.\n\n" .
            "SAPLARIN"
        );

        return back()->with('success', 'Laporan nota ditolak.');
    }

    private function uploadFileToPublic($file, $uid, $jenis)
    {
        $folder = public_path('assets/notabbm');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $tanggal = date('Ymd');
        $extension = $file->getClientOriginalExtension();

        $filename = $uid . '-' . $jenis . '-' . $tanggal . '-' . time() . '.' . $extension;

        $file->move($folder, $filename);

        return 'assets/notabbm/' . $filename;
    }
    public function sinkron(Request $request, $uid)
{
    $request->validate([
        'jenis' => 'required',
        'url_drive' => 'required|url',
    ]);

    $bbm = ModelBBM::where(
        'bbm_uid',
        $uid
    )->firstOrFail();

    if ($request->jenis === 'spt') {

        $this->hapusFileLokal(
            $bbm->bbm_spt_file
        );

        $bbm->update([
            'bbm_spt_file' => $request->url_drive,
            'bbm_spt_sync' => true,
        ]);
    }

    if ($request->jenis === 'acc') {

        $this->hapusFileLokal(
            $bbm->bbm_acc_pimpinan_file
        );

        $bbm->update([
            'bbm_acc_pimpinan_file' => $request->url_drive,
            'bbm_acc_pimpinan_sync' => true,
        ]);
    }

    if ($request->jenis === 'nota') {

        $this->hapusFileLokal(
            $bbm->bbm_laporan_nota_file
        );

        $bbm->update([
            'bbm_laporan_nota_file' => $request->url_drive,
            'bbm_laporan_nota_sync' => true,
        ]);
    }

    return back()->with(
        'success',
        'File berhasil disinkronkan'
    );
}
}