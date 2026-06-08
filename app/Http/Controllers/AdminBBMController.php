<?php

namespace App\Http\Controllers;

use App\Models\ModelBBM;
use App\Services\BBMEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\ModelDriveFolder;
use App\Services\GoogleDriveServiceDB;
use Illuminate\Support\Facades\DB;

class AdminBBMController extends Controller
{
    public function index()
    {
        $bbms = ModelBBM::orderBy('created_at', 'desc')->get();

        return view('administrator.bbm.index', compact('bbms'));
    }

    public function terimaPengajuan(Request $request, $uid, BBMEmailService $emailService)
    {
        $request->validate([
            'bbm_acc_pimpinan_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        $accFile = $this->uploadFileToPublic($request->file('bbm_acc_pimpinan_file'), $bbm->bbm_uid, 'acc-pimpinan');

        $bbm->update([
            'bbm_acc_pimpinan_file' => $accFile,
            'bbm_acc_pimpinan_sync' => false,
            'bbm_status_pengajuan' => 'Pengajuan Diterima',
            'bbm_catatan_admin' => null,
        ]);

        $emailService->kirimKePengaju($bbm, 'Pengajuan BBM Diterima', "Yth. {$bbm->bbm_pengaju_nama},\n\n" . "Pengajuan BBM Anda telah diterima.\n\n" . "No Plat    : {$bbm->bbm_no_plat}\n" . "Jumlah BBM : {$bbm->bbm_liter} Liter\n" . "Status     : Pengajuan Diterima\n\n" . "Silakan upload laporan nota setelah pencairan atau setelah nota tersedia.\n\n" . 'SAPLARIN');

        return back()->with('success', 'Pengajuan BBM diterima dan dokumen ACC pimpinan berhasil diupload.');
    }

    public function tolakPengajuan(Request $request, $uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        $bbm->update([
            'bbm_status_pengajuan' => 'Pengajuan Ditolak',
            'bbm_catatan_admin' => $request->catatan,
        ]);

        $emailService->kirimKePengaju($bbm, 'Pengajuan BBM Ditolak', "Yth. {$bbm->bbm_pengaju_nama},\n\n" . "Pengajuan BBM Anda ditolak.\n\n" . "No Plat    : {$bbm->bbm_no_plat}\n" . "Jumlah BBM : {$bbm->bbm_liter} Liter\n" . 'Catatan    : ' . ($request->catatan ?? '-') . "\n\n" . 'SAPLARIN');

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

        $emailService->kirimKePengaju($bbm, 'Laporan Nota BBM Diterima', "Yth. {$bbm->bbm_pengaju_nama},\n\n" . "Laporan nota BBM Anda telah diterima.\n\n" . "No Plat      : {$bbm->bbm_no_plat}\n" . "Jumlah BBM   : {$bbm->bbm_liter} Liter\n" . 'Tanggal Nota : ' . ($bbm->bbm_tanggal_nota ? $bbm->bbm_tanggal_nota->format('d/m/Y') : '-') . "\n" . "Status Nota  : Laporan Nota Diterima\n\n" . "Proses pengajuan BBM telah selesai.\n\n" . 'SAPLARIN');

        return back()->with('success', 'Laporan nota diterima.');
    }

    public function tolakLaporan(Request $request, $uid, BBMEmailService $emailService)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        $bbm->update([
            'bbm_status_laporan' => 'Laporan Nota Ditolak',
            'bbm_catatan_admin' => $request->catatan,
        ]);

        $emailService->kirimKePengaju($bbm, 'Laporan Nota BBM Ditolak', "Yth. {$bbm->bbm_pengaju_nama},\n\n" . "Laporan nota BBM Anda ditolak.\n\n" . "No Plat    : {$bbm->bbm_no_plat}\n" . "Jumlah BBM : {$bbm->bbm_liter} Liter\n" . 'Catatan    : ' . ($request->catatan ?? '-') . "\n\n" . "Silakan perbaiki dan upload kembali laporan nota.\n\n" . 'SAPLARIN');

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

        return back()->with('success', 'File berhasil disinkronkan ke Google Drive dan file lokal dihapus.');
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

    private function hapusFileLokal($path)
{
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'http')) {
            return;
        }

        $relativePath = parse_url($path, PHP_URL_PATH);
        $relativePath = ltrim($relativePath, '/');

        $fullPath = public_path($relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    public function sinkronPengajuan($uid, GoogleDriveServiceDB $googleDrive)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)->firstOrFail();

        if ($bbm->bbm_status_pengajuan !== 'Pengajuan Diterima') {
            return back()->with('error', 'Sinkron hanya bisa dilakukan setelah pengajuan diterima.');
        }

        $folder = ModelDriveFolder::with('json')->where('folder_prefix', 'bbm')->where('folder_status', 1)->first();

        if (!$folder) {
            return back()->with('error', 'Folder Drive BBM belum diatur.');
        }

        if (!$folder->json) {
            return back()->with('error', 'JSON Credential Drive belum diatur.');
        }

        $jsonPath = storage_path('app/' . $folder->json->json_file);

        if (!file_exists($jsonPath)) {
            return back()->with('error', 'File JSON Credential tidak ditemukan: ' . $jsonPath);
        }

        $googleDrive->setCredential($jsonPath);

        $hasil = [];

        $hasil[] = $this->sinkronFileByUid($googleDrive, $folder->folder_drive_id, $bbm, 'spt', 'bbm_spt_file', 'bbm_spt_sync', 'SPT');

        $hasil[] = $this->sinkronFileByUid($googleDrive, $folder->folder_drive_id, $bbm, 'acc-pimpinan', 'bbm_acc_pimpinan_file', 'bbm_acc_pimpinan_sync', 'ACC Pimpinan');

        if ($bbm->bbm_laporan_nota_file) {
            $hasil[] = $this->sinkronFileByUid($googleDrive, $folder->folder_drive_id, $bbm, 'nota', 'bbm_laporan_nota_file', 'bbm_laporan_nota_sync', 'Nota');
        }

        return back()->with('success', implode(' | ', $hasil));
    }
    private function sinkronFileByUid(GoogleDriveServiceDB $googleDrive, $folderId, ModelBBM $bbm, $jenis, $fieldFile, $fieldSync, $label)
    {
        if (!$bbm->{$fieldFile}) {
            return "{$label}: file belum ada";
        }

        if ($bbm->{$fieldSync}) {
            return "{$label}: sudah sinkron";
        }

        if (str_starts_with($bbm->{$fieldFile}, 'http')) {
            $bbm->update([
                $fieldSync => 1,
            ]);

            return "{$label}: sudah berupa URL Drive";
        }

        $keyword = $bbm->bbm_uid . '-' . $jenis;

        $result = $googleDrive->findFileByKeyword($keyword, $folderId);

        if (($result['status'] ?? 0) != 1) {
            return "{$label}: belum ditemukan. Keyword: {$keyword}. Pesan: " . ($result['message'] ?? '-');
        }

        $oldFile = $bbm->{$fieldFile};

        DB::transaction(function () use ($bbm, $fieldFile, $fieldSync, $result, $oldFile) {
            $bbm->update([
                $fieldFile => $result['file_url'],
                $fieldSync => 1,
            ]);

            $this->hapusFileLokal($oldFile);
        });

        return "{$label}: berhasil sinkron dan file hosting dihapus";
    }
}