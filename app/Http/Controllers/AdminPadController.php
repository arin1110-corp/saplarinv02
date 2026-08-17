<?php

namespace App\Http\Controllers;

use App\Models\ModelPadJenis;
use App\Models\ModelPadKomponen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ModelPadTarget;
use Illuminate\Support\Facades\DB;
use App\Models\ModelPadRealisasi;

class AdminPadController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MASTER JENIS PAD
    |--------------------------------------------------------------------------
    */

    public function jenis()
    {
        $jenis = ModelPadJenis::orderBy('pad_jenis_nama')->get();

        return view('administrator-v2.pad.jenis.index', compact('jenis'));
    }

    public function jenisStore(Request $request)
    {
        $request->validate([
            'pad_jenis_kode' => 'nullable|string|max:100',
            'pad_jenis_nama' => 'required|string|max:255',
            'pad_jenis_keterangan' => 'nullable|string',
        ]);

        ModelPadJenis::create([
            'pad_jenis_uid' => (string) Str::uuid(),

            'pad_jenis_kode' => $request->pad_jenis_kode,

            'pad_jenis_nama' => $request->pad_jenis_nama,

            'pad_jenis_keterangan' => $request->pad_jenis_keterangan,

            'pad_jenis_status' => true,
        ]);

        return back()->with('success', 'Jenis PAD berhasil ditambahkan.');
    }

    public function jenisUpdate(Request $request, $uid)
    {
        $request->validate([
            'pad_jenis_kode' => 'nullable|string|max:100',
            'pad_jenis_nama' => 'required|string|max:255',
            'pad_jenis_keterangan' => 'nullable|string',
        ]);

        $jenis = ModelPadJenis::where('pad_jenis_uid', $uid)->firstOrFail();

        $jenis->update([
            'pad_jenis_kode' => $request->pad_jenis_kode,

            'pad_jenis_nama' => $request->pad_jenis_nama,

            'pad_jenis_keterangan' => $request->pad_jenis_keterangan,
        ]);

        return back()->with('success', 'Jenis PAD berhasil diperbarui.');
    }

    public function jenisStatus($uid)
    {
        $jenis = ModelPadJenis::where('pad_jenis_uid', $uid)->firstOrFail();

        $jenis->update([
            'pad_jenis_status' => !$jenis->pad_jenis_status,
        ]);

        return back()->with('success', 'Status jenis PAD berhasil diubah.');
    }

    /*
    |--------------------------------------------------------------------------
    | MASTER KOMPONEN PAD
    |--------------------------------------------------------------------------
    */

    public function komponen()
    {
        $komponen = ModelPadKomponen::with('jenis')->orderBy('pad_komponen_nama')->get();

        $jenis = ModelPadJenis::where('pad_jenis_status', true)->orderBy('pad_jenis_nama')->get();

        return view('administrator-v2.pad.komponen.index', compact('komponen', 'jenis'));
    }

    public function komponenStore(Request $request)
    {
        $request->validate([
            'pad_komponen_jenis' => 'required|exists:saplarin_pad_jenis,pad_jenis_id',

            'pad_komponen_kode' => 'nullable|string|max:100',

            'pad_komponen_nama' => 'required|string|max:255',

            'pad_komponen_keterangan' => 'nullable|string',
        ]);

        ModelPadKomponen::create([
            'pad_komponen_uid' => (string) Str::uuid(),

            'pad_komponen_jenis' => $request->pad_komponen_jenis,

            'pad_komponen_kode' => $request->pad_komponen_kode,

            'pad_komponen_nama' => $request->pad_komponen_nama,

            'pad_komponen_keterangan' => $request->pad_komponen_keterangan,

            'pad_komponen_status' => true,
        ]);

        return back()->with('success', 'Komponen PAD berhasil ditambahkan.');
    }

    public function komponenUpdate(Request $request, $uid)
    {
        $request->validate([
            'pad_komponen_jenis' => 'required|exists:saplarin_pad_jenis,pad_jenis_id',

            'pad_komponen_kode' => 'nullable|string|max:100',

            'pad_komponen_nama' => 'required|string|max:255',

            'pad_komponen_keterangan' => 'nullable|string',
        ]);

        $komponen = ModelPadKomponen::where('pad_komponen_uid', $uid)->firstOrFail();

        $komponen->update([
            'pad_komponen_jenis' => $request->pad_komponen_jenis,

            'pad_komponen_kode' => $request->pad_komponen_kode,

            'pad_komponen_nama' => $request->pad_komponen_nama,

            'pad_komponen_keterangan' => $request->pad_komponen_keterangan,
        ]);

        return back()->with('success', 'Komponen PAD berhasil diperbarui.');
    }

    public function komponenStatus($uid)
    {
        $komponen = ModelPadKomponen::where('pad_komponen_uid', $uid)->firstOrFail();

        $komponen->update([
            'pad_komponen_status' => !$komponen->pad_komponen_status,
        ]);

        return back()->with('success', 'Status komponen PAD berhasil diubah.');
    }
    /*
|--------------------------------------------------------------------------
| TARGET PAD
|--------------------------------------------------------------------------
*/

    public function target(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $targets = ModelPadTarget::with(['jenis', 'komponen'])
            ->where('pad_target_tahun', $tahun)
            ->orderBy('pad_target_unit_nama')
            ->orderBy('pad_target_komponen')
            ->get();

        $jenis = ModelPadJenis::where('pad_jenis_status', true)
            ->with([
                'komponen' => function ($query) {
                    $query->where('pad_komponen_status', true)->orderBy('pad_komponen_nama');
                },
            ])
            ->orderBy('pad_jenis_nama')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | REKAP
    |--------------------------------------------------------------------------
    */

        $totalTarget = $targets->sum('pad_target_nominal');

        $totalRencana = $targets->sum('pad_target_rencana');

        $jumlahUnit = $targets->pluck('pad_target_unit')->unique()->count();

        return view('administrator-v2.pad.target.index', compact('targets', 'jenis', 'tahun', 'totalTarget', 'totalRencana', 'jumlahUnit'));
    }

    public function targetStore(Request $request)
    {
        $request->validate([
            'pad_target_tahun' => 'required|integer|min:2000|max:2100',

            'pad_target_jenis' => 'required|exists:saplarin_pad_jenis,pad_jenis_id',

            'pad_target_komponen' => 'nullable|exists:saplarin_pad_komponen,pad_komponen_id',

            'pad_target_unit' => 'required|string|max:100',

            'pad_target_unit_kode' => 'nullable|string|max:100',

            'pad_target_unit_nama' => 'required|string|max:255',

            'pad_target_nominal' => 'required|numeric|min:0',

            'pad_target_rencana' => 'required|numeric|min:0',

            'pad_target_keterangan' => 'nullable|string',
        ]);

        /*
    |--------------------------------------------------------------------------
    | CEK DUPLIKAT
    |--------------------------------------------------------------------------
    */

        $exists = ModelPadTarget::where('pad_target_tahun', $request->pad_target_tahun)->where('pad_target_komponen', $request->pad_target_komponen)->where('pad_target_unit', $request->pad_target_unit)->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Target untuk komponen dan unit tersebut sudah tersedia pada tahun tersebut.');
        }

        ModelPadTarget::create([
            'pad_target_uid' => (string) Str::uuid(),

            'pad_target_tahun' => $request->pad_target_tahun,

            'pad_target_jenis' => $request->pad_target_jenis,

            'pad_target_komponen' => $request->pad_target_komponen,

            'pad_target_unit' => $request->pad_target_unit,

            'pad_target_unit_kode' => $request->pad_target_unit_kode,

            'pad_target_unit_nama' => $request->pad_target_unit_nama,

            'pad_target_nominal' => $request->pad_target_nominal,

            'pad_target_rencana' => $request->pad_target_rencana,

            'pad_target_keterangan' => $request->pad_target_keterangan,

            'pad_target_status' => true,
        ]);

        return back()->with('success', 'Target PAD berhasil ditambahkan.');
    }

    public function targetUpdate(Request $request, $uid)
    {
        $request->validate([
            'pad_target_tahun' => 'required|integer|min:2000|max:2100',

            'pad_target_jenis' => 'required|exists:saplarin_pad_jenis,pad_jenis_id',

            'pad_target_komponen' => 'nullable|exists:saplarin_pad_komponen,pad_komponen_id',

            'pad_target_unit' => 'required|string|max:100',

            'pad_target_unit_kode' => 'nullable|string|max:100',

            'pad_target_unit_nama' => 'required|string|max:255',

            'pad_target_nominal' => 'required|numeric|min:0',

            'pad_target_rencana' => 'required|numeric|min:0',

            'pad_target_keterangan' => 'nullable|string',
        ]);

        $target = ModelPadTarget::where('pad_target_uid', $uid)->firstOrFail();

        $exists = ModelPadTarget::where('pad_target_id', '!=', $target->pad_target_id)->where('pad_target_tahun', $request->pad_target_tahun)->where('pad_target_komponen', $request->pad_target_komponen)->where('pad_target_unit', $request->pad_target_unit)->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Target untuk komponen dan unit tersebut sudah tersedia.');
        }

        $target->update([
            'pad_target_tahun' => $request->pad_target_tahun,

            'pad_target_jenis' => $request->pad_target_jenis,

            'pad_target_komponen' => $request->pad_target_komponen,

            'pad_target_unit' => $request->pad_target_unit,

            'pad_target_unit_kode' => $request->pad_target_unit_kode,

            'pad_target_unit_nama' => $request->pad_target_unit_nama,

            'pad_target_nominal' => $request->pad_target_nominal,

            'pad_target_rencana' => $request->pad_target_rencana,

            'pad_target_keterangan' => $request->pad_target_keterangan,
        ]);

        return back()->with('success', 'Target PAD berhasil diperbarui.');
    }

    public function targetStatus($uid)
    {
        $target = ModelPadTarget::where('pad_target_uid', $uid)->firstOrFail();

        $target->update([
            'pad_target_status' => !$target->pad_target_status,
        ]);

        return back()->with('success', 'Status target PAD berhasil diubah.');
    }
    /**
     * =========================================================
     * PERMINTAAN PAD
     * =========================================================
     */
    public function permintaan(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $search = trim($request->get('search', ''));

        $permintaan = ModelPadRealisasi::with(['target.jenis', 'target.komponen', 'subkomponen'])

            /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

            ->whereHas('target', function ($query) use ($tahun) {
                $query->where('pad_target_tahun', $tahun);
            })

            /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    /*
                        |--------------------------------------------------------------------------
                        | PENGINPUT
                        |--------------------------------------------------------------------------
                        */

                    $q->where('pad_realisasi_input_nama', 'like', '%' . $search . '%')

                    ->orWhere('pad_realisasi_input', 'like', '%' . $search . '%')

                        /*
                        |--------------------------------------------------------------------------
                        | UNIT
                        |--------------------------------------------------------------------------
                        */

                        ->orWhere('pad_realisasi_unit', 'like', '%' . $search . '%')

                        /*
                        |--------------------------------------------------------------------------
                        | KETERANGAN
                        |--------------------------------------------------------------------------
                        */

                        ->orWhere('pad_realisasi_keterangan', 'like', '%' . $search . '%')

                        /*
                        |--------------------------------------------------------------------------
                        | SUBKOMPONEN
                        |--------------------------------------------------------------------------
                        */

                        ->orWhereHas('subkomponen', function ($sub) use ($search) {
                            $sub->where('pad_subkomponen_nama', 'like', '%' . $search . '%')->orWhere('pad_subkomponen_kode', 'like', '%' . $search . '%');
                        })

                        /*
                        |--------------------------------------------------------------------------
                        | KOMPONEN
                        |--------------------------------------------------------------------------
                        */

                        ->orWhereHas('target.komponen', function ($komponen) use ($search) {
                            $komponen->where('pad_komponen_nama', 'like', '%' . $search . '%')->orWhere('pad_komponen_kode', 'like', '%' . $search . '%');
                        });
                });
            })

            /*
        |--------------------------------------------------------------------------
        | TERBARU
        |--------------------------------------------------------------------------
        */

            ->orderBy('pad_realisasi_tanggal', 'desc')

            ->orderBy('pad_realisasi_id', 'desc')

            ->paginate(15)

            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | TOTAL NOMINAL
    |--------------------------------------------------------------------------
    */

        $totalNominal = ModelPadRealisasi::whereHas('target', function ($query) use ($tahun) {
            $query->where('pad_target_tahun', $tahun);
        })->sum('pad_realisasi_nominal');

        /*
    |--------------------------------------------------------------------------
    | TOTAL DATA
    |--------------------------------------------------------------------------
    */

        $totalData = ModelPadRealisasi::whereHas('target', function ($query) use ($tahun) {
            $query->where('pad_target_tahun', $tahun);
        })->count();

        return view('administrator-v2.pad.permintaan.index', compact('permintaan', 'tahun', 'search', 'totalNominal', 'totalData'));
    }

    /**
     * =========================================================
     * DETAIL PERMINTAAN
     * =========================================================
     */
    public function permintaanDetail($uid)
    {
        $permintaan = ModelPadRealisasi::with(['target.jenis', 'target.komponen', 'subkomponen'])
            ->where('pad_realisasi_uid', $uid)
            ->firstOrFail();

        return view('administrator-v2.pad.permintaan.detail', compact('permintaan'));
    }
    public function laporan(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */

        $tahun = $request->get('tahun', now()->year);

        $unit = $request->get('unit', '');

        $search = trim($request->get('search', ''));

        /*
    |--------------------------------------------------------------------------
    | TARGET AKTIF
    |--------------------------------------------------------------------------
    */

        $targetQuery = ModelPadTarget::with(['jenis', 'komponen'])
            ->where('pad_target_tahun', $tahun)
            ->where('pad_target_status', true);

        /*
    |--------------------------------------------------------------------------
    | FILTER UNIT
    |--------------------------------------------------------------------------
    */

        if ($unit !== '') {
            $targetQuery->where('pad_target_unit', $unit);
        }

        /*
    |--------------------------------------------------------------------------
    | TOTAL TARGET
    |--------------------------------------------------------------------------
    */

        $totalTarget = (clone $targetQuery)->sum('pad_target_nominal');

        /*
    |--------------------------------------------------------------------------
    | TOTAL REALISASI
    |--------------------------------------------------------------------------
    */

        $realisasiQuery = ModelPadRealisasi::query()
            ->where('pad_realisasi_status', 'Diterima')
            ->whereHas('target', function ($query) use ($tahun, $unit) {
                $query->where('pad_target_tahun', $tahun);

                $query->where('pad_target_status', true);

                if ($unit !== '') {
                    $query->where('pad_target_unit', $unit);
                }
            });

        $totalRealisasi = (clone $realisasiQuery)->sum('pad_realisasi_nominal');

        /*
    |--------------------------------------------------------------------------
    | SISA
    |--------------------------------------------------------------------------
    */

        $sisaTarget = max($totalTarget - $totalRealisasi, 0);

        /*
    |--------------------------------------------------------------------------
    | PERSENTASE
    |--------------------------------------------------------------------------
    */

        $persenRealisasi = $totalTarget > 0 ? ($totalRealisasi / $totalTarget) * 100 : 0;

        /*
    |--------------------------------------------------------------------------
    | DATA UNIT
    |--------------------------------------------------------------------------
    */

        $unitList = (clone $targetQuery)->select('pad_target_unit', 'pad_target_unit_kode', 'pad_target_unit_nama')->groupBy('pad_target_unit', 'pad_target_unit_kode', 'pad_target_unit_nama')->orderBy('pad_target_unit_nama')->get();

        /*
    |--------------------------------------------------------------------------
    | PIE REALISASI PER UNIT
    |--------------------------------------------------------------------------
    */

        $unitIds = $unitList->pluck('pad_target_unit')->filter()->values();

        $chartUnit = collect();

        foreach ($unitList as $item) {
            $targetIds = ModelPadTarget::query()->where('pad_target_tahun', $tahun)->where('pad_target_status', true)->where('pad_target_unit', $item->pad_target_unit)->pluck('pad_target_id');

            $realisasi = ModelPadRealisasi::query()->where('pad_realisasi_status', 'Diterima')->whereIn('pad_realisasi_target', $targetIds)->sum('pad_realisasi_nominal');

            if ($realisasi > 0) {
                $chartUnit->push([
                    'label' => $item->pad_target_unit_nama ?: $item->pad_target_unit_kode ?: 'Unit',

                    'realisasi' => $realisasi,
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | PIE REALISASI PER JENIS PAD
    |--------------------------------------------------------------------------
    */

        $jenisList = ModelPadTarget::with('jenis')
            ->where('pad_target_tahun', $tahun)
            ->where('pad_target_status', true)
            ->when($unit !== '', function ($query) use ($unit) {
                $query->where('pad_target_unit', $unit);
            })
            ->get()
            ->groupBy('pad_target_jenis');

        $chartJenis = collect();

        foreach ($jenisList as $targets) {
            $first = $targets->first();

            if (!$first || !$first->jenis) {
                continue;
            }

            $targetIds = $targets->pluck('pad_target_id');

            $realisasi = ModelPadRealisasi::query()->where('pad_realisasi_status', 'Diterima')->whereIn('pad_realisasi_target', $targetIds)->sum('pad_realisasi_nominal');

            if ($realisasi > 0) {
                $chartJenis->push([
                    'label' => $first->jenis->pad_jenis_nama,

                    'realisasi' => $realisasi,
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | PIE REALISASI PER KOMPONEN
    |--------------------------------------------------------------------------
    */

        $komponenList = ModelPadTarget::with('komponen')
            ->where('pad_target_tahun', $tahun)
            ->where('pad_target_status', true)
            ->when($unit !== '', function ($query) use ($unit) {
                $query->where('pad_target_unit', $unit);
            })
            ->get()
            ->groupBy('pad_target_komponen');

        $chartKomponen = collect();

        foreach ($komponenList as $targets) {
            $first = $targets->first();

            if (!$first || !$first->komponen) {
                continue;
            }

            $targetIds = $targets->pluck('pad_target_id');

            $realisasi = ModelPadRealisasi::query()->where('pad_realisasi_status', 'Diterima')->whereIn('pad_realisasi_target', $targetIds)->sum('pad_realisasi_nominal');

            if ($realisasi > 0) {
                $chartKomponen->push([
                    'label' => $first->komponen->pad_komponen_nama,

                    'realisasi' => $realisasi,
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | REKAP TABEL
    |--------------------------------------------------------------------------
    */

        $pagus = ModelPadTarget::with(['jenis', 'komponen'])

            ->where('pad_target_tahun', $tahun)

            ->where('pad_target_status', true)

            ->when($unit !== '', function ($query) use ($unit) {
                $query->where('pad_target_unit', $unit);
            })

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pad_target_unit_nama', 'like', '%' . $search . '%')

                        ->orWhere('pad_target_unit_kode', 'like', '%' . $search . '%')

                        ->orWhereHas('jenis', function ($jenis) use ($search) {
                            $jenis->where('pad_jenis_nama', 'like', '%' . $search . '%');
                        })

                        ->orWhereHas('komponen', function ($komponen) use ($search) {
                            $komponen->where('pad_komponen_nama', 'like', '%' . $search . '%')->orWhere('pad_komponen_kode', 'like', '%' . $search . '%');
                        });
                });
            })

            ->orderBy('pad_target_unit_nama')

            ->orderBy('pad_target_komponen')

            ->paginate(15)

            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | TAMBAHKAN REALISASI KE TARGET
    |--------------------------------------------------------------------------
    */

        $pagus->getCollection()->transform(function ($item) {
            $realisasi = ModelPadRealisasi::query()->where('pad_realisasi_status', 'Diterima')->where('pad_realisasi_target', $item->pad_target_id)->sum('pad_realisasi_nominal');

            $item->laporan_realisasi = $realisasi;

            $item->laporan_sisa = max($item->pad_target_nominal - $realisasi, 0);

            $item->laporan_persen = $item->pad_target_nominal > 0 ? ($realisasi / $item->pad_target_nominal) * 100 : 0;

            return $item;
        });

        /*
    |--------------------------------------------------------------------------
    | TAHUN LIST
    |--------------------------------------------------------------------------
    */

        $tahunList = ModelPadTarget::query()->select('pad_target_tahun')->distinct()->orderBy('pad_target_tahun', 'desc')->pluck('pad_target_tahun');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([now()->year]);
        }

        /*
    |--------------------------------------------------------------------------
    | CHART SERAPAN
    |--------------------------------------------------------------------------
    */

        $chartSerapan = collect([
            [
                'label' => 'Realisasi',
                'total' => $totalRealisasi,
            ],
            [
                'label' => 'Sisa Target',
                'total' => $sisaTarget,
            ],
        ]);

        return view('administrator-v2.laporan-pad.index', compact('tahun', 'tahunList', 'unit', 'unitList', 'search', 'totalTarget', 'totalRealisasi', 'sisaTarget', 'persenRealisasi', 'chartSerapan', 'chartUnit', 'chartJenis', 'chartKomponen', 'pagus'));
    }
}