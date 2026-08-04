@extends('administrator-v2.layouts.app')

@section('title', 'Laporan Aktivitas')

@section('content')

    @if (session('success'))
        <div
            class="mb-6 rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-5 py-4">

            <div class="flex items-center gap-3">

                <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>

                <span class="text-green-700 dark:text-green-300">

                    {{ session('success') }}

                </span>

            </div>

        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

                Laporan Aktivitas

            </h1>

            <p class="text-slate-500 dark:text-slate-400 mt-1">

                Admin hanya dapat mengaktifkan atau menonaktifkan kegiatan dan aktivitas.

            </p>

        </div>

    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6 mb-6">

        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Tahun

                    </label>

                    <select name="tahun"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Tahun

                        </option>

                        @foreach ($tahun ?? [] as $t)
                            <option value="{{ $t }}" @selected(request('tahun') == $t)>

                                {{ $t }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Sub Kegiatan

                    </label>

                    <select name="sub_kegiatan"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Sub Kegiatan

                        </option>

                        @foreach ($subKegiatans ?? [] as $sub)
                            <option value="{{ $sub->sub_kegiatan_id }}" @selected(request('sub_kegiatan') == $sub->sub_kegiatan_id)>

                                {{ $sub->sub_kegiatan_nama }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Status

                    </label>

                    <select name="status"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Status

                        </option>

                        <option value="Aktif" @selected(request('status') == 'Aktif')>

                            Aktif

                        </option>

                        <option value="Nonaktif" @selected(request('status') == 'Nonaktif')>

                            Nonaktif

                        </option>

                    </select>

                </div>

                <div class="flex items-end gap-3">

                    <button class="flex-1 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3">

                        <i class="bi bi-search me-2"></i>

                        Filter

                    </button>

                    <a href="{{ route('admin.laporan-aktivitas.index') }}"
                        class="rounded-2xl bg-slate-600 hover:bg-slate-700 text-white font-semibold px-5 py-3">

                        Reset

                    </a>

                </div>

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

            <div
                class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">

                <div class="px-7 py-6 border-b border-slate-200 dark:border-slate-800">

                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">

                        <div>

                            <div class="text-sm text-slate-500 dark:text-slate-400">

                                {{ $kegiatan->laporan_kegiatan_tahun }}

                                •

                                {{ $kegiatan->laporan_kegiatan_bidang_nama }}

                            </div>

                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">

                                {{ $kegiatan->laporan_kegiatan_nama }}

                            </h2>

                            <p class="text-slate-500 dark:text-slate-400 mt-2">

                                {{ $kegiatan->laporan_kegiatan_deskripsi ?: '-' }}

                            </p>

                            <div class="mt-3 text-sm text-slate-500 dark:text-slate-400">

                                Dibuat oleh

                                <span class="font-semibold">

                                    {{ $kegiatan->laporan_kegiatan_user_nama }}

                                </span>

                            </div>

                        </div>

                        <div>

                            @if ($kegiatan->laporan_kegiatan_status == 'Aktif')
                                <form method="POST"
                                    action="{{ route('admin.laporan-aktivitas.kegiatan.nonaktif', $kegiatan->laporan_kegiatan_uid) }}"
                                    onsubmit="return confirm('Nonaktifkan kegiatan ini?')">

                                    @csrf

                                    <button class="rounded-xl bg-red-600 hover:bg-red-700 text-white px-5 py-3">

                                        Nonaktifkan

                                    </button>

                                </form>
                            @else
                                <form method="POST"
                                    action="{{ route('admin.laporan-aktivitas.kegiatan.aktif', $kegiatan->laporan_kegiatan_uid) }}">

                                    @csrf

                                    <button class="rounded-xl bg-green-600 hover:bg-green-700 text-white px-5 py-3">

                                        Aktifkan

                                    </button>

                                </form>
                            @endif

                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-5 p-7">

                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5">

                        <div class="text-sm text-slate-500 dark:text-slate-400">

                            Total Aktivitas

                        </div>

                        <div class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">

                            {{ $totalAktivitas }}

                        </div>

                        <div class="text-xs text-slate-500 mt-2">

                            Total 100%

                        </div>

                    </div>

                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5">

                        <div class="text-sm text-slate-500">

                            TW I

                        </div>

                        <div class="mt-2 text-2xl font-bold text-green-600">

                            {{ number_format($tw1Persen, 2, ',', '.') }}%

                        </div>

                        <div class="text-xs text-slate-500 mt-2">

                            {{ $tw1Count }} Aktivitas

                        </div>

                    </div>

                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5">

                        <div class="text-sm text-slate-500">

                            TW II

                        </div>

                        <div class="mt-2 text-2xl font-bold text-blue-600">

                            {{ number_format($tw2Persen, 2, ',', '.') }}%

                        </div>

                        <div class="text-xs text-slate-500 mt-2">

                            {{ $tw2Count }} Aktivitas

                        </div>

                    </div>

                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5">

                        <div class="text-sm text-slate-500">

                            TW III

                        </div>

                        <div class="mt-2 text-2xl font-bold text-amber-600">

                            {{ number_format($tw3Persen, 2, ',', '.') }}%

                        </div>

                        <div class="text-xs text-slate-500 mt-2">

                            {{ $tw3Count }} Aktivitas

                        </div>

                    </div>

                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5">

                        <div class="text-sm text-slate-500">

                            TW IV

                        </div>

                        <div class="mt-2 text-2xl font-bold text-red-600">

                            {{ number_format($tw4Persen, 2, ',', '.') }}%

                        </div>

                        <div class="text-xs text-slate-500 mt-2">

                            {{ $tw4Count }} Aktivitas

                        </div>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead class="bg-slate-100 dark:bg-slate-800">

                            <tr>

                                <th class="px-5 py-4 text-left">No</th>

                                <th class="px-5 py-4 text-left">Aktivitas</th>

                                <th class="px-5 py-4 text-left">Tanggal</th>

                                <th class="px-5 py-4 text-center">TW</th>

                                <th class="px-5 py-4 text-left">Bukti</th>

                                <th class="px-5 py-4 text-center">Status</th>

                                <th class="px-5 py-4 text-center">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>
                            @forelse($kegiatan->aktivitas as $aktivitas)
                                <tr class="border-b border-slate-200 dark:border-slate-800">

                                    <td class="px-5 py-4">

                                        {{ $loop->iteration }}

                                    </td>

                                    <td class="px-5 py-4">

                                        <div class="font-semibold text-slate-800 dark:text-white">

                                            {{ $aktivitas->aktivitas_nama }}

                                        </div>

                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">

                                            {{ $aktivitas->aktivitas_deskripsi ?: '-' }}

                                        </div>

                                    </td>

                                    <td class="px-5 py-4 whitespace-nowrap">

                                        {{ \Carbon\Carbon::parse($aktivitas->aktivitas_tanggal)->translatedFormat('d F Y') }}

                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1 text-xs font-semibold">

                                            {{ $aktivitas->aktivitas_triwulan }}

                                        </span>

                                    </td>

                                    <td class="px-5 py-4">

                                        @if ($aktivitas->aktivitas_file)
                                            <a href="{{ asset($aktivitas->aktivitas_file) }}" target="_blank"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400">

                                                <i class="bi bi-paperclip"></i>

                                                Lihat Bukti

                                            </a>
                                        @else
                                            <span class="text-slate-400">

                                                -

                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        @if ($aktivitas->aktivitas_status == 'Aktif')
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                                                Aktif

                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 py-1 text-xs font-semibold">

                                                Nonaktif

                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        @if ($aktivitas->aktivitas_status == 'Aktif')
                                            <form method="POST"
                                                action="{{ route('admin.laporan-aktivitas.aktivitas.nonaktif', $aktivitas->aktivitas_uid) }}"
                                                onsubmit="return confirm('Nonaktifkan aktivitas ini?')">

                                                @csrf

                                                <button
                                                    class="rounded-xl bg-red-600 hover:bg-red-700 text-white px-4 py-2 text-sm">

                                                    Nonaktifkan

                                                </button>

                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ route('admin.laporan-aktivitas.aktivitas.aktif', $aktivitas->aktivitas_uid) }}">

                                                @csrf

                                                <button
                                                    class="rounded-xl bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm">

                                                    Aktifkan

                                                </button>

                                            </form>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="py-10 text-center text-slate-500 dark:text-slate-400">

                                        Belum ada aktivitas.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @empty

            <div
                class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 p-12 text-center">

                <div class="flex flex-col items-center">

                    <div
                        class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-5">

                        <i class="bi bi-inboxes text-4xl text-slate-400"></i>

                    </div>

                    <h3 class="text-xl font-bold text-slate-700 dark:text-slate-200">

                        Belum Ada Data

                    </h3>

                    <p class="text-slate-500 dark:text-slate-400 mt-2">

                        Belum ada laporan aktivitas yang tersedia.

                    </p>

                </div>

            </div>

        @endforelse

    </div>
@endsection

@push('styles')
    <style>
        .table-responsive {

            overflow-x: auto;

        }

        .table-responsive table {

            min-width: 1200px;

        }

        .table-responsive::-webkit-scrollbar {

            height: 8px;

        }

        .table-responsive::-webkit-scrollbar-thumb {

            background: #94a3b8;

            border-radius: 999px;

        }

        .dark .table-responsive::-webkit-scrollbar-thumb {

            background: #475569;

        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {

            $('.confirm-nonaktif').on('submit', function() {

                return confirm('Yakin ingin menonaktifkan data ini ?');

            });

            $('.confirm-aktif').on('submit', function() {

                return confirm('Aktifkan kembali data ini ?');

            });

        });
    </script>
@endpush
