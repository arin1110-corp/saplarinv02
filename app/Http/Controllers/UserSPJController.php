<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJPagu;
use App\Models\ModelSPJRealisasi;
use App\Models\ModelSPJUnit;
use App\Services\ArinDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSPJController extends Controller
{
    public function index()
    {
        $pagus = ModelSPJPagu::with(['unit', 'program', 'kegiatan', 'subKegiatan', 'detail', 'realisasi'])
            ->where('spj_pagu_status', 1)
            ->orderBy('spj_pagu_tahun', 'desc')
            ->orderBy('spj_pagu_unit_id', 'asc')
            ->get();

        $units = ModelSPJUnit::where('unit_status', 1)->orderBy('unit_kode', 'asc')->get();

        return view('user.spj.index', compact('pagus', 'units'));
    }

    public function store(Request $request, $uid, ArinDriveService $arinDrive)
    {
        $request->validate([
            'spj_uraian' => 'required|string',
            'spj_nominal' => 'required|numeric|min:1',
            'spj_tanggal' => 'required|date',
            'spj_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:204800',
        ]);

        $pagu = ModelSPJPagu::with(['unit', 'realisasi'])
            ->where('spj_pagu_uid', $uid)
            ->where('spj_pagu_status', 1)
            ->firstOrFail();

        $totalRealisasi = $pagu->realisasi()->where('spj_status', 'Aktif')->sum('spj_nominal');

        $sisaPagu = $pagu->spj_pagu_final - $totalRealisasi;

        if ((float) $request->spj_nominal > (float) $sisaPagu) {
            return back()->with('error', 'Nominal SPJ melebihi sisa pagu ' . ($pagu->unit->unit_nama ?? '-') . '. Sisa pagu: Rp ' . number_format($sisaPagu, 0, ',', '.'));
        }

        $spjUid = (string) Str::uuid();

        $file = $request->file('spj_file');

        $filename = $spjUid . '_SPJ_' . date('Ymd_His') . '.' . $file->getClientOriginalExtension();

        $spjFile = $arinDrive->uploadSPJ($file, 'spj', $filename, $spjUid);

        ModelSPJRealisasi::create([
            'spj_uid' => $spjUid,
            'spj_pagu_id' => $pagu->spj_pagu_id,
            'spj_uraian' => $request->spj_uraian,
            'spj_nominal' => $request->spj_nominal,
            'spj_tanggal' => $request->spj_tanggal,
            'spj_tanggal_input' => Carbon::now(),
            'spj_file' => $spjFile,

            'spj_operator_id' => session('pegawai_id'),
            'spj_operator_nama' => session('pegawai_nama'),
            'spj_operator_nip' => session('pegawai_nip'),

            'spj_bidang_id' => session('pegawai_bidang_id'),
            'spj_bidang_nama' => session('pegawai_bidang'),

            'spj_status' => 'Aktif',
        ]);

        return back()->with('success', 'SPJ berhasil diinput.');
    }
    private function checkPermission(ModelSPJRealisasi $spj)
    {
        if (session('active_role') == 'Admin') {
            return;
        }

        if (session('active_role') == 'Operator SPJ' && $spj->spj_operator_id == session('pegawai_id')) {
            return;
        }

        abort(403);
    }
    public function edit($uid)
    {
        $spj = ModelSPJRealisasi::where('spj_uid', $uid)->firstOrFail();

        $this->checkPermission($spj);

        return response()->json([
            'success' => true,
            'data' => [
                'spj_uid' => $spj->spj_uid,
                'spj_uraian' => $spj->spj_uraian,
                'spj_nominal' => $spj->spj_nominal,
                'spj_tanggal' => optional($spj->spj_tanggal)->format('Y-m-d'),
                'spj_file' => $spj->spj_file,
            ],
        ]);
    }
    public function update(Request $request, $uid, ArinDriveService $arinDrive)
    {
        $spj = ModelSPJRealisasi::where('spj_uid', $uid)->firstOrFail();

        $this->checkPermission($spj);
        $pagu = ModelSPJPagu::with('realisasi')
            ->findOrFail($spj->spj_pagu_id);

        $request->validate([
            'spj_uraian' => 'required|string',
            'spj_nominal' => 'required|numeric|min:1',
            'spj_tanggal' => 'required|date',
            'spj_file' => 'nullable|file|max:204800',
        ]);
        $totalRealisasi = $pagu->realisasi()
            ->where('spj_status', 'Aktif')
            ->where('spj_id', '!=', $spj->spj_id)
            ->sum('spj_nominal');

        $sisaPagu = $pagu->spj_pagu_final - $totalRealisasi;

        if ((float)$request->spj_nominal > (float)$sisaPagu) {
            return back()->with(
                'error',
                'Nominal SPJ melebihi sisa pagu. Sisa pagu: Rp ' .
                    number_format($sisaPagu, 0, ',', '.')
            );
        }

        $spj->spj_uraian = $request->spj_uraian;
        $spj->spj_nominal = $request->spj_nominal;
        $spj->spj_tanggal = $request->spj_tanggal;

        if ($request->hasFile('spj_file')) {
            $file = $request->file('spj_file');

            $filename = $spj->spj_uid . '_SPJ_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();

            $url = $arinDrive->uploadSPJ($file, 'spj', $filename, $spj->spj_uid);

            $spj->spj_file = $url;
        }

        $spj->save();

        return redirect()->back()->with('success', 'SPJ berhasil diperbarui.');
    }
    public function destroy($uid, ArinDriveService $arinDrive)
    {
        $spj = ModelSPJRealisasi::where('spj_uid', $uid)->firstOrFail();

        $this->checkPermission($spj);

        try {
            $arinDrive->delete($spj->spj_uid);
        } catch (\Throwable $e) {
        }

        $spj->delete();

        return back()->with('success', 'SPJ berhasil dihapus.');
    }
}