<?php

namespace App\Http\Controllers;

use App\Models\ModelBBM;
use App\Services\ArinDriveService;
use App\Services\BBMEmailService;
use Illuminate\Http\Request;
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

    public function store(Request $request, BBMEmailService $emailService, ArinDriveService $arinDrive)
    {
        $request->validate([
            'bbm_no_plat' => 'required|string|max:50',
            'bbm_uraian_kegiatan' => 'required|string',
            'bbm_liter' => 'required|numeric|min:0.01',
            'bbm_spt_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'bbm_foto_mobil_file' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
            'bbm_bukti_tambahan.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx|max:5120',
        ]);

        $uid = (string) Str::uuid();

        $sptFile = $arinDrive->upload(
            $request->file('bbm_spt_file'),
            'bbm_spt',
            $uid . '_SPT.' . $request->file('bbm_spt_file')->getClientOriginalExtension(),
            $uid
        );

        $fotoMobilFile = $arinDrive->upload(
            $request->file('bbm_foto_mobil_file'),
            'bbm_foto_mobil',
            $uid . '_FOTO_MOBIL.' . $request->file('bbm_foto_mobil_file')->getClientOriginalExtension(),
            $uid
        );

        $buktiTambahan = $this->uploadBuktiTambahan(
            $request,
            $arinDrive,
            $uid,
            'PENGAJUAN'
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
            'bbm_spt_sync' => true,

            'bbm_foto_mobil_file' => $fotoMobilFile,
            'bbm_foto_mobil_sync' => true,

            'bbm_bukti_tambahan_file' => $buktiTambahan,
            'bbm_bukti_tambahan_sync' => count($buktiTambahan) > 0,

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
                "Status       : {$bbm->bbm_status_pengajuan}\n" .
                "Bukti Tambahan: " . count($buktiTambahan) . " file\n\n" .
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
            ->with('success', 'Pengajuan BBM berhasil dikirim.');
    }

    public function show($uid)
    {
        $bbm = ModelBBM::where('bbm_uid', $uid)
            ->where('bbm_pengaju_id', session('pegawai_id'))
            ->firstOrFail();

        return view('user.bbm.show', compact('bbm'));
    }

    public function uploadLaporan(Request $request, $uid, BBMEmailService $emailService, ArinDriveService $arinDrive)
    {
        $request->validate([
            'bbm_tanggal_nota' => 'required|date',
            'bbm_laporan_nota_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'bbm_bukti_tambahan.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx|max:5120',
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

        $tanggal = date('Ymd', strtotime($request->bbm_tanggal_nota));

        $notaFile = $arinDrive->upload(
            $request->file('bbm_laporan_nota_file'),
            'bbm_nota',
            $bbm->bbm_uid . '_NOTA_' . $tanggal . '.' . $request->file('bbm_laporan_nota_file')->getClientOriginalExtension(),
            $bbm->bbm_uid
        );

        $buktiLama = $bbm->bbm_bukti_tambahan_file ?? [];

        if (is_string($buktiLama)) {
            $buktiLama = json_decode($buktiLama, true) ?: [];
        }

        if (!is_array($buktiLama)) {
            $buktiLama = [];
        }

        $buktiBaru = $this->uploadBuktiTambahan(
            $request,
            $arinDrive,
            $bbm->bbm_uid,
            'NOTA'
        );

        $buktiGabungan = array_merge($buktiLama, $buktiBaru);

        $bbm->update([
            'bbm_tanggal_nota' => $request->bbm_tanggal_nota,
            'bbm_laporan_nota_file' => $notaFile,
            'bbm_laporan_nota_sync' => true,
            'bbm_bukti_tambahan_file' => $buktiGabungan,
            'bbm_bukti_tambahan_sync' => count($buktiGabungan) > 0,
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
                "Status Nota  : Menunggu Verifikasi\n" .
                "Bukti Tambahan Baru: " . count($buktiBaru) . " file\n\n" .
                "Silakan login ke SAPLARIN untuk melakukan verifikasi laporan nota.\n\n" .
                "SAPLARIN"
        );

        return back()->with('success', 'Laporan nota berhasil diupload.');
    }

    private function uploadBuktiTambahan(Request $request, ArinDriveService $arinDrive, string $uid, string $jenis): array
    {
        $buktiTambahan = [];

        if (!$request->hasFile('bbm_bukti_tambahan')) {
            return $buktiTambahan;
        }

        foreach ($request->file('bbm_bukti_tambahan') as $index => $file) {
            if (!$file) {
                continue;
            }

            $namaAsli = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $url = $arinDrive->upload(
                $file,
                'bbm_bukti',
                $uid . '_BUKTI_' . $jenis . '_' . ($index + 1) . '_' . time() . '.' . $extension,
                $uid
            );

            $buktiTambahan[] = [
                'jenis' => $jenis,
                'nama' => $namaAsli,
                'file' => $url,
                'uploaded_at' => now()->format('Y-m-d H:i:s'),
            ];
        }

        return $buktiTambahan;
    }
}