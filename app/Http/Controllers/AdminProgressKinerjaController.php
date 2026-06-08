<?php

namespace App\Http\Controllers;

use App\Models\ModelProgressKinerja;
use App\Models\ModelProgressBukti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminProgressKinerjaController extends Controller
{
    public function index()
    {
        $progress = ModelProgressKinerja::with('buktis')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrator.progress.index', compact('progress'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'progress_bidang_id' => 'nullable',
            'progress_bidang_nama' => 'required|string|max:255',
            'progress_kegiatan' => 'required|string|max:255',
            'progress_deskripsi' => 'nullable|string',
            'progress_tanggal_mulai' => 'required|date',
            'progress_tanggal_selesai' => 'required|date|after_or_equal:progress_tanggal_mulai',
            'progress_persentase' => 'required|numeric|min:0|max:100',
            'bukti_admin' => 'nullable|array|max:5',
            'bukti_admin.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $uid = (string) Str::uuid();

        $progress = ModelProgressKinerja::create([
            'progress_uid' => $uid,

            'progress_bidang_id' => $request->progress_bidang_id,
            'progress_bidang_nama' => $request->progress_bidang_nama,

            'progress_kegiatan' => $request->progress_kegiatan,
            'progress_deskripsi' => $request->progress_deskripsi,

            'progress_tanggal_mulai' => $request->progress_tanggal_mulai,
            'progress_tanggal_selesai' => $request->progress_tanggal_selesai,

            'progress_persentase' => $request->progress_persentase,
            'progress_status' => 'Belum Diisi',

            'progress_created_by' => session('pegawai_id'),
            'progress_created_by_nama' => session('pegawai_nama'),
        ]);

        if ($request->hasFile('bukti_admin')) {
            foreach ($request->file('bukti_admin') as $file) {
                $path = $this->uploadBukti($file, $uid, 'admin');

                ModelProgressBukti::create([
                    'bukti_progress_id' => $progress->progress_id,
                    'bukti_file' => $path,
                    'bukti_nama_file' => $file->getClientOriginalName(),
                    'bukti_tipe' => 'admin',
                    'bukti_upload_by' => session('pegawai_id'),
                    'bukti_upload_by_nama' => session('pegawai_nama'),
                ]);
            }
        }

        return back()->with('success', 'Data progress kinerja berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'progress_id' => 'required|exists:saplarin_progress_kinerja,progress_id',
            'progress_bidang_id' => 'nullable',
            'progress_bidang_nama' => 'required|string|max:255',
            'progress_kegiatan' => 'required|string|max:255',
            'progress_deskripsi' => 'nullable|string',
            'progress_tanggal_mulai' => 'required|date',
            'progress_tanggal_selesai' => 'required|date|after_or_equal:progress_tanggal_mulai',
            'progress_persentase' => 'required|numeric|min:0|max:100',
        ]);

        $progress = ModelProgressKinerja::findOrFail($request->progress_id);

        $progress->update([
            'progress_bidang_id' => $request->progress_bidang_id,
            'progress_bidang_nama' => $request->progress_bidang_nama,
            'progress_kegiatan' => $request->progress_kegiatan,
            'progress_deskripsi' => $request->progress_deskripsi,
            'progress_tanggal_mulai' => $request->progress_tanggal_mulai,
            'progress_tanggal_selesai' => $request->progress_tanggal_selesai,
            'progress_persentase' => $request->progress_persentase,
        ]);

        return back()->with('success', 'Data progress kinerja berhasil diperbarui.');
    }

    public function delete($uid)
    {
        $progress = ModelProgressKinerja::where('progress_uid', $uid)->firstOrFail();

        $progress->delete();

        return back()->with('success', 'Data progress kinerja berhasil dihapus.');
    }

    private function uploadBukti($file, $uid, $tipe)
    {
        $folder = public_path('assets/progress-kinerja');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $extension = $file->getClientOriginalExtension();

        $filename = $uid . '-' . $tipe . '-' . date('Ymd') . '-' . time() . '-' . rand(100, 999) . '.' . $extension;

        $file->move($folder, $filename);

        return 'assets/progress-kinerja/' . $filename;
    }
}