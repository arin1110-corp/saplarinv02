@extends('administrator-v2.layouts.app')

@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-description','Selamat datang di SAPLARIN')

@section('content')

<div class="space-y-6">

    {{-- Statistic --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-slate-500 text-sm">
                        Total Pagu
                    </p>

                    <h2 class="text-3xl font-bold mt-2 dark:text-white">
                        Rp 2.4 M
                    </h2>

                    <span class="text-green-600 text-sm mt-2 inline-block">
                        ↑ 12%
                    </span>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">

                    💰

                </div>

            </div>

        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500 text-sm">
                        Total SPJ
                    </p>

                    <h2 class="text-3xl font-bold mt-2 dark:text-white">
                        1.284
                    </h2>

                    <span class="text-blue-600 text-sm">
                        +83 Hari Ini
                    </span>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">

                    📄

                </div>

            </div>

        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500 text-sm">
                        User Aktif
                    </p>

                    <h2 class="text-3xl font-bold mt-2 dark:text-white">
                        86
                    </h2>

                    <span class="text-green-600 text-sm">
                        Online
                    </span>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">

                    👥

                </div>

            </div>

        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">

            <div class="flex justify-between">

                <div>

                    <p class="text-slate-500 text-sm">
                        Serapan
                    </p>

                    <h2 class="text-3xl font-bold mt-2 dark:text-white">
                        74%
                    </h2>

                    <span class="text-red-500 text-sm">
                        Target 80%
                    </span>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">

                    📊

                </div>

            </div>

        </div>

    </div>

    {{-- Chart --}}
    <div class="grid xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2">

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="p-6 border-b dark:border-slate-800">

                    <h3 class="font-bold text-lg dark:text-white">
                        Grafik Realisasi
                    </h3>

                </div>

                <div class="p-6">

                    <div id="realisasiChart" class="h-96"></div>

                </div>

            </div>

        </div>

        <div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="p-6 border-b dark:border-slate-800">

                    <h3 class="font-bold dark:text-white">
                        Progress Bidang
                    </h3>

                </div>

                <div class="space-y-5 p-6">

                    @foreach([
                        ['Sekretariat',88],
                        ['Cagar Budaya',62],
                        ['Kesenian',91],
                        ['Tradisi',54],
                        ['UPTD',76]
                    ] as $row)

                        <div>

                            <div class="flex justify-between mb-2">

                                <span class="text-sm dark:text-white">
                                    {{ $row[0] }}
                                </span>

                                <span class="font-semibold dark:text-white">
                                    {{ $row[1] }}%
                                </span>

                            </div>

                            <div class="h-3 rounded-full bg-slate-200 dark:bg-slate-700">

                                <div
                                    class="h-3 rounded-full bg-blue-600"
                                    style="width:{{ $row[1] }}%">

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

    {{-- Activity --}}
    <div class="grid xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2">

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="flex justify-between items-center p-6 border-b dark:border-slate-800">

                    <h3 class="font-bold text-lg dark:text-white">
                        SPJ Terbaru
                    </h3>

                    <button class="text-blue-600 text-sm">
                        Lihat Semua
                    </button>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                        <tr class="border-b dark:border-slate-800">

                            <th class="text-left p-4">Nomor</th>
                            <th class="text-left p-4">Bidang</th>
                            <th class="text-left p-4">Nominal</th>
                            <th class="text-left p-4">Status</th>

                        </tr>

                        </thead>

                        <tbody>

                        @for($i=1;$i<=8;$i++)

                            <tr class="border-b dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800">

                                <td class="p-4 dark:text-white">
                                    SPJ-2026-00{{ $i }}
                                </td>

                                <td class="p-4 dark:text-white">
                                    Kesenian
                                </td>

                                <td class="p-4 dark:text-white">
                                    Rp 12.500.000
                                </td>

                                <td class="p-4">

                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">

                                        Disetujui

                                    </span>

                                </td>

                            </tr>

                        @endfor

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">

                <div class="p-6 border-b dark:border-slate-800">

                    <h3 class="font-bold dark:text-white">

                        Aktivitas Terbaru

                    </h3>

                </div>

                <div class="divide-y dark:divide-slate-800">

                    @for($i=1;$i<=8;$i++)

                        <div class="flex gap-4 p-5">

                            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">

                                👤

                            </div>

                            <div>

                                <h5 class="font-semibold dark:text-white">

                                    Indra Ardika

                                </h5>

                                <p class="text-sm text-slate-500">

                                    Menginput SPJ baru

                                </p>

                                <small class="text-slate-400">

                                    {{ $i }} menit lalu

                                </small>

                            </div>

                        </div>

                    @endfor

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

new ApexCharts(document.querySelector("#realisasiChart"),{

    chart:{
        height:350,
        type:'area',
        toolbar:{show:false}
    },

    series:[{

        name:'Realisasi',

        data:[12,22,19,32,41,52,46,63,72,68,81,93]

    }],

    stroke:{
        curve:'smooth',
        width:4
    },

    fill:{
        type:'gradient',
        gradient:{
            opacityFrom:.5,
            opacityTo:.05
        }
    },

    colors:['#2563eb'],

    xaxis:{
        categories:[
            'Jan','Feb','Mar','Apr','Mei','Jun',
            'Jul','Ags','Sep','Okt','Nov','Des'
        ]
    },

    grid:{
        borderColor:'#e5e7eb'
    }

}).render();

</script>

@endpush