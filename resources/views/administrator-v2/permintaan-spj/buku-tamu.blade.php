@extends('administrator-v2.layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- =========================================================
        HEADER
    ========================================================== --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Buku Tamu SPJ
            </h1>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Daftar pegawai yang meminta akses untuk melihat file SPJ.
            </p>
        </div>


        {{-- =========================================================
        SEARCH
    ========================================================== --}}
        <div class="rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">

            <form method="GET" action="{{ route('admin.permintaan.spj.buku-tamu') }}"
                class="flex flex-col gap-3 md:flex-row">

                <div class="relative w-full">

                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NIP, unit, tujuan atau nama file..."
                        class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">

                </div>


                <button type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    <i class="bi bi-search me-1"></i>
                    Cari
                </button>


                @if (request('search'))
                    <a href="{{ route('admin.permintaan.spj.buku-tamu') }}"
                        class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>
                @endif

            </form>

        </div>


        {{-- =========================================================
        TABLE
    ========================================================== --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-slate-900">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 dark:bg-slate-800">

                        <tr>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                No
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Pemohon
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Unit / Bidang
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                File SPJ
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Tujuan
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Waktu
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-center font-semibold text-slate-600 dark:text-slate-300">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                        @forelse($tamu as $index => $item)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50">

                                {{-- NO --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300">

                                    {{ $tamu->firstItem() + $index }}

                                </td>


                                {{-- PEMOHON --}}
                                <td class="px-5 py-4">

                                    <div class="font-medium text-slate-800 dark:text-white">
                                        {{ $item->buku_tamu_nama ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $item->buku_tamu_nip ?? '-' }}
                                    </div>

                                </td>


                                {{-- UNIT --}}
                                <td class="px-5 py-4">

                                    <div class="text-slate-700 dark:text-slate-300">
                                        {{ $item->buku_tamu_unit ?? '-' }}
                                    </div>

                                </td>


                                {{-- FILE SPJ --}}
                                <td class="max-w-xs px-5 py-4">

                                    @php

                                        $fileUrl = $item->buku_tamu_file ?? null;

                                        $fileName = '-';

                                        if ($fileUrl) {
                                            $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));
                                        }

                                        /*
                                         * Jika nanti field nama file SPJ dari relasi
                                         * tersedia, bisa diprioritaskan di sini.
                                         */
                                        if (isset($item->spj) && $item->spj && !empty($item->spj->spj_file)) {
                                            $spjUrl = $item->spj->spj_file;

                                            $fileName = basename(parse_url($spjUrl, PHP_URL_PATH));
                                        }

                                    @endphp


                                    <div class="flex min-w-0 items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-900/20">

                                            <i class="bi bi-file-earmark-pdf"></i>

                                        </div>


                                        <div class="min-w-0">

                                            <div class="truncate font-medium text-slate-700 dark:text-slate-300"
                                                title="{{ $fileName }}">
                                                {{ $fileName }}
                                            </div>

                                            <div class="mt-1 text-xs text-slate-400">
                                                SPJ
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- TUJUAN --}}
                                <td class="max-w-xs px-5 py-4">

                                    <div class="line-clamp-2 text-slate-600 dark:text-slate-300"
                                        title="{{ $item->buku_tamu_tujuan ?? '-' }}">
                                        {{ $item->buku_tamu_tujuan ?? '-' }}
                                    </div>

                                </td>


                                {{-- WAKTU --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300">

                                    @if ($item->buku_tamu_waktu)
                                        <div>
                                            {{ $item->buku_tamu_waktu->format('d/m/Y') }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-400">
                                            {{ $item->buku_tamu_waktu->format('H:i:s') }}
                                        </div>
                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="whitespace-nowrap px-5 py-4 text-center">

                                    <button type="button" onclick="showDetail({{ $item->buku_tamu_id }})"
                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40">

                                        <i class="bi bi-eye"></i>

                                        Detail

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-5 py-14 text-center text-slate-500 dark:text-slate-400">

                                    <i class="bi bi-journal-x mb-3 block text-4xl"></i>

                                    <div class="font-medium">
                                        Belum ada data buku tamu SPJ.
                                    </div>

                                    <div class="mt-1 text-xs">
                                        Data permintaan akses file SPJ akan muncul di sini.
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
            @if ($tamu->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">

                    {{ $tamu->links() }}

                </div>
            @endif

        </div>

    </div>



    {{-- =============================================================
    MODAL DETAIL
============================================================= --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4">

        <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900">

            {{-- HEADER --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 dark:border-slate-700">

                <div>

                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Detail Buku Tamu SPJ
                    </h3>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Informasi lengkap permintaan akses file SPJ.
                    </p>

                </div>


                <button type="button" onclick="closeDetail()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            {{-- BODY --}}
            <div class="max-h-[75vh] space-y-5 overflow-y-auto px-6 py-6">


                {{-- DATA PEMOHON --}}
                <div>

                    <div class="mb-3 flex items-center gap-2">

                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">

                            <i class="bi bi-person"></i>

                        </div>

                        <h4 class="font-semibold text-slate-800 dark:text-white">
                            Data Pemohon
                        </h4>

                    </div>


                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        {{-- NAMA --}}
                        <div>

                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Nama
                            </label>

                            <div id="detailNama"
                                class="mt-1 rounded-lg bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800 dark:bg-slate-800 dark:text-white">
                                -
                            </div>

                        </div>


                        {{-- NIP --}}
                        <div>

                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                NIP
                            </label>

                            <div id="detailNip"
                                class="mt-1 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                -
                            </div>

                        </div>


                        {{-- UNIT --}}
                        <div class="md:col-span-2">

                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Unit / Bidang
                            </label>

                            <div id="detailUnit"
                                class="mt-1 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                -
                            </div>

                        </div>

                    </div>

                </div>


                {{-- DETAIL PERMINTAAN --}}
                <div>

                    <div class="mb-3 flex items-center gap-2">

                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400">

                            <i class="bi bi-file-earmark-text"></i>

                        </div>

                        <h4 class="font-semibold text-slate-800 dark:text-white">
                            Detail Permintaan
                        </h4>

                    </div>


                    {{-- FILE --}}
                    <div>

                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            File SPJ yang Diminta
                        </label>


                        <div
                            class="mt-1 flex items-center justify-between gap-3 rounded-lg bg-slate-50 p-3 dark:bg-slate-800">

                            <div class="flex min-w-0 items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-900/20">

                                    <i class="bi bi-file-earmark-pdf text-lg"></i>

                                </div>


                                <div class="min-w-0">

                                    <div id="detailFile"
                                        class="truncate text-sm font-medium text-slate-700 dark:text-slate-300">
                                        -
                                    </div>

                                </div>

                            </div>


                            <a id="detailFileButton" href="#" target="_blank" rel="noopener noreferrer"
                                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">

                                <i class="bi bi-box-arrow-up-right"></i>

                                Lihat File

                            </a>

                        </div>

                    </div>


                    {{-- TUJUAN --}}
                    <div class="mt-4">

                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Tujuan
                        </label>

                        <div id="detailTujuan"
                            class="mt-1 whitespace-pre-line rounded-lg bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            -
                        </div>

                    </div>


                    {{-- WAKTU --}}
                    <div class="mt-4">

                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Waktu Akses
                        </label>

                        <div id="detailWaktu"
                            class="mt-1 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            -
                        </div>

                    </div>

                </div>


                {{-- INFORMASI TEKNIS --}}
                <div>

                    <div class="mb-3 flex items-center gap-2">

                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">

                            <i class="bi bi-pc-display"></i>

                        </div>

                        <h4 class="font-semibold text-slate-800 dark:text-white">
                            Informasi Teknis
                        </h4>

                    </div>


                    <div
                        class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">


                        {{-- UID BUKU TAMU --}}
                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                UID Buku Tamu
                            </div>

                            <code id="detailUid" class="mt-1 block break-all text-xs text-slate-600 dark:text-slate-300">
                                -
                            </code>

                        </div>


                        {{-- UID SPJ --}}
                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                UID SPJ
                            </div>

                            <code id="detailSpjUid"
                                class="mt-1 block break-all text-xs text-slate-600 dark:text-slate-300">
                                -
                            </code>

                        </div>


                        {{-- IP --}}
                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                IP Address
                            </div>

                            <div id="detailIp" class="mt-1 break-all text-sm text-slate-600 dark:text-slate-300">
                                -
                            </div>

                        </div>


                        {{-- USER AGENT --}}
                        <div>

                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                User Agent
                            </div>

                            <div id="detailUserAgent"
                                class="mt-1 break-all text-xs leading-5 text-slate-600 dark:text-slate-300">
                                -
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="flex justify-end border-t border-slate-200 px-6 py-4 dark:border-slate-700">

                <button type="button" onclick="closeDetail()"
                    class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">

                    Tutup

                </button>

            </div>

        </div>

    </div>



    {{-- =============================================================
    DATA + JAVASCRIPT
============================================================= --}}
    <script>
        /*
        |--------------------------------------------------------------------------
        | Data Buku Tamu
        |--------------------------------------------------------------------------
        |
        | Mengambil data dari pagination yang sedang aktif.
        |
        */

        const bukuTamuData = @json($tamu->items());


        /*
        |--------------------------------------------------------------------------
        | SHOW DETAIL
        |--------------------------------------------------------------------------
        */

        function showDetail(id) {
            const item = bukuTamuData.find(
                data => Number(data.buku_tamu_id) === Number(id)
            );


            if (!item) {

                console.error('Data buku tamu tidak ditemukan:', id);

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | DATA PEMOHON
            |--------------------------------------------------------------------------
            */

            document.getElementById('detailNama').textContent =
                item.buku_tamu_nama ?? '-';


            document.getElementById('detailNip').textContent =
                item.buku_tamu_nip ?? '-';


            document.getElementById('detailUnit').textContent =
                item.buku_tamu_unit ?? '-';


            /*
            |--------------------------------------------------------------------------
            | TUJUAN
            |--------------------------------------------------------------------------
            */

            document.getElementById('detailTujuan').textContent =
                item.buku_tamu_tujuan ?? '-';


            /*
            |--------------------------------------------------------------------------
            | WAKTU
            |--------------------------------------------------------------------------
            */

            let waktu = '-';


            if (item.buku_tamu_waktu) {

                const date = new Date(item.buku_tamu_waktu);


                if (!isNaN(date.getTime())) {

                    waktu = date.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });

                } else {

                    waktu = item.buku_tamu_waktu;

                }

            }


            document.getElementById('detailWaktu').textContent = waktu;


            /*
            |--------------------------------------------------------------------------
            | UID
            |--------------------------------------------------------------------------
            */

            document.getElementById('detailUid').textContent =
                item.buku_tamu_uid ?? '-';


            document.getElementById('detailSpjUid').textContent =
                item.spj_uid ?? '-';


            /*
            |--------------------------------------------------------------------------
            | IP ADDRESS
            |--------------------------------------------------------------------------
            */

            document.getElementById('detailIp').textContent =
                item.buku_tamu_ip ?? '-';


            /*
            |--------------------------------------------------------------------------
            | USER AGENT
            |--------------------------------------------------------------------------
            */

            document.getElementById('detailUserAgent').textContent =
                item.buku_tamu_user_agent ?? '-';


            /*
            |--------------------------------------------------------------------------
            | FILE
            |--------------------------------------------------------------------------
            */

            let fileUrl = item.buku_tamu_file ?? '';


            /*
             * Jika file di buku tamu kosong,
             * coba ambil dari relasi SPJ.
             */

            if (
                !fileUrl &&
                item.spj &&
                item.spj.spj_file
            ) {

                fileUrl = item.spj.spj_file;

            }


            /*
            |--------------------------------------------------------------------------
            | NAMA FILE
            |--------------------------------------------------------------------------
            */

            let fileName = 'File tidak tersedia';


            if (fileUrl) {

                try {

                    const url = new URL(fileUrl);

                    const path = url.pathname;

                    fileName =
                        decodeURIComponent(
                            path.substring(
                                path.lastIndexOf('/') + 1
                            )
                        );

                } catch (error) {

                    const parts = fileUrl.split('/');

                    fileName =
                        parts[parts.length - 1] || 'File SPJ';

                }

            }


            document.getElementById('detailFile').textContent =
                fileName;


            /*
            |--------------------------------------------------------------------------
            | BUTTON FILE
            |--------------------------------------------------------------------------
            */

            const fileButton =
                document.getElementById('detailFileButton');


            if (fileUrl) {

                fileButton.href = fileUrl;

                fileButton.classList.remove('hidden');

            } else {

                fileButton.href = '#';

                fileButton.classList.add('hidden');

            }


            /*
            |--------------------------------------------------------------------------
            | OPEN MODAL
            |--------------------------------------------------------------------------
            */

            const modal =
                document.getElementById('detailModal');


            modal.classList.remove('hidden');

            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE DETAIL
        |--------------------------------------------------------------------------
        */

        function closeDetail() {

            const modal =
                document.getElementById('detailModal');


            modal.classList.add('hidden');

            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | CLICK BACKDROP
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('detailModal')
            ?.addEventListener('click', function(event) {

                if (event.target === this) {

                    closeDetail();

                }

            });


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                const modal =
                    document.getElementById('detailModal');


                if (
                    modal &&
                    !modal.classList.contains('hidden')
                ) {

                    closeDetail();

                }

            }

        });
    </script>
@endsection
