@extends('administrator-v2.layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Standar Harga
            </h1>

            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Master SSH dan ASB
            </p>
        </div>

        <a href="{{ route('admin.standar-harga.import') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl
                   bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">

            <i class="bi bi-file-earmark-excel"></i>

            Import Excel

        </a>

    </div>


    {{-- REKAP --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        {{-- SSH --}}
        <div class="rounded-2xl bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-slate-800 p-5">

            <p class="text-sm text-slate-500 dark:text-slate-400">
                Total SSH
            </p>

            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">
                {{ number_format($totalSSH) }}
            </h2>

        </div>


        {{-- ASB --}}
        <div class="rounded-2xl bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-slate-800 p-5">

            <p class="text-sm text-slate-500 dark:text-slate-400">
                Total ASB
            </p>

            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">
                {{ number_format($totalASB) }}
            </h2>

        </div>


        {{-- AKTIF --}}
        <div class="rounded-2xl bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-slate-800 p-5">

            <p class="text-sm text-slate-500 dark:text-slate-400">
                Aktif
            </p>

            <h2 class="text-2xl font-bold text-emerald-600 mt-1">
                {{ number_format($aktif) }}
            </h2>

        </div>


        {{-- NONAKTIF --}}
        <div class="rounded-2xl bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-slate-800 p-5">

            <p class="text-sm text-slate-500 dark:text-slate-400">
                Nonaktif
            </p>

            <h2 class="text-2xl font-bold text-red-600 mt-1">
                {{ number_format($nonaktif) }}
            </h2>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-800 p-5">

        <form method="GET"
              action="{{ route('admin.standar-harga.index') }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- TAHUN --}}
            <div>

                <label class="block text-sm font-medium
                              text-slate-700 dark:text-slate-300 mb-2">

                    Tahun

                </label>

                <select name="tahun"
                        class="w-full rounded-xl border border-slate-300
                               dark:border-slate-700
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               px-4 py-3">

                    @for($i = now()->year + 1; $i >= 2020; $i--)

                        <option value="{{ $i }}"
                            {{ $tahun == $i ? 'selected' : '' }}>

                            {{ $i }}

                        </option>

                    @endfor

                </select>

            </div>


            {{-- JENIS --}}
            <div>

                <label class="block text-sm font-medium
                              text-slate-700 dark:text-slate-300 mb-2">

                    Jenis

                </label>

                <select name="jenis"
                        class="w-full rounded-xl border border-slate-300
                               dark:border-slate-700
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               px-4 py-3">

                    <option value="">
                        Semua
                    </option>

                    <option value="SSH"
                        {{ $jenis === 'SSH' ? 'selected' : '' }}>
                        SSH
                    </option>

                    <option value="ASB"
                        {{ $jenis === 'ASB' ? 'selected' : '' }}>
                        ASB
                    </option>

                </select>

            </div>


            {{-- SEARCH --}}
            <div>

                <label class="block text-sm font-medium
                              text-slate-700 dark:text-slate-300 mb-2">

                    Pencarian

                </label>

                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari kode, uraian, spesifikasi..."

                       class="w-full rounded-xl border border-slate-300
                              dark:border-slate-700
                              bg-white dark:bg-slate-800
                              text-slate-900 dark:text-white
                              px-4 py-3">

            </div>


            {{-- BUTTON --}}
            <div class="flex items-end gap-2">

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-slate-900
                               dark:bg-blue-600
                               text-white font-semibold">

                    <i class="bi bi-search mr-1"></i>

                    Cari

                </button>

                <a href="{{ route('admin.standar-harga.index') }}"
                   class="px-5 py-3 rounded-xl border
                          border-slate-300 dark:border-slate-700
                          text-slate-700 dark:text-slate-300">

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- TABLE --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-800
                overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="px-4 py-4 text-left">
                            No
                        </th>

                        <th class="px-4 py-4 text-left">
                            Jenis
                        </th>

                        <th class="px-4 py-4 text-left">
                            Kode Kelompok
                        </th>

                        <th class="px-4 py-4 text-left">
                            ID Standar
                        </th>

                        <th class="px-4 py-4 text-left">
                            Kode Barang
                        </th>

                        <th class="px-4 py-4 text-left">
                            Uraian Barang
                        </th>

                        <th class="px-4 py-4 text-left">
                            Spesifikasi
                        </th>

                        <th class="px-4 py-4 text-left">
                            Satuan
                        </th>

                        <th class="px-4 py-4 text-right">
                            Harga
                        </th>

                        <th class="px-4 py-4 text-center">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200
                             dark:divide-slate-800">

                    @forelse($standarHarga as $item)

                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">

                            <td class="px-4 py-4 text-slate-500">

                                {{ $standarHarga->firstItem() + $loop->index }}

                            </td>


                            <td class="px-4 py-4">

                                @if($item->standar_harga_jenis === 'SSH')

                                    <span class="inline-flex px-3 py-1 rounded-lg
                                                 bg-blue-100 text-blue-700
                                                 dark:bg-blue-900/30 dark:text-blue-400
                                                 font-semibold">

                                        SSH

                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 rounded-lg
                                                 bg-purple-100 text-purple-700
                                                 dark:bg-purple-900/30 dark:text-purple-400
                                                 font-semibold">

                                        ASB

                                    </span>

                                @endif

                            </td>


                            <td class="px-4 py-4 text-slate-700 dark:text-slate-300">

                                {{ $item->standar_harga_kode_kelompok ?: '-' }}

                            </td>


                            <td class="px-4 py-4 text-slate-700 dark:text-slate-300">

                                {{ $item->standar_harga_id_standar ?: '-' }}

                            </td>


                            <td class="px-4 py-4 text-slate-700 dark:text-slate-300">

                                {{ $item->standar_harga_kode_barang ?: '-' }}

                            </td>


                            <td class="px-4 py-4">

                                <div class="font-semibold text-slate-900 dark:text-white">

                                    {{ $item->standar_harga_uraian_barang ?: '-' }}

                                </div>

                                @if($item->standar_harga_uraian_kelompok)

                                    <div class="text-xs text-slate-500 mt-1">

                                        {{ $item->standar_harga_uraian_kelompok }}

                                    </div>

                                @endif

                            </td>


                            <td class="px-4 py-4 text-slate-600 dark:text-slate-400">

                                {{ $item->standar_harga_spesifikasi ?: '-' }}

                            </td>


                            <td class="px-4 py-4">

                                {{ $item->standar_harga_satuan ?: '-' }}

                            </td>


                            <td class="px-4 py-4 text-right font-semibold">

                                Rp
                                {{ number_format($item->standar_harga_satuan_harga, 0, ',', '.') }}

                            </td>


                            <td class="px-4 py-4 text-center">

                                <form method="POST"
                                      action="{{ route(
                                          'admin.standar-harga.status',
                                          $item->standar_harga_id
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5
                                                   rounded-lg text-xs font-semibold

                                                   {{ $item->standar_harga_status

                                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'

                                                        : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                                   }}">

                                        {{ $item->standar_harga_status
                                            ? 'Aktif'
                                            : 'Nonaktif'
                                        }}

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="px-6 py-12 text-center">

                                <div class="text-slate-400">

                                    <i class="bi bi-database-x text-4xl"></i>

                                    <p class="mt-3 font-semibold">
                                        Belum ada data
                                    </p>

                                    <p class="text-sm mt-1">
                                        Silakan import data SSH atau ASB.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($standarHarga->hasPages())

            <div class="px-5 py-4 border-t
                        border-slate-200 dark:border-slate-800">

                {{ $standarHarga->links() }}

            </div>

        @endif

    </div>

</div>

@endsection