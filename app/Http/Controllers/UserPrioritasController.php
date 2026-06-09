<?php

namespace App\Http\Controllers;

use App\Models\ModelPrioritas;
use App\Models\ModelPrioritasBukti;
use App\Models\ModelPrioritasBuktiFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserPrioritasController extends Controller
{
    public function index()
    {
        $prioritas = ModelPrioritas::with(['bukti.files'])
            ->where('prioritas_status', 'Aktif')
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.prioritas.index', compact('prioritas'));
    }

    public function storeBukti(Request $request, $uid)
    {
        $request->validate([
            'bukti_deskripsi_kegiatan' => 'required|string',
            'bukti_tanggal_kegiatan' => 'required|date',
            'bukti_file' => 'required|array|max:5',
            'bukti_file.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $prioritas = ModelPrioritas::where('prioritas_uid', $uid)
            ->where('prioritas_status', 'Aktif')
            ->firstOrFail();

        $bukti = ModelPrioritasBukti::create([
            'bukti_uid' => Str::uuid(),
            'bukti_prioritas_id' => $prioritas->prioritas_id,

            'bukti_op_id' => session('pegawai_id'),
            'bukti_op_bidang' => session('pegawai_bidang'),

            'bukti_deskripsi_kegiatan' => $request->bukti_deskripsi_kegiatan,
            'bukti_tanggal_kegiatan' => $request->bukti_tanggal_kegiatan,

            'bukti_user_id' => session('pegawai_id'),
            'bukti_user_nama' => session('pegawai_nama'),
            'bukti_user_nip' => session('pegawai_nip'),

            'bukti_bidang_id' => session('pegawai_bidang_id'),
            'bukti_bidang_nama' => session('pegawai_bidang'),

            'bukti_status' => 'Aktif',
        ]);

        foreach ($request->file('bukti_file') as $file) {
            $path = $this->uploadFile($file, $bukti->bukti_uid);

            ModelPrioritasBuktiFile::create([
                'file_bukti_id' => $bukti->bukti_id,
                'file_path' => $path,
                'file_nama_asli' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('success', 'Bukti dukung prioritas berhasil ditambahkan.');
    }

    private function uploadFile($file, $uid)
    {
        $folder = public_path('assets/prioritas');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $extension = $file->getClientOriginalExtension();

        $filename = $uid . '-bukti-' . date('Ymd') . '-' . time() . '-' . rand(100, 999) . '.' . $extension;

        $file->move($folder, $filename);

        return 'assets/prioritas/' . $filename;
    }
}