<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ModelKegiatan;
use App\Models\ModelProgram;
use App\Models\ModelSubKegiatan;
use App\Models\SubKegiatanIndikator;
use App\Models\SubKegiatanLaporan;
use App\Models\SubKegiatanLaporanDetail;
use App\Models\SubKegiatanPermasalahan;
use App\Models\SubKegiatanSolusi;
use App\Models\SubKegiatanTindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanSubKegiatanController extends Controller
{
    public function index()
    {
        $laporanSubKegiatans = SubKegiatanLaporan::with([
                'subKegiatan',
                'detail.indikator',
                'permasalahan',
                'solusi',
                'tindakLanjut',
            ])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->latest()
            ->get();

        $programs = ModelProgram::orderBy('program_nama')->get();

        $masterKegiatans = ModelKegiatan::orderBy('kegiatan_nama')->get();

        $subKegiatans = ModelSubKegiatan::where('sub_kegiatan_status', 1)
            ->orderBy('sub_kegiatan_nama')
            ->get();

        $indikatorMap = SubKegiatanIndikator::where('status', 1)
            ->orderBy('id')
            ->get()
            ->groupBy(function ($item) {
                return $item->sub_kegiatan_id . '_' . $item->tahun;
            })
            ->map(function ($items) {
                return $items->values();
            });

        return view('user.laporan-sub-kegiatan.index', compact(
            'laporanSubKegiatans',
            'programs',
            'masterKegiatans',
            'subKegiatans',
            'indikatorMap'
        ));
    }

    public function storeSubKegiatan(Request $request)
    {
        $request->validate([
            'sub_kegiatan_id' => 'required',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric',
        ]);

        $user = session('user_info');

        $cek = SubKegiatanLaporan::where('sub_kegiatan_id', $request->sub_kegiatan_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();

        if ($cek) {
            return back()->with('error', 'Sub kegiatan ini sudah ditambahkan pada bulan dan tahun tersebut.');
        }

        SubKegiatanLaporan::create([
            'sub_kegiatan_id' => $request->sub_kegiatan_id,
            'pegawai_id' => $user['id'] ?? $user['pegawai_id'] ?? null,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Sub kegiatan berhasil ditambahkan. Silakan input realisasi laporan.');
    }

    public function storeLaporan(Request $request, $id)
    {
        $request->validate([
            'realisasi' => 'required|array',
        ]);

        $laporan = SubKegiatanLaporan::findOrFail($id);

        DB::transaction(function () use ($request, $laporan) {
            foreach ($request->realisasi as $indikatorId => $nilai) {
                SubKegiatanLaporanDetail::updateOrCreate(
                    [
                        'laporan_id' => $laporan->id,
                        'indikator_id' => $indikatorId,
                    ],
                    [
                        'realisasi' => $nilai ?? 0,
                    ]
                );
            }

            SubKegiatanPermasalahan::where('laporan_id', $laporan->id)->delete();
            SubKegiatanSolusi::where('laporan_id', $laporan->id)->delete();
            SubKegiatanTindakLanjut::where('laporan_id', $laporan->id)->delete();

            foreach ($request->permasalahan ?? [] as $item) {
                if (trim($item) !== '') {
                    SubKegiatanPermasalahan::create([
                        'laporan_id' => $laporan->id,
                        'uraian' => $item,
                    ]);
                }
            }

            foreach ($request->solusi ?? [] as $item) {
                if (trim($item) !== '') {
                    SubKegiatanSolusi::create([
                        'laporan_id' => $laporan->id,
                        'uraian' => $item,
                    ]);
                }
            }

            foreach ($request->tindak_lanjut ?? [] as $item) {
                if (trim($item) !== '') {
                    SubKegiatanTindakLanjut::create([
                        'laporan_id' => $laporan->id,
                        'uraian' => $item,
                    ]);
                }
            }

            $laporan->update([
                'catatan' => $request->catatan,
            ]);
        });

        return back()->with('success', 'Laporan sub kegiatan berhasil disimpan.');
    }

    public function getIndikator(Request $request)
    {
        $request->validate([
            'sub_kegiatan_id' => 'required',
            'tahun' => 'required',
        ]);

        $indikator = SubKegiatanIndikator::where('sub_kegiatan_id', $request->sub_kegiatan_id)
            ->where('tahun', $request->tahun)
            ->where('status', 1)
            ->orderBy('id')
            ->get();

        return response()->json($indikator);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_kegiatan_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'realisasi' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $user = session('user_info');

            $laporan = SubKegiatanLaporan::create([
                'sub_kegiatan_id' => $request->sub_kegiatan_id,
                'pegawai_id' => $user['id'] ?? $user['pegawai_id'] ?? null,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'catatan' => $request->catatan,
            ]);

            foreach ($request->realisasi as $indikatorId => $nilai) {
                SubKegiatanLaporanDetail::create([
                    'laporan_id' => $laporan->id,
                    'indikator_id' => $indikatorId,
                    'realisasi' => $nilai ?? 0,
                ]);
            }

            foreach ($request->permasalahan ?? [] as $item) {
                if (trim($item) !== '') {
                    SubKegiatanPermasalahan::create([
                        'laporan_id' => $laporan->id,
                        'uraian' => $item,
                    ]);
                }
            }

            foreach ($request->solusi ?? [] as $item) {
                if (trim($item) !== '') {
                    SubKegiatanSolusi::create([
                        'laporan_id' => $laporan->id,
                        'uraian' => $item,
                    ]);
                }
            }

            foreach ($request->tindak_lanjut ?? [] as $item) {
                if (trim($item) !== '') {
                    SubKegiatanTindakLanjut::create([
                        'laporan_id' => $laporan->id,
                        'uraian' => $item,
                    ]);
                }
            }
        });

        return redirect()
            ->route('user.laporan-sub-kegiatan.index')
            ->with('success', 'Laporan sub kegiatan berhasil disimpan.');
    }

    public function show($id)
    {
        $laporan = SubKegiatanLaporan::with([
            'subKegiatan',
            'detail.indikator',
            'permasalahan',
            'solusi',
            'tindakLanjut',
        ])->findOrFail($id);

        return view('user.laporan-sub-kegiatan.show', compact('laporan'));
    }
}