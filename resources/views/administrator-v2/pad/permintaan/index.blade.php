@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan PAD')
@section('page_title', 'Permintaan PAD')
@section('breadcrumb', 'Permintaan PAD')

@section('content')

    <div class="space-y-6">

        {{-- =========================================================
        ALERT
    ========================================================== --}}

        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20
                    border border-green-200 dark:border-green-800
                    text-green-700 dark:text-green-300
                    px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20
                    border border-red-200 dark:border-red-800
                    text-red-700 dark:text-red-300
                    px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif


        {{-- =========================================================
        HEADER
    ========================================================== --}}

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Permintaan PAD
                </h1>

                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Data penerimaan dan realisasi PAD tahun {{ $tahun }}.
                </p>
            </div>

        </div>


        {{-- =========================================================
        RINGKASAN
    ========================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- TOTAL DATA --}}

            <div
                class="bg-white dark:bg-slate-800
                    rounded-2xl
                    border border-slate-200 dark:border-slate-700
                    p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Total Penerimaan
                        </p>

                        <p class="text-2xl font-bold
                              text-slate-900 dark:text-white mt-1">
                            {{ number_format($totalData, 0, ',', '.') }}
                        </p>

                        <p class="text-xs text-slate-400 mt-1">
                            Data tahun {{ $tahun }}
                        </p>
                    </div>

                    <div
                        class="w-12 h-12 rounded-xl
                            bg-blue-50 dark:bg-blue-900/30
                            flex items-center justify-center
                            text-blue-600 dark:text-blue-400">

                        <span class="text-xl">
                            ≡
                        </span>

                    </div>

                </div>

            </div>


            {{-- TOTAL NOMINAL --}}

            <div
                class="bg-white dark:bg-slate-800
                    rounded-2xl
                    border border-slate-200 dark:border-slate-700
                    p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Total Realisasi PAD
                        </p>

                        <p class="text-2xl font-bold
                              text-slate-900 dark:text-white mt-1">

                            Rp
                            {{ number_format($totalNominal, 0, ',', '.') }}

                        </p>

                        <p class="text-xs text-slate-400 mt-1">
                            Tahun {{ $tahun }}
                        </p>
                    </div>

                    <div
                        class="w-12 h-12 rounded-xl
                            bg-emerald-50 dark:bg-emerald-900/30
                            flex items-center justify-center
                            text-emerald-600 dark:text-emerald-400">

                        <span class="text-xl">
                            ↗
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
        FILTER
    ========================================================== --}}

        <div
            class="bg-white dark:bg-slate-800
                rounded-2xl
                border border-slate-200 dark:border-slate-700
                p-5">

            <form method="GET" action="{{ route('admin.pad.permintaan.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- TAHUN --}}

                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">
                            Tahun
                        </label>

                        <select name="tahun"
                            class="w-full rounded-xl
                                   border border-slate-300
                                   dark:border-slate-600
                                   bg-white dark:bg-slate-700
                                   text-slate-800 dark:text-white
                                   px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500">

                            @for ($i = now()->year - 2; $i <= now()->year + 1; $i++)
                                <option value="{{ $i }}" {{ (int) $tahun === $i ? 'selected' : '' }}>

                                    {{ $i }}

                                </option>
                            @endfor

                        </select>

                    </div>


                    {{-- UNIT --}}

                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">
                            Unit
                        </label>

                        <select name="unit"
                            class="w-full rounded-xl
                                   border border-slate-300
                                   dark:border-slate-600
                                   bg-white dark:bg-slate-700
                                   text-slate-800 dark:text-white
                                   px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                Semua Unit
                            </option>

                            @foreach ($daftarUnit as $item)
                                <option value="{{ $item }}" {{ $unit == $item ? 'selected' : '' }}>

                                    {{ $item }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- SUBKOMPONEN --}}

                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">
                            Subkomponen
                        </label>

                        <select name="subkomponen"
                            class="w-full rounded-xl
                                   border border-slate-300
                                   dark:border-slate-600
                                   bg-white dark:bg-slate-700
                                   text-slate-800 dark:text-white
                                   px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                Semua Subkomponen
                            </option>

                            @foreach ($daftarSubkomponen as $item)
                                <option value="{{ $item->pad_subkomponen_id }}"
                                    {{ (string) $subkomponen === (string) $item->pad_subkomponen_id ? 'selected' : '' }}>

                                    {{ $item->pad_subkomponen_kode }}
                                    -
                                    {{ $item->pad_subkomponen_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- SEARCH --}}

                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">
                            Pencarian
                        </label>

                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari data..."
                            class="w-full rounded-xl
                                  border border-slate-300
                                  dark:border-slate-600
                                  bg-white dark:bg-slate-700
                                  text-slate-800 dark:text-white
                                  placeholder-slate-400
                                  px-4 py-2.5
                                  focus:ring-2 focus:ring-blue-500">

                    </div>

                </div>


                {{-- BUTTON --}}

                <div class="flex justify-end gap-2 mt-5">

                    <a href="{{ route('admin.pad.permintaan.index') }}"
                        class="px-5 py-2.5 rounded-xl
                          bg-slate-100 dark:bg-slate-700
                          text-slate-700 dark:text-slate-200
                          font-semibold
                          hover:bg-slate-200 dark:hover:bg-slate-600
                          transition">

                        Reset

                    </a>


                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl
                               bg-blue-600
                               text-white
                               font-semibold
                               hover:bg-blue-700
                               transition">

                        Tampilkan

                    </button>

                </div>

            </form>

        </div>


        {{-- =========================================================
        REKAP TRIWULAN
    ========================================================== --}}

        @if ($subkomponen)

            @php
                $selectedSubkomponen = $daftarSubkomponen->firstWhere('pad_subkomponen_id', $subkomponen);
            @endphp

            <div
                class="bg-white dark:bg-slate-800
                rounded-2xl
                border border-slate-200 dark:border-slate-700
                overflow-hidden">

                <div class="p-6 border-b
                    border-slate-200 dark:border-slate-700">

                    <div class="flex flex-col md:flex-row
                        md:items-center md:justify-between gap-3">

                        <div>

                            <h2 class="text-lg font-bold
                               text-slate-900 dark:text-white">

                                Rekap Realisasi Triwulan

                            </h2>

                            <p class="text-sm
                              text-slate-500 dark:text-slate-400 mt-1">

                                {{ $selectedSubkomponen->pad_subkomponen_kode ?? '' }}
                                -
                                {{ $selectedSubkomponen->pad_subkomponen_nama ?? '-' }}

                                @if ($unit)
                                    <span class="mx-1">•</span>
                                    {{ $unit }}
                                @else
                                    <span class="mx-1">•</span>
                                    Seluruh Unit
                                @endif

                            </p>

                        </div>

                        <span class="text-sm
                             text-slate-500 dark:text-slate-400">

                            Tahun {{ $tahun }}

                        </span>

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr
                                class="border-b
                               border-slate-200
                               dark:border-slate-700
                               text-left
                               text-slate-500
                               dark:text-slate-400">

                                <th class="px-6 py-4">
                                    No
                                </th>

                                <th class="px-6 py-4">
                                    Unit
                                </th>

                                <th class="px-6 py-4 text-right">
                                    TW I
                                </th>

                                <th class="px-6 py-4 text-right">
                                    TW II
                                </th>

                                <th class="px-6 py-4 text-right">
                                    TW III
                                </th>

                                <th class="px-6 py-4 text-right">
                                    TW IV
                                </th>

                                <th class="px-6 py-4 text-right">
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($rekapTriwulan as $item)
                                <tr
                                    class="border-b
                                   border-slate-100
                                   dark:border-slate-700
                                   hover:bg-slate-50
                                   dark:hover:bg-slate-700/40">

                                    <td
                                        class="px-6 py-4
                                       text-slate-500
                                       dark:text-slate-400">

                                        {{ $loop->iteration }}

                                    </td>


                                    <td class="px-6 py-4">

                                        <span
                                            class="font-semibold
                                             text-slate-800
                                             dark:text-white">

                                            {{ $item->unit }}

                                        </span>

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($item->tw1, 0, ',', '.') }}

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($item->tw2, 0, ',', '.') }}

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($item->tw3, 0, ',', '.') }}

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($item->tw4, 0, ',', '.') }}

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        <span
                                            class="font-bold
                                             text-blue-600
                                             dark:text-blue-400">

                                            Rp
                                            {{ number_format($item->total, 0, ',', '.') }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7"
                                        class="px-6 py-10
                                       text-center
                                       text-slate-500
                                       dark:text-slate-400">

                                        Belum ada realisasi untuk
                                        subkomponen ini.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        @if ($rekapTriwulan->count())
                            <tfoot>

                                <tr
                                    class="bg-slate-50
                                   dark:bg-slate-900/50
                                   font-bold
                                   text-slate-800
                                   dark:text-white">

                                    <td colspan="2" class="px-6 py-4">

                                        TOTAL SELURUH UNIT

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($rekapTriwulan->sum('tw1'), 0, ',', '.') }}

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($rekapTriwulan->sum('tw2'), 0, ',', '.') }}

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($rekapTriwulan->sum('tw3'), 0, ',', '.') }}

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        Rp
                                        {{ number_format($rekapTriwulan->sum('tw4'), 0, ',', '.') }}

                                    </td>

                                    <td
                                        class="px-6 py-4 text-right
                                       text-blue-600
                                       dark:text-blue-400">

                                        Rp
                                        {{ number_format($rekapTriwulan->sum('total'), 0, ',', '.') }}

                                    </td>

                                </tr>

                            </tfoot>
                        @endif

                    </table>

                </div>

            </div>

        @endif


        {{-- =========================================================
        TABEL PERMINTAAN
    ========================================================== --}}

        <div
            class="bg-white dark:bg-slate-800
                rounded-2xl
                border border-slate-200 dark:border-slate-700
                overflow-hidden">

            {{-- HEADER TABLE --}}

            <div class="p-6 border-b
                    border-slate-200 dark:border-slate-700">

                <div class="flex flex-col md:flex-row
                        md:items-center md:justify-between gap-2">

                    <div>

                        <h2 class="text-lg font-bold
                               text-slate-900 dark:text-white">

                            Riwayat Penerimaan PAD

                        </h2>

                        <p class="text-sm
                              text-slate-500 dark:text-slate-400 mt-1">

                            Daftar seluruh penerimaan PAD tahun {{ $tahun }}.

                        </p>

                    </div>

                    @if ($unit || $subkomponen || $search)
                        <div class="text-sm
                                text-slate-500 dark:text-slate-400">

                            Filter aktif

                        </div>
                    @endif

                </div>

            </div>


            {{-- TABLE --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr
                            class="border-b
                               border-slate-200
                               dark:border-slate-700
                               text-left
                               text-slate-500
                               dark:text-slate-400">

                            <th class="py-4 px-5">
                                No
                            </th>

                            <th class="py-4 px-5">
                                Tanggal
                            </th>

                            <th class="py-4 px-5">
                                Unit
                            </th>

                            <th class="py-4 px-5">
                                Komponen
                            </th>

                            <th class="py-4 px-5">
                                Subkomponen
                            </th>

                            <th class="py-4 px-5">
                                Nominal
                            </th>

                            <th class="py-4 px-5">
                                Keterangan
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($permintaan as $item)

                            <tr
                                class="border-b
                                   border-slate-100
                                   dark:border-slate-700
                                   hover:bg-slate-50
                                   dark:hover:bg-slate-700/40
                                   transition">

                                {{-- NO --}}

                                <td
                                    class="py-4 px-5
                                       text-slate-500
                                       dark:text-slate-400">

                                    {{ $permintaan->firstItem() + $loop->index }}

                                </td>


                                {{-- TANGGAL --}}

                                <td
                                    class="py-4 px-5
                                       text-slate-700
                                       dark:text-slate-300
                                       whitespace-nowrap">

                                    {{ \Carbon\Carbon::parse($item->pad_realisasi_tanggal)->format('d/m/Y') }}

                                </td>


                                {{-- UNIT --}}

                                <td class="py-4 px-5">

                                    <div
                                        class="font-semibold
                                            text-slate-800
                                            dark:text-white">

                                        {{ $item->target->pad_target_unit_nama ?? '-' }}

                                    </div>

                                </td>


                                {{-- KOMPONEN --}}

                                <td class="py-4 px-5">

                                    @if ($item->target?->komponen)
                                        <div
                                            class="font-semibold
                                                text-slate-800
                                                dark:text-white">

                                            {{ $item->target->komponen->pad_komponen_nama }}

                                        </div>

                                        @if ($item->target->komponen->pad_komponen_kode)
                                            <div
                                                class="text-xs
                                                    text-slate-400 mt-1">

                                                {{ $item->target->komponen->pad_komponen_kode }}

                                            </div>
                                        @endif
                                    @else
                                        <span class="text-slate-400">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- SUBKOMPONEN --}}

                                <td class="py-4 px-5">

                                    @if ($item->subkomponen)
                                        <div
                                            class="font-semibold
                                                text-slate-800
                                                dark:text-white">

                                            {{ $item->subkomponen->pad_subkomponen_nama }}

                                        </div>

                                        @if ($item->subkomponen->pad_subkomponen_kode)
                                            <div
                                                class="text-xs
                                                    text-slate-400 mt-1">

                                                {{ $item->subkomponen->pad_subkomponen_kode }}

                                            </div>
                                        @endif
                                    @else
                                        <span class="text-slate-400">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- NOMINAL --}}

                                <td class="py-4 px-5 whitespace-nowrap">

                                    <span
                                        class="font-bold
                                             text-emerald-600
                                             dark:text-emerald-400">

                                        Rp
                                        {{ number_format($item->pad_realisasi_nominal, 0, ',', '.') }}

                                    </span>

                                </td>


                                {{-- KETERANGAN --}}

                                <td class="py-4 px-5">

                                    <span
                                        class="text-slate-600
                                             dark:text-slate-300">

                                        {{ $item->pad_realisasi_keterangan ?: '-' }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="py-12 px-5
                                       text-center">

                                    <div
                                        class="text-slate-400
                                            dark:text-slate-500">

                                        <div class="text-3xl mb-3">
                                            ∅
                                        </div>

                                        <p class="font-semibold">
                                            Belum ada data penerimaan PAD.
                                        </p>

                                        <p class="text-sm mt-1">
                                            Data penerimaan akan tampil di sini.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
            PAGINATION
        ====================================================== --}}

            @if ($permintaan->hasPages())
                <div
                    class="px-6 py-5
                        border-t border-slate-200
                        dark:border-slate-700">

                    {{ $permintaan->links() }}

                </div>
            @endif

        </div>

    </div>

@endsection
