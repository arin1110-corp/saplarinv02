<?php

namespace App\Http\Controllers;

use App\Models\ModelBBM;
use App\Services\ArinDriveService;
use App\Services\BBMEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminBBMController extends Controller
{
    public function index()
    {
        $bbms = ModelBBM::orderBy('created_at', 'desc')->get();

        return view('administrator.bbm.index', compact('bbms'));
    }

    public function terimaPengajuan(Request $request, $uid, BBMEmailService $emailService, ArinDriveService $arinDrive)
    {
        $request->validate([
            'bbm_acc_pimpinan_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        $file = $request->file('bbm_acc_pimpinan_file');

        $accFile = $arinDrive->upload(
            $file,
            'bbm_acc',
            $bbm->bbm_uid . '_ACC_PIMPINAN.' . $file->getClientOriginalExtension(),
            $bbm->bbm_uid
        );

        $bbm->update([
            'bbm_acc_pimpinan_file' => $accFile,
            'bbm_acc_pimpinan_sync' => true,
            'bbm_status_pengajuan' => 'Pengajuan Diterima',
            'bbm_catatan_admin' => null,
        ]);

        $emailService->kirimKePengaju(
            $bbm,
            'Pengajuan BBM Diterima',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
                "Pengajuan BBM Anda telah diterima.\n\n" .
                "No Plat    : {$bbm->bbm_no_plat}\n" .
                "Jumlah BBM : {$bbm->bbm_liter} Liter\n" .
                "Status     : Pengajuan Diterima\n\n" .
                "Silakan upload laporan nota setelah pencairan atau setelah nota tersedia.\n\n" .
                "SAPLARIN"
        );

        return back()->with('success', 'Pengajuan BBM diterima dan dokumen ACC pimpinan berhasil diupload ke Google Drive.');
    }

    public function tolakPengajuan(Request $request, $uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        $bbm->update([
            'bbm_status_pengajuan' => 'Pengajuan Ditolak',
            'bbm_catatan_admin' => $request->catatan,
        ]);

        $emailService->kirimKePengaju(
            $bbm,
            'Pengajuan BBM Ditolak',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
                "Pengajuan BBM Anda ditolak.\n\n" .
                "No Plat    : {$bbm->bbm_no_plat}\n" .
                "Jumlah BBM : {$bbm->bbm_liter} Liter\n" .
                "Catatan    : " . ($request->catatan ?? '-') . "\n\n" .
                "SAPLARIN"
        );

        return back()->with('success', 'Pengajuan BBM ditolak.');
    }

    public function terimaLaporan($uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        if (!$bbm->bbm_laporan_nota_file) {
            return back()->with('error', 'User belum upload laporan nota.');
        }

        $bbm->update([
            'bbm_status_laporan' => 'Laporan Nota Diterima',
            'bbm_catatan_admin' => null,
        ]);

        $tanggalNota = '-';

        if ($bbm->bbm_tanggal_nota) {
            $tanggalNota = is_string($bbm->bbm_tanggal_nota)
                ? date('d/m/Y', strtotime($bbm->bbm_tanggal_nota))
                : $bbm->bbm_tanggal_nota->format('d/m/Y');
        }

        $emailService->kirimKePengaju(
            $bbm,
            'Laporan Nota BBM Diterima',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
                "Laporan nota BBM Anda telah diterima.\n\n" .
                "No Plat      : {$bbm->bbm_no_plat}\n" .
                "Jumlah BBM   : {$bbm->bbm_liter} Liter\n" .
                "Tanggal Nota : {$tanggalNota}\n" .
                "Status Nota  : Laporan Nota Diterima\n\n" .
                "Proses pengajuan BBM telah selesai.\n\n" .
                "SAPLARIN"
        );

        return back()->with('success', 'Laporan nota diterima.');
    }

    public function tolakLaporan(Request $request, $uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        $bbm->update([
            'bbm_status_laporan' => 'Laporan Nota Ditolak',
            'bbm_catatan_admin' => $request->catatan,
        ]);

        $emailService->kirimKePengaju(
            $bbm,
            'Laporan Nota BBM Ditolak',
            "Yth. {$bbm->bbm_pengaju_nama},\n\n" .
                "Laporan nota BBM Anda ditolak.\n\n" .
                "No Plat    : {$bbm->bbm_no_plat}\n" .
                "Jumlah BBM : {$bbm->bbm_liter} Liter\n" .
                "Catatan    : " . ($request->catatan ?? '-') . "\n\n" .
                "Silakan perbaiki dan upload kembali laporan nota.\n\n" .
                "SAPLARIN"
        );

        return back()->with('success', 'Laporan nota ditolak.');
    }

    public function sinkronFile(Request $request, $uid)
    {
        $request->validate([
            'jenis_file' => 'required|in:spt,acc_pimpinan,nota',
            'drive_url' => 'required|url',
        ]);

        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        if ($request->jenis_file === 'spt') {
            $this->hapusFileLokal($bbm->bbm_spt_file);

            $bbm->update([
                'bbm_spt_file' => $request->drive_url,
                'bbm_spt_sync' => true,
            ]);
        }

        if ($request->jenis_file === 'acc_pimpinan') {
            $this->hapusFileLokal($bbm->bbm_acc_pimpinan_file);

            $bbm->update([
                'bbm_acc_pimpinan_file' => $request->drive_url,
                'bbm_acc_pimpinan_sync' => true,
            ]);
        }

        if ($request->jenis_file === 'nota') {
            $this->hapusFileLokal($bbm->bbm_laporan_nota_file);

            $bbm->update([
                'bbm_laporan_nota_file' => $request->drive_url,
                'bbm_laporan_nota_sync' => true,
            ]);
        }

        return back()->with('success', 'File berhasil disinkronkan.');
    }

    public function sinkronPengajuan($uid)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        if ($bbm->bbm_status_pengajuan !== 'Pengajuan Diterima') {
            return back()->with('error', 'Sinkron hanya bisa dilakukan setelah pengajuan diterima.');
        }

        return back()->with('success', 'File BBM sekarang otomatis tersimpan di Google Drive melalui ArinDrive. Sinkron manual lama tidak diperlukan.');
    }

    private function hapusFileLokal($path)
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $relativePath = parse_url($path, PHP_URL_PATH);
        $relativePath = ltrim($relativePath, '/');

        $fullPath = public_path($relativePath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}