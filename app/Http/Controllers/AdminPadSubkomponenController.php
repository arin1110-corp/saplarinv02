<?php

namespace App\Http\Controllers;

use App\Models\ModelPadKomponen;
use App\Models\ModelPadSubkomponen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPadSubkomponenController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));

        $komponen = ModelPadKomponen::where('pad_komponen_status', true)->orderBy('pad_komponen_nama')->get();

        $subkomponen = ModelPadSubkomponen::with('komponen')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pad_subkomponen_nama', 'like', '%' . $search . '%')

                        ->orWhere('pad_subkomponen_kode', 'like', '%' . $search . '%')

                        ->orWhereHas('komponen', function ($komponenQuery) use ($search) {
                            $komponenQuery->where('pad_komponen_nama', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('pad_subkomponen_nama')
            ->paginate(10)
            ->withQueryString();

        return view('administrator-v2.pad.subkomponen.index', compact('subkomponen', 'komponen', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pad_subkomponen_komponen' => 'required|exists:saplarin_pad_komponen,pad_komponen_id',

            'pad_subkomponen_kode' => 'nullable|string|max:100',

            'pad_subkomponen_nama' => 'required|string|max:255',

            'pad_subkomponen_keterangan' => 'nullable|string',
        ]);

        ModelPadSubkomponen::create([
            'pad_subkomponen_uid' => (string) Str::uuid(),

            'pad_subkomponen_komponen' => $request->pad_subkomponen_komponen,

            'pad_subkomponen_kode' => $request->pad_subkomponen_kode,

            'pad_subkomponen_nama' => $request->pad_subkomponen_nama,

            'pad_subkomponen_keterangan' => $request->pad_subkomponen_keterangan,

            'pad_subkomponen_status' => true,
        ]);

        return back()->with('success', 'Subkomponen berhasil ditambahkan.');
    }

    public function update(Request $request, $uid)
    {
        $subkomponen = ModelPadSubkomponen::where('pad_subkomponen_uid', $uid)->firstOrFail();

        $request->validate([
            'pad_subkomponen_komponen' => 'required|exists:saplarin_pad_komponen,pad_komponen_id',

            'pad_subkomponen_kode' => 'nullable|string|max:100',

            'pad_subkomponen_nama' => 'required|string|max:255',

            'pad_subkomponen_keterangan' => 'nullable|string',
        ]);

        $subkomponen->update([
            'pad_subkomponen_komponen' => $request->pad_subkomponen_komponen,

            'pad_subkomponen_kode' => $request->pad_subkomponen_kode,

            'pad_subkomponen_nama' => $request->pad_subkomponen_nama,

            'pad_subkomponen_keterangan' => $request->pad_subkomponen_keterangan,
        ]);

        return back()->with('success', 'Subkomponen berhasil diperbarui.');
    }

    public function toggle($uid)
    {
        $subkomponen = ModelPadSubkomponen::where('pad_subkomponen_uid', $uid)->firstOrFail();

        $subkomponen->update([
            'pad_subkomponen_status' => !$subkomponen->pad_subkomponen_status,
        ]);

        return back()->with('success', 'Status subkomponen berhasil diubah.');
    }
}