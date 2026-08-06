<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJRealisasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminSPJRequestController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->search);

        $spjs = ModelSPJRealisasi::with(['pagu.unit', 'pagu.program', 'pagu.kegiatan', 'pagu.subKegiatan'])

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query
                        ->where('spj_operator_nama', 'like', "%{$search}%")
                        ->orWhere('spj_operator_nip', 'like', "%{$search}%")
                        ->orWhere('spj_bidang_nama', 'like', "%{$search}%")
                        ->orWhere('spj_uraian', 'like', "%{$search}%")
                        ->orWhere('spj_nominal', 'like', "%{$search}%")
                        ->orWhere('spj_status', 'like', "%{$search}%");
                })

                    ->orWhereHas('pagu.unit', function ($query) use ($search) {
                        $query->where('unit_kode', 'like', "%{$search}%")->orWhere('unit_nama', 'like', "%{$search}%");
                    })

                    ->orWhereHas('pagu.program', function ($query) use ($search) {
                        $query->where('program_kode', 'like', "%{$search}%")->orWhere('program_nama', 'like', "%{$search}%");
                    })

                    ->orWhereHas('pagu.kegiatan', function ($query) use ($search) {
                        $query->where('kegiatan_kode', 'like', "%{$search}%")->orWhere('kegiatan_nama', 'like', "%{$search}%");
                    })

                    ->orWhereHas('pagu.subKegiatan', function ($query) use ($search) {
                        $query->where('sub_kegiatan_kode', 'like', "%{$search}%")->orWhere('sub_kegiatan_nama', 'like', "%{$search}%");
                    });
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view('administrator-v2.permintaan-spj.index', compact('spjs'));
    }

    public function toggle(Request $request, $uid)
    {
        $request->validate([
            'spj_catatan_admin' => 'nullable|string',
        ]);

        $spj = ModelSPJRealisasi::where('spj_uid', $uid)->firstOrFail();

        $statusBaru = $spj->spj_status === 'Aktif' ? 'Nonaktif' : 'Aktif';

        $spj->update([
            'spj_status' => $statusBaru,
            'spj_catatan_admin' => $request->spj_catatan_admin,
            'spj_status_by' => session('pegawai_id'),
            'spj_status_by_nama' => session('pegawai_nama'),
            'spj_status_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Status SPJ berhasil diperbarui.');
    }
}