@extends('administrator-v2.layouts.app')

@section('title', 'Permintaan KAK')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- ALERT --}}
        {{-- ========================================================= --}}

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
            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4">

                <ul class="list-disc ml-5 space-y-1 text-red-700 dark:text-red-300">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
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
                    Monitoring seluruh KAK yang diajukan operator.
                </p>

            </div>

            <div
                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 text-white px-5 py-3 shadow-lg shadow-blue-600/20">

                <i class="bi bi-file-earmark-text text-lg"></i>

                <span class="font-semibold">
                    {{ $kaks->total() }} Permintaan
                </span>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TABLE CARD --}}
        {{-- ========================================================= --}}

        <div
            class="rounded-3xl bg-white dark:bg-slate-900
            border border-slate-200 dark:border-slate-800
            shadow-sm overflow-hidden">


            {{-- ===================================================== --}}
            {{-- SEARCH --}}
            {{-- ===================================================== --}}

            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <form method="GET" class="w-full lg:w-auto">

                        <div class="relative w-full lg:w-[420px]">

                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            </i>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari program, kegiatan, sub kegiatan, pemohon..."
                                class="w-full rounded-2xl
                                border border-slate-300 dark:border-slate-700
                                bg-white dark:bg-slate-900
                                text-slate-800 dark:text-white
                                pl-11 pr-4 py-3
                                placeholder:text-slate-400
                                focus:outline-none
                                focus:ring-2 focus:ring-blue-500">

                        </div>

                    </form>


                    <div class="text-sm text-slate-500 dark:text-slate-400">

                        <i class="bi bi-info-circle mr-1"></i>

                        Menampilkan seluruh KAK yang masuk

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead
                        class="bg-slate-50 dark:bg-slate-800
                        border-b border-slate-200 dark:border-slate-700">

                        <tr>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                No
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Tahun
                            </th>

                            <th class="px-5 py-4 text-left min-w-[300px]">
                                Program / Kegiatan
                            </th>

                            <th class="px-5 py-4 text-left min-w-[250px]">
                                Sub Kegiatan
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Tahapan
                            </th>

                            <th class="px-5 py-4 text-left min-w-[180px]">
                                Pemohon
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-center whitespace-nowrap">
                                File
                            </th>

                            <th class="px-5 py-4 text-center whitespace-nowrap">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center whitespace-nowrap">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">

                        @forelse ($kaks as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition">


                                {{-- ================================================= --}}
                                {{-- NO --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6 whitespace-nowrap">

                                    <span class="font-medium text-slate-500 dark:text-slate-400">

                                        {{ $kaks->firstItem() + $loop->index }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- TAHUN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6 whitespace-nowrap">

                                    <span
                                        class="inline-flex items-center
                                        rounded-full
                                        bg-slate-100 dark:bg-slate-800
                                        border border-slate-200 dark:border-slate-700
                                        px-3 py-1
                                        text-xs font-bold
                                        text-slate-700 dark:text-slate-200">

                                        {{ $item->kak_tahun }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- PROGRAM / KEGIATAN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6">

                                    <div class="space-y-3">

                                        <div>

                                            <div
                                                class="text-[10px] font-bold uppercase tracking-wider
                                                text-blue-600 dark:text-blue-400 mb-1">

                                                Program

                                            </div>

                                            <div
                                                class="font-semibold leading-relaxed
                                                text-slate-800 dark:text-white">

                                                {{ $item->program_nama ?? '-' }}

                                            </div>

                                            <div class="text-xs text-slate-400 mt-1">

                                                {{ $item->program_kode ?? '-' }}

                                            </div>

                                        </div>


                                        <div
                                            class="border-t border-slate-100
                                            dark:border-slate-800 pt-3">

                                            <div
                                                class="text-[10px] font-bold uppercase tracking-wider
                                                text-slate-400 mb-1">

                                                Kegiatan

                                            </div>

                                            <div
                                                class="font-medium
                                                text-slate-700 dark:text-slate-200
                                                leading-relaxed">

                                                {{ $item->kegiatan_nama ?? '-' }}

                                            </div>

                                            <div class="text-xs text-slate-400 mt-1">

                                                {{ $item->kegiatan_kode ?? '-' }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- SUB KEGIATAN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6">

                                    <div
                                        class="font-semibold
                                        text-slate-800 dark:text-white
                                        leading-relaxed">

                                        {{ $item->sub_kegiatan_nama ?? '-' }}

                                    </div>

                                    <div class="mt-2">

                                        <span
                                            class="inline-flex
                                            rounded-lg
                                            bg-slate-100 dark:bg-slate-800
                                            px-2.5 py-1
                                            text-xs font-medium
                                            text-slate-500 dark:text-slate-400">

                                            {{ $item->sub_kegiatan_kode ?? '-' }}

                                        </span>

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- TAHAPAN --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6 whitespace-nowrap">

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
                                        class="inline-flex items-center gap-2
                                        rounded-xl
                                        px-3 py-2
                                        text-xs font-semibold
                                        {{ $tahapanClass }}">

                                        <i class="bi bi-layers"></i>

                                        {{ $item->kak_tahapan ?? '-' }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- PEMOHON --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6">

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0
                                            items-center justify-center
                                            rounded-xl
                                            bg-blue-100 dark:bg-blue-900/30
                                            text-blue-600 dark:text-blue-400">

                                            <i class="bi bi-person-fill"></i>

                                        </div>

                                        <div>

                                            <div
                                                class="font-semibold
                                                text-slate-800 dark:text-white
                                                leading-relaxed">

                                                {{ $item->kak_created_by_nama ?? '-' }}

                                            </div>

                                            <div class="text-xs text-slate-400 mt-1">

                                                ID: {{ $item->kak_created_by ?? '-' }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- TANGGAL --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6 whitespace-nowrap">

                                    <div
                                        class="font-medium
                                        text-slate-800 dark:text-white">

                                        {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}

                                    </div>

                                    <div class="text-xs text-slate-400 mt-1">

                                        Upload
                                        {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- FILE --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6 text-center">

                                    @if ($item->kak_file)
                                        <a href="{{ filter_var($item->kak_file, FILTER_VALIDATE_URL) ? $item->kak_file : asset($item->kak_file) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2
                                            rounded-xl
                                            bg-blue-100 dark:bg-blue-900/20
                                            text-blue-700 dark:text-blue-300
                                            px-4 py-2
                                            text-xs font-semibold
                                            hover:bg-blue-200 dark:hover:bg-blue-900/40
                                            transition">

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

                                <td class="px-5 py-6 text-center whitespace-nowrap">

                                    @if ((int) $item->kak_status === 1)
                                        <span
                                            class="inline-flex items-center gap-2
                                            rounded-full
                                            bg-green-100 dark:bg-green-900/20
                                            text-green-700 dark:text-green-300
                                            px-3 py-1.5
                                            text-xs font-semibold">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2
                                            rounded-full
                                            bg-red-100 dark:bg-red-900/20
                                            text-red-700 dark:text-red-300
                                            px-3 py-1.5
                                            text-xs font-semibold">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- AKSI --}}
                                {{-- ================================================= --}}

                                <td class="px-5 py-6 text-center">

                                    <div class="flex flex-col items-center gap-2">

                                        {{-- LIHAT KAK --}}

                                        @if ($item->kak_file)
                                            <a href="{{ filter_var($item->kak_file, FILTER_VALIDATE_URL) ? $item->kak_file : asset($item->kak_file) }}"
                                                target="_blank"
                                                class="inline-flex items-center justify-center gap-2
                                                rounded-xl
                                                bg-blue-600 hover:bg-blue-700
                                                text-white
                                                px-4 py-2.5
                                                text-xs font-semibold
                                                transition
                                                shadow-sm">

                                                <i class="bi bi-eye"></i>

                                                Lihat KAK

                                            </a>
                                        @endif


                                        {{-- CATATAN ADMIN --}}

                                        <button type="button" onclick='openCatatanModal(@json($item))'
                                            class="inline-flex items-center justify-center gap-2
                                            rounded-xl
                                            bg-amber-100 hover:bg-amber-200
                                            dark:bg-amber-900/20 dark:hover:bg-amber-900/40
                                            text-amber-700 dark:text-amber-300
                                            px-4 py-2.5
                                            text-xs font-semibold
                                            transition">

                                            <i class="bi bi-chat-left-text"></i>

                                            Catatan Admin

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10" class="py-20 text-center">

                                    <div class="flex flex-col items-center">

                                        <i
                                            class="bi bi-inbox text-5xl
                                            text-slate-300 dark:text-slate-700">
                                        </i>

                                        <div
                                            class="mt-4
                                            text-slate-500 dark:text-slate-400">

                                            Belum ada data permintaan KAK.

                                        </div>

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

            @if ($kaks->hasPages() || $kaks->total() > 0)

                <div
                    class="flex flex-col md:flex-row
                    items-center justify-between
                    gap-4
                    px-6 py-5
                    border-t
                    border-slate-200 dark:border-slate-800">

                    <div class="text-sm
                        text-slate-500 dark:text-slate-400">

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
                                {{ $kaks->total() }}
                            </span>

                            data
                        @else
                            Tidak ada data
                        @endif

                    </div>


                    <div>

                        {{ $kaks->appends(request()->query())->links() }}

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- MODAL CATATAN ADMIN --}}
    {{-- ============================================================= --}}

    <div id="catatanModal"
        class="fixed inset-0 z-50 hidden items-center justify-center
        bg-slate-900/60 backdrop-blur-sm px-4">

        <div
            class="w-full max-w-lg
            rounded-3xl
            bg-white dark:bg-slate-900
            border border-slate-200 dark:border-slate-800
            shadow-2xl">

            <div
                class="flex items-center justify-between
                px-6 py-5
                border-b border-slate-200 dark:border-slate-800">

                <div>

                    <h3 class="text-lg font-bold
                        text-slate-800 dark:text-white">

                        Catatan Admin

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                        Catatan untuk permintaan KAK.

                    </p>

                </div>

                <button type="button" onclick="closeCatatanModal()"
                    class="h-9 w-9 rounded-xl
                    text-slate-400 hover:text-slate-700
                    dark:hover:text-white
                    hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <form method="POST" id="catatanForm" action="">
                @csrf

                <div class="p-6">

                    <label class="block text-sm font-semibold
            text-slate-700 dark:text-slate-200 mb-2">

                        Catatan Admin

                    </label>

                    <textarea name="kak_catatan_admin" id="kak_catatan_admin" rows="5" placeholder="Tulis catatan admin..."
                        class="w-full rounded-2xl
            border border-slate-300 dark:border-slate-700
            bg-white dark:bg-slate-950
            text-slate-800 dark:text-white
            px-4 py-3
            focus:outline-none
            focus:ring-2 focus:ring-blue-500"></textarea>

                </div>

                <div
                    class="flex justify-end gap-3
        px-6 py-5
        border-t border-slate-200 dark:border-slate-800">

                    <button type="button" onclick="closeCatatanModal()"
                        class="rounded-xl
            border border-slate-300 dark:border-slate-700
            px-4 py-2.5
            text-sm font-semibold
            text-slate-600 dark:text-slate-300">

                        Batal

                    </button>

                    <button type="submit"
                        class="inline-flex items-center gap-2
            rounded-xl
            bg-blue-600 hover:bg-blue-700
            text-white
            px-5 py-2.5
            text-sm font-semibold">

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

            /*
            |--------------------------------------------------------------------------
            | URL FORM
            |--------------------------------------------------------------------------
            */

            form.action = "{{ url('/admin/permintaan-kak') }}/" + item.kak_id + "/catatan";

            /*
            |--------------------------------------------------------------------------
            | ISI CATATAN LAMA
            |--------------------------------------------------------------------------
            */

            textarea.value = item.kak_catatan_admin ?? '';

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN MODAL
            |--------------------------------------------------------------------------
            */

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }


        function closeCatatanModal() {

            const modal = document.getElementById('catatanModal');

            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }


        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                closeCatatanModal();

            }

        });


        document.getElementById('catatanModal').addEventListener('click', function(event) {

            if (event.target === this) {

                closeCatatanModal();

            }

        });
    </script>

@endsection
