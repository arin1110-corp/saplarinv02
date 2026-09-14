@extends('administrator-v2.layouts.app')

@section('title', 'Laporan SHS')
@section('page-title', 'Laporan SHS')
@section('page-description', 'Verifikasi usulan Standar Harga Satuan')

@section('content')

    {{-- =========================================================
        ALERT
    ========================================================== --}}
    @if (session('success'))
        <div
            class="mb-6 rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-5 py-4">
            <div class="flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>
                <span class="text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">
            <div class="flex items-center gap-3">
                <i class="bi bi-x-circle-fill text-red-600 text-xl"></i>
                <span class="text-red-700 dark:text-red-300">
                    {{ session('error') }}
                </span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl mt-1"></i>
                <div>
                    <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">
                        Terjadi Kesalahan
                    </h4>
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1">
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
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">
                Laporan SHS
            </h2>

            <p class="text-slate-500 dark:text-slate-400">
                Verifikasi usulan Standar Harga Satuan dari seluruh operator.
            </p>
        </div>

        <button type="button" onclick="openExportModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-white font-semibold transition">
            <i class="bi bi-file-earmark-excel"></i>
            Export Excel
        </button>
    </div>


    {{-- =========================================================
        SEARCH
    ========================================================== --}}
    <div class="mb-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
        <form method="GET" action="{{ url()->current() }}" class="flex flex-col md:flex-row md:items-center gap-3">

            <div class="relative flex-1">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari barang, unit, kelompok, operator, NIP..."
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white pl-11 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            </div>

            <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold">
                <i class="bi bi-search"></i>
                Cari
            </button>

            @if (request('search'))
                <a href="{{ url()->current() }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-3 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
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
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">
                    <tr>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Aksi</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">No</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Tahun</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Unit</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Barang</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Kode Kelompok</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Kelompok</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Harga Usulan</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Survey</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Operator</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($shs as $item)

                        @php
                            $referensiCount = $item->referensiHarga ? $item->referensiHarga->count() : 0;
                        @endphp

                        <tr
                            class="border-t border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50">

                            {{-- AKSI --}}
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">

                                    <button type="button" onclick='detailSHS(@json($item->loadMissing('referensiHarga')))'
                                        class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-800 px-3 py-2 text-xs font-medium text-white">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </button>

                                    @if ($item->shs_status === 'Diajukan')
                                        <button type="button" onclick='verifikasiSHS(@json($item))'
                                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-2 text-xs font-medium text-white">
                                            <i class="bi bi-check-circle"></i>
                                            Verifikasi
                                        </button>

                                        <form method="POST" action="{{ route('admin.shs.nonaktif', $item->shs_uid) }}">
                                            @csrf

                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-3 py-2 text-xs text-white"
                                                onclick="return confirm('Apakah usulan ini akan diubah menjadi Tidak Diajukan?')">
                                                <i class="bi bi-x-circle"></i>
                                                Tidak Diajukan
                                            </button>
                                        </form>
                                    @elseif ($item->shs_status === 'Tidak Diajukan')
                                        <form method="POST"
                                            action="{{ route('admin.laporan.shs.aktif', $item->shs_uid) }}">
                                            @csrf

                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 hover:bg-green-700 px-3 py-2 text-xs text-white"
                                                onclick="return confirm('Ajukan kembali usulan ini?')">
                                                <i class="bi bi-send"></i>
                                                Diajukan
                                            </button>
                                        </form>
                                    @elseif ($item->shs_status === 'Diverifikasi')
                                        <span
                                            class="inline-flex items-center gap-2 rounded-lg bg-blue-100 dark:bg-blue-900/20 px-3 py-2 text-xs font-semibold text-blue-700 dark:text-blue-300">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Sudah Diverifikasi
                                        </span>
                                    @endif

                                </div>
                            </td>


                            {{-- NO --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ method_exists($shs, 'firstItem') && $shs->firstItem() ? $shs->firstItem() + $loop->index : $loop->iteration }}
                            </td>


                            {{-- TAHUN --}}
                            <td class="px-4 py-4 whitespace-nowrap font-medium text-slate-700 dark:text-slate-200">
                                {{ $item->shs_tahun }}
                            </td>


                            {{-- UNIT --}}
                            <td class="px-4 py-4">
                                <div class="font-medium text-slate-800 dark:text-white">
                                    {{ $item->shs_unit_nama }}
                                </div>

                                @if ($item->shs_unit_kode)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $item->shs_unit_kode }}
                                    </div>
                                @endif
                            </td>


                            {{-- BARANG --}}
                            <td class="px-4 py-4 min-w-[220px]">
                                <div class="font-semibold text-slate-800 dark:text-white">
                                    {{ $item->shs_barang }}
                                </div>

                                @if ($item->shs_merek || $item->shs_tipe)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $item->shs_merek }}
                                        @if ($item->shs_merek && $item->shs_tipe)
                                            ·
                                        @endif
                                        {{ $item->shs_tipe }}
                                    </div>
                                @endif
                            </td>


                            {{-- KODE --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $item->shs_kode_kelompok }}
                            </td>


                            {{-- KELOMPOK --}}
                            <td class="px-4 py-4 min-w-[180px]">
                                {{ $item->shs_kelompok_barang }}
                            </td>


                            {{-- HARGA --}}
                            <td class="px-4 py-4 font-semibold whitespace-nowrap">
                                Rp {{ number_format((float) $item->shs_harga, 0, ',', '.') }}
                            </td>


                            {{-- SURVEY --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if ($referensiCount > 0)
                                    <button type="button" onclick='detailSHS(@json($item->loadMissing('referensiHarga')))'
                                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/20 px-3 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 dark:hover:bg-emerald-900/30">
                                        <i class="bi bi-bar-chart"></i>
                                        {{ min($referensiCount, 3) }} Survey
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500">
                                        Belum ada survey
                                    </span>
                                @endif
                            </td>


                            {{-- OPERATOR --}}
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-800 dark:text-white">
                                    {{ $item->shs_operator_nama }}
                                </div>

                                @if ($item->shs_operator_nip)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $item->shs_operator_nip }}
                                    </div>
                                @endif
                            </td>


                            {{-- STATUS --}}
                            <td class="px-4 py-4">
                                @if ($item->shs_status === 'Diajukan')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">
                                        Diajukan
                                    </span>
                                @elseif ($item->shs_status === 'Diverifikasi')
                                    <span
                                        class="inline-flex rounded-full bg-blue-100 dark:bg-blue-900/20 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300">
                                        Diverifikasi
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">
                                        Tidak Diajukan
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="11" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-inbox text-5xl text-slate-300 dark:text-slate-700 mb-4"></i>

                                    <div class="font-semibold text-slate-700 dark:text-slate-300">
                                        Data SHS tidak ditemukan
                                    </div>

                                    <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                        Belum ada data yang dapat ditampilkan.
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @endforelse
                </tbody>

            </table>
        </div>


        {{-- PAGINATION --}}
        @if (method_exists($shs, 'hasPages') && $shs->hasPages())
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-t border-slate-200 dark:border-slate-800 px-5 py-4">

                <div class="text-sm text-slate-500 dark:text-slate-400">
                    Menampilkan
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                        {{ $shs->firstItem() }}
                    </span>
                    -
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                        {{ $shs->lastItem() }}
                    </span>
                    dari
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
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
    <div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-7xl max-h-[94vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

            {{-- HEADER --}}
            <div
                class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5 shrink-0">

                <div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-11 h-11 rounded-2xl bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                            <i class="bi bi-file-earmark-text text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                                Detail Usulan SHS
                            </h3>

                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                Detail barang, metadata, dan tiga referensi survey harga.
                            </p>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="closeDetail()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>


            {{-- BODY --}}
            <div class="flex-1 overflow-y-auto p-6">

                {{-- DATA UTAMA --}}
                <div class="mb-8">

                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-box-seam text-blue-600"></i>

                        <h4 class="text-lg font-bold text-slate-800 dark:text-white">
                            Data Barang / Jasa
                        </h4>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

                        <div class="detail-box">
                            <span>Tahun</span>
                            <strong id="d_tahun">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Unit</span>
                            <strong id="d_unit">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Kode Kelompok</span>
                            <strong id="d_kode">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Kelompok</span>
                            <strong id="d_kelompok">-</strong>
                        </div>

                        <div class="detail-box md:col-span-2">
                            <span>Uraian / Nama Barang</span>
                            <strong id="d_barang">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Merk</span>
                            <strong id="d_merek">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Tipe</span>
                            <strong id="d_tipe">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Satuan</span>
                            <strong id="d_satuan">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>TKDN</span>
                            <strong id="d_tkdn">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Harga Rekomendasi / Usulan</span>
                            <strong id="d_harga" class="text-emerald-600 dark:text-emerald-400">-</strong>
                        </div>

                        <div class="detail-box md:col-span-2 xl:col-span-3">
                            <span>Dasar Usulan</span>
                            <strong id="d_dasar_usulan">-</strong>
                        </div>

                        <div class="detail-box md:col-span-2 xl:col-span-4">
                            <span>Spesifikasi</span>
                            <div id="d_spesifikasi" class="detail-value whitespace-pre-line">-</div>
                        </div>

                        <div class="detail-box md:col-span-2 xl:col-span-4">
                            <span>Keterangan</span>
                            <div id="d_keterangan" class="detail-value whitespace-pre-line">-</div>
                        </div>

                    </div>

                </div>


                {{-- SURVEY --}}
                <div class="border-t border-slate-200 dark:border-slate-700 pt-7">

                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-5">

                        <div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-bar-chart-line text-emerald-600"></i>

                                <h4 class="text-lg font-bold text-slate-800 dark:text-white">
                                    Referensi Survey Harga
                                </h4>
                            </div>

                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Maksimal tiga referensi harga yang digunakan sebagai pembanding.
                            </p>
                        </div>

                        <div id="d_survey_count"
                            class="inline-flex items-center gap-2 self-start md:self-auto rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">
                            0 Survey
                        </div>

                    </div>


                    <div id="d_referensi" class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        {{-- Diisi JavaScript --}}
                    </div>

                </div>


                {{-- OPERATOR --}}
                <div class="border-t border-slate-200 dark:border-slate-700 pt-7 mt-7">

                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-person-badge text-slate-500"></i>

                        <h4 class="text-lg font-bold text-slate-800 dark:text-white">
                            Data Operator
                        </h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div class="detail-box">
                            <span>Nama Operator</span>
                            <strong id="d_operator">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>NIP Operator</span>
                            <strong id="d_nip">-</strong>
                        </div>

                        <div class="detail-box">
                            <span>Status</span>
                            <strong id="d_status">-</strong>
                        </div>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-4 flex justify-end shrink-0">
                <button type="button" onclick="closeDetail()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                    Tutup
                </button>
            </div>

        </div>
    </div>



    {{-- =========================================================
        MODAL VERIFIKASI
    ========================================================== --}}
    <div id="modalVerifikasi"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        Verifikasi SHS
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Berikan keputusan terhadap usulan SHS.
                    </p>
                </div>

                <button type="button" onclick="closeVerifikasi()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>


            <form id="formVerifikasi" method="POST">
                @csrf

                <div class="p-6 space-y-5">

                    <div>
                        <label class="block mb-2 text-sm font-medium">
                            Barang
                        </label>

                        <input id="v_barang"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                            readonly>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium">
                            Harga Rekomendasi / Usulan
                        </label>

                        <input id="v_harga"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3 font-semibold"
                            readonly>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium">
                            Catatan Admin
                        </label>

                        <textarea name="shs_catatan_admin" rows="5"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="Masukkan catatan verifikasi jika diperlukan..."></textarea>
                    </div>

                </div>


                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeVerifikasi()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800">
                        Batal
                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-semibold text-white">
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
    <div id="modalExport" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-4xl max-h-[92vh] overflow-y-auto rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            {{-- HEADER --}}
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div
                        class="w-11 h-11 rounded-2xl bg-emerald-100 dark:bg-emerald-900/20 flex items-center justify-center">
                        <i class="bi bi-file-earmark-excel text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                            Export Laporan SHS
                        </h3>

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Export menggunakan format standar SHS.
                        </p>
                    </div>

                </div>

                <button type="button" onclick="closeExportModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>


            <form action="{{ route('admin.laporan-shs.export') }}" method="GET">

                {{-- Tetap kirim field lama agar kompatibel dengan controller export lama. --}}
                <input type="hidden" name="field[]" value="shs_tahun">
                <input type="hidden" name="field[]" value="shs_unit_nama">
                <input type="hidden" name="field[]" value="shs_kode_kelompok">
                <input type="hidden" name="field[]" value="shs_kelompok_barang">
                <input type="hidden" name="field[]" value="shs_barang">
                <input type="hidden" name="field[]" value="shs_merek">
                <input type="hidden" name="field[]" value="shs_tipe">
                <input type="hidden" name="field[]" value="shs_spesifikasi">
                <input type="hidden" name="field[]" value="shs_satuan">
                <input type="hidden" name="field[]" value="shs_harga">
                <input type="hidden" name="field[]" value="shs_tkdn">
                <input type="hidden" name="field[]" value="shs_kelompok">
                <input type="hidden" name="field[]" value="shs_dasar_usulan">
                <input type="hidden" name="field[]" value="shs_keterangan">
                <input type="hidden" name="field[]" value="shs_status">
                <input type="hidden" name="field[]" value="shs_operator_nama">
                <input type="hidden" name="field[]" value="created_at">


                <div class="p-6">

                    {{-- FILTER --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

                        {{-- TAHUN --}}
                        <div>

                            <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                Tahun
                            </label>

                            @php
                                /*
                                 * Ambil seluruh tahun SHS yang tersedia di database.
                                 * Fallback ke tahun berjalan + 2 tahun ke depan apabila
                                 * data tahun belum tersedia.
                                 */
                                $tahunExport = collect();

                                try {
                                    $tahunExport = \App\Models\ModelSHS::query()
                                        ->whereNotNull('shs_tahun')
                                        ->where('shs_tahun', '!=', '')
                                        ->select('shs_tahun')
                                        ->distinct()
                                        ->orderByDesc('shs_tahun')
                                        ->pluck('shs_tahun');
                                } catch (\Throwable $e) {
                                    $tahunExport = collect();
                                }

                                if ($tahunExport->isEmpty()) {
                                    $tahunExport = collect(range((int) now()->year - 2, (int) now()->year + 2))
                                        ->sortDesc()
                                        ->values();
                                }
                            @endphp

                            <select name="tahun"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-800 dark:text-white">

                                <option value="">
                                    Semua Tahun
                                </option>

                                @foreach ($tahunExport as $tahun)
                                    <option value="{{ $tahun }}"
                                        {{ (string) request('tahun') === (string) $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach

                            </select>

                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Pilih tahun SHS yang akan diekspor.
                            </p>

                        </div>


                        {{-- STATUS --}}
                        <div>

                            <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                Filter Status
                            </label>

                            <select name="status"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-800 dark:text-white">

                                <option value="">
                                    Semua Data
                                </option>

                                <option value="Diajukan" {{ request('status') === 'Diajukan' ? 'selected' : '' }}>
                                    Diajukan
                                </option>

                                <option value="Diverifikasi" {{ request('status') === 'Diverifikasi' ? 'selected' : '' }}>
                                    Diverifikasi
                                </option>

                                <option value="Tidak Diajukan"
                                    {{ request('status') === 'Tidak Diajukan' ? 'selected' : '' }}>
                                    Tidak Diajukan
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- FORMAT PREVIEW --}}
                    <div
                        class="rounded-2xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50/60 dark:bg-emerald-900/10 p-5">

                        <div class="flex items-start gap-3 mb-5">

                            <div
                                class="w-10 h-10 shrink-0 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <i class="bi bi-table text-emerald-600 dark:text-emerald-400"></i>
                            </div>

                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white">
                                    Format Export
                                </h4>

                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    Kolom survey otomatis mengambil data dari tabel referensi harga SHS.
                                </p>
                            </div>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                            <div class="export-column">
                                <span class="export-number">1</span>
                                <div>
                                    <strong>KODE KELOMPOK BARANG</strong>
                                    <small>shs_kode_kelompok</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">2</span>
                                <div>
                                    <strong>URAIAN KELOMPOK BARANG</strong>
                                    <small>shs_kelompok_barang</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">3</span>
                                <div>
                                    <strong>URAIAN/NAMA</strong>
                                    <small>shs_barang</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">4</span>
                                <div>
                                    <strong>MERK</strong>
                                    <small>shs_merek</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">5</span>
                                <div>
                                    <strong>SPESIFIKASI</strong>
                                    <small>shs_spesifikasi</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">6</span>
                                <div>
                                    <strong>SATUAN</strong>
                                    <small>shs_satuan</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">7</span>
                                <div>
                                    <strong>TKDN (%)</strong>
                                    <small>shs_tkdn</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">8</span>
                                <div>
                                    <strong>KELOMPOK</strong>
                                    <small>shs_kelompok</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">9</span>
                                <div>
                                    <strong>SURVEY I</strong>
                                    <small>Harga + Link Survey</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">10</span>
                                <div>
                                    <strong>SURVEY II</strong>
                                    <small>Harga + Link Survey</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">11</span>
                                <div>
                                    <strong>SURVEY III</strong>
                                    <small>Harga + Link Survey</small>
                                </div>
                            </div>

                            <div class="export-column">
                                <span class="export-number">12</span>
                                <div>
                                    <strong>REKOMENDASI HARGA</strong>
                                    <small>shs_harga</small>
                                </div>
                            </div>

                        </div>

                    </div>


                    <div
                        class="mt-5 rounded-2xl border border-blue-200 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/10 px-5 py-4">

                        <div class="flex items-start gap-3">

                            <i class="bi bi-info-circle-fill text-blue-600 dark:text-blue-400 mt-0.5"></i>

                            <div class="text-sm text-blue-700 dark:text-blue-300">

                                <div class="font-semibold mb-1">
                                    Catatan
                                </div>

                                <div>
                                    Survey I, Survey II, dan Survey III diambil berdasarkan urutan data pada tabel referensi
                                    harga SHS.
                                    Maksimal tiga referensi ditampilkan pada Excel.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeExportModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                        Batal
                    </button>

                    <button type="submit"
                        class="rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 text-white font-semibold">
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
    <div id="modalHistory" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-5xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        Riwayat Verifikasi SHS
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Riwayat proses verifikasi usulan SHS.
                    </p>
                </div>

                <button type="button" onclick="closeHistory()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <div id="historyContent" class="space-y-4"></div>
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

            /*
             * Hanya hapus overflow jika tidak ada modal lain yang sedang terbuka.
             */
            const openModal = document.querySelector(
                '.fixed.inset-0.z-50.flex'
            );

            if (!openModal) {
                document.body.classList.remove('overflow-hidden');
            }
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
                 */
                if (/^\d{1,3}(\.\d{3})+(,\d+)?$/.test(text)) {

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

            return 'Rp ' + Math.round(number).toLocaleString('id-ID');
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
           LINK AMAN
        ========================================================== */

        function normalizeLink(value) {

            if (!value) {
                return '';
            }

            const link = String(value).trim();

            if (
                !link.startsWith('http://') &&
                !link.startsWith('https://')
            ) {
                return '';
            }

            return link;
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

            const values = {
                d_tahun: item.shs_tahun ?? '-',
                d_unit: item.shs_unit_nama ?? '-',
                d_kode: item.shs_kode_kelompok ?? '-',
                d_kelompok: item.shs_kelompok_barang ?? '-',
                d_barang: item.shs_barang ?? '-',
                d_merek: item.shs_merek ?? '-',
                d_tipe: item.shs_tipe ?? '-',
                d_satuan: item.shs_satuan ?? '-',
                d_tkdn: item.shs_tkdn !== null &&
                    item.shs_tkdn !== undefined &&
                    item.shs_tkdn !== '' ?
                    item.shs_tkdn + ' %' :
                    '-',
                d_harga: formatRupiah(item.shs_harga),
                d_dasar_usulan: item.shs_dasar_usulan ?? '-',
                d_spesifikasi: item.shs_spesifikasi ?? '-',
                d_keterangan: item.shs_keterangan ?? '-',
                d_operator: item.shs_operator_nama ?? '-',
                d_nip: item.shs_operator_nip ?? '-',
                d_status: item.shs_status ?? '-'
            };


            Object.keys(values).forEach(function(id) {

                const element = document.getElementById(id);

                if (element) {
                    element.textContent = values[id];
                }

            });


            /* =====================================================
               REFERENSI HARGA
            ====================================================== */

            const container =
                document.getElementById('d_referensi');

            const surveyCount =
                document.getElementById('d_survey_count');

            if (!container) {
                return;
            }


            const referensi =
                Array.isArray(item.referensiHarga) ?
                item.referensiHarga.slice(0, 3) :
                [];


            if (surveyCount) {
                surveyCount.textContent =
                    referensi.length + ' Survey';
            }


            let html = '';


            /*
             * Tiga slot survey selalu ditampilkan.
             */
            for (let index = 0; index < 3; index++) {

                const ref = referensi[index] ?? null;

                const nomor = index + 1;

                if (ref) {

                    const harga =
                        ref.shs_referensi_harga ??
                        ref.harga ??
                        '';

                    const link =
                        ref.shs_referensi_link ??
                        ref.link ??
                        '';

                    const safeLink =
                        escapeHtml(normalizeLink(link));


                    html += `
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">

                        <div class="px-5 py-4 bg-slate-100 dark:bg-slate-800 flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-300">
                                        ${nomor}
                                    </span>
                                </div>

                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white">
                                        Survey ${nomor}
                                    </div>

                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        Referensi harga
                                    </div>
                                </div>

                            </div>

                            <i class="bi bi-check-circle-fill text-emerald-500"></i>

                        </div>


                        <div class="p-5 space-y-5">

                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-1">
                                    Harga
                                </div>

                                <div class="text-xl font-bold text-slate-800 dark:text-white">
                                    ${formatRupiah(harga)}
                                </div>
                            </div>


                            <div>

                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-2">
                                    Supplier / Link Survey
                                </div>

                                ${
                                    safeLink
                                        ? `
                                                <a href="${safeLink}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="group flex items-center gap-3 rounded-xl border border-blue-200 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/10 px-4 py-3 hover:bg-blue-100 dark:hover:bg-blue-900/20 transition">

                                                    <div class="w-9 h-9 shrink-0 rounded-lg bg-blue-600 flex items-center justify-center">
                                                        <i class="bi bi-box-arrow-up-right text-white"></i>
                                                    </div>

                                                    <div class="min-w-0 flex-1">
                                                        <div class="font-semibold text-blue-700 dark:text-blue-300">
                                                            Buka Link Survey
                                                        </div>

                                                        <div class="text-xs text-blue-600/70 dark:text-blue-400/70 truncate">
                                                            ${safeLink}
                                                        </div>
                                                    </div>

                                                </a>
                                            `
                                        : `
                                                <div class="rounded-xl border border-dashed border-slate-300 dark:border-slate-700 px-4 py-5 text-center">
                                                    <i class="bi bi-link-45deg text-2xl text-slate-400"></i>

                                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                        Tidak ada link survey
                                                    </div>
                                                </div>
                                            `
                                }

                            </div>

                        </div>

                    </div>
                `;

                } else {

                    html += `
                    <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 overflow-hidden">

                        <div class="px-5 py-4 bg-slate-50 dark:bg-slate-800/60 flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                <span class="text-sm font-bold text-slate-500 dark:text-slate-400">
                                    ${nomor}
                                </span>
                            </div>

                            <div>
                                <div class="font-bold text-slate-600 dark:text-slate-300">
                                    Survey ${nomor}
                                </div>

                                <div class="text-xs text-slate-400 dark:text-slate-500">
                                    Belum tersedia
                                </div>
                            </div>

                        </div>

                        <div class="p-8 text-center">

                            <i class="bi bi-database-x text-3xl text-slate-300 dark:text-slate-700"></i>

                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-3">
                                Belum ada referensi harga.
                            </div>

                        </div>

                    </div>
                `;
                }
            }


            container.innerHTML = html;


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
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">

                            <div class="font-semibold text-slate-800 dark:text-white">
                                ${escapeHtml(item.user ?? '-')}
                            </div>

                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                ${escapeHtml(item.tanggal ?? '-')}
                            </span>

                        </div>

                        <div class="mb-2">

                            <span class="inline-flex rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 px-3 py-1 text-xs">
                                ${escapeHtml(item.status ?? '-')}
                            </span>

                        </div>

                        <div class="text-sm text-slate-600 dark:text-slate-300">
                            ${escapeHtml(item.catatan ?? '-')}
                        </div>

                    </div>
                `;

                });

            } else {

                html = `
                <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                    Belum ada riwayat verifikasi.
                </div>
            `;
            }


            const historyContent =
                document.getElementById('historyContent');

            if (historyContent) {
                historyContent.innerHTML = html;
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

        document.addEventListener('DOMContentLoaded', function() {

            /*
             * Klik background modal untuk menutup.
             */
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

                modal.addEventListener('click', function(e) {

                    if (e.target === modal) {
                        hideModal(id);
                    }

                });

            });

        });



        /* =========================================================
           ESC KEY
        ========================================================== */

        document.addEventListener('keydown', function(e) {

            if (e.key !== 'Escape') {
                return;
            }

            [
                'modalDetail',
                'modalVerifikasi',
                'modalExport',
                'modalHistory'
            ].forEach(function(id) {

                const modal =
                    document.getElementById(id);

                if (
                    modal &&
                    !modal.classList.contains('hidden')
                ) {
                    hideModal(id);
                }

            });

        });
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
            box-shadow: 0 0 0 3px rgb(37 99 235 / .15);
        }


        textarea {
            resize: vertical;
        }


        /* =========================================================
           DETAIL BOX
        ========================================================== */

        .detail-box {
            min-width: 0;
            padding: 16px;
            border: 1px solid rgb(226 232 240);
            border-radius: 16px;
            background: rgb(248 250 252);
        }


        .dark .detail-box {
            border-color: rgb(51 65 85);
            background: rgb(15 23 42);
        }


        .detail-box>span {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            line-height: 1.3;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: rgb(100 116 139);
        }


        .dark .detail-box>span {
            color: rgb(148 163 184);
        }


        .detail-box>strong,
        .detail-value {
            display: block;
            color: rgb(30 41 59);
            font-size: 14px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }


        .dark .detail-box>strong,
        .dark .detail-value {
            color: rgb(226 232 240);
        }


        /* =========================================================
           EXPORT COLUMN
        ========================================================== */

        .export-column {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 13px;
            border: 1px solid rgb(226 232 240);
            border-radius: 14px;
            background: white;
        }


        .dark .export-column {
            border-color: rgb(51 65 85);
            background: rgb(15 23 42);
        }


        .export-column strong {
            display: block;
            font-size: 12px;
            line-height: 1.4;
            color: rgb(30 41 59);
        }


        .dark .export-column strong {
            color: rgb(226 232 240);
        }


        .export-column small {
            display: block;
            margin-top: 3px;
            font-size: 11px;
            color: rgb(100 116 139);
        }


        .dark .export-column small {
            color: rgb(148 163 184);
        }


        .export-number {
            width: 32px;
            height: 32px;
            min-width: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgb(220 252 231);
            color: rgb(21 128 61);
            font-size: 12px;
            font-weight: 700;
        }


        .dark .export-number {
            background: rgb(20 83 45 / .35);
            color: rgb(134 239 172);
        }


        /* =========================================================
           SCROLLBAR
        ========================================================== */

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


        /* =========================================================
           PAGINATION
        ========================================================== */

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
