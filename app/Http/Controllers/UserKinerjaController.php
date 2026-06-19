<?php

namespace App\Http\Controllers;

use App\Models\ModelKinerja;
use App\Models\ModelKinerjaProgress;
use App\Models\ModelKinerjaProgressBukti;
use App\Services\ArinDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserKinerjaController extends Controller
{
    public function index()
    {
        $bidangId = session('pegawai_bidang_id');
        $bidangNama = session('pegawai_bidang');

        $kinerjas = ModelKinerja::with([
                'progress.bukti',
                'progressTerbaru'
            ])
            ->where('kinerja_status', 'Aktif')
            ->where(function ($query) use ($bidangId, $bidangNama) {
                if (!empty($bidangId)) {
                    $query->where('kinerja_bidang_id', $bidangId);
                }

                if (!empty($bidangNama)) {
                    $query->orWhere('kinerja_bidang_nama', $bidangNama);
                }
            })
            ->orderBy('kinerja_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.kinerja.index', compact('kinerjas'));
    }

    public function storeProgress(Request $request, $uid, ArinDriveService $arinDrive)
    {
        $request->validate([
            'progress_tanggal_mulai' => 'required|date',
            'progress_tanggal_selesai' => 'required|date|after_or_equal:progress_tanggal_mulai',
            'progress_persentase' => 'required|numeric|min:0.01|max:100',
            'progress_keterangan' => 'nullable|string',
            'bukti_file' => 'required|array|max:5',
            'bukti_file.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $bidangId = session('pegawai_bidang_id');
        $bidangNama = session('pegawai_bidang');

        $kinerja = ModelKinerja::where('kinerja_uid', $uid)
            ->where('kinerja_status', 'Aktif')
            ->where(function ($query) use ($bidangId, $bidangNama) {
                if (!empty($bidangId)) {
                    $query->where('kinerja_bidang_id', $bidangId);
                }

                if (!empty($bidangNama)) {
                    $query->orWhere('kinerja_bidang_nama', $bidangNama);
                }
            })
            ->firstOrFail();

        $totalDiterima = ModelKinerjaProgress::where('progress_kinerja_id', $kinerja->kinerja_id)
            ->where('progress_status', 'Diterima')
            ->sum('progress_persentase');

        $totalMenunggu = ModelKinerjaProgress::where('progress_kinerja_id', $kinerja->kinerja_id)
            ->where('progress_status', 'Menunggu Verifikasi')
            ->sum('progress_persentase');

        $totalAkanMasuk = $totalDiterima + $totalMenunggu + $request->progress_persentase;

        if ($totalAkanMasuk > 100) {
            return back()->with(
                'error',
                'Total progress tidak boleh melebihi 100%. Total saat ini termasuk menunggu verifikasi: ' .
                    number_format($totalDiterima + $totalMenunggu, 2, ',', '.') . '%.'
            );
        }

        $triwulan = $this->getTriwulan($request->progress_tanggal_selesai);
        $progressUid = (string) Str::uuid();

        $progress = ModelKinerjaProgress::create([
            'progress_uid' => $progressUid,
            'progress_kinerja_id' => $kinerja->kinerja_id,
            'progress_tanggal_mulai' => $request->progress_tanggal_mulai,
            'progress_tanggal_selesai' => $request->progress_tanggal_selesai,
            'progress_triwulan' => $triwulan,
            'progress_persentase' => $request->progress_persentase,
            'progress_keterangan' => $request->progress_keterangan,
            'progress_status' => 'Menunggu Verifikasi',
            'progress_user_id' => session('pegawai_id'),
            'progress_user_nama' => session('pegawai_nama'),
            'progress_user_nip' => session('pegawai_nip'),
            'progress_bidang_id' => session('pegawai_bidang_id'),
            'progress_bidang_nama' => session('pegawai_bidang'),
        ]);

        foreach ($request->file('bukti_file') as $file) {
            $filename = $progress->progress_uid .
                '_BUKTI_KINERJA_' .
                date('Ymd_His') .
                '_' .
                rand(100, 999) .
                '.' .
                $file->getClientOriginalExtension();

            $path = $arinDrive->upload(
                $file,
                'kinerja',
                $filename,
                $progress->progress_uid
            );

            ModelKinerjaProgressBukti::create([
                'bukti_progress_id' => $progress->progress_id,
                'bukti_file' => $path,
                'bukti_nama_file' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('success', 'Progress berhasil dikirim. Progress masuk ' . $triwulan . ' dan menunggu verifikasi.');
    }

    public function updateProgress(Request $request, $uid, ArinDriveService $arinDrive)
    {
        $request->validate([
            'progress_tanggal_mulai' => 'required|date',
            'progress_tanggal_selesai' => 'required|date|after_or_equal:progress_tanggal_mulai',
            'progress_persentase' => 'required|numeric|min:0.01|max:100',
            'progress_keterangan' => 'nullable|string',
            'bukti_file' => 'nullable|array|max:5',
            'bukti_file.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $progress = ModelKinerjaProgress::with('kinerja')
            ->where('progress_uid', $uid)
            ->where('progress_user_id', session('pegawai_id'))
            ->firstOrFail();

        if ($progress->progress_status !== 'Menunggu Verifikasi') {
            return back()->with('error', 'Progress yang sudah diverifikasi tidak bisa diedit.');
        }

        $totalLain = ModelKinerjaProgress::where('progress_kinerja_id', $progress->progress_kinerja_id)
            ->where('progress_id', '!=', $progress->progress_id)
            ->whereIn('progress_status', ['Diterima', 'Menunggu Verifikasi'])
            ->sum('progress_persentase');

        if (($totalLain + $request->progress_persentase) > 100) {
            return back()->with(
                'error',
                'Total progress tidak boleh melebihi 100%. Total progress lain: ' .
                    number_format($totalLain, 2, ',', '.') . '%.'
            );
        }

        $triwulan = $this->getTriwulan($request->progress_tanggal_selesai);

        $progress->update([
            'progress_tanggal_mulai' => $request->progress_tanggal_mulai,
            'progress_tanggal_selesai' => $request->progress_tanggal_selesai,
            'progress_triwulan' => $triwulan,
            'progress_persentase' => $request->progress_persentase,
            'progress_keterangan' => $request->progress_keterangan,
        ]);

        if ($request->hasFile('bukti_file')) {
            foreach ($request->file('bukti_file') as $file) {
                $filename = $progress->progress_uid .
                    '_BUKTI_KINERJA_' .
                    date('Ymd_His') .
                    '_' .
                    rand(100, 999) .
                    '.' .
                    $file->getClientOriginalExtension();

                $path = $arinDrive->upload(
                    $file,
                    'kinerja',
                    $filename,
                    $progress->progress_uid
                );

                ModelKinerjaProgressBukti::create([
                    'bukti_progress_id' => $progress->progress_id,
                    'bukti_file' => $path,
                    'bukti_nama_file' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'Progress berhasil diperbarui dan tetap menunggu verifikasi.');
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