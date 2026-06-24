<?php

namespace App\Http\Controllers;

use App\Models\ModelPrioritas;
use App\Models\ModelPrioritasBukti;
use App\Models\ModelPrioritasBuktiFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
            'bukti_file.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:204800',
        ]);

        $prioritas = ModelPrioritas::where('prioritas_uid', $uid)
            ->where('prioritas_status', 'Aktif')
            ->firstOrFail();

        $buktiUid = (string) Str::uuid();

        $bukti = ModelPrioritasBukti::create([
            'bukti_uid' => $buktiUid,
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
            $uploaded = $this->uploadFileToArinDrive(
                $file,
                $bukti->bukti_uid,
                'bukti-prioritas',
                'prioritas/bukti'
            );

            ModelPrioritasBuktiFile::create([
                'file_bukti_id' => $bukti->bukti_id,
                'file_path' => $uploaded['url'],
                'file_nama_asli' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('success', 'Bukti dukung prioritas berhasil ditambahkan.');
    }

    private function uploadFileToArinDrive($file, $uid, $jenis, $folder)
    {
        $filename = $uid . '-' . $jenis . '-' . date('Ymd') . '-' . time() . '-' . rand(100, 999) . '.' . $file->getClientOriginalExtension();

        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->attach('file', fopen($file->getRealPath(), 'r'), $filename)
            ->post(env('ARINDRIVE_URL') . '/api/upload', [
                'group' => env('ARINDRIVE_GROUP', 'kantor'),
                'source_app' => 'saplarin',
                'folder' => $folder,
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
}