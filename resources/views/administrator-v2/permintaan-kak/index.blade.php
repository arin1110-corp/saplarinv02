@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan KAK')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- ALERT --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">

                <i class="bi bi-check-circle-fill mt-0.5 text-lg"></i>

                <div class="text-sm font-medium">
                    {{ session('success') }}
                </div>

            </div>
        @endif


        @if (session('error'))
            <div
                class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">

                <i class="bi bi-exclamation-circle-fill mt-0.5 text-lg"></i>

                <div class="text-sm font-medium">
                    {{ session('error') }}
                </div>

            </div>
        @endif


        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-800 dark:bg-red-900/20">

                <div class="mb-3 flex items-center gap-2 font-semibold text-red-700 dark:text-red-300">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    Terdapat kesalahan

                </div>

                <ul class="ml-5 list-disc space-y-1 text-sm text-red-700 dark:text-red-300">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">

                        <i class="bi bi-file-earmark-text text-xl"></i>

                    </div>

                    <div>

                        <h1 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-white lg:text-3xl">
                            Permintaan KAK
                        </h1>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Monitoring seluruh KAK yang diajukan operator.
                        </p>

                    </div>

                </div>

            </div>


            {{-- TOTAL --}}

            <div
                class="inline-flex w-fit items-center gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-3 text-blue-700 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-300">

                <i class="bi bi-file-earmark-text text-lg"></i>

                <div>

                    <div class="text-xs font-medium opacity-70">
                        Total Permintaan
                    </div>

                    <div class="text-lg font-bold leading-none">
                        {{ number_format($kaks->total(), 0, ',', '.') }}
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTER --}}
        {{-- ========================================================= --}}

        <div
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

            {{-- FILTER HEADER --}}

            <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">

                        <i class="bi bi-funnel-fill"></i>

                    </div>

                    <div>

                        <h2 class="font-semibold text-slate-800 dark:text-white">
                            Filter Data
                        </h2>

                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Gunakan filter untuk menemukan permintaan KAK.
                        </p>

                    </div>

                </div>

            </div>


            {{-- FILTER FORM --}}

            <form method="GET" class="p-6">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">


                    {{-- SEARCH --}}

                    <div class="xl:col-span-2">

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Pencarian

                        </label>

                        <div class="relative">

                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            </i>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Program, kegiatan, sub kegiatan, pemohon..."
                                class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">

                        </div>

                    </div>


                    {{-- TAHUN --}}

                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Tahun

                        </label>

                        <select name="tahun"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">

                            <option value="">
                                Semua Tahun
                            </option>

                            @foreach ($tahunList ?? [] as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>

                                    {{ $tahun }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- TAHAPAN --}}

                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Tahapan

                        </label>

                        <select name="tahapan"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">

                            <option value="">
                                Semua Tahapan
                            </option>

                            <option value="Induk" {{ request('tahapan') === 'Induk' ? 'selected' : '' }}>

                                Induk

                            </option>

                            <option value="Perubahan" {{ request('tahapan') === 'Perubahan' ? 'selected' : '' }}>

                                Perubahan

                            </option>

                            <option value="Pergeseran" {{ request('tahapan') === 'Pergeseran' ? 'selected' : '' }}>

                                Pergeseran

                            </option>

                        </select>

                    </div>

                </div>


                {{-- STATUS --}}

                <div class="mt-4">

                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                        Status

                    </label>

                    <select name="status"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>

                            Aktif

                        </option>

                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>

                            Nonaktif

                        </option>

                    </select>

                </div>


                {{-- BUTTON --}}

                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:justify-end">

                    <a href="{{ url()->current() }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">

                        <i class="bi bi-arrow-counterclockwise"></i>

                        Reset

                    </a>


                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700">

                        <i class="bi bi-search"></i>

                        Terapkan Filter

                    </button>

                </div>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- TABLE CARD --}}
        {{-- ========================================================= --}}

        <div
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">


            {{-- TABLE HEADER --}}

            <div
                class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">

                <div>

                    <h2 class="font-semibold text-slate-800 dark:text-white">
                        Daftar Permintaan
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Data permintaan KAK berdasarkan filter yang dipilih.
                    </p>

                </div>


                {{-- FILTER AKTIF --}}

                @if (request()->hasAny(['search', 'tahun', 'tahapan', 'status']))

                    <div class="flex flex-wrap items-center gap-2">

                        <span class="text-xs text-slate-400">
                            Filter:
                        </span>


                        @if (request('search'))
                            <span
                                class="max-w-[220px] truncate rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">

                                <i class="bi bi-search mr-1"></i>

                                {{ request('search') }}

                            </span>
                        @endif


                        @if (request('tahun'))
                            <span
                                class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">

                                Tahun {{ request('tahun') }}

                            </span>
                        @endif


                        @if (request('tahapan'))
                            <span
                                class="rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900/20 dark:text-purple-300">

                                {{ request('tahapan') }}

                            </span>
                        @endif


                        @if (request('status') !== null && request('status') !== '')
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ request('status') === '1'
                                    ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300'
                                    : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">

                                {{ request('status') === '1' ? 'Aktif' : 'Nonaktif' }}

                            </span>
                        @endif

                    </div>

                @endif

            </div>


            {{-- ========================================================= --}}
            {{-- TABLE --}}
            {{-- ========================================================= --}}

            <div class="overflow-x-auto">

                <table class="w-full min-w-[1450px] text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/70">

                        <tr
                            class="text-left text-[11px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">

                            <th class="w-12 px-4 py-4 text-center">
                                #
                            </th>

                            <th class="w-20 px-4 py-4">
                                Tahun
                            </th>

                            <th class="w-[420px] px-4 py-4">
                                Program / Kegiatan / Sub Kegiatan
                            </th>

                            <th class="w-28 px-4 py-4">
                                Tahapan
                            </th>

                            <th class="w-[260px] px-4 py-4">
                                Pemohon / Tanggal
                            </th>

                            <th class="w-24 px-4 py-4 text-center">
                                File
                            </th>

                            <th class="w-36 px-4 py-4 text-center">
                                Status
                            </th>

                            <th class="w-[230px] px-4 py-4 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                        @forelse ($kaks as $item)
                            <tr class="group transition hover:bg-blue-50/40 dark:hover:bg-slate-800/60">


                                {{-- NO --}}

                                <td class="px-4 py-5 text-center text-xs text-slate-400">

                                    {{ $kaks->firstItem() + $loop->index }}

                                </td>


                                {{-- TAHUN --}}

                                <td class="px-4 py-5">

                                    <span
                                        class="inline-flex rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">

                                        {{ $item->kak_tahun ?? '-' }}

                                    </span>

                                </td>


                                {{-- PROGRAM / KEGIATAN / SUB KEGIATAN --}}

                                <td class="px-4 py-5">

                                    {{-- PROGRAM --}}

                                    <div>

                                        <div
                                            class="mb-1 text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">

                                            Program

                                        </div>

                                        <div class="font-semibold leading-relaxed text-slate-800 dark:text-slate-100">

                                            {{ $item->program_nama ?? '-' }}

                                        </div>

                                        <div class="mt-1 text-[11px] text-slate-400">

                                            {{ $item->program_kode ?? '-' }}

                                        </div>

                                    </div>


                                    {{-- KEGIATAN --}}

                                    <div class="my-3 border-t border-slate-100 pt-3 dark:border-slate-800">

                                        <div class="mb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">

                                            Kegiatan

                                        </div>

                                        <div class="font-medium leading-relaxed text-slate-700 dark:text-slate-200">

                                            {{ $item->kegiatan_nama ?? '-' }}

                                        </div>

                                        <div class="mt-1 text-[11px] text-slate-400">

                                            {{ $item->kegiatan_kode ?? '-' }}

                                        </div>

                                    </div>


                                    {{-- SUB KEGIATAN --}}

                                    <div class="rounded-xl bg-slate-50 px-3 py-2.5 dark:bg-slate-800/70">

                                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">

                                            Sub Kegiatan

                                        </div>

                                        <div class="mt-1 font-semibold leading-relaxed text-slate-700 dark:text-slate-300">

                                            {{ $item->sub_kegiatan_nama ?? '-' }}

                                        </div>

                                        <div class="mt-1 text-[10px] text-slate-400">

                                            {{ $item->sub_kegiatan_kode ?? '-' }}

                                        </div>

                                    </div>

                                </td>


                                {{-- TAHAPAN --}}

                                <td class="px-4 py-5">

                                    @php

                                        $tahapan = strtolower(trim($item->kak_tahapan ?? ''));

                                        $tahapanClass = match ($tahapan) {
                                            'induk'
                                                => 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300',

                                            'perubahan'
                                                => 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300',

                                            'pergeseran'
                                                => 'bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-300',

                                            default
                                                => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                        };

                                    @endphp

                                    <span
                                        class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold {{ $tahapanClass }}">

                                        <i class="bi bi-layers"></i>

                                        {{ $item->kak_tahapan ?? '-' }}

                                    </span>

                                </td>


                                {{-- PEMOHON / TANGGAL --}}

                                <td class="px-4 py-5">

                                    {{-- PEMOHON --}}

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">

                                            <i class="bi bi-person-fill"></i>

                                        </div>

                                        <div class="min-w-0">

                                            <div class="font-semibold leading-relaxed text-slate-800 dark:text-white">

                                                {{ $item->kak_created_by_nama ?? '-' }}

                                            </div>

                                            <div class="mt-1 text-xs text-slate-400">

                                                ID: {{ $item->kak_created_by ?? '-' }}

                                            </div>

                                        </div>

                                    </div>


                                    {{-- TANGGAL --}}

                                    <div class="my-3 border-t border-slate-100 pt-3 dark:border-slate-800">

                                        <div class="flex items-center gap-2">

                                            <i class="bi bi-calendar3 text-xs text-blue-500"></i>

                                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">

                                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}

                                            </span>

                                        </div>

                                        @if ($item->created_at)
                                            <div class="mt-1 text-[10px] text-slate-400">

                                                Upload:
                                                {{ $item->created_at->format('d/m/Y H:i') }}

                                            </div>
                                        @endif

                                    </div>

                                </td>


                                {{-- FILE --}}

                                <td class="px-4 py-5 text-center">

                                    @if ($item->kak_file)
                                        <a href="{{ filter_var($item->kak_file, FILTER_VALIDATE_URL) ? $item->kak_file : asset($item->kak_file) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 rounded-xl bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/40">

                                            <i class="bi bi-file-earmark-pdf"></i>

                                            Lihat

                                        </a>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600">
                                            —
                                        </span>
                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td class="px-4 py-5 text-center">

                                    @if ((int) $item->kak_status === 1)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">

                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500">
                                            </span>

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 dark:bg-red-900/20 dark:text-red-300">

                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500">
                                            </span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- AKSI --}}

                                <td class="px-4 py-5 text-center">

                                    <div class="flex flex-col items-center gap-2">

                                        @if ($item->kak_file)
                                            <a href="{{ filter_var($item->kak_file, FILTER_VALIDATE_URL) ? $item->kak_file : asset($item->kak_file) }}"
                                                target="_blank"
                                                class="inline-flex w-full max-w-[160px] items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700">

                                                <i class="bi bi-eye"></i>

                                                Lihat KAK

                                            </a>
                                        @endif


                                        <button type="button" onclick='openCatatanModal(@json($item))'
                                            class="inline-flex w-full max-w-[160px] items-center justify-center gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100 dark:bg-amber-900/20 dark:text-amber-300 dark:hover:bg-amber-900/40">

                                            <i class="bi bi-chat-left-text"></i>

                                            Catatan Admin

                                        </button>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="8" class="py-20 text-center">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-100 dark:bg-slate-800">

                                            <i class="bi bi-inbox text-4xl text-slate-300 dark:text-slate-600">
                                            </i>

                                        </div>

                                        <h3 class="mt-5 font-semibold text-slate-700 dark:text-slate-300">

                                            Data tidak ditemukan

                                        </h3>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Belum ada permintaan KAK yang sesuai dengan filter.

                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ========================================================= --}}
            {{-- PAGINATION --}}
            {{-- ========================================================= --}}

            @if ($kaks->hasPages() || $kaks->total() > 0)

                <div
                    class="flex flex-col gap-4 border-t border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between dark:border-slate-800">

                    <div class="text-sm text-slate-500 dark:text-slate-400">

                        @if ($kaks->total() > 0)
                            Menampilkan

                            <span class="font-semibold text-slate-700 dark:text-slate-200">
                                {{ $kaks->firstItem() }}
                            </span>

                            -

                            <span class="font-semibold text-slate-700 dark:text-slate-200">
                                {{ $kaks->lastItem() }}
                            </span>

                            dari

                            <span class="font-semibold text-slate-700 dark:text-slate-200">
                                {{ number_format($kaks->total(), 0, ',', '.') }}
                            </span>

                            data
                        @else
                            Tidak ada data
                        @endif

                    </div>


                    <div>

                        {{ $kaks->withQueryString()->links() }}

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- MODAL CATATAN ADMIN --}}
    {{-- ============================================================= --}}

    <div id="catatanModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4 backdrop-blur-sm">

        <div
            class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 dark:border-slate-800">

                <div>

                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Catatan Admin
                    </h3>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Catatan untuk permintaan KAK.
                    </p>

                </div>

                <button type="button" onclick="closeCatatanModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-white">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <form method="POST" id="catatanForm" action="">

                @csrf

                <div class="p-6">

                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">

                        Catatan Admin

                    </label>

                    <textarea name="kak_catatan_admin" id="kak_catatan_admin" rows="5" placeholder="Tulis catatan admin..."
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white"></textarea>

                </div>


                <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-5 dark:border-slate-800">

                    <button type="button" onclick="closeCatatanModal()"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">

                        Batal

                    </button>


                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

                        <i class="bi bi-save"></i>

                        Simpan Catatan

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>
        function openCatatanModal(item) {

            const modal = document.getElementById('catatanModal');

            const form = document.getElementById('catatanForm');

            const textarea = document.getElementById('kak_catatan_admin');


            form.action =
                "{{ url('/admin/permintaan-kak') }}/" +
                item.kak_id +
                "/catatan";


            textarea.value =
                item.kak_catatan_admin ?? '';


            modal.classList.remove('hidden');

            modal.classList.add('flex');

        }


        function closeCatatanModal() {

            const modal =
                document.getElementById('catatanModal');


            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }


        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                closeCatatanModal();

            }

        });


        document
            .getElementById('catatanModal')
            .addEventListener('click', function(event) {

                if (event.target === this) {

                    closeCatatanModal();

                }

            });
    </script>

@endsection
