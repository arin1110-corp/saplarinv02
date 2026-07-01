<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Laporan Aktivitas</title>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white">

<div class="flex min-h-screen">

    @include('administrator.partials.sidebar')

    <div class="flex-1 p-6">

        @include('administrator.partials.header')

        @if (session('success'))
            <div class="mb-5 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                Laporan Aktivitas
            </h1>
            <p class="text-slate-400 text-sm">
                Admin hanya dapat mengaktifkan atau menonaktifkan kegiatan dan aktivitas.
            </p>
        </div>


        <div class="mb-6 bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <select name="tahun"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-3">
                    <option value="">Semua Tahun</option>
                    @foreach($tahun ?? [] as $t)
                        <option value="{{ $t }}" @selected(request('tahun') == $t)>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>

                <select name="sub_kegiatan"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-3">
                    <option value="">Semua Sub Kegiatan</option>

                    @foreach($subKegiatans ?? [] as $sub)
                        <option value="{{ $sub->sub_kegiatan_id }}"
                            @selected(request('sub_kegiatan') == $sub->sub_kegiatan_id)>
                            {{ $sub->sub_kegiatan_nama }}
                        </option>
                    @endforeach
                </select>

                <select name="status"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-3">
                    <option value="">Semua Status</option>
                    <option value="Aktif" @selected(request('status') == 'Aktif')>Aktif</option>
                    <option value="Nonaktif" @selected(request('status') == 'Nonaktif')>Nonaktif</option>
                </select>

                <div class="flex gap-2">
                    <button class="bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-xl">
                        Filter
                    </button>

                    <a href="{{ route('admin.laporan-aktivitas.index') }}"
                        class="bg-slate-700 px-4 py-3 rounded-xl">
                        Reset
                    </a>
                </div>

            </form>
        </div>


        <div class="space-y-6">

            @forelse ($kegiatans as $kegiatan)
                @php
                    $aktivitasAktif = $kegiatan->aktivitas->where('aktivitas_status', 'Aktif');

                    $totalAktivitas = $aktivitasAktif->count();

                    $tw1Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW I')->count();
                    $tw2Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW II')->count();
                    $tw3Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW III')->count();
                    $tw4Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW IV')->count();

                    $tw1Persen = $totalAktivitas > 0 ? ($tw1Count / $totalAktivitas) * 100 : 0;
                    $tw2Persen = $totalAktivitas > 0 ? ($tw2Count / $totalAktivitas) * 100 : 0;
                    $tw3Persen = $totalAktivitas > 0 ? ($tw3Count / $totalAktivitas) * 100 : 0;
                    $tw4Persen = $totalAktivitas > 0 ? ($tw4Count / $totalAktivitas) * 100 : 0;
                @endphp

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">

                    <div class="flex justify-between items-start gap-4 mb-6">
                        <div>
                            <div class="text-xs text-slate-400">
                                {{ $kegiatan->laporan_kegiatan_tahun }} |
                                {{ $kegiatan->laporan_kegiatan_bidang_nama }}
                            </div>

                            <h2 class="text-xl font-bold">
                                {{ $kegiatan->laporan_kegiatan_nama }}
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                {{ $kegiatan->laporan_kegiatan_deskripsi ?: '-' }}
                            </p>

                            <div class="text-xs text-slate-500 mt-2">
                                Dibuat oleh:
                                {{ $kegiatan->laporan_kegiatan_user_nama }}
                            </div>
                        </div>

                        <div>
                            @if ($kegiatan->laporan_kegiatan_status === 'Aktif')
                                <form method="POST"
                                    action="{{ route('admin.laporan-aktivitas.kegiatan.nonaktif', $kegiatan->laporan_kegiatan_uid) }}"
                                    onsubmit="return confirm('Nonaktifkan kegiatan ini?')">
                                    @csrf
                                    <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-xl text-sm">
                                        Nonaktifkan
                                    </button>
                                </form>
                            @else
                                <form method="POST"
                                    action="{{ route('admin.laporan-aktivitas.kegiatan.aktif', $kegiatan->laporan_kegiatan_uid) }}">
                                    @csrf
                                    <button class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-xl text-sm">
                                        Aktifkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
                        <div class="bg-slate-800 rounded-xl p-4">
                            <p class="text-xs text-slate-400">Total Aktivitas</p>
                            <p class="text-2xl font-bold">{{ $totalAktivitas }}</p>
                            <p class="text-xs text-slate-500">Total 100%</p>
                        </div>

                        <div class="bg-slate-800 rounded-xl p-4">
                            <p class="text-xs text-slate-400">TW I</p>
                            <p class="text-xl font-bold">{{ number_format($tw1Persen, 2, ',', '.') }}%</p>
                            <p class="text-xs text-slate-500">{{ $tw1Count }} aktivitas</p>
                        </div>

                        <div class="bg-slate-800 rounded-xl p-4">
                            <p class="text-xs text-slate-400">TW II</p>
                            <p class="text-xl font-bold">{{ number_format($tw2Persen, 2, ',', '.') }}%</p>
                            <p class="text-xs text-slate-500">{{ $tw2Count }} aktivitas</p>
                        </div>

                        <div class="bg-slate-800 rounded-xl p-4">
                            <p class="text-xs text-slate-400">TW III</p>
                            <p class="text-xl font-bold">{{ number_format($tw3Persen, 2, ',', '.') }}%</p>
                            <p class="text-xs text-slate-500">{{ $tw3Count }} aktivitas</p>
                        </div>

                        <div class="bg-slate-800 rounded-xl p-4">
                            <p class="text-xs text-slate-400">TW IV</p>
                            <p class="text-xl font-bold">{{ number_format($tw4Persen, 2, ',', '.') }}%</p>
                            <p class="text-xs text-slate-500">{{ $tw4Count }} aktivitas</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-700 text-left text-slate-400">
                                    <th class="py-3 px-3">No</th>
                                    <th class="py-3 px-3">Aktivitas</th>
                                    <th class="py-3 px-3">Tanggal</th>
                                    <th class="py-3 px-3">TW</th>
                                    <th class="py-3 px-3">Bukti</th>
                                    <th class="py-3 px-3">Status</th>
                                    <th class="py-3 px-3">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($kegiatan->aktivitas as $aktivitas)
                                    <tr class="border-b border-slate-800">
                                        <td class="py-4 px-3">{{ $loop->iteration }}</td>

                                        <td class="py-4 px-3">
                                            <div class="font-semibold">
                                                {{ $aktivitas->aktivitas_nama }}
                                            </div>
                                            <div class="text-xs text-slate-400">
                                                {{ $aktivitas->aktivitas_uraian ?: '-' }}
                                            </div>
                                        </td>

                                        <td class="py-4 px-3">
                                            {{ $aktivitas->aktivitas_tanggal_mulai?->format('d/m/Y') }}
                                            -
                                            {{ $aktivitas->aktivitas_tanggal_selesai?->format('d/m/Y') }}
                                        </td>

                                        <td class="py-4 px-3">
                                            {{ $aktivitas->aktivitas_triwulan }}
                                        </td>

                                        <td class="py-4 px-3">
                                            <div class="flex flex-col gap-1">
                                                @foreach ($aktivitas->bukti as $bukti)
                                                    <a href="{{ asset($bukti->bukti_file) }}"
                                                        target="_blank"
                                                        class="text-blue-400 hover:underline">
                                                        Bukti
                                                    </a>
                                                @endforeach
                                            </div>
                                        </td>

                                        <td class="py-4 px-3">
                                            {{ $aktivitas->aktivitas_status }}
                                        </td>

                                        <td class="py-4 px-3">
                                            @if ($aktivitas->aktivitas_status === 'Aktif')
                                                <form method="POST"
                                                    action="{{ route('admin.laporan-aktivitas.aktivitas.nonaktif', $aktivitas->aktivitas_uid) }}"
                                                    onsubmit="return confirm('Nonaktifkan aktivitas ini?')">
                                                    @csrf
                                                    <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                                        Nonaktif
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('admin.laporan-aktivitas.aktivitas.aktif', $aktivitas->aktivitas_uid) }}">
                                                    @csrf
                                                    <button class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-xs">
                                                        Aktif
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-500">
                                            Belum ada aktivitas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            @empty
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center text-slate-400">
                    Belum ada laporan aktivitas.
                </div>
            @endforelse

        </div>

        @if(method_exists($kegiatans, 'links'))
            <div class="mt-6">
                {{ $kegiatans->links() }}
            </div>
        @endif

    </div>

</div>

</body>
</html>