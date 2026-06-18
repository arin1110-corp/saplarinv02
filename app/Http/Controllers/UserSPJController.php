<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJPagu;
use App\Models\ModelSPJRealisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSPJController extends Controller
{
    public function index()
    {
        $pagus = ModelSPJPagu::with([
                'program',
                'kegiatan',
                'subKegiatan',
                'detail',
                'realisasi',
            ])
            ->where('spj_pagu_status', 1)
            ->orderBy('spj_pagu_tahun', 'desc')
            ->get();

        return view('user.spj.index', compact('pagus'));
    }

    public function store(Request $request, $uid)
    {
        $request->validate([
            'spj_uraian' => 'required|string',
            'spj_nominal' => 'required|numeric|min:1',
            'spj_tanggal' => 'required|date',
            'spj_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ]);

        $pagu = ModelSPJPagu::where('spj_pagu_uid', $uid)
            ->where('spj_pagu_status', 1)
            ->firstOrFail();

        $spjUid = (string) Str::uuid();

        $uploaded = $this->uploadFileToArinDrive(
            $request->file('spj_file'),
            $spjUid,
            'spj',
            'spj/realisasi'
        );

        ModelSPJRealisasi::create([
            'spj_uid' => $spjUid,
            'spj_pagu_id' => $pagu->spj_pagu_id,
            'spj_uraian' => $request->spj_uraian,
            'spj_nominal' => $request->spj_nominal,
            'spj_tanggal' => $request->spj_tanggal,
            'spj_tanggal_input' => Carbon::now(),
            'spj_file' => $uploaded['url'],
            'spj_operator_id' => session('pegawai_id'),
            'spj_operator_nama' => session('pegawai_nama'),
            'spj_operator_nip' => session('pegawai_nip'),
            'spj_bidang_id' => session('pegawai_bidang_id'),
            'spj_bidang_nama' => session('pegawai_bidang'),
            'spj_status' => 'Aktif',
        ]);

        return back()->with('success', 'SPJ berhasil diinput.');
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