@extends('administrator-v2.layouts.app')

@section('title', 'Laporan SPJ')

@section('content')

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

            Laporan SPJ

        </h1>

        <p class="text-slate-500 dark:text-slate-400 mt-1">

            Grafik pie serapan pagu, unit dan sub kegiatan berdasarkan SPJ aktif.

        </p>

    </div>

</div>

<div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6 mb-6">

    <form method="GET" action="{{ route('admin.laporan-spj.index') }}">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div>

                <label class="block text-sm font-semibold mb-2">

                    Tahun Anggaran

                </label>

                <select
                    name="tahun"
                    class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    @foreach($tahunList as $item)

                    <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>

                        {{ $item }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="block text-sm font-semibold mb-2">

                    Unit

                </label>

                <select
                    name="unit_id"
                    class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    <option value="">

                        Semua Unit

                    </option>

                    @foreach($units as $unit)

                    <option value="{{ $unit->unit_id }}" {{ $unitId == $unit->unit_id ? 'selected' : '' }}>

                        {{ $unit->unit_kode }}

                        -

                        {{ $unit->unit_nama }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div class="flex items-end">

                <button
                    class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3">

                    <i class="bi bi-search me-2"></i>

                    Tampilkan

                </button>

            </div>

        </div>

    </form>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-sm">

        <div class="text-sm text-slate-500 dark:text-slate-400">

            Total Pagu

        </div>

        <div class="mt-3 text-3xl font-bold text-blue-600 dark:text-blue-400">

            Rp {{ number_format($totalPagu,0,',','.') }}

        </div>

    </div>

    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-sm">

        <div class="text-sm text-slate-500 dark:text-slate-400">

            Total Realisasi

        </div>

        <div class="mt-3 text-3xl font-bold text-green-600 dark:text-green-400">

            Rp {{ number_format($totalRealisasi,0,',','.') }}

        </div>

    </div>

    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-sm">

        <div class="text-sm text-slate-500 dark:text-slate-400">

            Sisa Pagu

        </div>

        <div class="mt-3 text-3xl font-bold text-amber-500">

            Rp {{ number_format($sisaPagu,0,',','.') }}

        </div>

    </div>

    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 shadow-sm">

        <div class="text-sm text-slate-500 dark:text-slate-400">

            Serapan

        </div>

        <div class="mt-3 text-3xl font-bold text-sky-500">

            {{ number_format($persenSerapan,2,',','.') }}%

        </div>

    </div>

</div>
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">

                        Pie Serapan Pagu

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                        Komposisi realisasi dan sisa pagu.

                    </p>

                </div>

                <div class="text-right">

                    <div class="text-xs text-slate-500 dark:text-slate-400">

                        Serapan

                    </div>

                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">

                        {{ number_format($persenSerapan,2,',','.') }}%

                    </div>

                </div>

            </div>

        </div>

        <div class="p-6">

            <div class="h-[420px]">

                <canvas id="chartSerapan"></canvas>

            </div>

        </div>

    </div>

    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">

            <h2 class="text-xl font-bold text-slate-800 dark:text-white">

                Pie Realisasi per Unit

            </h2>

            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                Komposisi realisasi SPJ aktif berdasarkan unit.

            </p>

        </div>

        <div class="p-6">

            <div class="h-[420px]">

                <canvas id="chartUnit"></canvas>

            </div>

        </div>

    </div>

    <div class="xl:col-span-2 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">

            <h2 class="text-xl font-bold text-slate-800 dark:text-white">

                Pie Realisasi per Sub Kegiatan

            </h2>

            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                Komposisi realisasi SPJ aktif berdasarkan sub kegiatan.

            </p>

        </div>

        <div class="p-6">

            <div class="h-[460px]">

                <canvas id="chartSubKegiatan"></canvas>

            </div>

        </div>

    </div>

</div>

<div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">

        <h2 class="text-xl font-bold text-slate-800 dark:text-white">

            Ringkasan Sub Kegiatan

        </h2>

        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">

            Rekap pagu dan realisasi berdasarkan sub kegiatan.

        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-100 dark:bg-slate-800">

                <tr>

                    <th class="px-5 py-4 text-left">

                        Unit

                    </th>

                    <th class="px-5 py-4 text-left">

                        Sub Kegiatan

                    </th>

                    <th class="px-5 py-4 text-right">

                        Pagu

                    </th>

                    <th class="px-5 py-4 text-right">

                        Realisasi

                    </th>

                    <th class="px-5 py-4 text-right">

                        Sisa

                    </th>

                    <th class="px-5 py-4 text-right">

                        Serapan

                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($chartSubKegiatan as $item)

                <tr class="border-b border-slate-200 dark:border-slate-800">

                    <td class="px-5 py-4">

                        {{ $item['unit'] }}

                    </td>

                    <td class="px-5 py-4">

                        {{ $item['label'] }}

                    </td>

                    <td class="px-5 py-4 text-right font-medium">

                        Rp {{ number_format($item['pagu'],0,',','.') }}

                    </td>

                    <td class="px-5 py-4 text-right font-medium text-green-600 dark:text-green-400">

                        Rp {{ number_format($item['realisasi'],0,',','.') }}

                    </td>

                    <td class="px-5 py-4 text-right font-medium text-amber-600 dark:text-amber-400">

                        Rp {{ number_format($item['sisa'],0,',','.') }}

                    </td>

                    <td class="px-5 py-4 text-right">

                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1 text-xs font-semibold">

                            {{ number_format($item['serapan'],2,',','.') }}%

                        </span>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="py-12 text-center text-slate-500 dark:text-slate-400">

                        Belum ada data.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
@push('scripts')

<script>

const chartSerapanLabels = @json($chartSerapan->pluck('label'));
const chartSerapanData   = @json($chartSerapan->pluck('total'));

const chartUnitLabels = @json($chartUnit->pluck('label'));
const chartUnitData   = @json($chartUnit->pluck('realisasi'));

const chartSubLabels = @json($chartSubKegiatan->pluck('label'));
const chartSubData   = @json($chartSubKegiatan->pluck('realisasi'));

function rupiah(value){

    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');

}

function getPalette(canvasId){

    if(canvasId === 'chartSerapan'){

        return [

            '#22c55e',

            '#ef4444'

        ];

    }

    return [

        '#3b82f6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6',
        '#06b6d4',
        '#f97316',
        '#ec4899',
        '#84cc16',
        '#14b8a6',
        '#6366f1',
        '#a855f7',
        '#eab308',
        '#0ea5e9',
        '#f43f5e',
        '#22c55e',
        '#fb7185',
        '#38bdf8',
        '#c084fc',
        '#4ade80'

    ];

}

function createPieChart(canvasId, labels, data){

    const ctx = document.getElementById(canvasId);

    if(!ctx){

        return;

    }

    if(!data || data.length === 0 || data.every(value => Number(value) <= 0)){

        ctx.parentElement.innerHTML = `
            <div class="h-full flex items-center justify-center text-slate-500 dark:text-slate-400">
                Belum ada data.
            </div>
        `;

        return;

    }

    const palette = getPalette(canvasId);

    new Chart(ctx,{

        type:'doughnut',

        data:{

            labels:labels,

            datasets:[{

                data:data,

                backgroundColor:data.map((v,i)=>palette[i%palette.length]),

                hoverBackgroundColor:data.map((v,i)=>palette[i%palette.length]),

                borderColor:'#ffffff',

                borderWidth:4,

                hoverOffset:14

            }]

        },

        options:{

            responsive:true,

            maintainAspectRatio:false,

            cutout:'58%',

            plugins:{

                legend:{

                    position:'bottom',

                    labels:{

                        color:document.documentElement.classList.contains('dark')
                            ? '#e2e8f0'
                            : '#334155',

                        boxWidth:14,

                        boxHeight:14,

                        padding:18,

                        font:{

                            size:12,

                            weight:'600'

                        }

                    }

                },

                tooltip:{

                    callbacks:{

                        label:function(context){

                            const total=context.dataset.data.reduce((a,b)=>Number(a)+Number(b),0);

                            const value=Number(context.raw||0);

                            const persen=total>0?((value/total)*100).toFixed(2):0;

                            return context.label+' : '+rupiah(value)+' ('+persen+'%)';

                        }

                    }

                }

            }

        }

    });

}

createPieChart('chartSerapan',chartSerapanLabels,chartSerapanData);

createPieChart('chartUnit',chartUnitLabels,chartUnitData);

createPieChart('chartSubKegiatan',chartSubLabels,chartSubData);

</script>

@endpush

@endsection