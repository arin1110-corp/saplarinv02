@extends('administrator-v2.layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Buku Tamu SPJ
            </h1>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Daftar akses dan permintaan melihat file SPJ.
            </p>
        </div>


        {{-- SEARCH --}}
        <div class="rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">

            <form method="GET" action="{{ route('admin.permintaan.spj.buku-tamu') }}"
                class="flex flex-col gap-3 md:flex-row">

                <div class="relative flex-1">

                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NIP, unit, dokumen SPJ atau tujuan..."
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


        {{-- TABLE --}}
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
                                Nama
                            </th>

                            <th
                                class="min-w-[50px] px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                NIP
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Unit
                            </th>

                            <th class="min-w-[200px] px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Dokumen SPJ
                            </th>

                            <th class="min-w-[120px] px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
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


                                {{-- NAMA --}}
                                <td class="px-5 py-4">

                                    <div class="font-medium text-slate-800 dark:text-white">

                                        {{ $item->buku_tamu_nama ?: '-' }}

                                    </div>

                                </td>


                                {{-- NIP --}}
                                <td class="min-w-[50px] px-5 py-4 text-slate-600 dark:text-slate-300">

                                    {{ $item->buku_tamu_nip ?: '-' }}

                                </td>


                                {{-- UNIT --}}
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-300">

                                    {{ $item->buku_tamu_unit ?: '-' }}

                                </td>


                                {{-- DOKUMEN SPJ --}}
                                <td class="px-5 py-4">

                                    @if ($item->spj)
                                        <div class="flex items-start gap-3">

                                            <div
                                                class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-slate-800 dark:text-blue-400">

                                                <i class="bi bi-file-earmark-text"></i>

                                            </div>

                                            <div class="min-w-0">

                                                <div class="font-medium leading-5 text-slate-800 dark:text-white">

                                                    {{ $item->spj->spj_uraian ?: 'Dokumen SPJ' }}

                                                </div>

                                                <div class="mt-1 text-xs text-slate-400">

                                                    UID:
                                                    {{ $item->spj_uid }}

                                                </div>

                                            </div>

                                        </div>
                                    @else
                                        <span class="text-slate-400">
                                            Data SPJ tidak ditemukan
                                        </span>
                                    @endif

                                </td>


                                {{-- TUJUAN --}}
                                <td class="px-5 py-4">

                                    <div class="max-w-xs leading-5 text-slate-600 dark:text-slate-300">

                                        {{ $item->buku_tamu_tujuan ?: '-' }}

                                    </div>

                                </td>


                                {{-- WAKTU --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300">

                                    @if ($item->buku_tamu_waktu)
                                        {{ $item->buku_tamu_waktu->format('d/m/Y H:i') }}
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

                                <td colspan="8" class="px-5 py-14 text-center">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">

                                            <i class="bi bi-journal-x text-2xl text-slate-400"></i>

                                        </div>

                                        <div class="font-medium text-slate-700 dark:text-slate-300">

                                            Belum ada data buku tamu SPJ

                                        </div>

                                        <div class="mt-1 text-sm text-slate-400">

                                            Data akan muncul setelah ada pegawai yang mengakses file SPJ.

                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
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
