<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Laporan SPJ</title>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #0f172a;
        }

        input,
        select,
        textarea {
            background: #1e293b;
            border: 1px solid #334155;
            color: white;
        }
    </style>
</head>

<body class="text-white">

    <div class="flex min-h-screen">

        @include('administrator.partials.sidebar')

        <div class="flex-1 p-6 overflow-x-hidden">

            @include('administrator.partials.header')

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">
                        Laporan SPJ
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Grafik pie serapan pagu, unit, dan sub kegiatan berdasarkan SPJ aktif.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.laporan.spj') }}"
                class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 mb-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm text-slate-300 mb-2">
                            Tahun Anggaran
                        </label>

                        <select name="tahun" class="w-full rounded-xl px-4 py-3">
                            @foreach ($tahunList as $item)
                                <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-300 mb-2">
                            Unit
                        </label>

                        <select name="unit_id" class="w-full rounded-xl px-4 py-3">
                            <option value="">Semua Unit</option>

                            @foreach ($units as $unit)
                                <option value="{{ $unit->unit_id }}" {{ $unitId == $unit->unit_id ? 'selected' : '' }}>
                                    {{ $unit->unit_kode }} - {{ $unit->unit_nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-xl font-semibold">
                            Tampilkan
                        </button>
                    </div>

                </div>

            </form>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                    <p class="text-slate-400 text-sm">Total Pagu</p>
                    <h2 class="text-2xl font-bold mt-2">
                        Rp {{ number_format($totalPagu, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                    <p class="text-slate-400 text-sm">Total Realisasi</p>
                    <h2 class="text-2xl font-bold mt-2 text-green-300">
                        Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                    <p class="text-slate-400 text-sm">Sisa Pagu</p>
                    <h2 class="text-2xl font-bold mt-2 text-amber-300">
                        Rp {{ number_format($sisaPagu, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                    <p class="text-slate-400 text-sm">Serapan</p>
                    <h2 class="text-2xl font-bold mt-2 text-blue-300">
                        {{ number_format($persenSerapan, 2, ',', '.') }}%
                    </h2>
                </div>

            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 xl:col-span-2">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-lg font-bold">
                                Pie Serapan Pagu
                            </h2>

                            <p class="text-slate-400 text-sm">
                                Menampilkan komposisi realisasi dan sisa pagu.
                            </p>
                        </div>

                        <div class="text-sm text-slate-300">
                            Serapan:
                            <span class="font-bold text-blue-300">
                                {{ number_format($persenSerapan, 2, ',', '.') }}%
                            </span>
                        </div>
                    </div>

                    <div class="h-[420px]">
                        <canvas id="chartSerapan"></canvas>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">
                    <h2 class="text-lg font-bold mb-2">
                        Pie Realisasi per Unit
                    </h2>

                    <p class="text-slate-400 text-sm mb-5">
                        Komposisi realisasi SPJ aktif berdasarkan unit pengampu.
                    </p>

                    <div class="h-[420px]">
                        <canvas id="chartUnit"></canvas>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">
                    <h2 class="text-lg font-bold mb-2">
                        Pie Realisasi per Sub Kegiatan
                    </h2>

                    <p class="text-slate-400 text-sm mb-5">
                        Komposisi realisasi SPJ aktif berdasarkan sub kegiatan.
                    </p>

                    <div class="h-[420px]">
                        <canvas id="chartSubKegiatan"></canvas>
                    </div>
                </div>

            </div>

            <div class="mt-6 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

                <h2 class="text-lg font-bold mb-4">
                    Ringkasan Sub Kegiatan
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-700 text-slate-400">
                                <th class="py-3 px-3 text-left">Unit</th>
                                <th class="py-3 px-3 text-left">Sub Kegiatan</th>
                                <th class="py-3 px-3 text-right">Pagu</th>
                                <th class="py-3 px-3 text-right">Realisasi</th>
                                <th class="py-3 px-3 text-right">Sisa</th>
                                <th class="py-3 px-3 text-right">Serapan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($chartSubKegiatan as $item)
                                <tr class="border-b border-slate-800 hover:bg-slate-800/70">
                                    <td class="py-3 px-3">
                                        {{ $item['unit'] }}
                                    </td>

                                    <td class="py-3 px-3">
                                        {{ $item['label'] }}
                                    </td>

                                    <td class="py-3 px-3 text-right">
                                        Rp {{ number_format($item['pagu'], 0, ',', '.') }}
                                    </td>

                                    <td class="py-3 px-3 text-right text-green-300">
                                        Rp {{ number_format($item['realisasi'], 0, ',', '.') }}
                                    </td>

                                    <td class="py-3 px-3 text-right text-amber-300">
                                        Rp {{ number_format($item['sisa'], 0, ',', '.') }}
                                    </td>

                                    <td class="py-3 px-3 text-right text-blue-300">
                                        {{ number_format($item['serapan'], 2, ',', '.') }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">
                                        Belum ada data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

    <script>
    const chartSerapanLabels = @json($chartSerapan->pluck('label'));
    const chartSerapanData = @json($chartSerapan->pluck('total'));

    const chartUnitLabels = @json($chartUnit->pluck('label'));
    const chartUnitData = @json($chartUnit->pluck('realisasi'));

    const chartSubLabels = @json($chartSubKegiatan->pluck('label'));
    const chartSubData = @json($chartSubKegiatan->pluck('realisasi'));

    function rupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function getPalette(canvasId) {
        if (canvasId === 'chartSerapan') {
            return [
                '#22c55e', // Realisasi
                '#ef4444'  // Sisa Pagu
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

    function createPieChart(canvasId, labels, data) {
        const ctx = document.getElementById(canvasId);

        if (!ctx) {
            return;
        }

        if (!data || data.length === 0 || data.every(value => Number(value) <= 0)) {
            const parent = ctx.parentElement;
            parent.innerHTML = `
                <div class="h-full flex items-center justify-center text-slate-500">
                    Belum ada realisasi SPJ aktif.
                </div>
            `;
            return;
        }

        const palette = getPalette(canvasId);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: data.map((_, index) => palette[index % palette.length]),
                    hoverBackgroundColor: data.map((_, index) => palette[index % palette.length]),
                    borderColor: '#0f172a',
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
                            color: '#ffffff',
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
                        backgroundColor: '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#475569',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => Number(a) + Number(b), 0);
                                const value = Number(context.raw || 0);
                                const percent = total > 0 ? ((value / total) * 100).toFixed(2) : 0;

                                return context.label + ': ' + rupiah(value) + ' (' + percent + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    createPieChart('chartSerapan', chartSerapanLabels, chartSerapanData);
    createPieChart('chartUnit', chartUnitLabels, chartUnitData);
    createPieChart('chartSubKegiatan', chartSubLabels, chartSubData);
</script>

</body>

</html>