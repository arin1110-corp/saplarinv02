<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Laporan Sub Kegiatan</title>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white">

    <div class="flex min-h-screen">

        @include('administrator.partials.sidebar')

        <div class="flex-1 p-6">

            @include('administrator.partials.header')

            <div class="mb-6">
                <h1 class="text-2xl font-bold">Laporan Sub Kegiatan</h1>
                <p class="text-slate-400 text-sm">
                    Rekap laporan realisasi indikator, permasalahan, solusi, dan tindak lanjut dari operator.
                </p>
            </div>

            <div class="space-y-6">

                @forelse ($laporan as $item)
                    @php
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

                        $totalPersen = 0;
                        $jumlahIndikator = $item->detail->count();

                        foreach ($item->detail as $detail) {
                            $persen = $detail->detail_target > 0
                                ? ($detail->detail_realisasi / $detail->detail_target) * 100
                                : 0;

                            if ($persen > 100) {
                                $persen = 100;
                            }

                            $totalPersen += $persen;
                        }

                        $rataCapaian = $jumlahIndikator > 0
                            ? $totalPersen / $jumlahIndikator
                            : 0;
                    @endphp

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">

                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6">

                            <div>
                                <div class="text-xs text-slate-400">
                                    {{ $bulanNama[$item->laporan_bulan] ?? '-' }} {{ $item->laporan_tahun }}
                                </div>

                                <h2 class="text-xl font-bold mt-1">
                                    {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                </h2>

                                <p class="text-sm text-slate-400 mt-1">
                                    Kode:
                                    {{ $item->subKegiatan->sub_kegiatan_kode ?? '-' }}
                                </p>

                                <div class="mt-3 text-sm text-slate-400">
                                    Operator:
                                    <span class="text-white font-semibold">
                                        {{ $item->laporan_created_by_nama ?? '-' }}
                                    </span>
                                    /
                                    {{ $item->laporan_created_by_nip ?? '-' }}
                                </div>
                            </div>

                            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-4 min-w-[220px]">
                                <p class="text-xs text-slate-400">Rata-rata Capaian</p>

                                <div class="text-3xl font-black mt-1
                                    {{ $rataCapaian >= 100 ? 'text-green-300' : ($rataCapaian >= 60 ? 'text-yellow-300' : 'text-red-300') }}">
                                    {{ number_format($rataCapaian, 2, ',', '.') }}%
                                </div>

                                <div class="w-full bg-slate-700 rounded-full h-2 mt-3">
                                    <div class="h-2 rounded-full
                                        {{ $rataCapaian >= 100 ? 'bg-green-500' : ($rataCapaian >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                        style="width: {{ min($rataCapaian, 100) }}%">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mb-6">
                            <h3 class="font-bold mb-3">Realisasi Indikator</h3>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-700 text-slate-400 text-left">
                                            <th class="py-3 px-3">No</th>
                                            <th class="py-3 px-3">Indikator</th>
                                            <th class="py-3 px-3 text-right">Target</th>
                                            <th class="py-3 px-3 text-right">Realisasi</th>
                                            <th class="py-3 px-3 text-right">Capaian</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($item->detail as $detail)
                                            @php
                                                $persen = $detail->detail_target > 0
                                                    ? ($detail->detail_realisasi / $detail->detail_target) * 100
                                                    : 0;

                                                if ($persen > 100) {
                                                    $persen = 100;
                                                }
                                            @endphp

                                            <tr class="border-b border-slate-800">
                                                <td class="py-3 px-3">{{ $loop->iteration }}</td>

                                                <td class="py-3 px-3">
                                                    {{ $detail->detail_indikator_nama }}
                                                </td>

                                                <td class="py-3 px-3 text-right">
                                                    {{ number_format($detail->detail_target, 2, ',', '.') }}
                                                    {{ $detail->detail_satuan }}
                                                </td>

                                                <td class="py-3 px-3 text-right text-green-300">
                                                    {{ number_format($detail->detail_realisasi, 2, ',', '.') }}
                                                    {{ $detail->detail_satuan }}
                                                </td>

                                                <td class="py-3 px-3 text-right font-bold">
                                                    {{ number_format($persen, 2, ',', '.') }}%
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-6 text-center text-slate-500">
                                                    Belum ada indikator.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5">
                                <h3 class="font-bold mb-3 text-red-300">Permasalahan</h3>

                                <ol class="list-decimal list-inside space-y-2 text-sm text-slate-300">
                                    @forelse ($item->permasalahan as $masalah)
                                        <li>{{ $masalah->permasalahan_uraian }}</li>
                                    @empty
                                        <li class="list-none text-slate-500">Tidak ada permasalahan.</li>
                                    @endforelse
                                </ol>
                            </div>

                            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5">
                                <h3 class="font-bold mb-3 text-blue-300">Solusi</h3>

                                <ol class="list-decimal list-inside space-y-2 text-sm text-slate-300">
                                    @forelse ($item->solusi as $solusi)
                                        <li>{{ $solusi->solusi_uraian }}</li>
                                    @empty
                                        <li class="list-none text-slate-500">Tidak ada solusi.</li>
                                    @endforelse
                                </ol>
                            </div>

                            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5">
                                <h3 class="font-bold mb-3 text-green-300">Tindak Lanjut</h3>

                                <ol class="list-decimal list-inside space-y-2 text-sm text-slate-300">
                                    @forelse ($item->tindakLanjut as $tl)
                                        <li>{{ $tl->tindak_lanjut_uraian }}</li>
                                    @empty
                                        <li class="list-none text-slate-500">Tidak ada tindak lanjut.</li>
                                    @endforelse
                                </ol>
                            </div>

                        </div>

                    </div>
                @empty
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center text-slate-400">
                        Belum ada laporan sub kegiatan.
                    </div>
                @endforelse

            </div>

        </div>

    </div>

</body>

</html>