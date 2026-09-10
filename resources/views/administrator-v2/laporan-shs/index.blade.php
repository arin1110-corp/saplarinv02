@extends('administrator-v2.layouts.app')

@section('title', 'Laporan SHS')
@section('page-title', 'Laporan SHS')
@section('page-description', 'Verifikasi usulan Standar Harga Satuan')

@section('content')

    {{-- =========================================================
        ALERT SUCCESS
    ========================================================== --}}
    @if (session('success'))
        <div
            class="mb-6 rounded-2xl border border-green-200
                   bg-green-50 dark:bg-green-900/20
                   dark:border-green-800 px-5 py-4">

            <div class="flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>

                <span class="text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </span>
            </div>

        </div>
    @endif


    {{-- =========================================================
        ALERT ERROR
    ========================================================== --}}
    @if (session('error'))
        <div
            class="mb-6 rounded-2xl border border-red-200
                   bg-red-50 dark:bg-red-900/20
                   dark:border-red-800 px-5 py-4">

            <div class="flex items-center gap-3">
                <i class="bi bi-x-circle-fill text-red-600 text-xl"></i>

                <span class="text-red-700 dark:text-red-300">
                    {{ session('error') }}
                </span>
            </div>

        </div>
    @endif


    {{-- =========================================================
        VALIDATION ERROR
    ========================================================== --}}
    @if ($errors->any())
        <div
            class="mb-6 rounded-2xl border border-red-200
                   bg-red-50 dark:bg-red-900/20
                   dark:border-red-800 px-5 py-4">

            <div class="flex items-start gap-3">

                <i class="bi bi-exclamation-triangle-fill
                           text-red-600 text-xl mt-1">
                </i>

                <div>

                    <h4
                        class="font-semibold
                               text-red-700
                               dark:text-red-300
                               mb-2">
                        Terjadi Kesalahan
                    </h4>

                    <ul
                        class="list-disc
                               list-inside
                               text-sm
                               text-red-600
                               dark:text-red-300
                               space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>
    @endif


    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div
        class="flex flex-col
               lg:flex-row
               lg:items-center
               lg:justify-between
               gap-4
               mb-6">

        <div>

            <h2
                class="text-2xl
                       font-bold
                       text-slate-800
                       dark:text-white">
                Laporan SHS
            </h2>

            <p class="text-slate-500
                       dark:text-slate-400">
                Verifikasi usulan Standar Harga Satuan
                dari seluruh operator.
            </p>

        </div>


        <button type="button" onclick="openExportModal()"
            class="inline-flex
                   items-center
                   gap-2
                   rounded-xl
                   bg-emerald-600
                   hover:bg-emerald-700
                   px-5 py-3
                   text-white
                   font-semibold
                   transition">

            <i class="bi bi-file-earmark-excel"></i>

            Export Excel

        </button>

    </div>


    {{-- =========================================================
        SEARCH
    ========================================================== --}}
    <div
        class="mb-6
               bg-white
               dark:bg-slate-900
               rounded-3xl
               border
               border-slate-200
               dark:border-slate-800
               shadow-sm
               p-5">

        <form method="GET" action="{{ url()->current() }}"
            class="flex flex-col
                   md:flex-row
                   md:items-center
                   gap-3">

            <div class="relative flex-1">

                <i
                    class="bi bi-search
                           absolute
                           left-4
                           top-1/2
                           -translate-y-1/2
                           text-slate-400">
                </i>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari barang, unit, kelompok, operator, NIP..."
                    class="w-full
                           rounded-xl
                           border
                           border-slate-300
                           dark:border-slate-700
                           bg-white
                           dark:bg-slate-800
                           text-slate-800
                           dark:text-white
                           pl-11
                           pr-4
                           py-3
                           focus:outline-none
                           focus:border-blue-500
                           focus:ring-2
                           focus:ring-blue-500/20">

            </div>


            <button type="submit"
                class="inline-flex
                       items-center
                       justify-center
                       gap-2
                       rounded-xl
                       bg-blue-600
                       hover:bg-blue-700
                       px-5 py-3
                       text-white
                       font-semibold">

                <i class="bi bi-search"></i>

                Cari

            </button>


            @if (request('search'))
                <a href="{{ url()->current() }}"
                    class="inline-flex
                           items-center
                           justify-center
                           gap-2
                           rounded-xl
                           border
                           border-slate-300
                           dark:border-slate-700
                           px-5 py-3
                           text-slate-700
                           dark:text-slate-200
                           hover:bg-slate-100
                           dark:hover:bg-slate-800">

                    <i class="bi bi-x-circle"></i>

                    Reset

                </a>
            @endif

        </form>

    </div>


    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <div
        class="bg-white
               dark:bg-slate-900
               rounded-3xl
               border
               border-slate-200
               dark:border-slate-800
               shadow-sm
               overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-100
                           dark:bg-slate-800">

                    <tr>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            Aksi
                        </th>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            No
                        </th>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            Unit
                        </th>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            Barang
                        </th>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            Kelompok
                        </th>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            Harga
                        </th>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            Operator
                        </th>

                        <th
                            class="px-4 py-4
                                   text-left
                                   whitespace-nowrap">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($shs as $item)
                        <tr
                            class="border-t
                                   border-slate-200
                                   dark:border-slate-800
                                   hover:bg-slate-50
                                   dark:hover:bg-slate-800/50">

                            {{-- =================================================
                                AKSI
                            ================================================== --}}
                            <td class="px-4 py-4">

                                <div
                                    class="flex
                                           flex-wrap
                                           gap-2">

                                    {{-- DETAIL --}}
                                    <button type="button" onclick='detailSHS(@json($item->loadMissing('referensiHarga')))'
                                        class="inline-flex
                                               items-center
                                               gap-2
                                               rounded-lg
                                               bg-slate-700
                                               hover:bg-slate-800
                                               px-3 py-2
                                               text-xs
                                               font-medium
                                               text-white">

                                        <i class="bi bi-eye"></i>

                                        Detail

                                    </button>


                                    {{-- DIAJUKAN --}}
                                    @if ($item->shs_status === 'Diajukan')
                                        <button type="button" onclick='verifikasiSHS(@json($item))'
                                            class="inline-flex
                                                   items-center
                                                   gap-2
                                                   rounded-lg
                                                   bg-blue-600
                                                   hover:bg-blue-700
                                                   px-3 py-2
                                                   text-xs
                                                   font-medium
                                                   text-white">

                                            <i class="bi bi-check-circle"></i>

                                            Verifikasi

                                        </button>


                                        <form method="POST" action="{{ route('admin.shs.nonaktif', $item->shs_uid) }}">

                                            @csrf

                                            <button type="submit"
                                                class="inline-flex
                                                       items-center
                                                       gap-2
                                                       rounded-lg
                                                       bg-red-600
                                                       hover:bg-red-700
                                                       px-3 py-2
                                                       text-xs
                                                       text-white">

                                                <i class="bi bi-x-circle"></i>

                                                Tidak Diajukan

                                            </button>

                                        </form>


                                        {{-- TIDAK DIAJUKAN --}}
                                    @elseif ($item->shs_status === 'Tidak Diajukan')
                                        <form method="POST"
                                            action="{{ route('admin.laporan.shs.aktif', $item->shs_uid) }}">

                                            @csrf

                                            <button type="submit"
                                                class="inline-flex
                                                       items-center
                                                       gap-2
                                                       rounded-lg
                                                       bg-green-600
                                                       hover:bg-green-700
                                                       px-3 py-2
                                                       text-xs
                                                       text-white">

                                                <i class="bi bi-send"></i>

                                                Diajukan

                                            </button>

                                        </form>


                                        {{-- DIVERIFIKASI --}}
                                    @elseif ($item->shs_status === 'Diverifikasi')
                                        <span
                                            class="inline-flex
                                                   items-center
                                                   gap-2
                                                   rounded-lg
                                                   bg-blue-100
                                                   dark:bg-blue-900/20
                                                   px-3 py-2
                                                   text-xs
                                                   font-semibold
                                                   text-blue-700
                                                   dark:text-blue-300">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Sudah Diverifikasi

                                        </span>
                                    @endif

                                </div>

                            </td>


                            {{-- NOMOR --}}
                            <td class="px-4 py-4
                                       whitespace-nowrap">

                                {{ $shs->firstItem() + $loop->index }}

                            </td>


                            {{-- UNIT --}}
                            <td class="px-4 py-4">

                                <div
                                    class="font-medium
                                           text-slate-800
                                           dark:text-white">

                                    {{ $item->shs_unit_nama }}

                                </div>

                                @if ($item->shs_unit_kode)
                                    <div
                                        class="text-xs
                                               text-slate-500
                                               dark:text-slate-400">

                                        {{ $item->shs_unit_kode }}

                                    </div>
                                @endif

                            </td>


                            {{-- BARANG --}}
                            <td class="px-4 py-4">

                                <div
                                    class="font-semibold
                                           text-slate-800
                                           dark:text-white">

                                    {{ $item->shs_barang }}

                                </div>

                                <div
                                    class="text-xs
                                           text-slate-500
                                           dark:text-slate-400
                                           mt-1">

                                    {{ $item->shs_satuan }}

                                </div>

                            </td>


                            {{-- KELOMPOK --}}
                            <td class="px-4 py-4">

                                {{ $item->shs_kelompok_barang }}

                            </td>


                            {{-- HARGA --}}
                            <td
                                class="px-4 py-4
                                       font-semibold
                                       whitespace-nowrap">

                                Rp
                                {{ number_format((float) $item->shs_harga, 0, ',', '.') }}

                            </td>


                            {{-- OPERATOR --}}
                            <td class="px-4 py-4">

                                <div
                                    class="font-semibold
                                           text-slate-800
                                           dark:text-white">

                                    {{ $item->shs_operator_nama }}

                                </div>

                                <div
                                    class="text-xs
                                           text-slate-500
                                           dark:text-slate-400
                                           mt-1">

                                    {{ $item->shs_operator_nip }}

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-4 py-4">

                                @if ($item->shs_status === 'Diajukan')
                                    <span
                                        class="inline-flex
                                               rounded-full
                                               bg-green-100
                                               dark:bg-green-900/20
                                               px-3 py-1
                                               text-xs
                                               font-semibold
                                               text-green-700
                                               dark:text-green-300">

                                        Diajukan

                                    </span>
                                @elseif ($item->shs_status === 'Diverifikasi')
                                    <span
                                        class="inline-flex
                                               rounded-full
                                               bg-blue-100
                                               dark:bg-blue-900/20
                                               px-3 py-1
                                               text-xs
                                               font-semibold
                                               text-blue-700
                                               dark:text-blue-300">

                                        Diverifikasi

                                    </span>
                                @else
                                    <span
                                        class="inline-flex
                                               rounded-full
                                               bg-red-100
                                               dark:bg-red-900/20
                                               px-3 py-1
                                               text-xs
                                               font-semibold
                                               text-red-700
                                               dark:text-red-300">

                                        Tidak Diajukan

                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-16
                                       text-center">

                                <div
                                    class="flex
                                           flex-col
                                           items-center
                                           justify-center">

                                    <i
                                        class="bi bi-inbox
                                               text-5xl
                                               text-slate-300
                                               dark:text-slate-700
                                               mb-4">
                                    </i>

                                    <div
                                        class="font-semibold
                                               text-slate-700
                                               dark:text-slate-300">

                                        Data SHS tidak ditemukan

                                    </div>

                                    <div
                                        class="text-sm
                                               text-slate-500
                                               dark:text-slate-400
                                               mt-1">

                                        Belum ada data yang dapat ditampilkan.

                                    </div>

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
        @if ($shs->hasPages())
            <div
                class="flex
                       flex-col
                       md:flex-row
                       md:items-center
                       md:justify-between
                       gap-4
                       border-t
                       border-slate-200
                       dark:border-slate-800
                       px-5 py-4">

                <div
                    class="text-sm
                           text-slate-500
                           dark:text-slate-400">

                    Menampilkan

                    <span
                        class="font-semibold
                               text-slate-700
                               dark:text-slate-200">

                        {{ $shs->firstItem() }}

                    </span>

                    -

                    <span
                        class="font-semibold
                               text-slate-700
                               dark:text-slate-200">

                        {{ $shs->lastItem() }}

                    </span>

                    dari

                    <span
                        class="font-semibold
                               text-slate-700
                               dark:text-slate-200">

                        {{ $shs->total() }}

                    </span>

                    data

                </div>


                <div>

                    {{ $shs->withQueryString()->links() }}

                </div>

            </div>
        @endif

    </div>



    {{-- =========================================================
        MODAL DETAIL
    ========================================================== --}}
    <div id="modalDetail"
        class="fixed
               inset-0
               z-50
               hidden
               items-center
               justify-center
               bg-black/60
               backdrop-blur-sm
               p-4">

        <div
            class="w-full
                   max-w-6xl
                   max-h-[92vh]
                   overflow-hidden
                   rounded-3xl
                   bg-white
                   dark:bg-slate-900
                   border
                   border-slate-200
                   dark:border-slate-700
                   shadow-2xl
                   flex
                   flex-col">

            {{-- HEADER --}}
            <div
                class="flex
                       items-center
                       justify-between
                       border-b
                       border-slate-200
                       dark:border-slate-700
                       px-6 py-5">

                <div>

                    <h3
                        class="text-2xl
                               font-bold
                               text-slate-800
                               dark:text-white">

                        Detail Usulan SHS

                    </h3>

                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400">

                        Detail usulan yang dikirim operator.

                    </p>

                </div>


                <button type="button" onclick="closeDetail()"
                    class="w-10
                           h-10
                           rounded-xl
                           hover:bg-slate-100
                           dark:hover:bg-slate-800
                           transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            {{-- BODY --}}
            <div class="flex-1
                       overflow-y-auto
                       p-6">

                {{-- =================================================
                    DATA UTAMA
                ================================================== --}}
                <div
                    class="grid
                           grid-cols-1
                           lg:grid-cols-2
                           gap-6">

                    {{-- TAHUN --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Tahun

                        </label>

                        <input id="d_tahun"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- UNIT --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Unit

                        </label>

                        <input id="d_unit"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- KELOMPOK BARANG --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Kelompok Barang

                        </label>

                        <input id="d_kelompok"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- KODE KELOMPOK --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Kode Kelompok

                        </label>

                        <input id="d_kode"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- BARANG --}}
                    <div class="lg:col-span-2">

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Nama Barang

                        </label>

                        <input id="d_barang"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- SATUAN --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Satuan

                        </label>

                        <input id="d_satuan"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- KELOMPOK SHS --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Kelompok SHS

                        </label>

                        <input id="d_tipe"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- HARGA USULAN --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Harga Usulan

                        </label>

                        <input id="d_harga"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3
                                   font-semibold"
                            readonly>

                    </div>


                    {{-- TKDN --}}
                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            TKDN

                        </label>

                        <input id="d_tkdn"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    {{-- SPESIFIKASI --}}
                    <div class="lg:col-span-2">

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Spesifikasi

                        </label>

                        <textarea id="d_spesifikasi" rows="6"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-50
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly></textarea>

                    </div>

                </div>


                {{-- =================================================
                    REFERENSI HARGA
                ================================================== --}}
                <div
                    class="mt-8
                           border-t
                           border-slate-200
                           dark:border-slate-700
                           pt-6">

                    <div class="mb-4">

                        <h4
                            class="text-lg
                                   font-semibold
                                   text-slate-800
                                   dark:text-white">

                            Referensi Harga

                        </h4>

                        <p
                            class="text-sm
                                   text-slate-500
                                   dark:text-slate-400">

                            Harga pembanding dan sumber/link referensi.

                        </p>

                    </div>


                    {{-- CONTAINER REFERENSI --}}
                    <div id="d_referensi" class="w-full">

                        {{-- Diisi JavaScript --}}

                    </div>

                </div>


                {{-- =================================================
                    LINK SURVEI LAMA
                ================================================== --}}
                <div
                    class="mt-8
                           border-t
                           border-slate-200
                           dark:border-slate-700
                           pt-6">

                    <h4
                        class="text-lg
                               font-semibold
                               text-slate-800
                               dark:text-white
                               mb-2">

                        Link Survei Lama

                    </h4>

                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400
                               mb-4">

                        Data link survei lama yang tersimpan pada data utama.

                    </p>

                    <textarea id="d_link" rows="5"
                        class="w-full
                               rounded-xl
                               border
                               border-slate-300
                               dark:border-slate-700
                               bg-slate-50
                               dark:bg-slate-800
                               px-4 py-3
                               text-sm"
                        readonly></textarea>

                </div>


                {{-- =================================================
                    DATA OPERATOR
                ================================================== --}}
                <div
                    class="mt-8
                           border-t
                           border-slate-200
                           dark:border-slate-700
                           pt-6">

                    <h4
                        class="text-lg
                               font-semibold
                               text-slate-800
                               dark:text-white
                               mb-4">

                        Data Operator

                    </h4>


                    <div
                        class="grid
                               grid-cols-1
                               lg:grid-cols-2
                               gap-5">

                        <div>

                            <label
                                class="block
                                       mb-2
                                       text-sm
                                       font-medium">

                                Nama Operator

                            </label>

                            <input id="d_operator"
                                class="w-full
                                       rounded-xl
                                       border
                                       border-slate-300
                                       dark:border-slate-700
                                       bg-slate-50
                                       dark:bg-slate-800
                                       px-4 py-3"
                                readonly>

                        </div>


                        <div>

                            <label
                                class="block
                                       mb-2
                                       text-sm
                                       font-medium">

                                NIP Operator

                            </label>

                            <input id="d_nip"
                                class="w-full
                                       rounded-xl
                                       border
                                       border-slate-300
                                       dark:border-slate-700
                                       bg-slate-50
                                       dark:bg-slate-800
                                       px-4 py-3"
                                readonly>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        MODAL VERIFIKASI
    ========================================================== --}}
    <div id="modalVerifikasi"
        class="fixed
               inset-0
               z-50
               hidden
               items-center
               justify-center
               bg-black/60
               backdrop-blur-sm
               p-4">

        <div
            class="w-full
                   max-w-2xl
                   rounded-3xl
                   bg-white
                   dark:bg-slate-900
                   border
                   border-slate-200
                   dark:border-slate-700
                   shadow-2xl">

            <div
                class="flex
                       items-center
                       justify-between
                       border-b
                       border-slate-200
                       dark:border-slate-700
                       px-6 py-5">

                <div>

                    <h3
                        class="text-xl
                               font-bold
                               text-slate-800
                               dark:text-white">

                        Verifikasi SHS

                    </h3>

                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400">

                        Berikan keputusan terhadap usulan SHS.

                    </p>

                </div>


                <button type="button" onclick="closeVerifikasi()"
                    class="w-10
                           h-10
                           rounded-xl
                           hover:bg-slate-100
                           dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <form id="formVerifikasi" method="POST">

                @csrf

                <div class="p-6 space-y-5">

                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Barang

                        </label>

                        <input id="v_barang"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-100
                                   dark:bg-slate-800
                                   px-4 py-3"
                            readonly>

                    </div>


                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Harga

                        </label>

                        <input id="v_harga"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-slate-100
                                   dark:bg-slate-800
                                   px-4 py-3
                                   font-semibold"
                            readonly>

                    </div>


                    <div>

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-medium">

                            Catatan Admin

                        </label>

                        <textarea name="shs_catatan_admin" rows="5"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-white
                                   dark:bg-slate-800
                                   px-4 py-3"
                            placeholder="Masukkan catatan verifikasi jika diperlukan..."></textarea>

                    </div>

                </div>


                <div
                    class="flex
                           justify-end
                           gap-3
                           border-t
                           border-slate-200
                           dark:border-slate-700
                           px-6 py-5">

                    <button type="button" onclick="closeVerifikasi()"
                        class="rounded-xl
                               border
                               border-slate-300
                               dark:border-slate-700
                               px-5 py-2.5
                               hover:bg-slate-100
                               dark:hover:bg-slate-800">

                        Batal

                    </button>


                    <button type="submit"
                        class="rounded-xl
                               bg-blue-600
                               hover:bg-blue-700
                               px-5 py-2.5
                               font-semibold
                               text-white">

                        <i class="bi bi-check-circle me-2"></i>

                        Verifikasi

                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
        MODAL EXPORT
    ========================================================== --}}
    <div id="modalExport"
        class="fixed
               inset-0
               z-50
               hidden
               items-center
               justify-center
               bg-black/60
               backdrop-blur-sm
               p-4">

        <div
            class="w-full
                   max-w-4xl
                   max-h-[90vh]
                   overflow-y-auto
                   rounded-3xl
                   bg-white
                   dark:bg-slate-900
                   border
                   border-slate-200
                   dark:border-slate-700
                   shadow-2xl">

            <div
                class="flex
                       items-center
                       justify-between
                       border-b
                       border-slate-200
                       dark:border-slate-700
                       px-6 py-5">

                <div>

                    <h3
                        class="text-xl
                               font-bold
                               text-slate-800
                               dark:text-white">

                        Export Usulan SHS

                    </h3>

                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400">

                        Pilih data yang ingin diexport ke Excel.

                    </p>

                </div>


                <button type="button" onclick="closeExportModal()"
                    class="w-10
                           h-10
                           rounded-xl
                           hover:bg-slate-100
                           dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <form action="{{ route('admin.laporan-shs.export') }}" method="GET">

                <div class="p-6">

                    {{-- FILTER STATUS --}}
                    <div class="mb-6">

                        <label
                            class="block
                                   mb-2
                                   text-sm
                                   font-semibold">

                            Filter Status

                        </label>

                        <select name="status"
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-300
                                   dark:border-slate-700
                                   bg-white
                                   dark:bg-slate-800
                                   px-4 py-3">

                            <option value="">
                                Semua Data
                            </option>

                            <option value="Diajukan">
                                Diajukan
                            </option>

                            <option value="Diverifikasi">
                                Diverifikasi
                            </option>

                            <option value="Tidak Diajukan">
                                Tidak Diajukan
                            </option>

                        </select>

                    </div>


                    {{-- BUTTON CHECK --}}
                    <div
                        class="flex
                               flex-wrap
                               gap-3
                               mb-6">

                        <button type="button" id="checkAll"
                            class="rounded-xl
                                   bg-blue-600
                                   hover:bg-blue-700
                                   px-4 py-2
                                   text-white">

                            <i class="bi bi-check2-square me-2"></i>

                            Centang Semua

                        </button>


                        <button type="button" id="uncheckAll"
                            class="rounded-xl
                                   bg-slate-600
                                   hover:bg-slate-700
                                   px-4 py-2
                                   text-white">

                            <i class="bi bi-square me-2"></i>

                            Hapus Semua

                        </button>

                    </div>


                    @php

                        $fields = [
                            ['shs_tahun', 'Tahun', true],

                            ['shs_unit_nama', 'Unit', true],

                            ['shs_kode_kelompok', 'Kode Kelompok', true],

                            ['shs_kelompok_barang', 'Kelompok Barang', true],

                            ['shs_barang', 'Nama Barang', true],

                            ['shs_merek', 'Merek', false],

                            ['shs_tipe', 'Tipe / Model', false],

                            ['shs_spesifikasi', 'Spesifikasi', false],

                            ['shs_satuan', 'Satuan', false],

                            ['shs_harga', 'Harga', false],

                            ['shs_tkdn', 'TKDN', false],

                            ['shs_link_survei', 'Link Survei Lama', false],

                            ['shs_kelompok', 'Kelompok SHS', false],

                            ['shs_dasar_usulan', 'Dasar Usulan', false],

                            ['shs_keterangan', 'Keterangan', false],

                            ['shs_status', 'Status', false],

                            ['shs_operator_nama', 'Operator', false],

                            ['created_at', 'Tanggal Input', false],
                        ];

                    @endphp


                    <div
                        class="grid
                               grid-cols-1
                               md:grid-cols-2
                               lg:grid-cols-3
                               gap-3">

                        @foreach ($fields as $field)
                            <label
                                class="flex
                                       items-center
                                       gap-3
                                       rounded-xl
                                       border
                                       border-slate-200
                                       dark:border-slate-700
                                       p-3
                                       hover:border-blue-500
                                       cursor-pointer">

                                <input type="checkbox" class="field rounded" name="field[]" value="{{ $field[0] }}"
                                    {{ $field[2] ? 'checked' : '' }}>

                                <span>
                                    {{ $field[1] }}
                                </span>

                            </label>
                        @endforeach

                    </div>

                </div>


                <div
                    class="flex
                           justify-end
                           gap-3
                           border-t
                           border-slate-200
                           dark:border-slate-700
                           px-6 py-5">

                    <button type="button" onclick="closeExportModal()"
                        class="rounded-xl
                               border
                               border-slate-300
                               dark:border-slate-700
                               px-5 py-2.5">

                        Batal

                    </button>


                    <button type="submit"
                        class="rounded-xl
                               bg-emerald-600
                               hover:bg-emerald-700
                               px-5 py-2.5
                               text-white
                               font-semibold">

                        <i class="bi bi-file-earmark-excel me-2"></i>

                        Export Excel

                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
        MODAL HISTORY
    ========================================================== --}}
    <div id="modalHistory"
        class="fixed
               inset-0
               z-50
               hidden
               items-center
               justify-center
               bg-black/60
               backdrop-blur-sm
               p-4">

        <div
            class="w-full
                   max-w-5xl
                   max-h-[90vh]
                   overflow-hidden
                   rounded-3xl
                   bg-white
                   dark:bg-slate-900
                   border
                   border-slate-200
                   dark:border-slate-700
                   shadow-2xl
                   flex
                   flex-col">

            <div
                class="flex
                       items-center
                       justify-between
                       border-b
                       border-slate-200
                       dark:border-slate-700
                       px-6 py-5">

                <div>

                    <h3
                        class="text-xl
                               font-bold
                               text-slate-800
                               dark:text-white">

                        Riwayat Verifikasi SHS

                    </h3>

                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400">

                        Riwayat proses verifikasi usulan SHS.

                    </p>

                </div>


                <button type="button" onclick="closeHistory()"
                    class="w-10
                           h-10
                           rounded-xl
                           hover:bg-slate-100
                           dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <div class="flex-1
                       overflow-y-auto
                       p-6">

                <div id="historyContent" class="space-y-4">
                </div>

            </div>

        </div>

    </div>

@endsection



{{-- =============================================================
    JAVASCRIPT
============================================================== --}}
@push('scripts')
    <script>
        /* =========================================================
           MODAL
        ========================================================== */

        function showModal(id) {

            const modal = document.getElementById(id);

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }


        function hideModal(id) {

            const modal = document.getElementById(id);

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }



        /* =========================================================
           FORMAT RUPIAH
        ========================================================== */

        function formatRupiah(value) {

            if (
                value === null ||
                value === undefined ||
                value === ''
            ) {
                return 'Rp 0';
            }

            let number;

            if (typeof value === 'number') {

                number = value;

            } else {

                let text = String(value)
                    .replace(/Rp/gi, '')
                    .trim();

                /*
                 * Format Indonesia:
                 * 1.000.000
                 * 1.000.000,50
                 */
                if (
                    /^\d{1,3}(\.\d{3})+(,\d+)?$/.test(text)
                ) {

                    text = text
                        .replace(/\./g, '')
                        .replace(',', '.');

                } else {

                    text = text.replace(/,/g, '');
                }

                number = Number(text);
            }

            if (Number.isNaN(number)) {
                return 'Rp 0';
            }

            return 'Rp ' +
                Math.round(number).toLocaleString('id-ID');
        }



        /* =========================================================
           ESCAPE HTML
        ========================================================== */

        function escapeHtml(value) {

            if (
                value === null ||
                value === undefined
            ) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }



        /* =========================================================
           DETAIL SHS
        ========================================================== */

        function detailSHS(item) {

            if (!item) {
                return;
            }


            /* =====================================================
               DATA UTAMA
            ====================================================== */

            const tahun =
                document.getElementById('d_tahun');

            const unit =
                document.getElementById('d_unit');

            const kelompok =
                document.getElementById('d_kelompok');

            const kode =
                document.getElementById('d_kode');

            const barang =
                document.getElementById('d_barang');

            const satuan =
                document.getElementById('d_satuan');

            const tipe =
                document.getElementById('d_tipe');

            const harga =
                document.getElementById('d_harga');

            const tkdn =
                document.getElementById('d_tkdn');

            const spesifikasi =
                document.getElementById('d_spesifikasi');

            const linkLama =
                document.getElementById('d_link');

            const operator =
                document.getElementById('d_operator');

            const nip =
                document.getElementById('d_nip');


            if (tahun) {
                tahun.value = item.shs_tahun ?? '';
            }

            if (unit) {
                unit.value = item.shs_unit_nama ?? '';
            }

            if (kelompok) {
                kelompok.value =
                    item.shs_kelompok_barang ?? '';
            }

            if (kode) {
                kode.value =
                    item.shs_kode_kelompok ?? '';
            }

            if (barang) {
                barang.value =
                    item.shs_barang ?? '';
            }

            if (satuan) {
                satuan.value =
                    item.shs_satuan ?? '';
            }

            if (tipe) {
                tipe.value =
                    item.shs_kelompok ?? '';
            }

            if (harga) {
                harga.value =
                    formatRupiah(item.shs_harga);
            }

            if (tkdn) {

                tkdn.value =
                    item.shs_tkdn !== null &&
                    item.shs_tkdn !== undefined &&
                    item.shs_tkdn !== ''
                        ? item.shs_tkdn + ' %'
                        : '-';
            }

            if (spesifikasi) {
                spesifikasi.value =
                    item.shs_spesifikasi ?? '';
            }

            if (linkLama) {
                linkLama.value =
                    item.shs_link_survei ?? '';
            }

            if (operator) {
                operator.value =
                    item.shs_operator_nama ?? '';
            }

            if (nip) {
                nip.value =
                    item.shs_operator_nip ?? '';
            }



            /* =====================================================
               REFERENSI HARGA
            ====================================================== */

            const container =
                document.getElementById('d_referensi');

            if (!container) {
                return;
            }


            /*
             * Relasi Laravel:
             *
             * referensiHarga
             *
             * Jika JSON Laravel menggunakan nama relasi:
             * referensiHarga
             *
             * maka data akan berada di:
             *
             * item.referensiHarga
             *
             * Fallback referensi_harga tetap disediakan.
             */

            const referensi =
                item.referensiHarga ??
                item.referensi_harga ??
                [];


            let html = '';


            /* =====================================================
               ADA REFERENSI
            ====================================================== */

            if (
                Array.isArray(referensi) &&
                referensi.length > 0
            ) {

                html = `
                    <div
                        class="overflow-x-auto
                               rounded-2xl
                               border
                               border-slate-200
                               dark:border-slate-700">

                        <div class="min-w-[850px]">

                            <!-- HEADER -->

                            <div
                                class="grid
                                       grid-cols-12
                                       gap-4
                                       items-center
                                       bg-slate-100
                                       dark:bg-slate-800
                                       px-5
                                       py-3
                                       text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wide
                                       text-slate-500
                                       dark:text-slate-400">

                                <div class="col-span-1">
                                    No
                                </div>

                                <div class="col-span-4">
                                    Referensi
                                </div>

                                <div class="col-span-3">
                                    Harga
                                </div>

                                <div class="col-span-4">
                                    Link
                                </div>

                            </div>
                `;


                /* =================================================
                   LOOP REFERENSI
                ================================================== */

                referensi.forEach(function(ref, index) {

                    /*
                     * Nama field dari model:
                     *
                     * shs_referensi_harga
                     * shs_referensi_link
                     *
                     * Fallback disediakan jika JSON menggunakan
                     * nama sederhana.
                     */

                    const hargaReferensi =
                        ref.shs_referensi_harga ??
                        ref.harga ??
                        0;


                    const linkReferensi =
                        ref.shs_referensi_link ??
                        ref.link ??
                        '';


                    const safeLink =
                        escapeHtml(linkReferensi);


                    html += `
                        <div
                            class="grid
                                   grid-cols-12
                                   gap-4
                                   items-center
                                   px-5
                                   py-4
                                   border-t
                                   border-slate-200
                                   dark:border-slate-700
                                   bg-white
                                   dark:bg-slate-900">

                            <!-- NO -->

                            <div class="col-span-1">

                                <span
                                    class="inline-flex
                                           items-center
                                           justify-center
                                           w-8
                                           h-8
                                           rounded-lg
                                           bg-slate-100
                                           dark:bg-slate-800
                                           text-sm
                                           font-semibold
                                           text-slate-700
                                           dark:text-slate-200">

                                    ${index + 1}

                                </span>

                            </div>


                            <!-- REFERENSI -->

                            <div
                                class="col-span-4
                                       min-w-0">

                                <div
                                    class="font-semibold
                                           text-slate-800
                                           dark:text-white">

                                    Referensi Harga ${index + 1}

                                </div>

                            </div>


                            <!-- HARGA -->

                            <div
                                class="col-span-3">

                                <div
                                    class="font-bold
                                           whitespace-nowrap
                                           text-slate-800
                                           dark:text-white">

                                    ${formatRupiah(hargaReferensi)}

                                </div>

                            </div>


                            <!-- LINK -->

                            <div
                                class="col-span-4
                                       min-w-0">

                                ${
                                    linkReferensi
                                        ? `
                                            <div
                                                class="flex
                                                       items-center
                                                       gap-3
                                                       min-w-0">

                                                <a
                                                    href="${safeLink}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex
                                                           shrink-0
                                                           items-center
                                                           justify-center
                                                           gap-2
                                                           rounded-xl
                                                           bg-blue-600
                                                           hover:bg-blue-700
                                                           px-4
                                                           py-2
                                                           text-sm
                                                           font-semibold
                                                           text-white
                                                           transition">

                                                    <i
                                                        class="bi
                                                               bi-box-arrow-up-right">
                                                    </i>

                                                    Buka Link

                                                </a>


                                                <div
                                                    class="min-w-0
                                                           flex-1
                                                           truncate
                                                           text-xs
                                                           text-slate-500
                                                           dark:text-slate-400"
                                                    title="${safeLink}">

                                                    ${safeLink}

                                                </div>

                                            </div>
                                        `
                                        : `
                                            <span
                                                class="text-sm
                                                       text-slate-400
                                                       dark:text-slate-500">

                                                Tidak ada link

                                            </span>
                                        `
                                }

                            </div>

                        </div>
                    `;
                });


                html += `
                        </div>

                    </div>
                `;


            } else {


                /* =================================================
                   TIDAK ADA REFERENSI
                ================================================== */

                html = `
                    <div
                        class="rounded-2xl
                               border
                               border-dashed
                               border-slate-300
                               dark:border-slate-700
                               p-8
                               text-center">

                        <div
                            class="flex
                                   justify-center
                                   mb-3">

                            <i
                                class="bi
                                       bi-link-45deg
                                       text-3xl
                                       text-slate-400">
                            </i>

                        </div>

                        <div
                            class="text-sm
                                   text-slate-500
                                   dark:text-slate-400">

                            Belum ada referensi harga.

                        </div>

                    </div>
                `;
            }


            container.innerHTML = html;


            /* =====================================================
               TAMPILKAN MODAL
            ====================================================== */

            showModal('modalDetail');
        }



        /* =========================================================
           CLOSE DETAIL
        ========================================================== */

        function closeDetail() {

            hideModal('modalDetail');

        }



        /* =========================================================
           VERIFIKASI
        ========================================================== */

        function verifikasiSHS(item) {

            if (!item) {
                return;
            }


            const barang =
                document.getElementById('v_barang');

            const harga =
                document.getElementById('v_harga');

            const form =
                document.getElementById('formVerifikasi');


            if (barang) {

                barang.value =
                    item.shs_barang ?? '';
            }


            if (harga) {

                harga.value =
                    formatRupiah(item.shs_harga);
            }


            if (form) {

                form.action =
                    '{{ url('/administrator/laporan/shs/verifikasi') }}/' +
                    item.shs_uid;
            }


            showModal('modalVerifikasi');
        }



        /* =========================================================
           CLOSE VERIFIKASI
        ========================================================== */

        function closeVerifikasi() {

            const form =
                document.getElementById('formVerifikasi');


            if (form) {
                form.reset();
            }


            hideModal('modalVerifikasi');
        }



        /* =========================================================
           EXPORT
        ========================================================== */

        function openExportModal() {

            showModal('modalExport');

        }


        function closeExportModal() {

            hideModal('modalExport');

        }



        /* =========================================================
           HISTORY
        ========================================================== */

        function historySHS(data) {

            let html = '';


            if (
                data &&
                data.history &&
                data.history.length
            ) {

                data.history.forEach(function(item) {

                    html += `
                        <div
                            class="rounded-2xl
                                   border
                                   border-slate-200
                                   dark:border-slate-700
                                   p-5">

                            <div
                                class="flex
                                       flex-col
                                       md:flex-row
                                       md:items-center
                                       md:justify-between
                                       gap-2
                                       mb-3">

                                <div
                                    class="font-semibold
                                           text-slate-800
                                           dark:text-white">

                                    ${escapeHtml(
                                        item.user ?? '-'
                                    )}

                                </div>


                                <span
                                    class="text-xs
                                           text-slate-500
                                           dark:text-slate-400">

                                    ${escapeHtml(
                                        item.tanggal ?? '-'
                                    )}

                                </span>

                            </div>


                            <div class="mb-2">

                                <span
                                    class="inline-flex
                                           rounded-full
                                           bg-blue-100
                                           text-blue-700
                                           dark:bg-blue-900/20
                                           dark:text-blue-300
                                           px-3
                                           py-1
                                           text-xs">

                                    ${escapeHtml(
                                        item.status ?? '-'
                                    )}

                                </span>

                            </div>


                            <div
                                class="text-sm
                                       text-slate-600
                                       dark:text-slate-300">

                                ${escapeHtml(
                                    item.catatan ?? '-'
                                )}

                            </div>

                        </div>
                    `;
                });


            } else {

                html = `
                    <div
                        class="text-center
                               py-16
                               text-slate-500
                               dark:text-slate-400">

                        Belum ada riwayat verifikasi.

                    </div>
                `;
            }


            const historyContent =
                document.getElementById('historyContent');


            if (historyContent) {

                historyContent.innerHTML =
                    html;
            }


            showModal('modalHistory');
        }



        /* =========================================================
           CLOSE HISTORY
        ========================================================== */

        function closeHistory() {

            hideModal('modalHistory');

        }



        /* =========================================================
           DOM READY
        ========================================================== */

        document.addEventListener(
            'DOMContentLoaded',
            function() {

                /* =================================================
                   CHECK ALL
                ================================================== */

                const checkAll =
                    document.getElementById('checkAll');


                if (checkAll) {

                    checkAll.addEventListener(
                        'click',
                        function() {

                            document
                                .querySelectorAll('.field')
                                .forEach(function(el) {

                                    el.checked = true;

                                });
                        }
                    );
                }



                /* =================================================
                   UNCHECK ALL
                ================================================== */

                const uncheckAll =
                    document.getElementById('uncheckAll');


                if (uncheckAll) {

                    uncheckAll.addEventListener(
                        'click',
                        function() {

                            document
                                .querySelectorAll('.field')
                                .forEach(function(el) {

                                    el.checked = false;

                                });
                        }
                    );
                }



                /* =================================================
                   BACKGROUND MODAL
                ================================================== */

                [
                    'modalDetail',
                    'modalVerifikasi',
                    'modalExport',
                    'modalHistory'
                ].forEach(function(id) {

                    const modal =
                        document.getElementById(id);


                    if (!modal) {
                        return;
                    }


                    modal.addEventListener(
                        'click',
                        function(e) {

                            if (e.target === modal) {

                                hideModal(id);

                            }
                        }
                    );
                });

            }
        );



        /* =========================================================
           ESC KEY
        ========================================================== */

        document.addEventListener(
            'keydown',
            function(e) {

                if (e.key === 'Escape') {

                    closeDetail();

                    closeVerifikasi();

                    closeExportModal();

                    closeHistory();
                }

            }
        );
    </script>
@endpush



{{-- =============================================================
    STYLE
============================================================== --}}
@push('styles')
    <style>
        input,
        textarea,
        select {
            transition: .2s;
        }


        input:focus,
        textarea:focus,
        select:focus {

            outline: none;

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgb(37 99 235 / .15);
        }


        textarea {
            resize: vertical;
        }


        ::-webkit-scrollbar {

            width: 8px;
            height: 8px;

        }


        ::-webkit-scrollbar-thumb {

            background: #94a3b8;

            border-radius: 20px;

        }


        .dark ::-webkit-scrollbar-thumb {

            background: #475569;

        }


        ::-webkit-scrollbar-track {

            background: transparent;

        }


        /*
         * Pagination Laravel
         */
        nav[role="navigation"] {

            display: flex;

            flex-wrap: wrap;

            gap: 4px;

        }


        nav[role="navigation"] a,
        nav[role="navigation"] span {

            min-width: 38px;

            min-height: 38px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

        }
    </style>
@endpush
