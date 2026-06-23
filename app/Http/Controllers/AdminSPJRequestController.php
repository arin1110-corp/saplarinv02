<?php

namespace App\Http\Controllers;

use App\Models\ModelSPJRealisasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminSPJRequestController extends Controller
{
    public function index()
    {
        $spjs = ModelSPJRealisasi::with([
                'pagu.unit',
                'pagu.program',
                'pagu.kegiatan',
                'pagu.subKegiatan',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrator.spj.permintaan', compact('spjs'));
    }

    public function toggle(Request $request, $uid)
    {
        $request->validate([
            'spj_catatan_admin' => 'nullable|string',
        ]);

        $spj = ModelSPJRealisasi::where('spj_uid', $uid)
            ->firstOrFail();

        $statusBaru = $spj->spj_status === 'Aktif'
            ? 'Nonaktif'
            : 'Aktif';

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