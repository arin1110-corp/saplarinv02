<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\ModelSHSSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSHSSatuanController extends Controller
{
    public function index()
    {
        $satuan = ModelSHSSatuan::orderBy('satuan_nama')->get();

        return view(
            'administrator.shs-satuan.index',
            compact('satuan')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'satuan_nama' => 'required|string|max:100|unique:saplarin_shs_satuan,satuan_nama',
        ]);

        ModelSHSSatuan::create([

            'satuan_uid' => Str::uuid(),

            'satuan_nama' => $request->satuan_nama,

            'satuan_status' => true,

            'created_by' => session('admin_nama'),

        ]);

        return back()->with(
            'success',
            'Satuan berhasil ditambahkan.'
        );
    }

    public function update(Request $request, $uid)
    {
        $request->validate([
            'satuan_nama' =>
                'required|string|max:100|unique:saplarin_shs_satuan,satuan_nama,' .
                ModelSHSSatuan::where('satuan_uid', $uid)->value('satuan_id') .
                ',satuan_id',
        ]);

        $data = ModelSHSSatuan::where(
            'satuan_uid',
            $uid
        )->firstOrFail();

        $data->update([

            'satuan_nama' => $request->satuan_nama,

            'updated_by' => session('admin_nama'),

        ]);

        return back()->with(
            'success',
            'Satuan berhasil diperbarui.'
        );
    }
    public function status($uid)
    {
        $data = ModelSHSSatuan::where('satuan_uid', $uid)->firstOrFail();

        $data->satuan_status = !$data->satuan_status;

        $data->save();

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function aktif($uid)
    {
        ModelSHSSatuan::where(
            'satuan_uid',
            $uid
        )->update([

            'satuan_status' => true,

            'updated_by' => session('admin_nama'),

        ]);

        return back()->with(
            'success',
            'Satuan berhasil diaktifkan.'
        );
    }

    public function nonaktif($uid)
    {
        ModelSHSSatuan::where(
            'satuan_uid',
            $uid
        )->update([

            'satuan_status' => false,

            'updated_by' => session('admin_nama'),

        ]);

        return back()->with(
            'success',
            'Satuan berhasil dinonaktifkan.'
        );
    }
}