@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan KAK')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- ALERT SUCCESS --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div
                class="rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 p-4 text-green-700 dark:text-green-300">

                {{ session('success') }}

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- ALERT ERROR --}}
        {{-- ========================================================= --}}

        @if (session('error'))
            <div
                class="rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 p-4 text-red-700 dark:text-red-300">

                {{ session('error') }}

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- VALIDATION ERROR --}}
        {{-- ========================================================= --}}

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 p-4">

                <ul class="list-disc ml-5 text-red-700 dark:text-red-300 space-y-1">

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

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            <div>

                <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

                    Permintaan KAK

                </h1>

                <p class="text-slate-500 dark:text-slate-400 mt-2">

                    Monitoring seluruh dokumen KAK yang diunggah oleh operator.

                </p>

            </div>


            {{-- TOTAL DATA --}}

            <div
                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 text-white px-5 py-3 shadow-lg shadow-blue-600/20">

                <i class="bi bi-file-earmark-text text-lg"></i>

                <span class="font-semibold">

                    {{ $kaks->count() }} Permintaan

                </span>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TABLE CARD --}}
        {{-- ========================================================= --}}

        <div
            class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">


            {{-- ===================================================== --}}
            {{-- SEARCH --}}
            {{-- ===================================================== --}}

            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <form method="GET">

                        <div class="relative w-full lg:w-96">

                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            </i>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari Program, Kegiatan, Sub Kegiatan, Pemohon..."
                                class="w-full rounded-2xl
                                       border border-slate-300
                                       dark:border-slate-700
                                       bg-white
                                       dark:bg-slate-900
                                       pl-11 pr-4 py-3
                                       focus:ring-2
                                       focus:ring-blue-500">

                        </div>

                    </form>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead
                        class="sticky top-0
                               bg-slate-50
                               dark:bg-slate-800
                               border-b
                               border-slate-200
                               dark:border-slate-700">

                        <tr>

                            {{-- NO --}}

                            <th class="px-5 py-4 w-16">

                                No

                            </th>


                            {{-- TAHUN --}}

                            <th class="px-5 py-4">

                                Tahun

                            </th>


                            {{-- PROGRAM / KEGIATAN --}}

                            <th class="px-5 py-4">

                                Program / Kegiatan

                            </th>


                            {{-- SUB KEGIATAN --}}

                            <th class="px-5 py-4">

                                Sub Kegiatan

                            </th>


                            {{-- TAHAPAN --}}

                            <th class="px-5 py-4">

                                Tahapan

                            </th>


                            {{-- PEMOHON --}}

                            <th class="px-5 py-4">

                                Pemohon

                            </th>


                            {{-- TANGGAL --}}

                            <th class="px-5 py-4">

                                Tanggal

                            </th>


                            {{-- FILE --}}

                            <th class="px-5 py-4 text-center">

                                File

                            </th>


                            {{-- STATUS --}}

                            <th class="px-5 py-4 text-center">

                                Status

                            </th>


                            {{-- AKSI --}}

                            <th class="px-5 py-4 text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">


                        @forelse($kaks as $item)
                            <tr
                                class="hover:bg-slate-50
                                       dark:hover:bg-slate-800
                                       transition">


                                {{-- ================================================= --}}
                                {{-- NO --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- TAHUN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5">

                                    <span
                                        class="inline-flex
                                               rounded-xl
                                               bg-slate-100
                                               dark:bg-slate-800
                                               px-3 py-1
                                               text-xs
                                               font-bold">

                                        {{ $item->kak_tahun }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- PROGRAM / KEGIATAN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5">

                                    <div class="min-w-[260px]">

                                        {{-- PROGRAM --}}

                                        <div class="font-semibold">

                                            {{ $item->program_nama ?? '-' }}

                                        </div>


                                        @if (!empty($item->program_kode))
                                            <div class="text-xs text-slate-500 mt-1">

                                                {{ $item->program_kode }}

                                            </div>
                                        @endif


                                        {{-- KEGIATAN --}}

                                        <div class="text-sm text-slate-600 dark:text-slate-300 mt-2">

                                            {{ $item->kegiatan_nama ?? '-' }}

                                        </div>


                                        @if (!empty($item->kegiatan_kode))
                                            <div class="text-xs text-slate-500 mt-1">

                                                {{ $item->kegiatan_kode }}

                                            </div>
                                        @endif

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- SUB KEGIATAN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5">

                                    <div class="min-w-[260px]">

                                        <div class="font-semibold">

                                            {{ $item->sub_kegiatan_nama ?? '-' }}

                                        </div>


                                        <div class="text-xs text-slate-500 mt-1">

                                            {{ $item->sub_kegiatan_kode ?? '-' }}

                                        </div>

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- TAHAPAN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5">

                                    <span
                                        class="inline-flex
                                               items-center
                                               gap-2
                                               rounded-xl
                                               bg-blue-100
                                               dark:bg-blue-900/20
                                               text-blue-700
                                               dark:text-blue-300
                                               px-3 py-2
                                               text-xs
                                               font-semibold">

                                        <i class="bi bi-layers"></i>

                                        {{ $item->kak_tahapan }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- PEMOHON --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5">

                                    <div class="min-w-[180px]">

                                        <div class="font-semibold">

                                            {{ $item->kak_created_by_nama ?? '-' }}

                                        </div>


                                        @if ($item->kak_created_by)
                                            <div class="text-xs text-slate-500 mt-1">

                                                ID:
                                                {{ $item->kak_created_by }}

                                            </div>
                                        @endif

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- TANGGAL --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5 whitespace-nowrap">

                                    <div class="font-medium">

                                        {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}

                                    </div>


                                    <div class="text-xs text-slate-500 mt-1">

                                        Upload

                                        {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- FILE --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5 text-center">

                                    @if ($item->kak_file)
                                        <a href="{{ filter_var($item->kak_file, FILTER_VALIDATE_URL) ? $item->kak_file : asset($item->kak_file) }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="inline-flex
                                                   items-center
                                                   gap-2
                                                   rounded-xl
                                                   bg-blue-100
                                                   dark:bg-blue-900/20
                                                   text-blue-700
                                                   dark:text-blue-300
                                                   px-3 py-2
                                                   text-xs
                                                   font-semibold
                                                   transition
                                                   hover:bg-blue-200
                                                   dark:hover:bg-blue-900/40">

                                            <i class="bi bi-file-earmark-pdf"></i>

                                            Lihat

                                        </a>
                                    @else
                                        <span class="text-slate-400">

                                            -

                                        </span>
                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5 text-center">

                                    @if ((int) $item->kak_status === 1)
                                        <span
                                            class="inline-flex
                                                   items-center
                                                   gap-2
                                                   rounded-full
                                                   bg-green-100
                                                   dark:bg-green-900/20
                                                   text-green-700
                                                   dark:text-green-300
                                                   px-3 py-1
                                                   text-xs
                                                   font-semibold">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex
                                                   items-center
                                                   gap-2
                                                   rounded-full
                                                   bg-red-100
                                                   dark:bg-red-900/20
                                                   text-red-700
                                                   dark:text-red-300
                                                   px-3 py-1
                                                   text-xs
                                                   font-semibold">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- AKSI --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-5 text-center">

                                    <div class="flex justify-center">

                                        <a href="{{ filter_var($item->kak_file, FILTER_VALIDATE_URL) ? $item->kak_file : asset($item->kak_file) }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="inline-flex
                                                   items-center
                                                   gap-2
                                                   rounded-xl
                                                   bg-blue-600
                                                   hover:bg-blue-700
                                                   px-4 py-2
                                                   text-white
                                                   text-xs
                                                   font-semibold
                                                   transition">

                                            <i class="bi bi-eye"></i>

                                            Lihat KAK

                                        </a>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            {{-- ================================================= --}}
                            {{-- EMPTY --}}
                            {{-- ================================================= --}}

                            <tr>

                                <td colspan="10" class="py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <i
                                            class="bi bi-file-earmark-x
                                                   text-5xl
                                                   text-slate-300
                                                   dark:text-slate-700">
                                        </i>


                                        <div class="mt-4 text-slate-500">

                                            Belum ada data Permintaan KAK.

                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse


                    </tbody>

                </table>

            </div>


            {{-- ========================================================= --}}
            {{-- FOOTER --}}
            {{-- ========================================================= --}}

            @if ($kaks->count() > 0)
                <div
                    class="flex flex-col
                           md:flex-row
                           items-center
                           justify-between
                           gap-4
                           px-6 py-5
                           border-t
                           border-slate-200
                           dark:border-slate-800">

                    <div class="text-sm text-slate-500">

                        Menampilkan

                        <span class="font-semibold">

                            {{ $kaks->count() }}

                        </span>

                        data Permintaan KAK

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection
