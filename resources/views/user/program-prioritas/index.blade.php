@extends('user.layouts.app')

@section('title', 'Kinerja Prioritas')
@section('page_title', 'Kinerja Prioritas')
@section('breadcrumb', 'Kinerja Prioritas')

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

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold">
            Laporan Kinerja Prioritas
        </h2>

        <p class="text-purple-100 text-sm mt-2">
            Input rencana aksi dari program prioritas, lalu input capaian beserta bukti dukung.
        </p>
    </div>

    @forelse ($prioritas as $item)
        @php
            $totalTargetPrioritas = $item->rencana->sum(function ($r) {
                return (int) $r->rencana_target;
            });

            $totalCapaianAktif = $item->rencana->sum(function ($r) {
                return $r->capaian
                    ->where('capaian_status', 'Aktif')
                    ->sum('capaian_jumlah');
            });

            $persenPrioritas = $totalTargetPrioritas > 0
                ? ($totalCapaianAktif / $totalTargetPrioritas) * 100
                : 0;

            if ($persenPrioritas > 100) {
                $persenPrioritas = 100;
            }
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">

                <div>
                    <div class="text-sm text-slate-500">
                        Tahun {{ $item->prioritas_tahun }}
                    </div>

                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $item->prioritas_judul }}
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $item->prioritas_deskripsi ?: '-' }}
                    </p>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4">
                            <p class="text-xs text-purple-600">Target Prioritas</p>
                            <p class="text-2xl font-bold text-purple-800">
                                {{ $totalTargetPrioritas }}
                            </p>
                        </div>

                        <div class="bg-green-50 border border-green-100 rounded-2xl p-4">
                            <p class="text-xs text-green-600">Capaian Aktif</p>
                            <p class="text-2xl font-bold text-green-700">
                                {{ $totalCapaianAktif }}
                            </p>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                            <p class="text-xs text-blue-600">Persentase Prioritas</p>
                            <p class="text-2xl font-bold text-blue-700">
                                {{ number_format($persenPrioritas, 2, ',', '.') }}%
                            </p>
                        </div>
                    </div>
                </div>

                <button type="button"
                    onclick='openRencanaModal(@json($item))'
                    class="px-5 py-3 rounded-2xl bg-purple-600 text-white font-semibold hover:bg-purple-700">
                    + Tambah Rencana Aksi
                </button>

            </div>

            <div class="space-y-5">

                @forelse ($item->rencana as $rencana)
                    @php
                        $targetRencana = (int) $rencana->rencana_target;

                        $capaianAktifRencana = $rencana->capaian
                            ->where('capaian_status', 'Aktif')
                            ->sum('capaian_jumlah');

                        $persenRencana = $targetRencana > 0
                            ? ($capaianAktifRencana / $targetRencana) * 100
                            : 0;

                        if ($persenRencana > 100) {
                            $persenRencana = 100;
                        }
                    @endphp

                    <div class="border border-slate-200 rounded-3xl p-5 bg-slate-50">

                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="font-bold text-slate-900 text-lg">
                                        {{ $rencana->rencana_judul }}
                                    </h4>

                                    @if ($rencana->rencana_status === 'Aktif')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                            Nonaktif
                                        </span>
                                    @endif

                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                        {{ number_format($persenRencana, 2, ',', '.') }}%
                                    </span>
                                </div>

                                <div class="text-sm text-slate-600 mt-2">
                                    <span class="font-semibold">Target:</span>
                                    {{ $targetRencana }} capaian
                                </div>

                                <div class="text-xs text-slate-500 mt-2">
                                    Capaian aktif rencana ini:
                                    <b>{{ $capaianAktifRencana }}</b>
                                </div>
                            </div>

                            @if ($rencana->rencana_status === 'Aktif')
                                <button type="button"
                                    onclick='openCapaianModal(@json($rencana))'
                                    class="px-4 py-2 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                                    + Input Capaian
                                </button>
                            @endif

                        </div>

                        <div class="overflow-x-auto bg-white rounded-2xl border border-slate-200">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-slate-500">
                                        <th class="py-3 px-3">No</th>
                                        <th class="py-3 px-3">Judul Capaian</th>
                                        <th class="py-3 px-3">Jumlah</th>
                                        <th class="py-3 px-3">Persen</th>
                                        <th class="py-3 px-3">Deskripsi</th>
                                        <th class="py-3 px-3">Rentang Tanggal</th>
                                        <th class="py-3 px-3">Bukti</th>
                                        <th class="py-3 px-3">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($rencana->capaian->sortByDesc('capaian_tanggal_selesai') as $capaian)
                                        @php
                                            $jumlahCapaianBaris = (int) ($capaian->capaian_jumlah ?? 1);

                                            $persenCapaianBaris = $targetRencana > 0
                                                ? ($jumlahCapaianBaris / $targetRencana) * 100
                                                : 0;

                                            if ($persenCapaianBaris > 100) {
                                                $persenCapaianBaris = 100;
                                            }
                                        @endphp

                                        <tr class="border-b hover:bg-slate-50">
                                            <td class="py-4 px-3">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td class="py-4 px-3">
                                                <div class="font-semibold text-slate-900">
                                                    {{ $capaian->capaian_judul }}
                                                </div>
                                            </td>

                                            <td class="py-4 px-3">
                                                {{ $jumlahCapaianBaris }}
                                            </td>

                                            <td class="py-4 px-3">
                                                @if ($capaian->capaian_status === 'Aktif')
                                                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs">
                                                        {{ number_format($persenCapaianBaris, 2, ',', '.') }}%
                                                    </span>
                                                @else
                                                    <span class="text-slate-400 text-xs">
                                                        0%
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="py-4 px-3">
                                                {{ $capaian->capaian_deskripsi }}
                                            </td>

                                            <td class="py-4 px-3">
                                                {{ $capaian->capaian_tanggal_mulai?->format('d/m/Y') }}
                                                -
                                                {{ $capaian->capaian_tanggal_selesai?->format('d/m/Y') }}
                                            </td>

                                            <td class="py-4 px-3">
                                                <div class="flex flex-col gap-1">
                                                    @foreach ($capaian->files as $file)
                                                        <a href="{{ asset($file->file_path) }}"
                                                            target="_blank"
                                                            class="text-blue-600 hover:underline">
                                                            Bukti
                                                        </a>
                                                    @endforeach

                                                    @if ($capaian->files->isEmpty())
                                                        <span class="text-slate-400">-</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="py-4 px-3">
                                                @if ($capaian->capaian_status === 'Aktif')
                                                    <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="py-8 text-center text-slate-500">
                                                Belum ada capaian.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                @empty
                    <div class="border border-dashed border-slate-300 rounded-3xl p-8 text-center text-slate-500">
                        Belum ada rencana aksi.
                    </div>
                @endforelse

            </div>

        </div>
    @empty
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            Belum ada program prioritas aktif.
        </div>
    @endforelse

</div>

@include('user.program-prioritas.partials.modal')

@endsection