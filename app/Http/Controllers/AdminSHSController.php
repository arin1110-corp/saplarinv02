<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\ModelSHS;
use Illuminate\Http\Request;

class AdminSHSController extends Controller
{
    public function index()
    {
        $shs = ModelSHS::orderByDesc('created_at')->get();

        return view(
            'administrator.shs.index',
            compact('shs')
        );
    }

    public function show($uid)
    {
        $shs = ModelSHS::where(
            'shs_uid',
            $uid
        )->firstOrFail();

        return response()->json($shs);
    }

    public function verifikasi(Request $request, $uid)
    {
        $request->validate([

            'shs_catatan_admin' => 'nullable|string'

        ]);

        $shs = ModelSHS::where(
            'shs_uid',
            $uid
        )->firstOrFail();

        $shs->update([

            'shs_status' => 'Diverifikasi',

            'shs_catatan_admin' => $request->shs_catatan_admin,

            'shs_verifikasi_at' => now(),

            'shs_verifikasi_nama' => session('admin_nama'),

            'shs_verifikasi_nip' => session('admin_nip'),

            'shs_verifikasi_jabatan' => session('admin_jabatan'),

            'shs_verifikasi_bidang' => session('admin_bidang'),

        ]);

        return back()->with(
            'success',
            'Usulan SHS berhasil diverifikasi.'
        );
    }

    public function aktif($uid)
    {
        $shs = ModelSHS::where(
            'shs_uid',
            $uid
        )->firstOrFail();

        $shs->update([

            'shs_status' => 'Aktif'

        ]);

        return back()->with(
            'success',
            'SHS berhasil diaktifkan.'
        );
    }

    public function nonaktif(Request $request, $uid)
    {
        $shs = ModelSHS::where(
            'shs_uid',
            $uid
        )->firstOrFail();

        $shs->update([

            'shs_status' => 'Nonaktif',

            'shs_catatan_admin' => $request->shs_catatan_admin

        ]);

        return back()->with(
            'success',
            'SHS berhasil dinonaktifkan.'
        );
    }
}