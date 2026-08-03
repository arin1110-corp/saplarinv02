@extends('administrator-v2.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('page-description', 'SAPLARIN Tahun Anggaran '.$tahun)

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

                    <h2 class="text-3xl font-bold mt-2 dark:text-white">

                        Rp {{ number_format($totalPagu,0,',','.') }}

                    </h2>

                    <p class="text-xs text-slate-500 mt-3">

                        {{ number_format($jumlahPaguSPJ,0,',','.') }}

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

                    <h2 class="text-3xl font-bold mt-2 text-green-600">

                        Rp {{ number_format($totalRealisasiSPJ,0,',','.') }}

                    </h2>

                    <p class="text-xs text-slate-500 mt-3">

                        {{ number_format($jumlahInputSPJ,0,',','.') }}

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

                    <h2 class="text-3xl font-bold mt-2 text-amber-500">

                        Rp {{ number_format($sisaPagu,0,',','.') }}

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

                        {{ number_format($persenSerapan,2,',','.') }}%

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

    {{-- Statistic 2 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500 text-sm">

                        Total User

                    </p>

                    <h2 class="text-3xl font-bold mt-2 dark:text-white">

                        {{ number_format($totalUser,0,',','.') }}

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

                        {{ number_format($jumlahBBM,0,',','.') }}

                    </h2>

                    <p class="text-xs text-yellow-500 mt-3">

                        {{ number_format($bbmMenunggu,0,',','.') }}

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

                        {{ number_format($jumlahPrioritas,0,',','.') }}

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

                        {{ number_format($jumlahAktivitas,0,',','.') }}

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

                    <h3 class="text-lg font-bold dark:text-white">

                        Grafik Realisasi SPJ

                    </h3>

                    <p class="text-sm text-slate-500">

                        Monitoring Serapan Anggaran Tahun {{ $tahun }}

                    </p>

                </div>

                <div class="p-6">

                    <div id="chartRealisasi" class="h-96"></div>

                </div>

            </div>

        </div>

        {{-- Status --}}
        <div>

            <div class="rounded-3xl overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-600 text-white shadow-xl">

                <div class="p-7">

                    <h3 class="text-2xl font-bold">

                        Status SAPLARIN

                    </h3>

                    <p class="mt-2 text-blue-100">

                        Monitoring BBM, Prioritas, Aktivitas dan SPJ secara realtime.

                    </p>

                    <div class="mt-8">

                        <div class="flex justify-between text-sm">

                            <span>Serapan</span>

                            <span>{{ number_format($persenSerapan,2,',','.') }}%</span>

                        </div>

                        <div class="mt-3 h-3 rounded-full bg-white/20">

                            <div

                                class="h-3 rounded-full bg-white"

                                style="width: {{ $persenSerapan }}%">

                            </div>

                        </div>

                    </div>

                    <div class="mt-8 space-y-5">

                        <div class="flex justify-between">

                            <span class="text-blue-100">

                                Total Pagu

                            </span>

                            <strong>

                                Rp {{ number_format($totalPagu,0,',','.') }}

                            </strong>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-blue-100">

                                Realisasi

                            </span>

                            <strong>

                                Rp {{ number_format($totalRealisasiSPJ,0,',','.') }}

                            </strong>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-blue-100">

                                Sisa

                            </span>

                            <strong>

                                Rp {{ number_format($sisaPagu,0,',','.') }}

                            </strong>

                        </div>

                    </div>

                    <a

                        href="{{ route('admin.spj.index') }}"

                        class="mt-8 inline-flex w-full items-center justify-center rounded-2xl bg-white py-3 font-semibold text-blue-700 hover:bg-blue-50 transition">

                        Kelola SPJ

                    </a>

                </div>

            </div>

        </div>

    </div>

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

            <a

                href="{{ route('admin.spj.index') }}"

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

                                ->where('spj_status','Aktif')

                                ->sum('spj_nominal');

                            $sisaItem =

                                $item->spj_pagu_final -

                                $realisasiItem;

                        @endphp

                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition">

                            <td class="p-5">

                                <div class="font-semibold dark:text-white">

                                    {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $item->program->program_nama ?? '-' }}

                                </div>

                            </td>

                            <td class="text-right p-5">

                                Rp {{ number_format($item->spj_pagu_final,0,',','.') }}

                            </td>

                            <td class="text-right p-5 text-green-600">

                                Rp {{ number_format($realisasiItem,0,',','.') }}

                            </td>

                            <td class="text-right p-5 text-amber-500">

                                Rp {{ number_format($sisaItem,0,',','.') }}

                            </td>

                            <td class="text-center p-5">

                                @if($item->spj_pagu_status==1)

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

                            {{ number_format($totalUser,0,',','.') }}

                        </h4>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">

                        <i class="bi bi-people text-blue-600 text-2xl"></i>

                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Pengajuan BBM

                        </p>

                        <h4 class="text-2xl font-bold dark:text-white">

                            {{ number_format($jumlahBBM,0,',','.') }}

                        </h4>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">

                        <i class="bi bi-fuel-pump text-yellow-600 text-2xl"></i>

                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Menunggu Verifikasi

                        </p>

                        <h4 class="text-2xl font-bold text-orange-500">

                            {{ number_format($bbmMenunggu,0,',','.') }}

                        </h4>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">

                        <i class="bi bi-clock-history text-orange-600 text-2xl"></i>

                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Program Prioritas

                        </p>

                        <h4 class="text-2xl font-bold dark:text-white">

                            {{ number_format($jumlahPrioritas,0,',','.') }}

                        </h4>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">

                        <i class="bi bi-kanban text-green-600 text-2xl"></i>

                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">

                            Aktivitas

                        </p>

                        <h4 class="text-2xl font-bold dark:text-white">

                            {{ number_format($jumlahAktivitas,0,',','.') }}

                        </h4>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">

                        <i class="bi bi-clipboard-data text-indigo-600 text-2xl"></i>

                    </div>

                </div>

            </div>

        </div>

        {{-- Progress Serapan --}}
        <div class="xl:col-span-2 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

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

                            {{ number_format($persenSerapan,2,',','.') }}%

                        </strong>

                    </div>

                    <div class="h-4 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">

                        <div

                            class="h-4 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600"

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

                            Rp {{ number_format($totalPagu,0,',','.') }}

                        </div>

                    </div>

                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-5">

                        <div class="text-sm text-slate-500">

                            Total Realisasi

                        </div>

                        <div class="mt-2 text-xl font-bold text-green-600">

                            Rp {{ number_format($totalRealisasiSPJ,0,',','.') }}

                        </div>

                    </div>

                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-5">

                        <div class="text-sm text-slate-500">

                            Sisa Anggaran

                        </div>

                        <div class="mt-2 text-xl font-bold text-amber-500">

                            Rp {{ number_format($sisaPagu,0,',','.') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener("DOMContentLoaded",function(){

    new ApexCharts(

        document.querySelector("#chartRealisasi"),

        {

            chart:{

                type:'area',

                height:360,

                toolbar:{show:false}

            },

            series:[{

                name:'Serapan',

                data:[

                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }},
                    {{ round($persenSerapan) }}

                ]

            }],

            xaxis:{

                categories:[

                    'Jan','Feb','Mar','Apr','Mei','Jun',

                    'Jul','Agu','Sep','Okt','Nov','Des'

                ]

            },

            stroke:{

                curve:'smooth',

                width:3

            },

            fill:{

                type:'gradient',

                gradient:{

                    opacityFrom:0.45,

                    opacityTo:0.05

                }

            },

            dataLabels:{

                enabled:false

            },

            grid:{

                borderColor:'#e5e7eb'

            },

            colors:['#2563eb']

        }

    ).render();

});

</script>

@endpush
