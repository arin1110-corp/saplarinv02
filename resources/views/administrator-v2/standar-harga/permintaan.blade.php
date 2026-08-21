@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan Standar Harga')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div
            class="flex flex-col lg:flex-row
                lg:items-end
                lg:justify-between
                gap-5">

            <div>

                <div
                    class="flex items-center gap-2
                        text-sm
                        text-slate-400
                        dark:text-slate-500
                        mb-2">

                    <span>
                        Standar Harga
                    </span>

                    <span>/</span>

                    <span class="text-slate-500 dark:text-slate-400">
                        Permintaan
                    </span>

                </div>


                <h1
                    class="text-2xl
                       font-extrabold
                       text-slate-900
                       dark:text-white">

                    Permintaan Standar Harga

                </h1>


                <p
                    class="text-sm
                      text-slate-500
                      dark:text-slate-400
                      mt-1">

                    Daftar standar harga yang telah dipilih dan digunakan oleh operator.

                </p>

            </div>


            {{-- FILTER --}}

            <form method="GET" action="{{ route('admin.standar-harga.permintaan.index') }}" class="flex items-center gap-2">

                <select name="tahun" onchange="this.form.submit()"
                    class="h-11
                           rounded-xl
                           border border-slate-200
                           dark:border-slate-700
                           bg-white
                           dark:bg-slate-900
                           px-4
                           text-sm
                           font-semibold
                           text-slate-700
                           dark:text-slate-200
                           focus:ring-2
                           focus:ring-blue-500">

                    @for ($i = now()->year - 2; $i <= now()->year + 2; $i++)
                        <option value="{{ $i }}" {{ (int) $tahun === $i ? 'selected' : '' }}>

                            Tahun {{ $i }}

                        </option>
                    @endfor

                </select>


                <select name="jenis" onchange="this.form.submit()"
                    class="h-11
                           rounded-xl
                           border border-slate-200
                           dark:border-slate-700
                           bg-white
                           dark:bg-slate-900
                           px-4
                           text-sm
                           font-semibold
                           text-slate-700
                           dark:text-slate-200
                           focus:ring-2
                           focus:ring-blue-500">

                    <option value="SSH" {{ $jenis === 'SSH' ? 'selected' : '' }}>

                        SSH

                    </option>

                    <option value="ASB" {{ $jenis === 'ASB' ? 'selected' : '' }}>

                        ASB

                    </option>

                </select>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- STATISTIK --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1
                md:grid-cols-2
                gap-4">


            {{-- JUMLAH --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900
                    border border-slate-200
                    dark:border-slate-800
                    p-6
                    shadow-sm">

                <div class="flex items-center
                        justify-between">

                    <div>

                        <p
                            class="text-sm
                              text-slate-500
                              dark:text-slate-400">

                            {{ $jenis }} Digunakan

                        </p>


                        <h2
                            class="mt-1
                               text-3xl
                               font-extrabold
                               text-slate-900
                               dark:text-white">

                            {{ number_format($totalDigunakan, 0, ',', '.') }}

                        </h2>


                        <p
                            class="mt-1
                              text-xs
                              text-slate-400
                              dark:text-slate-500">

                            Tahun {{ $tahun }}

                        </p>

                    </div>


                    <div
                        class="flex h-14 w-14
                            items-center justify-center
                            rounded-2xl
                            bg-blue-50
                            dark:bg-blue-900/30">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7
                                text-blue-600
                                dark:text-blue-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />

                            <circle cx="12" cy="12" r="9" />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- TOTAL NILAI --}}

            <div
                class="rounded-3xl
                    bg-white
                    dark:bg-slate-900
                    border border-slate-200
                    dark:border-slate-800
                    p-6
                    shadow-sm">

                <div class="flex items-center
                        justify-between">

                    <div>

                        <p
                            class="text-sm
                              text-slate-500
                              dark:text-slate-400">

                            Total Nilai

                        </p>


                        <h2
                            class="mt-1
                               text-2xl
                               font-extrabold
                               text-slate-900
                               dark:text-white">

                            Rp

                            {{ number_format($totalNominal, 0, ',', '.') }}

                        </h2>


                        <p
                            class="mt-1
                              text-xs
                              text-slate-400
                              dark:text-slate-500">

                            Dari standar harga yang digunakan

                        </p>

                    </div>


                    <div
                        class="flex h-14 w-14
                            items-center justify-center
                            rounded-2xl
                            bg-emerald-50
                            dark:bg-emerald-900/30">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7
                                text-emerald-600
                                dark:text-emerald-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12M8 9.5c0-1.1 1.8-2 4-2s4 .9 4 2-1.8 2-4 2-4 .9-4 2 1.8 2 4 2 4-.9 4-2" />

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SEARCH + EXPORT --}}
        {{-- ========================================================= --}}

        <div
            class="rounded-3xl
                bg-white
                dark:bg-slate-900
                border border-slate-200
                dark:border-slate-800
                p-4
                shadow-sm">

            <div class="flex flex-col lg:flex-row gap-3">

                {{-- SEARCH --}}

                <form method="GET" action="{{ route('admin.standar-harga.permintaan.index') }}" class="flex-1">

                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <input type="hidden" name="jenis" value="{{ $jenis }}">


                    <div class="relative">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute
                                left-4
                                top-1/2
                                -translate-y-1/2
                                h-5 w-5
                                text-slate-400
                                dark:text-slate-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                            <circle cx="11" cy="11" r="7" />

                            <path stroke-linecap="round" d="M20 20l-4-4" />

                        </svg>


                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari kode barang, uraian, spesifikasi, rekening..."
                            class="w-full
                                  rounded-2xl
                                  border border-slate-200
                                  dark:border-slate-700
                                  bg-slate-50
                                  dark:bg-slate-950
                                  px-4
                                  py-3
                                  pl-11
                                  text-sm
                                  text-slate-700
                                  dark:text-slate-200
                                  placeholder:text-slate-400
                                  focus:border-blue-500
                                  focus:ring-2
                                  focus:ring-blue-100
                                  dark:focus:ring-blue-900/30">

                    </div>

                </form>


                {{-- EXPORT --}}

                <div class="flex flex-col sm:flex-row gap-2">

                    {{-- FILTER EXPORT --}}

                    <form method="GET" action="{{ route('admin.standar-harga.permintaan.export') }}"
                        class="flex flex-col sm:flex-row gap-2">

                        {{-- TAHUN --}}

                        <select name="tahun" required
                            class="h-11 rounded-xl
                       border border-slate-200
                       dark:border-slate-700
                       bg-white
                       dark:bg-slate-950
                       px-4
                       text-sm
                       font-semibold
                       text-slate-700
                       dark:text-slate-200
                       focus:border-blue-500
                       focus:ring-2
                       focus:ring-blue-500">

                            @for ($i = now()->year - 2; $i <= now()->year + 2; $i++)
                                <option value="{{ $i }}" {{ (int) $tahun === $i ? 'selected' : '' }}>

                                    Tahun {{ $i }}

                                </option>
                            @endfor

                        </select>


                        {{-- JENIS --}}

                        <select name="jenis" required
                            class="h-11 rounded-xl
                       border border-slate-200
                       dark:border-slate-700
                       bg-white
                       dark:bg-slate-950
                       px-4
                       text-sm
                       font-semibold
                       text-slate-700
                       dark:text-slate-200
                       focus:border-blue-500
                       focus:ring-2
                       focus:ring-blue-500">

                            <option value="SSH" {{ $jenis === 'SSH' ? 'selected' : '' }}>

                                SSH

                            </option>

                            <option value="ASB" {{ $jenis === 'ASB' ? 'selected' : '' }}>

                                ASB

                            </option>

                        </select>


                        {{-- EXPORT --}}

                        <button type="submit"
                            class="inline-flex
                       items-center
                       justify-center
                       gap-2
                       h-11
                       px-5
                       rounded-xl
                       bg-emerald-600
                       hover:bg-emerald-700
                       dark:bg-emerald-600
                       dark:hover:bg-emerald-500
                       text-white
                       text-sm
                       font-semibold
                       transition
                       whitespace-nowrap">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4" />

                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 21h14" />

                            </svg>

                            Export Excel

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DAFTAR --}}
        {{-- ========================================================= --}}

        <div
            class="rounded-3xl
                bg-white
                dark:bg-slate-900
                border border-slate-200
                dark:border-slate-800
                shadow-sm
                overflow-hidden">


            {{-- HEADER --}}

            <div
                class="px-6 py-5
                    border-b
                    border-slate-200
                    dark:border-slate-800">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10
                            items-center justify-center
                            rounded-xl
                            bg-blue-50
                            dark:bg-blue-900/30">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5
                                text-blue-600
                                dark:text-blue-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13" />

                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h.01M3 12h.01M3 18h.01" />

                        </svg>

                    </div>


                    <div>

                        <h2
                            class="font-bold
                               text-slate-900
                               dark:text-white">

                            Daftar {{ $jenis }} Digunakan

                        </h2>


                        <p
                            class="text-xs
                              text-slate-500
                              dark:text-slate-400">

                            Hanya menampilkan standar harga
                            yang telah dipilih operator.

                        </p>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr
                            class="bg-slate-50
                               dark:bg-slate-950
                               border-b
                               border-slate-200
                               dark:border-slate-800">

                            <th
                                class="px-4 py-4
                                   text-center
                                   w-16
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                No

                            </th>


                            <th
                                class="px-4 py-4
                                   text-left
                                   whitespace-nowrap
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                Kode Barang

                            </th>


                            <th
                                class="px-4 py-4
                                   text-left
                                   min-w-[300px]
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                Uraian Barang

                            </th>


                            <th
                                class="px-4 py-4
                                   text-left
                                   min-w-[250px]
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                Spesifikasi

                            </th>


                            <th
                                class="px-4 py-4
                                   text-left
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                Satuan

                            </th>


                            <th
                                class="px-4 py-4
                                   text-right
                                   whitespace-nowrap
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                Harga Satuan

                            </th>


                            <th
                                class="px-4 py-4
                                   text-left
                                   whitespace-nowrap
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                Kode Rekening

                            </th>


                            <th
                                class="px-4 py-4
                                   text-left
                                   whitespace-nowrap
                                   text-xs
                                   font-bold
                                   uppercase
                                   text-slate-500
                                   dark:text-slate-400">

                                Penginput

                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y
                             divide-slate-100
                             dark:divide-slate-800">

                        @forelse($permintaan as $index => $item)

                            @php
                                $standar = $item->standarHarga;
                            @endphp


                            @if ($standar)
                                <tr
                                    class="hover:bg-slate-50
                                       dark:hover:bg-slate-800/50
                                       transition">


                                    {{-- NO --}}

                                    <td
                                        class="px-4 py-4
                                           text-center
                                           text-slate-400
                                           dark:text-slate-500">

                                        {{ $permintaan->firstItem() + $index }}

                                    </td>


                                    {{-- KODE --}}

                                    <td class="px-4 py-4">

                                        <span
                                            class="font-semibold
                                                 text-slate-700
                                                 dark:text-slate-200
                                                 whitespace-nowrap">

                                            {{ $standar->standar_harga_kode_barang ?: '-' }}

                                        </span>

                                    </td>


                                    {{-- URAIAN --}}

                                    <td class="px-4 py-4">

                                        <div
                                            class="font-semibold
                                                text-slate-800
                                                dark:text-slate-100">

                                            {{ $standar->standar_harga_uraian_barang ?: '-' }}

                                        </div>


                                        @if ($standar->standar_harga_uraian_kelompok)
                                            <div
                                                class="mt-1
                                                    text-xs
                                                    text-slate-400
                                                    dark:text-slate-500">

                                                {{ $standar->standar_harga_uraian_kelompok }}

                                            </div>
                                        @endif

                                    </td>


                                    {{-- SPESIFIKASI --}}

                                    <td class="px-4 py-4">

                                        <div
                                            class="text-slate-600
                                                dark:text-slate-300
                                                leading-relaxed">

                                            {{ $standar->standar_harga_spesifikasi ?: '-' }}

                                        </div>

                                    </td>


                                    {{-- SATUAN --}}

                                    <td class="px-4 py-4">

                                        <span
                                            class="inline-flex
                                                 rounded-lg
                                                 bg-slate-100
                                                 dark:bg-slate-800
                                                 px-2.5
                                                 py-1
                                                 text-xs
                                                 font-semibold
                                                 text-slate-600
                                                 dark:text-slate-300">

                                            {{ $standar->standar_harga_satuan ?: '-' }}

                                        </span>

                                    </td>


                                    {{-- HARGA --}}

                                    <td class="px-4 py-4
                                           text-right">

                                        <span
                                            class="font-bold
                                                 text-slate-800
                                                 dark:text-white
                                                 whitespace-nowrap">

                                            Rp

                                            {{ number_format($standar->standar_harga_satuan_harga ?? 0, 0, ',', '.') }}

                                        </span>

                                    </td>


                                    {{-- REKENING --}}

                                    <td class="px-4 py-4">

                                        <span
                                            class="text-xs
                                                 font-medium
                                                 text-slate-500
                                                 dark:text-slate-400">

                                            {{ $standar->standar_harga_kode_rekening ?: '-' }}

                                        </span>

                                    </td>


                                    {{-- PENGINPUT --}}

                                    <td class="px-4 py-4">

                                        <div
                                            class="font-semibold
                                                text-slate-700
                                                dark:text-slate-200">

                                            {{ $item->penggunaan_input_nama ?: '-' }}

                                        </div>


                                        @if ($item->penggunaan_input_nip)
                                            <div
                                                class="text-xs
                                                    text-slate-400
                                                    dark:text-slate-500
                                                    mt-1">

                                                {{ $item->penggunaan_input_nip }}

                                            </div>
                                        @endif

                                    </td>

                                </tr>
                            @endif

                        @empty

                            <tr>

                                <td colspan="8" class="px-6 py-16
                                       text-center">

                                    <div class="flex flex-col
                                            items-center">

                                        <div
                                            class="flex h-14 w-14
                                                items-center justify-center
                                                rounded-2xl
                                                bg-slate-100
                                                dark:bg-slate-800">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-7 w-7
                                                    text-slate-400
                                                    dark:text-slate-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5v-11z" />

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 8h8M8 12h8M8 16h5" />

                                            </svg>

                                        </div>


                                        <p
                                            class="mt-4
                                              font-semibold
                                              text-slate-700
                                              dark:text-slate-200">

                                            Belum ada permintaan

                                        </p>


                                        <p
                                            class="mt-1
                                              text-sm
                                              text-slate-400
                                              dark:text-slate-500">

                                            Belum ada
                                            {{ $jenis }}
                                            yang dipilih oleh operator.

                                        </p>

                                    </div>

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
                    class="border-t
                        border-slate-200
                        dark:border-slate-800
                        px-5 py-4">

                    {{ $permintaan->links() }}

                </div>
            @endif

        </div>

    </div>

@endsection
