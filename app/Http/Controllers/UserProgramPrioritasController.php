<?php

namespace App\Http\Controllers;

use App\Models\ModelProgramPrioritas;
use App\Models\ModelProgramPrioritasRencana;
use App\Models\ModelProgramPrioritasCapaian;
use App\Models\ModelProgramPrioritasCapaianFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserProgramPrioritasController extends Controller
{
    public function index()
    {
        $prioritas = ModelProgramPrioritas::with(['rencana.capaian.files'])
            ->where('prioritas_status', 'Aktif')
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.program-prioritas.index', compact('prioritas'));
    }

    public function storeRencana(Request $request, $uid)
    {
        $request->validate([
            'rencana_judul' => 'required|string|max:255',
            'rencana_target' => 'required|integer|min:1',
        ]);

        $prioritas = ModelProgramPrioritas::where('prioritas_uid', $uid)->where('prioritas_status', 'Aktif')->orderBy('created_at', 'asc')->firstOrFail();

        ModelProgramPrioritasRencana::create([
            'rencana_uid' => Str::uuid(),
            'rencana_prioritas_id' => $prioritas->prioritas_id,
            'rencana_judul' => $request->rencana_judul,
            'rencana_target' => $request->rencana_target,
            'rencana_user_id' => session('pegawai_id'),
            'rencana_user_nama' => session('pegawai_nama'),
            'rencana_user_nip' => session('pegawai_nip'),
            'rencana_bidang_id' => session('pegawai_bidang_id'),
            'rencana_bidang_nama' => session('pegawai_bidang'),
            'rencana_status' => 'Aktif',
        ]);

        return back()->with('success', 'Rencana aksi berhasil ditambahkan.');
    }

    public function storeCapaian(Request $request, $uid)
    {
        $request->validate([
            'capaian_judul' => 'required|string|max:255',
            'capaian_deskripsi' => 'required|string',
            'capaian_jumlah' => 'required|integer|min:1',
            'capaian_tanggal_mulai' => 'required|date',
            'capaian_tanggal_selesai' => 'required|date|after_or_equal:capaian_tanggal_mulai',
            'capaian_file' => 'required|array|max:5',
            'capaian_file.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $rencana = ModelProgramPrioritasRencana::where('rencana_uid', $uid)->where('rencana_status', 'Aktif')->firstOrFail();

        $capaian = ModelProgramPrioritasCapaian::create([
            'capaian_uid' => Str::uuid(),
            'capaian_rencana_id' => $rencana->rencana_id,
            'capaian_judul' => $request->capaian_judul,
            'capaian_deskripsi' => $request->capaian_deskripsi,
            'capaian_tanggal_mulai' => $request->capaian_tanggal_mulai,
            'capaian_tanggal_selesai' => $request->capaian_tanggal_selesai,
            'capaian_user_id' => session('pegawai_id'),
            'capaian_user_nama' => session('pegawai_nama'),
            'capaian_user_nip' => session('pegawai_nip'),
            'capaian_bidang_id' => session('pegawai_bidang_id'),
            'capaian_bidang_nama' => session('pegawai_bidang'),
            'capaian_status' => 'Aktif',
            'capaian_jumlah' => $request->capaian_jumlah,
        ]);

        foreach ($request->file('capaian_file') as $file) {
            $path = $this->uploadFile($file, $capaian->capaian_uid);

            ModelProgramPrioritasCapaianFile::create([
                'file_capaian_id' => $capaian->capaian_id,
                'file_path' => $path,
                'file_nama_asli' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('success', 'Capaian berhasil ditambahkan.');
    }

    private function uploadFile($file, $uid)
    {
        $folder = public_path('assets/program-prioritas');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $extension = $file->getClientOriginalExtension();

        $filename = $uid . '-capaian-' . date('Ymd') . '-' . time() . '-' . rand(100, 999) . '.' . $extension;

        $file->move($folder, $filename);

        return 'assets/program-prioritas/' . $filename;
    }
}