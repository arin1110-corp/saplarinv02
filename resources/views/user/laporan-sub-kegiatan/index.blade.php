@extends('user.layouts.app')

@section('title', 'Laporan Sub Kegiatan')
@section('page_title', 'Laporan Sub Kegiatan')
@section('breadcrumb', 'Laporan Sub Kegiatan')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Laporan Sub Kegiatan
                    </h2>
                    <p class="text-sm text-slate-500">
                        Input realisasi indikator, permasalahan, solusi, dan tindak lanjut.
                    </p>
                </div>

                <a href="{{ route('user.laporan-sub-kegiatan.create') }}"
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    + Input Laporan
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 overflow-x-auto">

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-slate-500">
                        <th class="py-3 px-3">No</th>
                        <th class="py-3 px-3">Bulan/Tahun</th>
                        <th class="py-3 px-3">Sub Kegiatan</th>
                        <th class="py-3 px-3">Capaian</th>
                        <th class="py-3 px-3">Operator</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Ringkasan</th>
                        <th class="py-3 px-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($laporans as $laporan)
                        @php
                            $totalTarget = $laporan->detail->sum('detail_target');
                            $totalRealisasi = $laporan->detail->sum('detail_realisasi');
                            $persen = $totalTarget > 0 ? ($totalRealisasi / $totalTarget) * 100 : 0;
                            if ($persen > 100) {
                                $persen = 100;
                            }

                            $bulanNama = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp

                        <tr class="border-b hover:bg-slate-50">
                            <td class="py-3 px-3">
                                {{ $loop->iteration }}
                            </td>

                            <td class="py-3 px-3">
                                {{ $bulanNama[$laporan->laporan_bulan] ?? '-' }} {{ $laporan->laporan_tahun }}
                            </td>

                            <td class="py-3 px-3">
                                <div class="font-semibold text-slate-800">
                                    {{ $laporan->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $laporan->subKegiatan->sub_kegiatan_kode ?? '-' }}
                                </div>
                            </td>

                            <td class="py-3 px-3">
                                <div class="font-bold text-blue-700">
                                    {{ number_format($persen, 2, ',', '.') }}%
                                </div>
                                <div class="w-36 bg-slate-100 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $persen }}%">
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-3">
                                <div class="font-semibold text-slate-800">
                                    {{ $laporan->laporan_created_by_nama ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $laporan->laporan_created_by_nip ?? '-' }}
                                </div>
                            </td>

                            <td class="py-3 px-3">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    {{ $laporan->laporan_status }}
                                </span>
                            </td>

                            <td class="py-3 px-3">
                                <div class="text-xs text-slate-600">
                                    Indikator: {{ $laporan->detail->count() }}
                                </div>
                                <div class="text-xs text-slate-600">
                                    Masalah: {{ $laporan->permasalahan->count() }}
                                </div>
                                <div class="text-xs text-slate-600">
                                    Solusi: {{ $laporan->solusi->count() }}
                                </div>
                                <div class="text-xs text-slate-600">
                                    Tindak lanjut: {{ $laporan->tindakLanjut->count() }}
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                @if ($laporan->laporan_created_by == session('pegawai_id'))
                                    <a href="{{ route('user.laporan-sub-kegiatan.edit', $laporan->laporan_uid) }}"
                                        class="px-3 py-2 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                Belum ada laporan sub kegiatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>

@endsection
