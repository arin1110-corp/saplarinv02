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
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                NIP
                            </th>

                            <th
                                class="whitespace-nowrap px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Unit
                            </th>

                            <th class="min-w-[280px] px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
                                Dokumen SPJ
                            </th>

                            <th class="min-w-[220px] px-5 py-4 text-left font-semibold text-slate-600 dark:text-slate-300">
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
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300">

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

                                    <button type="button"
                                        onclick="openBukuTamuModal(
                                        {{ \Illuminate\Support\Js::from([
                                            'nama' => $item->buku_tamu_nama,
                                            'nip' => $item->buku_tamu_nip,
                                            'unit' => $item->buku_tamu_unit,
                                            'tujuan' => $item->buku_tamu_tujuan,
                                            'waktu' => $item->buku_tamu_waktu ? $item->buku_tamu_waktu->format('d/m/Y H:i') : '-',
                                            'dokumen' => $item->spj?->spj_uraian ?? 'Dokumen SPJ',
                                            'spj_uid' => $item->spj_uid,
                                            'file' => $item->spj?->spj_file ?? null,
                                        ]) }}
                                    )"
                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100 dark:bg-slate-800 dark:text-blue-400 dark:hover:bg-slate-700">

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


    {{-- ===================================================================== --}}
    {{-- MODAL DETAIL BUKU TAMU --}}
    {{-- ===================================================================== --}}

    <div id="bukuTamuModal" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">

        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeBukuTamuModal()"></div>


        {{-- MODAL --}}
        <div class="relative flex min-h-full items-center justify-center p-4">

            <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900"
                onclick="event.stopPropagation()">

                {{-- HEADER --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 dark:border-slate-800">

                    <div>

                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                            Detail Buku Tamu SPJ
                        </h2>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Informasi pengunjung dan dokumen yang diminta.
                        </p>

                    </div>


                    <button type="button" onclick="closeBukuTamuModal()"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-white">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </div>


                {{-- BODY --}}
                <div class="space-y-5 p-6">

                    {{-- PENGUNJUNG --}}
                    <div>

                        <div class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Data Pengunjung
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div>

                                <div class="text-xs text-slate-400">
                                    Nama
                                </div>

                                <div id="detailNama" class="mt-1 font-medium text-slate-800 dark:text-white">
                                    -
                                </div>

                            </div>


                            <div>

                                <div class="text-xs text-slate-400">
                                    NIP
                                </div>

                                <div id="detailNip" class="mt-1 font-medium text-slate-800 dark:text-white">
                                    -
                                </div>

                            </div>


                            <div>

                                <div class="text-xs text-slate-400">
                                    Unit
                                </div>

                                <div id="detailUnit" class="mt-1 font-medium text-slate-800 dark:text-white">
                                    -
                                </div>

                            </div>


                            <div>

                                <div class="text-xs text-slate-400">
                                    Waktu
                                </div>

                                <div id="detailWaktu" class="mt-1 font-medium text-slate-800 dark:text-white">
                                    -
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- TUJUAN --}}
                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Tujuan
                        </div>

                        <div id="detailTujuan"
                            class="mt-2 rounded-xl bg-slate-50 p-4 leading-6 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            -
                        </div>

                    </div>


                    {{-- DOKUMEN --}}
                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Dokumen SPJ yang Diminta
                        </div>

                        <div class="mt-2 rounded-xl border border-slate-200 p-4 dark:border-slate-700">

                            <div class="flex items-start gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-slate-800 dark:text-blue-400">

                                    <i class="bi bi-file-earmark-text text-lg"></i>

                                </div>


                                <div class="min-w-0 flex-1">

                                    <div id="detailDokumen" class="font-medium leading-6 text-slate-800 dark:text-white">
                                        -
                                    </div>

                                    <div id="detailUid" class="mt-1 break-all text-xs text-slate-400">
                                        -
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- FILE --}}
                    <div id="detailFileWrapper" class="hidden">

                        <a id="detailFileButton" href="#" target="_blank" rel="noopener noreferrer"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 font-medium text-white transition hover:bg-blue-700">

                            <i class="bi bi-box-arrow-up-right"></i>

                            Lihat File SPJ

                        </a>

                    </div>


                    <div id="detailNoFile"
                        class="hidden rounded-xl bg-amber-50 p-4 text-sm text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">

                        <i class="bi bi-exclamation-circle me-1"></i>

                        File SPJ belum tersedia.

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="flex justify-end border-t border-slate-200 px-6 py-4 dark:border-slate-800">

                    <button type="button" onclick="closeBukuTamuModal()"
                        class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        Tutup
                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================================== --}}
    {{-- JAVASCRIPT --}}
    {{-- ===================================================================== --}}

    <script>
        function openBukuTamuModal(data) {
            document.getElementById('detailNama').textContent =
                data.nama || '-';

            document.getElementById('detailNip').textContent =
                data.nip || '-';

            document.getElementById('detailUnit').textContent =
                data.unit || '-';

            document.getElementById('detailWaktu').textContent =
                data.waktu || '-';

            document.getElementById('detailTujuan').textContent =
                data.tujuan || '-';

            document.getElementById('detailDokumen').textContent =
                data.dokumen || 'Dokumen SPJ';

            document.getElementById('detailUid').textContent =
                'UID SPJ: ' + (data.spj_uid || '-');


            const fileWrapper =
                document.getElementById('detailFileWrapper');

            const noFile =
                document.getElementById('detailNoFile');

            const fileButton =
                document.getElementById('detailFileButton');


            if (data.file) {

                fileButton.href = data.file;

                fileWrapper.classList.remove('hidden');

                noFile.classList.add('hidden');

            } else {

                fileButton.href = '#';

                fileWrapper.classList.add('hidden');

                noFile.classList.remove('hidden');

            }


            const modal =
                document.getElementById('bukuTamuModal');

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');
        }


        function closeBukuTamuModal() {
            const modal =
                document.getElementById('bukuTamuModal');

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');
        }


        /*
        |--------------------------------------------------------------------------
        | ESC UNTUK MENUTUP MODAL
        |--------------------------------------------------------------------------
        */

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                closeBukuTamuModal();

            }

        });
    </script>
@endsection
