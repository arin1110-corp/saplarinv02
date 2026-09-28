@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan SPJ')

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
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 dark:border-red-800 dark:bg-red-900/20">

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
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">

                        <i class="bi bi-journal-check text-xl"></i>

                    </div>

                    <div>

                        <h1 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-white lg:text-3xl">
                            Permintaan SPJ
                        </h1>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Monitoring seluruh permintaan SPJ yang diajukan operator.
                        </p>

                    </div>

                </div>

            </div>


            {{-- TOTAL --}}

            <div
                class="inline-flex w-fit items-center gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-3 text-blue-700 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-300">

                <i class="bi bi-journal-text text-lg"></i>

                <div>

                    <div class="text-xs font-medium opacity-70">
                        Total Permintaan
                    </div>

                    <div class="text-lg font-bold leading-none">
                        {{ number_format($spjs->total(), 0, ',', '.') }}
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
                            Gunakan filter untuk menemukan permintaan SPJ.
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

                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Unit, program, kegiatan, operator, uraian..."
                                class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500">

                        </div>

                    </div>


                    {{-- TAHUN --}}

                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Tahun

                        </label>

                        <select name="tahun"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">

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


                    {{-- STATUS --}}

                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Status

                        </label>

                        <select name="status"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">

                            <option value="">
                                Semua Status
                            </option>

                            <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>

                                Aktif

                            </option>

                            <option value="Nonaktif" {{ request('status') === 'Nonaktif' ? 'selected' : '' }}>

                                Nonaktif

                            </option>

                        </select>

                    </div>

                </div>


                {{-- UNIT --}}

                <div class="mt-4">

                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                        Unit

                    </label>

                    <select name="unit_id"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">

                        <option value="">
                            Semua Unit
                        </option>

                        @foreach ($units ?? [] as $unit)
                            <option value="{{ $unit->unit_id }}"
                                {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>

                                {{ $unit->unit_kode }} — {{ $unit->unit_nama }}

                            </option>
                        @endforeach

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
        {{-- TABLE --}}
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
                        Data permintaan SPJ berdasarkan filter yang dipilih.
                    </p>

                </div>


                {{-- FILTER AKTIF --}}

                @if (request()->hasAny(['search', 'tahun', 'unit_id', 'status']))

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

                                {{ request('tahun') }}

                            </span>
                        @endif


                        @if (request('status'))
                            <span
                                class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">

                                {{ request('status') }}

                            </span>
                        @endif

                    </div>

                @endif

            </div>


            {{-- ===================================================== --}}
            {{-- TABLE CONTENT --}}
            {{-- ===================================================== --}}

            <div class="overflow-x-auto">

                <table class="w-full min-w-[1500px] text-sm">


                    {{-- HEADER --}}

                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/70">

                        <tr
                            class="text-left text-[11px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">

                            <th class="w-12 px-4 py-4 text-center">
                                #
                            </th>

                            <th class="w-20 px-4 py-4">
                                Tahun
                            </th>

                            <th class="w-56 px-4 py-4">
                                Unit
                            </th>

                            <th class="w-[350px] px-4 py-4">
                                Program / Kegiatan
                            </th>

                            <th class="w-[250px] px-4 py-4">
                                Operator / Tanggal
                            </th>

                            <th class="w-[300px] px-4 py-4">
                                Uraian
                            </th>

                            <th class="w-[180px] px-4 py-4 text-right">
                                Nominal
                            </th>

                            <th class="w-24 px-4 py-4 text-center">
                                File
                            </th>

                            <th class="w-36 px-4 py-4 text-center">
                                Status
                            </th>

                            <th class="w-[250px] px-4 py-4">
                                Catatan
                            </th>

                        </tr>

                    </thead>


                    {{-- BODY --}}

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                        @forelse ($spjs as $item)
                            <tr class="group transition hover:bg-blue-50/40 dark:hover:bg-slate-800/60">


                                {{-- NO --}}

                                <td class="px-4 py-5 text-center text-xs text-slate-400">

                                    {{ $spjs->firstItem() + $loop->index }}

                                </td>


                                {{-- TAHUN --}}

                                <td class="px-4 py-5">

                                    <span
                                        class="inline-flex rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">

                                        {{ $item->pagu->spj_pagu_tahun ?? '-' }}

                                    </span>

                                </td>


                                {{-- UNIT --}}

                                <td class="px-4 py-5">

                                    <div class="font-bold text-blue-600 dark:text-blue-400">

                                        {{ $item->pagu->unit->unit_kode ?? '-' }}

                                    </div>

                                    <div class="mt-1 max-w-[220px] text-xs leading-relaxed text-slate-500">

                                        {{ $item->pagu->unit->unit_nama ?? '-' }}

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- PROGRAM + KEGIATAN + SUB KEGIATAN --}}
                                {{-- ================================================= --}}

                                <td class="px-4 py-5">

                                    {{-- PROGRAM --}}

                                    <div class="font-semibold leading-relaxed text-slate-800 dark:text-slate-100">

                                        {{ $item->pagu->program->program_nama ?? '-' }}

                                    </div>


                                    {{-- KEGIATAN --}}

                                    <div
                                        class="mt-1 text-xs font-medium leading-relaxed text-slate-500 dark:text-slate-400">

                                        <span class="text-slate-400">
                                            Kegiatan:
                                        </span>

                                        {{ $item->pagu->kegiatan->kegiatan_nama ?? '-' }}

                                    </div>


                                    {{-- SUB KEGIATAN --}}

                                    <div class="mt-2 rounded-lg bg-slate-50 px-3 py-2 dark:bg-slate-800/70">

                                        <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">

                                            Sub Kegiatan

                                        </div>

                                        <div
                                            class="mt-0.5 text-xs font-semibold leading-relaxed text-slate-600 dark:text-slate-300">

                                            {{ $item->pagu->subKegiatan->sub_kegiatan_nama ?? '-' }}

                                        </div>

                                        @if ($item->pagu->subKegiatan?->sub_kegiatan_kode)
                                            <div class="mt-1 text-[10px] text-slate-400">

                                                {{ $item->pagu->subKegiatan->sub_kegiatan_kode }}

                                            </div>
                                        @endif

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- OPERATOR + TANGGAL --}}
                                {{-- ================================================= --}}

                                <td class="px-4 py-5">

                                    {{-- OPERATOR --}}

                                    <div class="font-semibold leading-relaxed text-slate-800 dark:text-slate-100">

                                        {{ $item->spj_operator_nama }}

                                    </div>


                                    {{-- NIP --}}

                                    <div class="mt-1 text-xs text-slate-500">

                                        {{ $item->spj_operator_nip }}

                                    </div>


                                    {{-- BIDANG --}}

                                    @if ($item->spj_bidang_nama)
                                        <div class="mt-1 text-xs leading-relaxed text-slate-400">

                                            {{ $item->spj_bidang_nama }}

                                        </div>
                                    @endif


                                    {{-- DIVIDER --}}

                                    <div class="my-3 border-t border-slate-100 dark:border-slate-800">
                                    </div>


                                    {{-- TANGGAL SPJ --}}

                                    <div class="flex items-center gap-2">

                                        <i class="bi bi-calendar3 text-xs text-blue-500"></i>

                                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">

                                            {{ $item->spj_tanggal ? $item->spj_tanggal->format('d/m/Y') : '-' }}

                                        </span>

                                    </div>


                                    {{-- TANGGAL INPUT --}}

                                    @if ($item->spj_tanggal_input)
                                        <div class="mt-1 text-[10px] text-slate-400">

                                            Input:
                                            {{ $item->spj_tanggal_input->format('d/m/Y H:i') }}

                                        </div>
                                    @endif

                                </td>


                                {{-- URAIAN --}}

                                <td class="px-4 py-5">

                                    <div
                                        class="max-w-[300px] whitespace-normal leading-relaxed text-slate-600 dark:text-slate-300">

                                        {{ $item->spj_uraian ?: '-' }}

                                    </div>

                                </td>


                                {{-- NOMINAL --}}

                                <td class="px-4 py-5 text-right">

                                    <div class="whitespace-nowrap font-bold text-emerald-600 dark:text-emerald-400">

                                        Rp {{ number_format($item->spj_nominal, 0, ',', '.') }}

                                    </div>

                                </td>


                                {{-- FILE --}}

                                <td class="px-4 py-5 text-center">

                                    @if ($item->spj_file)
                                        <a href="{{ filter_var($item->spj_file, FILTER_VALIDATE_URL) ? $item->spj_file : asset($item->spj_file) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/40">

                                            <i class="bi bi-file-earmark-text"></i>

                                            Lihat

                                        </a>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600">
                                            —
                                        </span>
                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS + TOGGLE --}}
                                {{-- ================================================= --}}

                                <td class="px-4 py-5 text-center">

                                    <div class="flex flex-col items-center gap-2">

                                        @if ($item->spj_status === 'Aktif')
                                            {{-- STATUS --}}

                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">

                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500">
                                                </span>

                                                Aktif

                                            </span>


                                            {{-- BUTTON --}}

                                            <button type="button" onclick='openToggleModal(@json($item))'
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-[11px] font-semibold text-red-600 transition hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">

                                                <i class="bi bi-toggle-off"></i>

                                                Nonaktifkan

                                            </button>
                                        @else
                                            {{-- STATUS --}}

                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 dark:bg-red-900/20 dark:text-red-300">

                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500">
                                                </span>

                                                Nonaktif

                                            </span>


                                            {{-- BUTTON --}}

                                            <button type="button" onclick='openToggleModal(@json($item))'
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-[11px] font-semibold text-emerald-600 transition hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-900/40">

                                                <i class="bi bi-toggle-on"></i>

                                                Aktifkan

                                            </button>
                                        @endif


                                        {{-- STATUS DATE --}}

                                        @if ($item->spj_status_at)
                                            <div class="text-[10px] text-slate-400">

                                                {{ $item->spj_status_at->format('d/m/Y H:i') }}

                                            </div>
                                        @endif

                                    </div>

                                </td>


                                {{-- CATATAN --}}

                                <td class="px-4 py-5">

                                    <div
                                        class="max-w-[240px] whitespace-normal text-xs leading-relaxed text-slate-600 dark:text-slate-300">

                                        {{ $item->spj_catatan_admin ?: '—' }}

                                    </div>


                                    @if ($item->spj_status_by_nama)
                                        <div class="mt-2 text-[10px] text-slate-400">

                                            <i class="bi bi-person-check mr-1"></i>

                                            {{ $item->spj_status_by_nama }}

                                        </div>
                                    @endif

                                </td>

                            </tr>


                        @empty

                            {{-- EMPTY STATE --}}

                            <tr>

                                <td colspan="11" class="py-20 text-center">

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

                                            Belum ada permintaan SPJ yang sesuai dengan filter.

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

            @if ($spjs->hasPages() || $spjs->total() > 0)
                <div
                    class="flex flex-col gap-4 border-t border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between dark:border-slate-800">

                    <div class="text-sm text-slate-500">

                        Menampilkan

                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ $spjs->firstItem() ?? 0 }}
                        </span>

                        -

                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ $spjs->lastItem() ?? 0 }}
                        </span>

                        dari

                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ number_format($spjs->total(), 0, ',', '.') }}
                        </span>

                        data

                    </div>


                    <div>
                        {{ $spjs->withQueryString()->links() }}
                    </div>

                </div>
            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL --}}
    {{-- ========================================================= --}}

    @include('administrator-v2.permintaan-spj.modal')

@endsection
