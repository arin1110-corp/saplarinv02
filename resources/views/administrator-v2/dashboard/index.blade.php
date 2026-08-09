@extends('administrator-v2.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('page-description', 'SAPLARIN Tahun Anggaran ' . $tahun)

@section('content')

    <div class="space-y-6">



        {{-- Statistic --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Total Pagu Anggaran

                        </p>

                        <h2 class="font-bold leading-tight whitespace-nowrap overflow-hidden"
                            style="font-size:clamp(1.15rem,1.2vw,2.2rem);">

                            Rp {{ number_format($totalPagu, 0, ',', '.') }}

                        </h2>

                        <p class="text-xs text-slate-500 mt-3">

                            {{ number_format($jumlahPaguSPJ, 0, ',', '.') }}

                            Sub Kegiatan Aktif

                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">

                        <i class="bi bi-wallet2 text-3xl text-blue-600"></i>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Realisasi SPJ

                        </p>

                        <h2 class="font-bold leading-tight whitespace-nowrap overflow-hidden"
                            style="font-size:clamp(1.15rem,1.2vw,2.2rem);">

                            Rp {{ number_format($totalRealisasiSPJ, 0, ',', '.') }}

                        </h2>

                        <p class="text-xs text-slate-500 mt-3">

                            {{ number_format($jumlahInputSPJ, 0, ',', '.') }}

                            Input SPJ Aktif

                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">

                        <i class="bi bi-receipt text-3xl text-green-600"></i>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Sisa Pagu

                        </p>

                        <h2 class="font-bold leading-tight whitespace-nowrap overflow-hidden"
                            style="font-size:clamp(1.15rem,1.2vw,2.2rem);">

                            Rp {{ number_format($sisaPagu, 0, ',', '.') }}

                        </h2>

                        <p class="text-xs text-slate-500 mt-3">

                            Sisa Anggaran

                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">

                        <i class="bi bi-piggy-bank text-3xl text-amber-600"></i>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-600 text-white p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-blue-100 text-sm">

                            Serapan SPJ

                        </p>

                        <h2 class="text-4xl font-bold mt-2">

                            {{ number_format($persenSerapan, 2, ',', '.') }}%

                        </h2>

                        <p class="text-sm mt-3 text-blue-100">

                            Berdasarkan Pagu Final

                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-white/20 flex items-center justify-center">

                        <i class="bi bi-bar-chart-line text-3xl"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Statistic PAD --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Target PAD</p>
                        <h2 class="font-bold leading-tight whitespace-nowrap overflow-hidden"
                            style="font-size:clamp(1.15rem,1.2vw,2.2rem);">
                            Rp {{ number_format($totalTargetPAD, 0, ',', '.') }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-3">Target Penerimaan PAD</p>
                    </div>
                    <div class="w-16 h-16 rounded-3xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="bi bi-cash-stack text-3xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Realisasi PAD</p>
                        <h2 class="font-bold leading-tight whitespace-nowrap overflow-hidden"
                            style="font-size:clamp(1.15rem,1.2vw,2.2rem);">
                            Rp {{ number_format($totalRealisasiPAD, 0, ',', '.') }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-3">Penerimaan PAD</p>
                    </div>
                    <div class="w-16 h-16 rounded-3xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <i class="bi bi-graph-up-arrow text-3xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">
                <div class="flex justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Sisa Target PAD</p>
                        <h2 class="font-bold leading-tight whitespace-nowrap overflow-hidden"
                            style="font-size:clamp(1.15rem,1.2vw,2.2rem);">
                            Rp {{ number_format($sisaPAD, 0, ',', '.') }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-3">Target Belum Terealisasi</p>
                    </div>
                    <div class="w-16 h-16 rounded-3xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <i class="bi bi-piggy-bank text-3xl text-amber-600"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-gradient-to-br from-emerald-600 via-green-600 to-teal-500 text-white p-6">
                <div class="flex justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Capaian PAD</p>
                        <h2 class="text-4xl font-bold mt-2">{{ number_format($persenPAD, 2, ',', '.') }}%</h2>
                        <p class="text-sm mt-3 text-green-100">Berdasarkan Target PAD</p>
                    </div>
                    <div class="w-16 h-16 rounded-3xl bg-white/20 flex items-center justify-center">
                        <i class="bi bi-bar-chart-line text-3xl"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- MASTER DATA PROGRAM --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- PROGRAM --}}
            <div
                class="rounded-3xl bg-white dark:bg-slate-900
               border border-slate-200 dark:border-slate-800
               p-6">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Program
                        </p>

                        <h2 class="text-3xl font-bold mt-2 dark:text-white">
                            {{ number_format($jumlahProgram, 0, ',', '.') }}
                        </h2>

                        <p class="text-xs text-slate-500 mt-3">
                            Program Aktif
                        </p>

                    </div>

                    <div
                        class="w-16 h-16 rounded-3xl
                       bg-blue-100 dark:bg-blue-900/30
                       flex items-center justify-center">

                        <i class="bi bi-diagram-3 text-3xl text-blue-600"></i>

                    </div>

                </div>

            </div>


            {{-- KEGIATAN --}}
            <div
                class="rounded-3xl bg-white dark:bg-slate-900
               border border-slate-200 dark:border-slate-800
               p-6">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Kegiatan
                        </p>

                        <h2 class="text-3xl font-bold mt-2 dark:text-white">
                            {{ number_format($jumlahKegiatan, 0, ',', '.') }}
                        </h2>

                        <p class="text-xs text-slate-500 mt-3">
                            Kegiatan Aktif
                        </p>

                    </div>

                    <div
                        class="w-16 h-16 rounded-3xl
                       bg-indigo-100 dark:bg-indigo-900/30
                       flex items-center justify-center">

                        <i class="bi bi-list-task text-3xl text-indigo-600"></i>

                    </div>

                </div>

            </div>


            {{-- SUB KEGIATAN --}}
            <div
                class="rounded-3xl bg-white dark:bg-slate-900
               border border-slate-200 dark:border-slate-800
               p-6">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Sub Kegiatan
                        </p>

                        <h2 class="text-3xl font-bold mt-2 dark:text-white">
                            {{ number_format($jumlahSubKegiatan, 0, ',', '.') }}
                        </h2>

                        <p class="text-xs text-slate-500 mt-3">
                            Sub Kegiatan Aktif
                        </p>

                    </div>

                    <div
                        class="w-16 h-16 rounded-3xl
                       bg-purple-100 dark:bg-purple-900/30
                       flex items-center justify-center">

                        <i class="bi bi-list-check text-3xl text-purple-600"></i>

                    </div>

                </div>

            </div>

        </div>
        {{-- Statistic 2 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Total User

                        </p>

                        <h2 class="text-3xl font-bold mt-2 dark:text-white">

                            {{ number_format($totalUser, 0, ',', '.') }}

                        </h2>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">

                        <i class="bi bi-people text-3xl text-indigo-600"></i>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Pengajuan BBM

                        </p>

                        <h2 class="text-3xl font-bold mt-2 dark:text-white">

                            {{ number_format($jumlahBBM, 0, ',', '.') }}

                        </h2>

                        <p class="text-xs text-yellow-500 mt-3">

                            {{ number_format($bbmMenunggu, 0, ',', '.') }}

                            Menunggu Verifikasi

                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">

                        <i class="bi bi-fuel-pump text-3xl text-yellow-600"></i>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Program Prioritas

                        </p>

                        <h2 class="text-3xl font-bold mt-2 dark:text-white">

                            {{ number_format($jumlahPrioritas, 0, ',', '.') }}

                        </h2>

                        <p class="text-xs text-slate-500 mt-3">

                            Program Aktif

                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">

                        <i class="bi bi-kanban text-3xl text-green-600"></i>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Aktivitas

                        </p>

                        <h2 class="text-3xl font-bold mt-2 dark:text-white">

                            {{ number_format($jumlahAktivitas, 0, ',', '.') }}

                        </h2>

                        <p class="text-xs text-slate-500 mt-3">

                            Laporan Aktivitas

                        </p>

                    </div>

                    <div class="w-16 h-16 rounded-3xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">

                        <i class="bi bi-clipboard-data text-3xl text-red-600"></i>

                    </div>

                </div>

            </div>

        </div>
        {{-- Chart + Status --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Chart --}}
            <div class="xl:col-span-2">

                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">

                        {{-- GANTI HEADER GRAFIK --}}

                        <h3 class="text-lg font-bold dark:text-white">

                            Grafik Jumlah SPJ Bulanan

                        </h3>

                        <p class="text-sm text-slate-500">

                            Monitoring jumlah transaksi SPJ Tahun {{ $tahun }}

                        </p>

                    </div>

                    <div class="p-6">

                        <div id="chartRealisasi" class="h-96"></div>

                    </div>

                    <div class="border-t border-slate-200 dark:border-slate-800">

                        <div class="px-6 py-5">

                            {{-- GANTI HEADER REKAP --}}

                            <h3 class="text-xl font-bold dark:text-white">

                                Rekap SPJ Bulanan

                            </h3>

                            <p class="text-sm text-slate-500">

                                Jumlah transaksi dan total realisasi setiap bulan.

                            </p>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="w-full">

                                <thead>

                                    <tr
                                        class="border-y border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800">

                                        <th class="text-left p-4">

                                            Bulan

                                        </th>

                                        <th class="text-center p-4">

                                            Jumlah SPJ

                                        </th>

                                        <th class="text-right p-4">

                                            Total Nominal

                                        </th>
                                        {{-- TAMBAHKAN KOLOM SERAPAN DI TABEL REKAP --}}

                                        <th class="text-center p-4">

                                            Serapan

                                        </th>

                                        <th class="text-center p-4">

                                            Aksi

                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($chartSPJ as $i => $item)
                                        <tr
                                            class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800">

                                            <td class="p-4 font-medium">

                                                {{ $item['bulan'] }}

                                            </td>

                                            <td class="p-4 text-center">

                                                <span
                                                    class="inline-flex rounded-xl bg-blue-100 dark:bg-blue-900/20 px-3 py-1 text-blue-700 dark:text-blue-300 font-semibold">

                                                    {{ $item['jumlah'] }} SPJ

                                                </span>

                                            </td>

                                            {{-- GANTI KOLOM NOMINAL DETAIL --}}

                                            <td class="p-4 text-right">

                                                <div class="font-semibold">

                                                    Rp {{ number_format($item['nominal'], 0, ',', '.') }}

                                                </div>

                                            </td>
                                            <td class="p-4 text-center">

                                                @php

                                                    $persen =
                                                        $totalRealisasiSPJ > 0
                                                            ? ($item['nominal'] / $totalRealisasiSPJ) * 100
                                                            : 0;

                                                @endphp

                                                <span
                                                    class="inline-flex rounded-xl bg-green-100 dark:bg-green-900/20 px-3 py-1 text-green-700 dark:text-green-300">

                                                    {{ number_format($persen, 1, ',', '.') }}%

                                                </span>

                                            </td>

                                            <td class="p-4 text-center">

                                                {{-- GANTI TOMBOL DETAIL --}}

                                                <a href="?tahun={{ $tahun }}&bulan={{ $i + 1 }}"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm text-white">

                                                    <i class="bi bi-eye"></i>

                                                    Detail

                                                </a>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Status --}}
            <div>

                <div
                    class="rounded-3xl overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-600 text-white shadow-xl">

                    <div class="p-7">

                        <h3 class="text-2xl font-bold">

                            Status SAPLARIN

                        </h3>

                        <p class="mt-2 text-blue-100">

                            Monitoring Realisasi SPJ secara realtime.

                        </p>

                        <div class="mt-8">

                            <div class="flex justify-between text-sm">

                                <span>Serapan</span>

                                <span>{{ number_format($persenSerapan, 2, ',', '.') }}%</span>

                            </div>

                            <div class="mt-3 h-3 rounded-full bg-white/20">

                                <div class="h-3 rounded-full bg-white" style="width: {{ $persenSerapan }}%">

                                </div>

                            </div>

                        </div>

                        <div class="mt-8 space-y-5">

                            <div class="flex justify-between">

                                <span class="text-blue-100">

                                    Total Pagu

                                </span>

                                <strong>

                                    Rp {{ number_format($totalPagu, 0, ',', '.') }}

                                </strong>

                            </div>

                            <div class="flex justify-between">

                                <span class="text-blue-100">

                                    Realisasi

                                </span>

                                <strong>

                                    Rp {{ number_format($totalRealisasiSPJ, 0, ',', '.') }}

                                </strong>

                            </div>

                            <div class="flex justify-between">

                                <span class="text-blue-100">

                                    Sisa

                                </span>

                                <strong>

                                    Rp {{ number_format($sisaPagu, 0, ',', '.') }}

                                </strong>

                            </div>

                        </div>

                        <a href="{{ route('admin.spj.index') }}"
                            class="mt-8 inline-flex w-full items-center justify-center rounded-2xl bg-white py-3 font-semibold text-blue-700 hover:bg-blue-50 transition">

                            Kelola SPJ

                        </a>

                    </div>

                </div>

            </div>

        </div>
        {{-- TEPAT DI ATAS DETAIL SPJ --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

            <div class="rounded-2xl bg-blue-50 dark:bg-blue-900/20 p-5">

                <div class="text-sm text-slate-500">

                    Bulan

                </div>

                <div class="mt-2 text-xl font-bold">

                    {{ $chartSPJ[$bulan - 1]['bulan'] ?? '-' }}

                </div>

            </div>

            <div class="rounded-2xl bg-green-50 dark:bg-green-900/20 p-5">

                <div class="text-sm text-slate-500">

                    Jumlah SPJ

                </div>

                <div class="mt-2 text-xl font-bold">

                    {{ count($detailSPJ) }}

                </div>

            </div>

            <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 p-5">

                <div class="text-sm text-slate-500">

                    Total Nominal

                </div>

                <div class="mt-2 text-xl font-bold">

                    Rp {{ number_format($detailSPJ->sum('spj_nominal'), 0, ',', '.') }}

                </div>

            </div>

        </div>
        @if ($bulan)

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="p-6 border-b border-slate-200 dark:border-slate-800">

                    {{-- GANTI HEADER DETAIL --}}

                    <h3 class="text-xl font-bold dark:text-white">

                        Detail Realisasi SPJ

                    </h3>

                    <p class="text-sm text-slate-500 mt-1">

                        {{ $chartSPJ[$bulan - 1]['bulan'] }}

                        •

                        {{ count($detailSPJ) }} SPJ

                    </p>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="border-b border-slate-200 dark:border-slate-800">

                                <th class="text-left p-4">

                                    Tanggal

                                </th>

                                <th class="text-left p-4">

                                    Unit

                                </th>

                                <th class="text-left p-4">

                                    Program

                                </th>

                                <th class="text-left p-4">

                                    Sub Kegiatan

                                </th>

                                <th class="text-left p-4">

                                    Operator

                                </th>

                                <th class="text-right p-4">

                                    Nominal

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($detailSPJ as $item)
                                <tr class="border-b border-slate-100 dark:border-slate-800">

                                    <td class="p-4">

                                        {{ \Carbon\Carbon::parse($item->spj_tanggal)->format('d/m/Y') }}

                                    </td>

                                    <td class="p-4">

                                        {{ $item->pagu->unit->unit_nama ?? '-' }}

                                    </td>

                                    <td class="p-4">

                                        {{ $item->pagu->program->program_nama ?? '-' }}

                                    </td>

                                    <td class="p-4">

                                        {{ $item->pagu->subKegiatan->sub_kegiatan_nama ?? '-' }}

                                    </td>

                                    <td class="p-4">

                                        {{ $item->spj_operator_nama }}

                                    </td>

                                    <td class="p-4 text-right font-semibold">

                                        Rp {{ number_format($item->spj_nominal, 0, ',', '.') }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center py-10 text-slate-500">

                                        Tidak ada SPJ.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @endif
        {{-- TEPAT SETELAH DETAIL SPJ --}}
        @if ($bulan)
            <div class="flex justify-end">

                <a href="?tahun={{ $tahun }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-700 hover:bg-slate-800 text-white px-4 py-2">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- BAGIAN PENERIMAAN PAD --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="xl:col-span-2">
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-lg font-bold dark:text-white">Grafik Penerimaan PAD Bulanan</h3>
                        <p class="text-sm text-slate-500">Monitoring penerimaan PAD Tahun {{ $tahun }}</p>
                    </div>

                    <div class="p-6">
                        <div id="chartPAD" class="h-96"></div>
                    </div>

                    <div class="border-t border-slate-200 dark:border-slate-800">

                        <div class="px-6 py-5">
                            <h3 class="text-xl font-bold dark:text-white">Rekap Penerimaan PAD Bulanan</h3>
                            <p class="text-sm text-slate-500">Jumlah transaksi dan total penerimaan PAD setiap bulan.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr
                                        class="border-y border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800">
                                        <th class="text-left p-4">Bulan</th>
                                        <th class="text-center p-4">Jumlah Penerimaan</th>
                                        <th class="text-right p-4">Total Nominal</th>
                                        <th class="text-center p-4">Capaian</th>
                                        <th class="text-center p-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chartPAD as $i => $item)
                                        @php
                                            $persenBulanPAD =
                                                $totalRealisasiPAD > 0
                                                    ? ($item['nominal'] / $totalRealisasiPAD) * 100
                                                    : 0;
                                        @endphp
                                        <tr
                                            class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800">
                                            <td class="p-4 font-medium">{{ $item['bulan'] }}</td>
                                            <td class="p-4 text-center">
                                                <span
                                                    class="inline-flex rounded-xl bg-green-100 dark:bg-green-900/20 px-3 py-1 text-green-700 dark:text-green-300 font-semibold">
                                                    {{ $item['jumlah'] }} Penerimaan
                                                </span>
                                            </td>
                                            <td class="p-4 text-right font-semibold">
                                                Rp {{ number_format($item['nominal'], 0, ',', '.') }}
                                            </td>
                                            <td class="p-4 text-center">
                                                <span
                                                    class="inline-flex rounded-xl bg-blue-100 dark:bg-blue-900/20 px-3 py-1 text-blue-700 dark:text-blue-300">
                                                    {{ number_format($persenBulanPAD, 1, ',', '.') }}%
                                                </span>
                                            </td>
                                            <td class="p-4 text-center">
                                                <a href="?tahun={{ $tahun }}&bulanPAD={{ $i + 1 }}"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 px-4 py-2 text-sm text-white">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div>
                <div
                    class="rounded-3xl overflow-hidden bg-gradient-to-br from-emerald-600 via-green-600 to-teal-500 text-white shadow-xl">
                    <div class="p-7">

                        <h3 class="text-2xl font-bold">Status Penerimaan PAD</h3>
                        <p class="mt-2 text-green-100">Monitoring penerimaan PAD secara realtime.</p>

                        <div class="mt-8">
                            <div class="flex justify-between text-sm">
                                <span>Capaian PAD</span>
                                <span>{{ number_format($persenPAD, 2, ',', '.') }}%</span>
                            </div>
                            <div class="mt-3 h-3 rounded-full bg-white/20">
                                <div class="h-3 rounded-full bg-white"
                                    style="width: {{ min(max($persenPAD, 0), 100) }}%"></div>
                            </div>
                        </div>

                        <div class="mt-8 space-y-5">
                            <div class="flex justify-between">
                                <span class="text-green-100">Target PAD</span>
                                <strong>Rp {{ number_format($totalTargetPAD, 0, ',', '.') }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-100">Realisasi</span>
                                <strong>Rp {{ number_format($totalRealisasiPAD, 0, ',', '.') }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-100">Sisa</span>
                                <strong>Rp {{ number_format($sisaPAD, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <a href="{{ route('admin.laporan-pad.index') }}"
                            class="mt-8 inline-flex w-full items-center justify-center rounded-2xl bg-white py-3 font-semibold text-green-700 hover:bg-green-50 transition">
                            Lihat Laporan PAD
                        </a>

                    </div>
                </div>
            </div>

        </div>

        @if ($bulanPAD)

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

                <div class="rounded-2xl bg-green-50 dark:bg-green-900/20 p-5">
                    <div class="text-sm text-slate-500">Bulan</div>
                    <div class="mt-2 text-xl font-bold">{{ $chartPAD[$bulanPAD - 1]['bulan'] ?? '-' }}</div>
                </div>

                <div class="rounded-2xl bg-blue-50 dark:bg-blue-900/20 p-5">
                    <div class="text-sm text-slate-500">Jumlah Penerimaan</div>
                    <div class="mt-2 text-xl font-bold">{{ count($detailPAD) }}</div>
                </div>

                <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 p-5">
                    <div class="text-sm text-slate-500">Total Penerimaan</div>
                    <div class="mt-2 text-xl font-bold">
                        Rp {{ number_format($detailPAD->sum('pad_realisasi_nominal'), 0, ',', '.') }}
                    </div>
                </div>

            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-xl font-bold dark:text-white">Detail Penerimaan PAD</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $chartPAD[$bulanPAD - 1]['bulan'] ?? '-' }} • {{ count($detailPAD) }} Penerimaan
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">

                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800">
                                <th class="text-left p-4">Tanggal</th>
                                <th class="text-left p-4">Unit</th>
                                <th class="text-left p-4">Jenis PAD</th>
                                <th class="text-left p-4">Komponen</th>
                                <th class="text-left p-4">Sub Komponen</th>
                                <th class="text-left p-4">Keterangan</th>
                                <th class="text-right p-4">Nominal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($detailPAD as $item)
                                <tr class="border-b border-slate-100 dark:border-slate-800">

                                    <td class="p-4">
                                        {{ \Carbon\Carbon::parse($item->pad_realisasi_tanggal)->format('d/m/Y') }}
                                    </td>

                                    <td class="p-4">
                                        {{ $item->target->pad_target_unit_nama ?? '-' }}
                                    </td>

                                    <td class="p-4">
                                        {{ $item->target->jenis->pad_jenis_nama ?? '-' }}
                                    </td>

                                    <td class="p-4">
                                        {{ $item->target->komponen->pad_komponen_nama ?? '-' }}
                                    </td>

                                    <td class="p-4">
                                        {{ $item->subkomponen->pad_subkomponen_nama ?? '-' }}
                                    </td>

                                    <td class="p-4">
                                        {{ $item->pad_realisasi_keterangan ?? '-' }}
                                    </td>

                                    <td class="p-4 text-right font-semibold">
                                        Rp {{ number_format($item->pad_realisasi_nominal, 0, ',', '.') }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-slate-500">
                                        Tidak ada penerimaan PAD.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>

            <div class="flex justify-end">
                <a href="?tahun={{ $tahun }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-700 hover:bg-slate-800 text-white px-4 py-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

        @endif

        {{-- Pagu Terbaru --}}
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">

                <div>

                    <h3 class="text-lg font-bold dark:text-white">

                        Data Pagu SPJ Terbaru

                    </h3>

                    <p class="text-sm text-slate-500">

                        5 Data Terbaru Tahun {{ $tahun }}

                    </p>

                </div>

                <a href="{{ route('admin.spj.index') }}"
                    class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-3">

                    Lihat Semua

                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-slate-200 dark:border-slate-800">

                            <th class="text-left p-5">

                                Sub Kegiatan

                            </th>

                            <th class="text-right p-5">

                                Pagu

                            </th>

                            <th class="text-right p-5">

                                Realisasi

                            </th>

                            <th class="text-right p-5">

                                Sisa

                            </th>

                            <th class="text-center p-5">

                                Status

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($paguTerbaru as $item)
                            @php

                                $realisasiItem = $item->realisasi

                                    ->where('spj_status', 'Aktif')

                                    ->sum('spj_nominal');

                                $sisaItem = $item->spj_pagu_final - $realisasiItem;

                            @endphp

                            <tr
                                class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition">

                                <td class="p-5">

                                    <div class="font-semibold dark:text-white">

                                        {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}

                                    </div>

                                    <div class="text-xs text-slate-500">

                                        {{ $item->program->program_nama ?? '-' }}

                                    </div>

                                </td>

                                <td class="text-right p-5">

                                    Rp {{ number_format($item->spj_pagu_final, 0, ',', '.') }}

                                </td>

                                <td class="text-right p-5 text-green-600">

                                    Rp {{ number_format($realisasiItem, 0, ',', '.') }}

                                </td>

                                <td class="text-right p-5 text-amber-500">

                                    Rp {{ number_format($sisaItem, 0, ',', '.') }}

                                </td>

                                <td class="text-center p-5">

                                    @if ($item->spj_pagu_status == 1)
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">

                                            Aktif

                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-10 text-slate-500">

                                    Belum ada data pagu SPJ.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
        {{-- Bottom --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Ringkasan Sistem --}}
            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="p-6 border-b border-slate-200 dark:border-slate-800">

                    <h3 class="text-lg font-bold dark:text-white">

                        Ringkasan Sistem

                    </h3>

                    <p class="text-sm text-slate-500">

                        Statistik SAPLARIN

                    </p>

                </div>

                <div class="p-6 space-y-6">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">

                                Total User

                            </p>

                            <h4 class="text-2xl font-bold dark:text-white">

                                {{ number_format($totalUser, 0, ',', '.') }}

                            </h4>

                        </div>

                        <div
                            class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">

                            <i class="bi bi-people text-blue-600 text-2xl"></i>

                        </div>

                    </div>

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">

                                Pengajuan BBM

                            </p>

                            <h4 class="text-2xl font-bold dark:text-white">

                                {{ number_format($jumlahBBM, 0, ',', '.') }}

                            </h4>

                        </div>

                        <div
                            class="w-14 h-14 rounded-2xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">

                            <i class="bi bi-fuel-pump text-yellow-600 text-2xl"></i>

                        </div>

                    </div>

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">

                                Menunggu Verifikasi

                            </p>

                            <h4 class="text-2xl font-bold text-orange-500">

                                {{ number_format($bbmMenunggu, 0, ',', '.') }}

                            </h4>

                        </div>

                        <div
                            class="w-14 h-14 rounded-2xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">

                            <i class="bi bi-clock-history text-orange-600 text-2xl"></i>

                        </div>

                    </div>

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">

                                Program Prioritas

                            </p>

                            <h4 class="text-2xl font-bold dark:text-white">

                                {{ number_format($jumlahPrioritas, 0, ',', '.') }}

                            </h4>

                        </div>

                        <div
                            class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">

                            <i class="bi bi-kanban text-green-600 text-2xl"></i>

                        </div>

                    </div>

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">

                                Aktivitas

                            </p>

                            <h4 class="text-2xl font-bold dark:text-white">

                                {{ number_format($jumlahAktivitas, 0, ',', '.') }}

                            </h4>

                        </div>

                        <div
                            class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">

                            <i class="bi bi-clipboard-data text-indigo-600 text-2xl"></i>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Progress Serapan --}}
            <div
                class="xl:col-span-2 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="p-6 border-b border-slate-200 dark:border-slate-800">

                    <h3 class="text-lg font-bold dark:text-white">

                        Progress Serapan Anggaran

                    </h3>

                    <p class="text-sm text-slate-500">

                        Perbandingan Pagu dan Realisasi

                    </p>

                </div>

                <div class="p-6 space-y-6">

                    <div>

                        <div class="flex justify-between mb-2">

                            <span class="dark:text-white">

                                Persentase Serapan

                            </span>

                            <strong class="dark:text-white">

                                {{ number_format($persenSerapan, 2, ',', '.') }}%

                            </strong>

                        </div>

                        <div class="h-4 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">

                            <div class="h-4 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600"
                                style="width: {{ $persenSerapan }}%">

                            </div>

                        </div>

                    </div>

                    <div class="grid md:grid-cols-3 gap-5">

                        <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-5">

                            <div class="text-sm text-slate-500">

                                Total Pagu

                            </div>

                            <div class="mt-2 text-xl font-bold dark:text-white">

                                Rp {{ number_format($totalPagu, 0, ',', '.') }}

                            </div>

                        </div>

                        <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-5">

                            <div class="text-sm text-slate-500">

                                Total Realisasi

                            </div>

                            <div class="mt-2 text-xl font-bold text-green-600">

                                Rp {{ number_format($totalRealisasiSPJ, 0, ',', '.') }}

                            </div>

                        </div>

                        <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-5">

                            <div class="text-sm text-slate-500">

                                Sisa Anggaran

                            </div>

                            <div class="mt-2 text-xl font-bold text-amber-500">

                                Rp {{ number_format($sisaPagu, 0, ',', '.') }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <div id="releaseModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center
           bg-black/60 backdrop-blur-sm
           p-3 sm:p-6
           overflow-y-auto">

        {{-- Tombol close selalu di pojok layar --}}
        <button onclick="closeReleaseModal()"
            class="fixed top-4 right-4 sm:top-6 sm:right-6
               w-11 h-11 sm:w-12 sm:h-12
               rounded-full bg-white shadow-xl
               z-[10000]
               flex items-center justify-center
               text-slate-700
               hover:bg-slate-100
               transition">

            <i class="bi bi-x-lg text-lg"></i>

        </button>

        <div class="relative w-full flex justify-center items-center">

            <img src="{{ asset('image/release/build-1121.26.1101.png') }}"
                class="block
                   w-auto
                   max-w-full
                   max-h-[calc(100vh-32px)]
                   sm:max-h-[90vh]
                   rounded-2xl sm:rounded-3xl
                   shadow-2xl
                   object-contain">

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            if (localStorage.getItem('release_1121.26.1101')) {

                return;

            }

            setTimeout(function() {

                $('#releaseModal')
                    .removeClass('hidden')
                    .addClass('flex');

                $('body').addClass('overflow-hidden');

            }, 500);

        });

        function closeReleaseModal() {

            localStorage.setItem('release_1121.26.1101', true);

            $('#releaseModal')
                .removeClass('flex')
                .addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }
        document.addEventListener("DOMContentLoaded", function() {

            const options = {

                chart: {
                    type: 'area',
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },

                series: [{
                    name: 'Jumlah SPJ',
                    data: @json(collect($chartSPJ)->pluck('jumlah'))
                }],

                xaxis: {
                    categories: @json(collect($chartSPJ)->pluck('bulan'))
                },

                yaxis: {
                    title: {
                        text: 'Jumlah SPJ'
                    }
                },

                stroke: {
                    curve: 'smooth',
                    width: 3
                },

                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + ' SPJ';
                        }
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.55,
                        opacityTo: 0.05
                    }
                },

                markers: {
                    size: 6,
                    hover: {
                        size: 8
                    }
                },

                dataLabels: {
                    enabled: true
                },

                grid: {
                    borderColor: '#e5e7eb'
                },

                colors: ['#2563eb']

            };

            const chart = new ApexCharts(
                document.querySelector("#chartRealisasi"),
                options
            );

            setTimeout(function() {

                chart.render();

            }, 500);

            window.addEventListener('resize', function() {

                chart.updateOptions({}, false, true);

            });

            document.addEventListener('sidebar-toggled', function() {

                setTimeout(function() {

                    chart.updateOptions({}, false, true);

                }, 350);

            });

        });

        document.addEventListener("DOMContentLoaded", function() {

            const elementPAD = document.querySelector("#chartPAD");

            if (!elementPAD) {
                return;
            }

            const optionsPAD = {
                chart: {
                    type: 'area',
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },

                series: [{
                    name: 'Penerimaan PAD',
                    data: @json(collect($chartPAD)->pluck('nominal'))
                }],

                xaxis: {
                    categories: @json(collect($chartPAD)->pluck('bulan'))
                },

                yaxis: {
                    title: {
                        text: 'Penerimaan PAD'
                    },
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                        }
                    }
                },

                stroke: {
                    curve: 'smooth',
                    width: 3
                },

                tooltip: {
                    y: {
                        formatter: function(value) {
                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                        }
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.55,
                        opacityTo: 0.05
                    }
                },

                markers: {
                    size: 6,
                    hover: {
                        size: 8
                    }
                },

                dataLabels: {
                    enabled: false
                },

                grid: {
                    borderColor: '#e5e7eb'
                },

                colors: ['#16a34a']
            };

            const chartPAD = new ApexCharts(elementPAD, optionsPAD);

            setTimeout(function() {
                chartPAD.render();
            }, 500);

            window.addEventListener('resize', function() {
                chartPAD.updateOptions({}, false, true);
            });

            document.addEventListener('sidebar-toggled', function() {
                setTimeout(function() {
                    chartPAD.updateOptions({}, false, true);
                }, 350);
            });

        });
    </script>
@endpush
