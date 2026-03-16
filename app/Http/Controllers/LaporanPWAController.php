<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ModelLaporanPWA;
use App\Imports\LaporanPWAImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ModelSubKegiatanPWA;
use App\Models\ModelDataPWA;

use Illuminate\Http\Request;

class LaporanPWAController extends Controller
{
    //
    public function importPWA(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new LaporanPWAImport(), $request->file('file'));

        return back()->with('success', 'Import berhasil');
    }
    public function inputDataPWA()
    {
        $subkegiatan = ModelSubKegiatanPWA::orderBy('subkegiatan_pwa_nama')->get();
        $dataPwa = ModelDataPWA::with('subkegiatan')->orderBy('data_pwa_tahun', 'desc')->get();

        return view('homepage_data_pwa', compact('subkegiatan', 'dataPwa'));
    }

    public function simpanDataPWA(Request $request)
    {
        $request->validate([
            'subkegiatan' => 'required',
            'tahun' => 'required',
            'pagu' => 'required',
            'realisasi' => 'required',
        ]);

        ModelDataPWA::create([
            'data_pwa_subkegiatan' => $request->subkegiatan,
            'data_pwa_tahun' => $request->tahun,
            'data_pwa_pagu' => str_replace('.', '', $request->pagu),
            'data_pwa_realisasi' => str_replace('.', '', $request->realisasi),
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
    public function laporanPWA()
    {
        $tahun = [2024, 2025];

        $data = [];

        foreach ($tahun as $t) {
            $sub = DB::table('saplarin_subkegiatan_pwa')->join('saplarin_data_pwa', 'saplarin_subkegiatan_pwa.subkegiatan_pwa_id', '=', 'saplarin_data_pwa.data_pwa_subkegiatan')->where('saplarin_data_pwa.data_pwa_tahun', $t)->select('saplarin_subkegiatan_pwa.subkegiatan_pwa_id', 'saplarin_data_pwa.data_pwa_id', 'saplarin_data_pwa.data_pwa_realisasi', 'saplarin_subkegiatan_pwa.subkegiatan_pwa_nama', 'saplarin_data_pwa.data_pwa_pagu')->get();

            foreach ($sub as $s) {
                $laporan = DB::table('saplarin_laporan_pwa')->where('laporan_pwa_data_pwa', $s->data_pwa_id)->get();

                $s->laporan = $laporan;

                $s->total = $laporan->sum('laporan_pwa_nominal');
            }

            $data[$t] = $sub;
        }

        return view('homepage_saplarin_laporanpwa', compact('data'));
    }
}