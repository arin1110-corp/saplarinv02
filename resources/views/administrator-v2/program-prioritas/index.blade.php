@extends('administrator-v2.layouts.app')

@section('title', 'Laporan Kinerja Prioritas')

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

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">

            <div class="flex items-center gap-3">

                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>

                <span class="text-red-700 dark:text-red-300">

                    {{ session('error') }}

                </span>

            </div>

        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">

            <div class="flex gap-3">

                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl mt-1"></i>

                <div>

                    <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">

                        Terjadi Kesalahan

                    </h4>

                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

                Laporan Kinerja Prioritas

            </h1>

            <p class="text-slate-500 dark:text-slate-400 mt-1">

                Admin mengelola program prioritas. Operator menginput rencana aksi dan capaian.

            </p>

        </div>

        <div class="flex flex-wrap gap-3">

            <a href="{{ route('admin.program-prioritas.export') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-white font-semibold">

                <i class="bi bi-file-earmark-excel"></i>

                Export Excel

            </a>

            <button onclick="openPrioritasModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold">

                <i class="bi bi-plus-circle"></i>

                Tambah Prioritas

            </button>

        </div>

    </div>

    <div class="space-y-6">

        @forelse ($prioritas as $item)

            @php

                $totalTargetPrioritas = 0;

                $totalCapaianAktif = 0;

                foreach ($item->rencana as $rencanaHitung) {
                    $totalTargetPrioritas += (int) $rencanaHitung->rencana_target;

                    $totalCapaianAktif += $rencanaHitung->capaian
                        ->where('capaian_status', 'Aktif')
                        ->sum('capaian_jumlah');
                }

                $persenPrioritas = $totalTargetPrioritas > 0 ? ($totalCapaianAktif / $totalTargetPrioritas) * 100 : 0;

                if ($persenPrioritas > 100) {
                    $persenPrioritas = 100;
                }

            @endphp

            <div
                class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">

                <div class="px-7 py-6 border-b border-slate-200 dark:border-slate-800">

                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">

                        <div>

                            <div class="text-sm text-slate-500 dark:text-slate-400">

                                Tahun {{ $item->prioritas_tahun }}

                            </div>

                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">

                                {{ $item->prioritas_judul }}

                            </h2>

                            <p class="text-slate-500 dark:text-slate-400 mt-2">

                                {{ $item->prioritas_deskripsi ?: '-' }}

                            </p>

                            <div class="flex flex-wrap gap-2 mt-5">

                                @if ($item->prioritas_status == 'Aktif')
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

                                <span
                                    class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 px-3 py-1 text-xs font-semibold">

                                    Target : {{ $totalTargetPrioritas }}

                                </span>

                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                                    Capaian : {{ $totalCapaianAktif }}

                                </span>

                                <span
                                    class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1 text-xs font-semibold">

                                    {{ number_format($persenPrioritas, 2, ',', '.') }}%

                                </span>

                            </div>

                        </div>

                        <div>

                            <button onclick='openEditPrioritasModal(@json($item))'
                                class="rounded-xl bg-amber-500 hover:bg-amber-600 text-white px-5 py-3">

                                <i class="bi bi-pencil-square me-2"></i>

                                Edit Prioritas

                            </button>

                        </div>

                    </div>

                </div>

                <div class="p-7 space-y-5">
                    @forelse ($item->rencana as $rencana)
                        @php

                            $targetRencana = (int) $rencana->rencana_target;

                            $capaianAktifRencana = $rencana->capaian
                                ->where('capaian_status', 'Aktif')
                                ->sum('capaian_jumlah');

                            $persenRencana = $targetRencana > 0 ? ($capaianAktifRencana / $targetRencana) * 100 : 0;

                            if ($persenRencana > 100) {
                                $persenRencana = 100;
                            }

                        @endphp

                        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">

                            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-800/50">

                                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-5">

                                    <div>

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">

                                                {{ $rencana->rencana_judul }}

                                            </h3>

                                            @if ($rencana->rencana_status == 'Aktif')
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

                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1 text-xs font-semibold">

                                                {{ number_format($persenRencana, 2, ',', '.') }}%

                                            </span>

                                        </div>

                                        <div class="mt-3 text-sm text-slate-600 dark:text-slate-400">

                                            <span class="font-semibold">

                                                Target :

                                            </span>

                                            {{ $targetRencana }} capaian

                                        </div>

                                        <div class="mt-2 text-xs text-slate-500 dark:text-slate-500">

                                            Dibuat oleh

                                            <span class="font-medium">

                                                {{ $rencana->rencana_user_nama ?? '-' }}

                                            </span>

                                            •

                                            {{ $rencana->rencana_bidang_nama ?? '-' }}

                                            •

                                            Capaian Aktif :

                                            {{ $capaianAktifRencana }}

                                        </div>

                                    </div>

                                    <div>

                                        @if ($rencana->rencana_status == 'Aktif')
                                            <form method="POST"
                                                action="{{ route('admin.program-prioritas.rencana.nonaktif', $rencana->rencana_uid) }}"
                                                onsubmit="return confirm('Nonaktifkan rencana aksi ini?')">

                                                @csrf

                                                <button
                                                    class="rounded-xl bg-red-600 hover:bg-red-700 text-white px-5 py-2.5">

                                                    Nonaktif

                                                </button>

                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ route('admin.program-prioritas.rencana.aktif', $rencana->rencana_uid) }}">

                                                @csrf

                                                <button
                                                    class="rounded-xl bg-green-600 hover:bg-green-700 text-white px-5 py-2.5">

                                                    Aktifkan

                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </div>

                            </div>

                            <div class="overflow-x-auto">

                                <table class="min-w-full text-sm">

                                    <thead class="bg-slate-100 dark:bg-slate-800">

                                        <tr>

                                            <th class="px-5 py-4 text-left">No</th>

                                            <th class="px-5 py-4 text-left">Capaian</th>

                                            <th class="px-5 py-4 text-center">Jumlah</th>

                                            <th class="px-5 py-4 text-center">Persen</th>

                                            <th class="px-5 py-4 text-left">Deskripsi</th>

                                            <th class="px-5 py-4 text-left">Periode</th>

                                            <th class="px-5 py-4 text-left">Operator</th>

                                            <th class="px-5 py-4 text-center">Bukti</th>

                                            <th class="px-5 py-4 text-center">Status</th>

                                            <th class="px-5 py-4 text-center">Aksi</th>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        @forelse ($rencana->capaian as $capaian)
                                            @php

                                                $jumlahCapaianBaris = (int) ($capaian->capaian_jumlah ?? 1);

                                                $persenCapaianBaris =
                                                    $targetRencana > 0
                                                        ? ($jumlahCapaianBaris / $targetRencana) * 100
                                                        : 0;

                                                if ($persenCapaianBaris > 100) {
                                                    $persenCapaianBaris = 100;
                                                }

                                            @endphp

                                            <tr class="border-b border-slate-200 dark:border-slate-800">

                                                <td class="px-5 py-4">

                                                    {{ $loop->iteration }}

                                                </td>

                                                <td class="px-5 py-4">

                                                    <div class="font-semibold text-slate-800 dark:text-white">

                                                        {{ $capaian->capaian_judul }}

                                                    </div>

                                                </td>

                                                <td class="px-5 py-4 text-center font-semibold">

                                                    {{ $jumlahCapaianBaris }}

                                                </td>

                                                <td class="px-5 py-4 text-center">

                                                    @if ($capaian->capaian_status == 'Aktif')
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1 text-xs font-semibold">

                                                            {{ number_format($persenCapaianBaris, 2, ',', '.') }}%

                                                        </span>
                                                    @else
                                                        <span class="text-slate-400">

                                                            0%

                                                        </span>
                                                    @endif

                                                </td>

                                                <td class="px-5 py-4">

                                                    {{ $capaian->capaian_deskripsi ?: '-' }}

                                                </td>

                                                <td class="px-5 py-4 whitespace-nowrap">

                                                    {{ $capaian->capaian_tanggal_mulai?->format('d/m/Y') }}

                                                    <br>

                                                    <span class="text-xs text-slate-500">

                                                        s/d

                                                        {{ $capaian->capaian_tanggal_selesai?->format('d/m/Y') }}

                                                    </span>

                                                </td>

                                                <td class="px-5 py-4">

                                                    <div class="font-semibold">

                                                        {{ $capaian->capaian_user_nama ?? '-' }}

                                                    </div>

                                                    <div class="text-xs text-slate-500 dark:text-slate-400">

                                                        {{ $capaian->capaian_user_nip ?? '-' }}

                                                    </div>

                                                    <div class="text-xs text-slate-500 dark:text-slate-400">

                                                        {{ $capaian->capaian_bidang_nama ?? '-' }}

                                                    </div>

                                                </td>

                                                <td class="px-5 py-4">

                                                    <div class="flex flex-col gap-2">

                                                        @forelse($capaian->files as $file)
                                                            <a href="{{ asset($file->file_path) }}" target="_blank"
                                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400">

                                                                <i class="bi bi-paperclip"></i>

                                                                Bukti

                                                            </a>

                                                        @empty

                                                            <span class="text-slate-400">

                                                                -

                                                            </span>
                                                        @endforelse

                                                    </div>

                                                </td>

                                                <td class="px-5 py-4 text-center">

                                                    @if ($capaian->capaian_status == 'Aktif')
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

                                                    @if ($capaian->capaian_status == 'Aktif')
                                                        <form method="POST"
                                                            action="{{ route('admin.program-prioritas.capaian.nonaktif', $capaian->capaian_uid) }}"
                                                            onsubmit="return confirm('Nonaktifkan capaian ini?')">

                                                            @csrf

                                                            <button
                                                                class="rounded-xl bg-red-600 hover:bg-red-700 text-white px-4 py-2 text-sm">

                                                                Nonaktif

                                                            </button>

                                                        </form>
                                                    @else
                                                        <form method="POST"
                                                            action="{{ route('admin.program-prioritas.capaian.aktif', $capaian->capaian_uid) }}">

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

                                                <td colspan="10"
                                                    class="text-center py-10 text-slate-500 dark:text-slate-400">

                                                    Belum ada capaian.

                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    @empty

                        <div
                            class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-500 dark:text-slate-400">

                            Belum ada rencana aksi.

                        </div>
                    @endforelse

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

                        Belum Ada Program Prioritas

                    </h3>

                    <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-lg">

                        Silakan tambahkan program prioritas terlebih dahulu agar operator dapat
                        menginput rencana aksi dan capaian.

                    </p>

                    <button onclick="openPrioritasModal()"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 font-semibold">

                        <i class="bi bi-plus-circle"></i>

                        Tambah Prioritas

                    </button>

                </div>

            </div>

        @endforelse

    </div>

    @include('administrator-v2.program-prioritas.partials.modal')

@endsection

@push('styles')
    <style>
        .table-responsive {

            overflow-x: auto;

        }

        .table-responsive table {

            min-width: 1400px;

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
        function openPrioritasModal() {

            $('#modalPrioritas')
                .removeClass('hidden')
                .addClass('flex');

            $('body').addClass('overflow-hidden');

        }

        function closePrioritasModal() {

            $('#modalPrioritas')
                .removeClass('flex')
                .addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }

        function openEditPrioritasModal(data) {

            $('#edit_prioritas_uid').val(data.prioritas_uid);

            $('#edit_prioritas_tahun').val(data.prioritas_tahun);

            $('#edit_prioritas_judul').val(data.prioritas_judul);

            $('#edit_prioritas_deskripsi').val(data.prioritas_deskripsi);

            $('#edit_prioritas_status').val(data.prioritas_status);

            $('#modalEditPrioritas')
                .removeClass('hidden')
                .addClass('flex');

            $('body').addClass('overflow-hidden');

        }

        function closeEditPrioritasModal() {

            $('#modalEditPrioritas')
                .removeClass('flex')
                .addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }

        $('#modalPrioritas,#modalEditPrioritas').on('click', function(e) {

            if (e.target === this) {

                $(this)
                    .removeClass('flex')
                    .addClass('hidden');

                $('body').removeClass('overflow-hidden');

            }

        });

        $(document).keydown(function(e) {

            if (e.key === 'Escape') {

                closePrioritasModal();

                closeEditPrioritasModal();

            }

        });
    </script>
@endpush
