<?php

namespace App\Http\Controllers;

use App\Models\ModelBBM;
use App\Services\BBMEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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

        $accFile = $this->uploadFileToArinDrive(
            $request->file('bbm_acc_pimpinan_file'),
            $bbm->bbm_uid,
            'acc-pimpinan'
        );

        $bbm->update([
            'bbm_acc_pimpinan_file' => $accFile['url'],
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

        return back()->with('success', 'Pengajuan BBM diterima dan dokumen ACC pimpinan berhasil diupload ke ArinDrive.');
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

        return back()->with('success', 'File BBM sekarang otomatis tersimpan di ArinDrive. Sinkron manual lama tidak diperlukan.');
    }

    private function uploadFileToArinDrive($file, $uid, $jenis, $tanggalNota = null)
    {
        $tanggal = $tanggalNota
            ? date('Ymd', strtotime($tanggalNota))
            : date('Ymd');

        $filename = $uid . '-' . $jenis . '-' . $tanggal . '.' . $file->getClientOriginalExtension();

        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->attach(
                'file',
                fopen($file->getRealPath(), 'r'),
                $filename
            )
            ->post(env('ARINDRIVE_URL') . '/api/upload', [
                'group' => env('ARINDRIVE_GROUP', 'kantor'),
                'source_app' => 'saplarin',
                'folder' => 'bbm/' . $jenis,
                'reference_id' => $uid,
                'jenis' => $jenis,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Gagal upload ke ArinDrive: ' . $response->body());
        }

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            throw new \Exception($result['message'] ?? 'Upload ArinDrive gagal.');
        }

        return [
            'file_id' => $result['data']['file_id'],
            'url' => $result['data']['url'],
            'name' => $result['data']['name'],
        ];
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