<!DOCTYPE html>
<html lang="id">

<head>
    @include('administrator.partials.head')
</head>

<body class="text-white">

<div class="flex min-h-screen">

    @include('administrator.partials.sidebar')

    <div class="flex-1 flex flex-col">

        @include('administrator.partials.header')

        <main class="p-6 space-y-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">
                        Dashboard Admin
                    </h1>
                    <p class="text-slate-400 text-sm">
                        Ringkasan data SAPLARIN Tahun Anggaran {{ $tahun }}
                    </p>
                </div>

                <form method="GET" class="flex gap-2">
                    <input type="number"
                        name="tahun"
                        value="{{ $tahun }}"
                        class="rounded-xl px-4 py-2 bg-slate-800 border border-slate-700 text-white w-32">

                    <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-semibold">
                        Filter
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Total Pagu SPJ</p>
                    <h2 class="text-2xl font-bold mt-2">
                        Rp {{ number_format($totalPagu, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-2">
                        {{ $jumlahPaguSPJ }} sub kegiatan aktif
                    </p>
                </div>

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Realisasi SPJ</p>
                    <h2 class="text-2xl font-bold mt-2 text-green-400">
                        Rp {{ number_format($totalRealisasiSPJ, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-2">
                        {{ $jumlahInputSPJ }} input SPJ aktif
                    </p>
                </div>

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Sisa Pagu</p>
                    <h2 class="text-2xl font-bold mt-2 text-amber-400">
                        Rp {{ number_format($sisaPagu, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-2">
                        Sisa anggaran tersedia
                    </p>
                </div>

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Serapan SPJ</p>
                    <h2 class="text-3xl font-bold mt-2 text-blue-400">
                        {{ number_format($persenSerapan, 2, ',', '.') }}%
                    </h2>
                    <p class="text-xs text-slate-500 mt-2">
                        Berdasarkan pagu final
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Total User</p>
                    <h2 class="text-3xl font-bold mt-2">
                        {{ number_format($totalUser, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Pengajuan BBM</p>
                    <h2 class="text-3xl font-bold mt-2">
                        {{ $jumlahBBM }}
                    </h2>
                    <p class="text-xs text-yellow-400 mt-2">
                        {{ $bbmMenunggu }} menunggu verifikasi
                    </p>
                </div>

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Kinerja Prioritas</p>
                    <h2 class="text-3xl font-bold mt-2">
                        {{ $jumlahPrioritas }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-2">
                        Program prioritas aktif
                    </p>
                </div>

                <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                    <p class="text-slate-400 text-sm">Laporan Aktivitas</p>
                    <h2 class="text-3xl font-bold mt-2">
                        {{ $jumlahAktivitas }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-2">
                        Kegiatan aktivitas terinput
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-slate-800 rounded-2xl border border-slate-700 p-5">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-semibold">
                                Data Pagu SPJ Terbaru
                            </h3>
                            <p class="text-xs text-slate-400">
                                5 data pagu terakhir pada tahun {{ $tahun }}
                            </p>
                        </div>

                        <a href="{{ route('admin.spj.index') }}"
                            class="text-sm bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-xl">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-slate-400">
                                <tr>
                                    <th class="text-left py-2">Sub Kegiatan</th>
                                    <th class="text-right">Pagu</th>
                                    <th class="text-right">Realisasi</th>
                                    <th class="text-right">Sisa</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($paguTerbaru as $item)
                                    @php
                                        $realisasiItem = $item->realisasi
                                            ->where('spj_status', 'Aktif')
                                            ->sum('spj_nominal');

                                        $sisaItem = $item->spj_pagu_final - $realisasiItem;
                                    @endphp

                                    <tr class="border-t border-slate-700">
                                        <td class="py-3">
                                            <div class="font-semibold">
                                                {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $item->program->program_nama ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="text-right">
                                            Rp {{ number_format($item->spj_pagu_final, 0, ',', '.') }}
                                        </td>

                                        <td class="text-right text-green-400">
                                            Rp {{ number_format($realisasiItem, 0, ',', '.') }}
                                        </td>

                                        <td class="text-right text-amber-400">
                                            Rp {{ number_format($sisaItem, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            @if ($item->spj_pagu_status == 1)
                                                <span class="text-green-400">Aktif</span>
                                            @else
                                                <span class="text-red-400">Nonaktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-t border-slate-700">
                                        <td colspan="5" class="py-5 text-center text-slate-400">
                                            Belum ada data pagu SPJ.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-bold">
                        Status SAPLARIN
                    </h3>

                    <p class="text-sm mt-2 text-blue-100">
                        Sistem digunakan untuk monitoring BBM kegiatan, kinerja prioritas,
                        laporan aktivitas, dan realisasi SPJ berdasarkan pagu sub kegiatan.
                    </p>

                    <div class="mt-5 bg-white/10 rounded-2xl p-4">
                        <p class="text-sm text-blue-100">Serapan Tahun Ini</p>
                        <h2 class="text-3xl font-bold mt-1">
                            {{ number_format($persenSerapan, 2, ',', '.') }}%
                        </h2>

                        <div class="w-full bg-white/20 rounded-full h-3 mt-4">
                            <div class="bg-white h-3 rounded-full"
                                style="width: {{ $persenSerapan }}%">
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.spj.index') }}"
                        class="inline-block mt-5 bg-white text-blue-700 px-4 py-2 rounded-xl font-semibold">
                        Kelola Pagu SPJ
                    </a>
                </div>

            </div>

        </main>

    </div>
</div>

@include('administrator.partials.modal')

</body>

</html>