@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan SPJ')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div
                class="rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 p-4 text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 p-4 text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 p-4">

                <ul class="list-disc ml-5 text-red-700 dark:text-red-300 space-y-1">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            <div>

                <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

                    Permintaan SPJ

                </h1>

                <p class="text-slate-500 dark:text-slate-400 mt-2">

                    Monitoring seluruh permintaan SPJ yang diajukan operator.

                </p>

            </div>

            <div
                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 text-white px-5 py-3 shadow-lg shadow-blue-600/20">

                <i class="bi bi-journal-check text-lg"></i>

                <span class="font-semibold">

                    {{ $spjs->total() }} Permintaan

                </span>

            </div>

        </div>

        <div
            class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <form method="GET">

                        <div class="relative w-full lg:w-96">

                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari Unit, Program, Operator, Uraian..."
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 pl-11 pr-4 py-3 focus:ring-2 focus:ring-blue-500">

                        </div>

                    </form>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead
                        class="sticky top-0 bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">

                        <tr>

                            <th class="px-5 py-4 w-16">

                                No

                            </th>

                            <th class="px-5 py-4">

                                Tahun

                            </th>

                            <th class="px-5 py-4">

                                Unit

                            </th>

                            <th class="px-5 py-4">

                                Program / Kegiatan

                            </th>

                            <th class="px-5 py-4">

                                Sub Kegiatan

                            </th>

                            <th class="px-5 py-4">

                                Operator

                            </th>

                            <th class="px-5 py-4">

                                Tanggal

                            </th>

                            <th class="px-5 py-4">

                                Uraian

                            </th>

                            <th class="px-5 py-4 text-right">

                                Nominal

                            </th>

                            <th class="px-5 py-4 text-center">

                                File

                            </th>

                            <th class="px-5 py-4 text-center">

                                Status

                            </th>

                            <th class="px-5 py-4">

                                Catatan

                            </th>

                            <th class="px-5 py-4 text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">

                        @forelse($spjs as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">

                                <td class="px-5 py-5">

                                    {{ $spjs->firstItem() + $loop->index }}

                                </td>

                                <td class="px-5 py-5">

                                    <span
                                        class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-bold">

                                        {{ $item->pagu->spj_pagu_tahun ?? '-' }}

                                    </span>

                                </td>

                                <td class="px-5 py-5">

                                    <div class="font-semibold text-blue-600">

                                        {{ $item->pagu->unit->unit_kode ?? '-' }}

                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">

                                        {{ $item->pagu->unit->unit_nama ?? '-' }}

                                    </div>

                                </td>

                                <td class="px-5 py-5">

                                    <div class="font-semibold">

                                        {{ $item->pagu->program->program_nama ?? '-' }}

                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">

                                        {{ $item->pagu->kegiatan->kegiatan_nama ?? '-' }}

                                    </div>

                                </td>
                                <td class="px-5 py-5">

                                    <div class="font-semibold">

                                        {{ $item->pagu->subKegiatan->sub_kegiatan_nama ?? '-' }}

                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">

                                        {{ $item->pagu->subKegiatan->sub_kegiatan_kode ?? '-' }}

                                    </div>

                                </td>

                                <td class="px-5 py-5">

                                    <div class="font-semibold">

                                        {{ $item->spj_operator_nama }}

                                    </div>

                                    <div class="text-xs text-slate-500">

                                        {{ $item->spj_operator_nip }}

                                    </div>

                                    <div class="text-xs text-slate-400 mt-1">

                                        {{ $item->spj_bidang_nama }}

                                    </div>

                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">

                                    <div class="font-medium">

                                        {{ $item->spj_tanggal ? $item->spj_tanggal->format('d/m/Y') : '-' }}

                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">

                                        Input

                                        {{ $item->spj_tanggal_input ? $item->spj_tanggal_input->format('d/m/Y H:i') : '-' }}

                                    </div>

                                </td>

                                <td class="px-5 py-5">

                                    <div class="max-w-sm whitespace-normal leading-relaxed">

                                        {{ $item->spj_uraian }}

                                    </div>

                                </td>

                                <td class="px-5 py-5 text-right">

                                    <div class="font-bold text-green-600 dark:text-green-400">

                                        Rp {{ number_format($item->spj_nominal, 0, ',', '.') }}

                                    </div>

                                </td>

                                <td class="px-5 py-5 text-center">

                                    @if ($item->spj_file)
                                        <a href="{{ filter_var($item->spj_file, FILTER_VALIDATE_URL) ? $item->spj_file : asset($item->spj_file) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 rounded-xl bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-2 text-xs font-semibold">

                                            <i class="bi bi-file-earmark-arrow-down"></i>

                                            Lihat

                                        </a>
                                    @else
                                        <span class="text-slate-400">

                                            -

                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-5 text-center">

                                    @if ($item->spj_status == 'Aktif')
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 py-1 text-xs font-semibold">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Nonaktif

                                        </span>
                                    @endif

                                    @if ($item->spj_status_at)
                                        <div class="text-[11px] text-slate-500 mt-2">

                                            {{ $item->spj_status_at->format('d/m/Y H:i') }}

                                        </div>
                                    @endif

                                </td>

                                <td class="px-5 py-5">

                                    <div class="max-w-xs whitespace-normal text-sm text-slate-600 dark:text-slate-300">

                                        {{ $item->spj_catatan_admin ?: '-' }}

                                    </div>

                                    @if ($item->spj_status_by_nama)
                                        <div class="text-xs text-slate-500 mt-2">

                                            Oleh :

                                            {{ $item->spj_status_by_nama }}

                                        </div>
                                    @endif

                                </td>

                                <td class="px-5 py-5">

                                    <div class="flex justify-center">

                                        <button type="button" onclick='openToggleModal(@json($item))'
                                            class="{{ $item->spj_status == 'Aktif' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}
                                        inline-flex items-center gap-2 rounded-xl px-4 py-2 text-white text-xs font-semibold transition">

                                            <i
                                                class="bi {{ $item->spj_status == 'Aktif' ? 'bi-x-circle' : 'bi-check-circle' }}"></i>

                                            {{ $item->spj_status == 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="13" class="py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <i class="bi bi-inbox text-5xl text-slate-300 dark:text-slate-700"></i>

                                        <div class="mt-4 text-slate-500">

                                            Belum ada data permintaan SPJ.

                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div
                class="flex flex-col md:flex-row items-center justify-between gap-4 px-6 py-5 border-t border-slate-200 dark:border-slate-800">

                <div class="text-sm text-slate-500">

                    Menampilkan

                    <span class="font-semibold">

                        {{ $spjs->firstItem() }}

                    </span>

                    -

                    <span class="font-semibold">

                        {{ $spjs->lastItem() }}

                    </span>

                    dari

                    <span class="font-semibold">

                        {{ $spjs->total() }}

                    </span>

                    data

                </div>

                {{ $spjs->links() }}

            </div>

        </div>

    </div>

    @include('administrator-v2.permintaan-spj.modal')

@endsection
