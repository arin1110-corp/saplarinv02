@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan PAD')
@section('page_title', 'Permintaan PAD')
@section('breadcrumb', 'Permintaan PAD')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- ALERT --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div
                class="bg-green-50
                    dark:bg-green-900/20
                    border border-green-200
                    dark:border-green-800
                    text-green-700
                    dark:text-green-300
                    px-5 py-4
                    rounded-2xl">

                {{ session('success') }}

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div
            class="flex flex-col
                lg:flex-row
                lg:items-center
                lg:justify-between
                gap-4">

            <div>

                <h1
                    class="text-2xl
                       font-bold
                       text-slate-900
                       dark:text-white">

                    Permintaan PAD

                </h1>

                <p
                    class="text-sm
                      text-slate-500
                      dark:text-slate-400
                      mt-1">

                    Data penerimaan PAD yang telah dimasukkan operator.

                </p>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="grid
                grid-cols-1
                sm:grid-cols-2
                gap-4">


            {{-- TOTAL DATA --}}

            <div
                class="bg-white
                    dark:bg-slate-900
                    border
                    border-slate-200
                    dark:border-slate-800
                    rounded-3xl
                    p-6">

                <div class="flex
                        items-center
                        justify-between">

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Total Penerimaan

                        </div>

                        <div
                            class="text-3xl
                                font-bold
                                text-slate-900
                                dark:text-white
                                mt-2">

                            {{ number_format($totalData, 0, ',', '.') }}

                        </div>

                    </div>


                    <div
                        class="w-12 h-12
                            rounded-2xl
                            bg-blue-50
                            dark:bg-blue-900/20
                            text-blue-600
                            dark:text-blue-400
                            flex
                            items-center
                            justify-center">

                        <i class="bi bi-receipt text-xl"></i>

                    </div>

                </div>

            </div>



            {{-- TOTAL NOMINAL --}}

            <div
                class="bg-white
                    dark:bg-slate-900
                    border
                    border-slate-200
                    dark:border-slate-800
                    rounded-3xl
                    p-6">

                <div class="flex
                        items-center
                        justify-between">

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Total Penerimaan

                        </div>

                        <div
                            class="text-2xl
                                font-bold
                                text-green-600
                                mt-2">

                            Rp
                            {{ number_format($totalNominal, 0, ',', '.') }}

                        </div>

                    </div>


                    <div
                        class="w-12 h-12
                            rounded-2xl
                            bg-green-50
                            dark:bg-green-900/20
                            text-green-600
                            dark:text-green-400
                            flex
                            items-center
                            justify-center">

                        <i class="bi bi-cash-stack text-xl"></i>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- TABLE CARD --}}
        {{-- ========================================================= --}}

        <div
            class="bg-white
                dark:bg-slate-900

                border
                border-slate-200
                dark:border-slate-800

                rounded-3xl
                overflow-hidden">


            {{-- ===================================================== --}}
            {{-- FILTER --}}
            {{-- ===================================================== --}}

            <div
                class="p-6
                    border-b
                    border-slate-200
                    dark:border-slate-800">

                <form method="GET">

                    <div
                        class="flex
                            flex-col
                            md:flex-row
                            gap-3">


                        {{-- SEARCH --}}

                        <div class="flex-1">

                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Cari nama, unit, subkomponen..."
                                class="w-full
                                   rounded-2xl

                                   border
                                   border-slate-300
                                   dark:border-slate-700

                                   bg-white
                                   dark:bg-slate-800

                                   text-slate-900
                                   dark:text-white

                                   px-4 py-3

                                   outline-none

                                   focus:ring-2
                                   focus:ring-blue-500">

                        </div>


                        {{-- TAHUN --}}

                        <select name="tahun"
                            class="rounded-2xl

                               border
                               border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-800

                               text-slate-900
                               dark:text-white

                               px-4 py-3

                               outline-none

                               focus:ring-2
                               focus:ring-blue-500">

                            @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>

                                    {{ $y }}

                                </option>
                            @endfor

                        </select>


                        {{-- BUTTON --}}

                        <button type="submit"
                            class="px-5 py-3
                               rounded-2xl

                               bg-blue-600
                               hover:bg-blue-700

                               text-white
                               font-semibold

                               transition">

                            <i class="bi bi-search me-1"></i>

                            Cari

                        </button>

                    </div>

                </form>

            </div>



            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr
                            class="border-b
                               border-slate-200
                               dark:border-slate-800

                               bg-slate-50
                               dark:bg-slate-800/50

                               text-left

                               text-slate-500
                               dark:text-slate-400">

                            <th class="py-4 px-4">
                                No
                            </th>

                            <th class="py-4 px-4">
                                Tanggal
                            </th>

                            <th class="py-4 px-4">
                                Unit
                            </th>

                            <th class="py-4 px-4">
                                Subkomponen
                            </th>

                            <th class="py-4 px-4">
                                Nominal
                            </th>

                            <th class="py-4 px-4">
                                Penginput
                            </th>

                            <th class="py-4 px-4">
                                Status
                            </th>

                            <th class="py-4 px-4 text-right">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($permintaan as $item)
                            <tr
                                class="border-b
                                   border-slate-100
                                   dark:border-slate-800

                                   hover:bg-slate-50
                                   dark:hover:bg-slate-800/50">

                                {{-- NO --}}

                                <td
                                    class="py-4 px-4
                                       text-slate-500
                                       dark:text-slate-400">

                                    {{ $permintaan->firstItem() + $loop->index }}

                                </td>


                                {{-- TANGGAL --}}

                                <td class="py-4 px-4
                                       whitespace-nowrap">

                                    <div
                                        class="font-semibold
                                            text-slate-800
                                            dark:text-white">

                                        {{ \Carbon\Carbon::parse($item->pad_realisasi_tanggal)->translatedFormat('d M Y') }}

                                    </div>

                                </td>


                                {{-- UNIT --}}

                                <td class="py-4 px-4">

                                    <div
                                        class="font-medium
                                            text-slate-800
                                            dark:text-white">

                                        {{ $item->target->pad_target_unit_nama ?? '-' }}

                                    </div>

                                </td>


                                {{-- SUBKOMPONEN --}}

                                <td class="py-4 px-4">

                                    <div
                                        class="font-semibold
                                            text-slate-800
                                            dark:text-white">

                                        {{ $item->subkomponen->pad_subkomponen_nama ?? '-' }}

                                    </div>


                                    @if ($item->subkomponen && $item->subkomponen->pad_subkomponen_kode)
                                        <div
                                            class="text-xs
                                                text-slate-400
                                                dark:text-slate-500
                                                mt-1">

                                            {{ $item->subkomponen->pad_subkomponen_kode }}

                                        </div>
                                    @endif

                                </td>


                                {{-- NOMINAL --}}

                                <td class="py-4 px-4
                                       whitespace-nowrap">

                                    <span
                                        class="font-bold
                                             text-slate-900
                                             dark:text-white">

                                        Rp
                                        {{ number_format($item->pad_realisasi_nominal, 0, ',', '.') }}

                                    </span>

                                </td>


                                {{-- PENGINPUT --}}

                                <td class="py-4 px-4">

                                    <div
                                        class="font-medium
                                            text-slate-800
                                            dark:text-white">

                                        {{ $item->pad_realisasi_input_nama }}

                                    </div>

                                    <div
                                        class="text-xs
                                            text-slate-400
                                            dark:text-slate-500
                                            mt-1">

                                        {{ $item->pad_realisasi_input_nip }}

                                    </div>

                                </td>


                                {{-- STATUS --}}

                                <td class="py-4 px-4">

                                    @if ($item->pad_realisasi_status === 'Diterima')
                                        <span
                                            class="inline-flex
                                                 items-center
                                                 gap-1.5

                                                 px-3 py-1.5

                                                 rounded-full

                                                 bg-green-100
                                                 dark:bg-green-900/30

                                                 text-green-700
                                                 dark:text-green-400

                                                 text-xs
                                                 font-semibold">

                                            <span
                                                class="w-1.5 h-1.5
                                                     rounded-full
                                                     bg-green-500"></span>

                                            Diterima

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex
                                                 items-center
                                                 gap-1.5

                                                 px-3 py-1.5

                                                 rounded-full

                                                 bg-slate-100
                                                 dark:bg-slate-800

                                                 text-slate-600
                                                 dark:text-slate-400

                                                 text-xs
                                                 font-semibold">

                                            {{ $item->pad_realisasi_status ?? '-' }}

                                        </span>
                                    @endif

                                </td>


                                {{-- AKSI --}}

                                <td class="py-4 px-4 text-right">

                                    <a href="{{ route('admin.pad.permintaan.detail', $item->pad_realisasi_uid) }}"
                                        class="inline-flex
                                           items-center
                                           justify-center

                                           w-10 h-10

                                           rounded-xl

                                           bg-blue-50
                                           dark:bg-blue-900/20

                                           text-blue-600
                                           dark:text-blue-400

                                           hover:bg-blue-100
                                           dark:hover:bg-blue-900/40

                                           transition">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="py-14
                                       text-center

                                       text-slate-500
                                       dark:text-slate-400">

                                    Belum ada penerimaan PAD.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- ===================================================== --}}
            {{-- PAGINATION --}}
            {{-- ===================================================== --}}

            @if ($permintaan->hasPages())
                <div
                    class="px-6 py-5
                        border-t
                        border-slate-200
                        dark:border-slate-800">

                    {{ $permintaan->links() }}

                </div>
            @endif

        </div>

    </div>

@endsection
