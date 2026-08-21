@extends('user.layouts.app')

@section('title', 'Standar Harga')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">

        <div>

            <div class="flex items-center gap-2 text-sm text-slate-400 mb-2">

                <a href="{{ route('user.dashboard') }}"
                   class="hover:text-blue-600 transition">

                    Dashboard

                </a>

                <span>/</span>

                <span class="text-slate-500">
                    Standar Harga
                </span>

            </div>

            <h1 class="text-2xl font-extrabold text-slate-900">
                Standar Harga
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Pilih standar harga yang digunakan dalam pelaksanaan kegiatan.
            </p>

        </div>


        {{-- FILTER --}}

        <form method="GET"
              action="{{ route('user.standar-harga.index') }}"
              class="flex items-center gap-2">

            <select name="tahun"
                    onchange="this.form.submit()"
                    class="h-11 rounded-xl
                           border border-slate-200
                           bg-white
                           px-4
                           text-sm
                           font-semibold
                           text-slate-700
                           outline-none
                           focus:border-blue-500
                           focus:ring-2
                           focus:ring-blue-100">

                @for($i = now()->year - 2; $i <= now()->year + 2; $i++)

                    <option value="{{ $i }}"
                        {{ (int) $tahun === $i ? 'selected' : '' }}>

                        Tahun {{ $i }}

                    </option>

                @endfor

            </select>


            <select name="jenis"
                    onchange="this.form.submit()"
                    class="h-11 rounded-xl
                           border border-slate-200
                           bg-white
                           px-4
                           text-sm
                           font-semibold
                           text-slate-700
                           outline-none
                           focus:border-blue-500
                           focus:ring-2
                           focus:ring-blue-100">

                <option value="SSH"
                    {{ $jenis === 'SSH' ? 'selected' : '' }}>
                    SSH
                </option>

                <option value="ASB"
                    {{ $jenis === 'ASB' ? 'selected' : '' }}>
                    ASB
                </option>

            </select>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERT --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="rounded-2xl
                    border border-emerald-200
                    bg-emerald-50
                    px-5 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0
                            items-center justify-center
                            rounded-xl
                            bg-emerald-100">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-emerald-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>

                    </svg>

                </div>

                <div>

                    <p class="font-semibold text-emerald-800">
                        Berhasil
                    </p>

                    <p class="text-sm text-emerald-600">
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="rounded-2xl
                    border border-red-200
                    bg-red-50
                    px-5 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0
                            items-center justify-center
                            rounded-xl
                            bg-red-100">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-red-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20.5h15.6a2 2 0 001.73-3.14l-7.82-13.5a2 2 0 00-3.42 0z"/>

                    </svg>

                </div>

                <div>

                    <p class="font-semibold text-red-800">
                        Terjadi Kesalahan
                    </p>

                    <p class="text-sm text-red-600">
                        {{ session('error') }}
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTIK --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- TOTAL --}}

        <div class="rounded-3xl
                    bg-white
                    border border-slate-200
                    p-6
                    shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total {{ $jenis }}
                    </p>

                    <h2 class="mt-1
                               text-3xl
                               font-extrabold
                               text-slate-900">

                        {{ number_format($totalMaster, 0, ',', '.') }}

                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Tahun {{ $tahun }}
                    </p>

                </div>


                {{-- ICON SVG --}}

                <div class="flex h-14 w-14 shrink-0
                            items-center justify-center
                            rounded-2xl
                            bg-blue-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7 text-blue-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5v-11z"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 8h8M8 12h8M8 16h5"/>

                    </svg>

                </div>

            </div>

        </div>


        {{-- DIGUNAKAN --}}

        <div class="rounded-3xl
                    bg-white
                    border border-slate-200
                    p-6
                    shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        {{ $jenis }} Digunakan
                    </p>

                    <h2 class="mt-1
                               text-3xl
                               font-extrabold
                               text-slate-900">

                        {{ number_format($totalDigunakan, 0, ',', '.') }}

                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Pilihan Anda
                    </p>

                </div>


                {{-- ICON SVG --}}

                <div class="flex h-14 w-14 shrink-0
                            items-center justify-center
                            rounded-2xl
                            bg-emerald-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7 text-emerald-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12l2 2 4-4"/>

                        <circle cx="12"
                                cy="12"
                                r="9"/>

                    </svg>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SEARCH --}}
    {{-- ========================================================= --}}

    <div class="rounded-3xl
                bg-white
                border border-slate-200
                p-4
                shadow-sm">

        <form method="GET"
              action="{{ route('user.standar-harga.index') }}">

            <input type="hidden"
                   name="tahun"
                   value="{{ $tahun }}">

            <input type="hidden"
                   name="jenis"
                   value="{{ $jenis }}">


            <div class="relative">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="absolute
                            left-4
                            top-1/2
                            -translate-y-1/2
                            h-5 w-5
                            text-slate-400"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <circle cx="11"
                            cy="11"
                            r="7"/>

                    <path stroke-linecap="round"
                          d="M20 20l-4-4"/>

                </svg>


                <input type="text"
                       name="search"
                       value="{{ $search }}"

                       placeholder="Cari kode barang, uraian, spesifikasi, rekening..."

                       class="w-full
                              rounded-2xl
                              border border-slate-200
                              bg-slate-50
                              px-4
                              py-3
                              pl-11
                              text-sm
                              text-slate-700
                              placeholder:text-slate-400
                              outline-none
                              focus:border-blue-500
                              focus:ring-2
                              focus:ring-blue-100">

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- FORM CHECKLIST --}}
    {{-- ========================================================= --}}

    <form method="POST"
          action="{{ route('user.standar-harga.store') }}">

        @csrf

        <input type="hidden"
               name="tahun"
               value="{{ $tahun }}">

        <input type="hidden"
               name="jenis"
               value="{{ $jenis }}">


        {{-- ===================================================== --}}
        {{-- TOOLBAR --}}
        {{-- ===================================================== --}}

        <div class="rounded-3xl
                    bg-white
                    border border-slate-200
                    p-5
                    shadow-sm
                    mb-4">

            <div class="flex flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-4">

                <div class="flex items-center gap-3">

                    {{-- ICON SVG --}}

                    <div class="flex h-10 w-10 shrink-0
                                items-center justify-center
                                rounded-xl
                                bg-blue-50">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 text-blue-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M8 6h13M8 12h13M8 18h13"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 6h.01M3 12h.01M3 18h.01"/>

                        </svg>

                    </div>


                    <div>

                        <h2 class="font-bold text-slate-900">
                            Daftar {{ $jenis }}
                        </h2>

                        <p class="text-xs text-slate-500 mt-0.5">
                            Centang standar harga yang digunakan.
                        </p>

                    </div>

                </div>


                <div class="flex flex-wrap items-center gap-2">

                    <button type="button"
                            onclick="checkAll()"
                            class="inline-flex
                                   items-center
                                   gap-2
                                   px-4
                                   py-2.5
                                   rounded-xl
                                   bg-slate-100
                                   hover:bg-slate-200
                                   text-slate-700
                                   text-sm
                                   font-semibold
                                   transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>

                        </svg>

                        Pilih Semua

                    </button>


                    <button type="button"
                            onclick="uncheckAll()"
                            class="inline-flex
                                   items-center
                                   gap-2
                                   px-4
                                   py-2.5
                                   rounded-xl
                                   bg-slate-100
                                   hover:bg-slate-200
                                   text-slate-700
                                   text-sm
                                   font-semibold
                                   transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6 18L18 6M6 6l12 12"/>

                        </svg>

                        Hapus Pilihan

                    </button>


                    <button type="submit"
                            class="inline-flex
                                   items-center
                                   gap-2
                                   px-5
                                   py-2.5
                                   rounded-xl
                                   bg-blue-600
                                   hover:bg-blue-700
                                   text-white
                                   text-sm
                                   font-bold
                                   transition
                                   shadow-sm">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 12.5l4 4L19 7"/>

                        </svg>

                        Simpan

                    </button>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TABLE --}}
        {{-- ========================================================= --}}

        <div class="rounded-3xl
                    bg-white
                    border border-slate-200
                    shadow-sm
                    overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="bg-slate-50
                                   border-b border-slate-200">

                            <th class="px-4 py-4
                                       text-center
                                       w-16
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Pilih

                            </th>


                            <th class="px-4 py-4
                                       text-left
                                       whitespace-nowrap
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Kode Barang

                            </th>


                            <th class="px-4 py-4
                                       text-left
                                       min-w-[300px]
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Uraian Barang

                            </th>


                            <th class="px-4 py-4
                                       text-left
                                       min-w-[250px]
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Spesifikasi

                            </th>


                            <th class="px-4 py-4
                                       text-left
                                       whitespace-nowrap
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Satuan

                            </th>


                            <th class="px-4 py-4
                                       text-right
                                       whitespace-nowrap
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Harga Satuan

                            </th>


                            <th class="px-4 py-4
                                       text-left
                                       whitespace-nowrap
                                       text-xs
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Kode Rekening

                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($data as $item)

                            @php

                                $checked = in_array(
                                    $item->standar_harga_id,
                                    $penggunaan
                                );

                            @endphp


                            <tr class="hover:bg-slate-50 transition">

                                {{-- CHECKBOX --}}

                                <td class="px-4 py-4 text-center">

                                    <input type="checkbox"
                                           name="standar_harga[]"
                                           value="{{ $item->standar_harga_id }}"

                                           class="standar-harga-checkbox
                                                  h-5 w-5
                                                  rounded-md
                                                  border-slate-300
                                                  text-blue-600
                                                  focus:ring-blue-500
                                                  cursor-pointer"

                                           {{ $checked ? 'checked' : '' }}>

                                </td>


                                {{-- KODE --}}

                                <td class="px-4 py-4">

                                    <span class="font-semibold
                                                 text-slate-700
                                                 whitespace-nowrap">

                                        {{ $item->standar_harga_kode_barang ?: '-' }}

                                    </span>

                                </td>


                                {{-- URAIAN --}}

                                <td class="px-4 py-4">

                                    <div class="font-semibold
                                                text-slate-800">

                                        {{ $item->standar_harga_uraian_barang ?: '-' }}

                                    </div>


                                    @if($item->standar_harga_uraian_kelompok)

                                        <div class="text-xs
                                                    text-slate-400
                                                    mt-1">

                                            {{ $item->standar_harga_uraian_kelompok }}

                                        </div>

                                    @endif

                                </td>


                                {{-- SPESIFIKASI --}}

                                <td class="px-4 py-4">

                                    <div class="text-slate-600
                                                leading-relaxed">

                                        {{ $item->standar_harga_spesifikasi ?: '-' }}

                                    </div>

                                </td>


                                {{-- SATUAN --}}

                                <td class="px-4 py-4">

                                    <span class="inline-flex
                                                 px-2.5 py-1
                                                 rounded-lg
                                                 bg-slate-100
                                                 text-slate-600
                                                 text-xs
                                                 font-semibold
                                                 whitespace-nowrap">

                                        {{ $item->standar_harga_satuan ?: '-' }}

                                    </span>

                                </td>


                                {{-- HARGA --}}

                                <td class="px-4 py-4 text-right">

                                    <span class="font-bold
                                                 text-slate-800
                                                 whitespace-nowrap">

                                        Rp
                                        {{ number_format(
                                            $item->standar_harga_satuan_harga ?? 0,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </span>

                                </td>


                                {{-- REKENING --}}

                                <td class="px-4 py-4">

                                    <span class="text-xs
                                                 font-medium
                                                 text-slate-500">

                                        {{ $item->standar_harga_kode_rekening ?: '-' }}

                                    </span>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="7"
                                    class="px-6 py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="flex h-14 w-14
                                                    items-center justify-center
                                                    rounded-2xl
                                                    bg-slate-100">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-7 w-7 text-slate-400"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5v-11z"/>

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M8 8h8M8 12h8M8 16h5"/>

                                            </svg>

                                        </div>


                                        <p class="mt-4
                                                  font-semibold
                                                  text-slate-700">

                                            Data tidak ditemukan

                                        </p>


                                        <p class="mt-1
                                                  text-sm
                                                  text-slate-400">

                                            Tidak ada data
                                            {{ $jenis }}
                                            untuk pencarian tersebut.

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

            @if($data->hasPages())

                <div class="border-t
                            border-slate-200
                            px-5 py-4">

                    {{ $data->links() }}

                </div>

            @endif

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

function checkAll()
{
    document
        .querySelectorAll('.standar-harga-checkbox')
        .forEach(function (checkbox) {

            checkbox.checked = true;

        });
}


function uncheckAll()
{
    document
        .querySelectorAll('.standar-harga-checkbox')
        .forEach(function (checkbox) {

            checkbox.checked = false;

        });
}

</script>

@endsection