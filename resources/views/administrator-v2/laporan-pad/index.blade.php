@extends('administrator-v2.layouts.app')

@section('title', 'Laporan PAD')
@section('page_title', 'Laporan PAD')
@section('breadcrumb', 'Laporan PAD')

@section('content')

    <div class="space-y-6">


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            <div>

                <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                    Laporan PAD
                </h1>

                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    Grafik target dan realisasi Pendapatan Asli Daerah berdasarkan penerimaan aktif.
                </p>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- FILTER --}}
        {{-- ========================================================= --}}

        <div
            class="rounded-3xl
                border
                border-slate-200
                dark:border-slate-800

                bg-white
                dark:bg-slate-900

                shadow-sm
                p-6">

            <form method="GET" action="{{ route('admin.laporan-pad.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                    {{-- TAHUN --}}

                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Tahun Anggaran
                        </label>

                        <select name="tahun"
                            class="w-full rounded-2xl
                               border border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-800

                               px-4 py-3

                               text-slate-800
                               dark:text-white">

                            @foreach ($tahunList as $item)
                                <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>

                                    {{ $item }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- UNIT --}}

                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Unit
                        </label>

                        <select name="unit"
                            class="w-full rounded-2xl
                               border border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-800

                               px-4 py-3

                               text-slate-800
                               dark:text-white">

                            <option value="">
                                Semua Unit
                            </option>

                            @foreach ($unitList as $item)
                                <option value="{{ $item->pad_target_unit }}"
                                    {{ $unit == $item->pad_target_unit ? 'selected' : '' }}>

                                    {{ $item->pad_target_unit }}
                                    -
                                    {{ $item->pad_target_unit_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTON --}}

                    <div class="flex items-end">

                        <button
                            class="w-full
                               rounded-2xl

                               bg-blue-600
                               hover:bg-blue-700

                               text-white
                               font-semibold

                               px-5 py-3">

                            <i class="bi bi-search me-2"></i>

                            Tampilkan

                        </button>

                    </div>

                </div>

            </form>

        </div>



        {{-- ========================================================= --}}
        {{-- CARDS --}}
        {{-- ========================================================= --}}

        <div
            class="grid
                grid-cols-1
                md:grid-cols-2
                xl:grid-cols-4
                gap-5">


            {{-- TARGET --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    p-6
                    shadow-sm">

                <div class="text-sm
                        text-slate-500
                        dark:text-slate-400">

                    Target PAD

                </div>

                <div
                    class="mt-3
                        text-3xl
                        font-bold

                        text-blue-600
                        dark:text-blue-400">

                    Rp
                    {{ number_format($totalTarget, 0, ',', '.') }}

                </div>

            </div>


            {{-- REALISASI --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    p-6
                    shadow-sm">

                <div class="text-sm
                        text-slate-500
                        dark:text-slate-400">

                    Total Realisasi

                </div>

                <div
                    class="mt-3
                        text-3xl
                        font-bold

                        text-green-600
                        dark:text-green-400">

                    Rp
                    {{ number_format($totalRealisasi, 0, ',', '.') }}

                </div>

            </div>


            {{-- SISA --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    p-6
                    shadow-sm">

                <div class="text-sm
                        text-slate-500
                        dark:text-slate-400">

                    Sisa Target

                </div>

                <div
                    class="mt-3
                        text-3xl
                        font-bold

                        text-amber-500">

                    Rp
                    {{ number_format($sisaTarget, 0, ',', '.') }}

                </div>

            </div>


            {{-- PERSENTASE --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    p-6
                    shadow-sm">

                <div class="text-sm
                        text-slate-500
                        dark:text-slate-400">

                    Realisasi

                </div>

                <div
                    class="mt-3
                        text-3xl
                        font-bold

                        text-sky-500">

                    {{ number_format($persenRealisasi, 2, ',', '.') }}%

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- CHART --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">


            {{-- PIE TARGET --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    shadow-sm
                    overflow-hidden">

                <div
                    class="px-6 py-5
                        border-b
                        border-slate-200
                        dark:border-slate-800">

                    <div class="flex items-center justify-between">

                        <div>

                            <h2
                                class="text-xl
                                   font-bold
                                   text-slate-800
                                   dark:text-white">

                                Pie Target PAD

                            </h2>

                            <p
                                class="text-sm
                                  text-slate-500
                                  dark:text-slate-400
                                  mt-1">

                                Komposisi realisasi dan sisa target.

                            </p>

                        </div>


                        <div class="text-right">

                            <div
                                class="text-xs
                                    text-slate-500
                                    dark:text-slate-400">

                                Realisasi

                            </div>

                            <div
                                class="text-2xl
                                    font-bold
                                    text-blue-600
                                    dark:text-blue-400">

                                {{ number_format($persenRealisasi, 2, ',', '.') }}%

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



            {{-- PIE UNIT --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    shadow-sm
                    overflow-hidden">

                <div
                    class="px-6 py-5
                        border-b
                        border-slate-200
                        dark:border-slate-800">

                    <h2
                        class="text-xl
                           font-bold
                           text-slate-800
                           dark:text-white">

                        Pie Realisasi per Unit

                    </h2>

                    <p
                        class="text-sm
                          text-slate-500
                          dark:text-slate-400
                          mt-1">

                        Komposisi realisasi PAD berdasarkan unit.

                    </p>

                </div>


                <div class="p-6">

                    <div class="h-[420px]">

                        <canvas id="chartUnit"></canvas>

                    </div>

                </div>

            </div>



            {{-- PIE JENIS --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    shadow-sm
                    overflow-hidden">

                <div
                    class="px-6 py-5
                        border-b
                        border-slate-200
                        dark:border-slate-800">

                    <h2
                        class="text-xl
                           font-bold
                           text-slate-800
                           dark:text-white">

                        Pie Realisasi per Jenis PAD

                    </h2>

                    <p
                        class="text-sm
                          text-slate-500
                          dark:text-slate-400
                          mt-1">

                        Komposisi realisasi berdasarkan jenis PAD.

                    </p>

                </div>


                <div class="p-6">

                    <div class="h-[420px]">

                        <canvas id="chartJenis"></canvas>

                    </div>

                </div>

            </div>



            {{-- PIE KOMPONEN --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900

                    border
                    border-slate-200
                    dark:border-slate-800

                    shadow-sm
                    overflow-hidden">

                <div
                    class="px-6 py-5
                        border-b
                        border-slate-200
                        dark:border-slate-800">

                    <h2
                        class="text-xl
                           font-bold
                           text-slate-800
                           dark:text-white">

                        Pie Realisasi per Komponen

                    </h2>

                    <p
                        class="text-sm
                          text-slate-500
                          dark:text-slate-400
                          mt-1">

                        Komposisi realisasi berdasarkan komponen PAD.

                    </p>

                </div>


                <div class="p-6">

                    <div class="h-[420px]">

                        <canvas id="chartKomponen"></canvas>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- TABEL REKAP --}}
        {{-- ========================================================= --}}

        <div
            class="rounded-3xl
                bg-white
                dark:bg-slate-900

                border
                border-slate-200
                dark:border-slate-800

                shadow-sm
                overflow-hidden">


            <div
                class="px-6 py-5
                    border-b
                    border-slate-200
                    dark:border-slate-800">

                <h2
                    class="text-xl
                       font-bold
                       text-slate-800
                       dark:text-white">

                    Ringkasan PAD

                </h2>

                <p
                    class="text-sm
                      text-slate-500
                      dark:text-slate-400
                      mt-1">

                    Rekap target dan realisasi berdasarkan unit dan komponen PAD.

                </p>

            </div>



            {{-- SEARCH --}}

            <div class="px-6 pt-5">

                <form method="GET">

                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <input type="hidden" name="unit" value="{{ $unit }}">

                    <div class="relative w-full md:w-96">

                        <i
                            class="bi bi-search
                              absolute
                              left-4
                              top-1/2
                              -translate-y-1/2
                              text-slate-400"></i>

                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari Unit / Jenis / Komponen..."
                            class="w-full
                               rounded-2xl

                               border
                               border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-900

                               pl-11
                               pr-4
                               py-3">

                    </div>

                </form>

            </div>



            {{-- TABLE --}}

            <div class="overflow-x-auto mt-5">

                <table class="min-w-full text-sm">

                    <thead class="bg-slate-100 dark:bg-slate-800">

                        <tr>

                            <th class="px-3 py-4 text-left">
                                No
                            </th>

                            <th class="px-5 py-4 text-left">
                                Unit
                            </th>

                            <th class="px-5 py-4 text-left">
                                Jenis PAD
                            </th>

                            <th class="px-5 py-4 text-left">
                                Komponen
                            </th>

                            <th class="px-5 py-4 text-right">
                                Target
                            </th>

                            <th class="px-5 py-4 text-right">
                                Realisasi
                            </th>

                            <th class="px-5 py-4 text-right">
                                Sisa
                            </th>

                            <th class="px-5 py-4 text-right">
                                %
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($pagus as $item)
                            <tr
                                class="border-b
                                   border-slate-200
                                   dark:border-slate-800">

                                <td class="px-5 py-4">

                                    {{ $pagus->firstItem() + $loop->index }}

                                </td>


                                {{-- UNIT --}}

                                <td class="px-5 py-4">

                                    <div class="font-semibold">

                                        {{ $item->pad_target_unit_kode ?: '-' }}

                                    </div>

                                    <div class="text-xs
                                            text-slate-500">

                                        {{ $item->pad_target_unit_nama ?: '-' }}

                                    </div>

                                </td>


                                {{-- JENIS --}}

                                <td class="px-5 py-4">

                                    <div class="font-semibold">

                                        {{ $item->jenis->pad_jenis_nama ?? '-' }}

                                    </div>

                                </td>


                                {{-- KOMPONEN --}}

                                <td class="px-5 py-4">

                                    <div class="font-semibold">

                                        {{ $item->komponen->pad_komponen_nama ?? '-' }}

                                    </div>

                                    <div class="text-xs
                                            text-slate-500">

                                        {{ $item->komponen->pad_komponen_kode ?? '-' }}

                                    </div>

                                </td>


                                {{-- TARGET --}}

                                <td
                                    class="px-5 py-4
                                       text-right
                                       font-medium">

                                    Rp
                                    {{ number_format($item->pad_target_nominal, 0, ',', '.') }}

                                </td>


                                {{-- REALISASI --}}

                                <td
                                    class="px-5 py-4
                                       text-right
                                       font-medium
                                       text-green-600
                                       dark:text-green-400">

                                    Rp
                                    {{ number_format($item->laporan_realisasi, 0, ',', '.') }}

                                </td>


                                {{-- SISA --}}

                                <td
                                    class="px-5 py-4
                                       text-right
                                       font-medium
                                       text-amber-600
                                       dark:text-amber-400">

                                    Rp
                                    {{ number_format($item->laporan_sisa, 0, ',', '.') }}

                                </td>


                                {{-- PERSEN --}}

                                <td class="px-5 py-4
                                       text-right">

                                    <span
                                        class="inline-flex
                                             items-center

                                             rounded-full

                                             bg-blue-100
                                             dark:bg-blue-900/20

                                             text-blue-700
                                             dark:text-blue-300

                                             px-3 py-1

                                             text-xs
                                             font-semibold">

                                        {{ number_format($item->laporan_persen, 2, ',', '.') }}%

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="py-12
                                       text-center

                                       text-slate-500
                                       dark:text-slate-400">

                                    Belum ada data.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- PAGINATION --}}

            <div
                class="flex
                    flex-col
                    md:flex-row

                    items-center
                    justify-between

                    gap-4

                    px-6
                    py-5

                    border-t
                    border-slate-200
                    dark:border-slate-700">

                <div class="text-sm
                        text-slate-500">

                    @if ($pagus->total() > 0)
                        Menampilkan

                        <span class="font-semibold">
                            {{ $pagus->firstItem() }}
                        </span>

                        -

                        <span class="font-semibold">
                            {{ $pagus->lastItem() }}
                        </span>

                        dari

                        <span class="font-semibold">
                            {{ $pagus->total() }}
                        </span>

                        data
                    @else
                        Tidak ada data
                    @endif

                </div>


                {{ $pagus->links() }}

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- CHART --}}
    {{-- ============================================================= --}}

    @push('scripts')
        <script>
            const chartSerapanLabels =
                @json($chartSerapan->pluck('label'));

            const chartSerapanData =
                @json($chartSerapan->pluck('total'));


            const chartUnitLabels =
                @json($chartUnit->pluck('label'));

            const chartUnitData =
                @json($chartUnit->pluck('realisasi'));


            const chartJenisLabels =
                @json($chartJenis->pluck('label'));

            const chartJenisData =
                @json($chartJenis->pluck('realisasi'));


            const chartKomponenLabels =
                @json($chartKomponen->pluck('label'));

            const chartKomponenData =
                @json($chartKomponen->pluck('realisasi'));


            function rupiah(value) {
                return 'Rp ' +
                    Number(value || 0)
                    .toLocaleString('id-ID');
            }


            function getPalette(canvasId) {

                if (canvasId === 'chartSerapan') {

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


            function createPieChart(
                canvasId,
                labels,
                data
            ) {

                const ctx =
                    document.getElementById(canvasId);


                if (!ctx) {
                    return;
                }


                if (
                    !data ||
                    data.length === 0 ||
                    data.every(
                        value => Number(value) <= 0
                    )
                ) {

                    ctx.parentElement.innerHTML = `
                <div class="h-full flex items-center justify-center
                            text-slate-500 dark:text-slate-400">

                    Belum ada data.

                </div>
            `;

                    return;

                }


                const palette =
                    getPalette(canvasId);


                new Chart(
                    ctx, {

                        type: 'doughnut',

                        data: {

                            labels: labels,

                            datasets: [{

                                data: data,

                                backgroundColor: data.map(
                                    (v, i) =>
                                    palette[
                                        i % palette.length
                                    ]
                                ),

                                hoverBackgroundColor: data.map(
                                    (v, i) =>
                                    palette[
                                        i % palette.length
                                    ]
                                ),

                                borderColor: '#ffffff',

                                borderWidth: 4,

                                hoverOffset: 14

                            }]

                        },

                        options: {

                            responsive: true,

                            maintainAspectRatio: false,

                            cutout: '58%',

                            plugins: {

                                legend: {

                                    position: 'bottom',

                                    labels: {

                                        color: document
                                            .documentElement
                                            .classList
                                            .contains('dark')

                                            ?
                                            '#e2e8f0' :
                                            '#334155',

                                        boxWidth: 14,

                                        boxHeight: 14,

                                        padding: 18,

                                        font: {

                                            size: 12,

                                            weight: '600'

                                        }

                                    }

                                },


                                tooltip: {

                                    callbacks: {

                                        label: function(context) {

                                            const total =
                                                context
                                                .dataset
                                                .data
                                                .reduce(
                                                    (a, b) =>
                                                    Number(a) +
                                                    Number(b),
                                                    0
                                                );


                                            const value =
                                                Number(
                                                    context.raw || 0
                                                );


                                            const persen =
                                                total > 0

                                                ?
                                                (
                                                    value /
                                                    total *
                                                    100
                                                ).toFixed(2)

                                                :
                                                0;


                                            return (
                                                context.label +
                                                ' : ' +
                                                rupiah(value) +
                                                ' (' +
                                                persen +
                                                '%)'
                                            );

                                        }

                                    }

                                }

                            }

                        }

                    }
                );

            }


            createPieChart(
                'chartSerapan',
                chartSerapanLabels,
                chartSerapanData
            );


            createPieChart(
                'chartUnit',
                chartUnitLabels,
                chartUnitData
            );


            createPieChart(
                'chartJenis',
                chartJenisLabels,
                chartJenisData
            );


            createPieChart(
                'chartKomponen',
                chartKomponenLabels,
                chartKomponenData
            );
        </script>
    @endpush

@endsection
