@extends('user.layouts.app')

@section('title', 'Usulan SHS')

@section('page_title', 'Standar Harga Satuan (SHS)')

@section('breadcrumb', 'Standar Harga Satuan')

@section('content')

    @php
        $tahunList = $shs->pluck('shs_tahun')->unique()->sortDesc()->values();

        $totalData = $shs->count();
        $totalDraft = $shs->where('shs_status', 'Draft')->count();
        $totalAktif = $shs->where('shs_status', 'Diajukan')->count();
        $totalNonaktif = $shs->where('shs_status', 'Tidak Diajukan')->count();
    @endphp

    <div class="space-y-6">

        {{-- ============================================================
            ALERT SUCCESS
        ============================================================ --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- ============================================================
            ALERT ERROR
        ============================================================ --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        {{-- ============================================================
            VALIDATION ERROR
        ============================================================ --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ============================================================
            HEADER
        ============================================================ --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div>
                    <h2 class="text-3xl font-bold">
                        Standar Harga Satuan (SHS)
                    </h2>

                    <p class="text-blue-100 text-sm mt-3 max-w-3xl">
                        Operator dapat mengusulkan Standar Harga Satuan (SHS)
                        Tahun 2028 berdasarkan kebutuhan masing-masing unit kerja.
                        Seluruh operator dapat melihat usulan SHS,
                        namun perubahan data hanya dapat dilakukan oleh
                        operator yang membuat usulan selama status masih Draft.
                    </p>
                </div>

                <div>
                    @php
                        $batasWaktu = \Carbon\Carbon::now()->day(25)->startOfDay();
                        $sudahDitutup = now()->gte($batasWaktu);
                    @endphp

                    @if (!$sudahDitutup)
                        <a href="{{ route('user.shs.create') }}"
                            class="inline-flex items-center gap-2 bg-white text-blue-700 hover:bg-blue-50 font-semibold px-6 py-3 rounded-2xl shadow">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />

                            </svg>

                            Tambah Usulan SHS

                        </a>
                    @else
                        <button type="button" disabled
                            class="inline-flex items-center gap-2 bg-slate-300 text-slate-500 cursor-not-allowed font-semibold px-6 py-3 rounded-2xl shadow">

                            🔒 Pengajuan SHS Ditutup

                        </button>
                    @endif
                </div>

            </div>

        </div>

        {{-- ============================================================
            STATISTIK
        ============================================================ --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-5">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5">

                <p class="text-sm text-slate-500">
                    Total Usulan
                </p>

                <h3 class="text-4xl font-bold text-slate-800 mt-3">
                    {{ $totalData }}
                </h3>

            </div>

            <div class="bg-green-50 rounded-3xl border border-green-200 shadow-sm p-5">

                <p class="text-sm text-green-700">
                    Diajukan
                </p>

                <h3 class="text-4xl font-bold text-green-700 mt-3">
                    {{ $totalAktif }}
                </h3>

            </div>

            <div class="bg-red-50 rounded-3xl border border-red-200 shadow-sm p-5">

                <p class="text-sm text-red-700">
                    Tidak Diajukan
                </p>

                <h3 class="text-4xl font-bold text-red-700 mt-3">
                    {{ $totalNonaktif }}
                </h3>

            </div>

        </div>

        {{-- ============================================================
            FILTER
        ============================================================ --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Filter Tahun --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Filter Tahun
                    </label>

                    <select id="filterTahun" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}">
                                {{ $tahun }}
                            </option>
                        @endforeach

                    </select>

                </div>

                {{-- Filter Unit --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Filter Unit
                    </label>

                    <select id="filterUnit" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                        <option value="">
                            Semua Unit
                        </option>

                        <option value="DISBUD" data-nama="Dinas Kebudayaan Provinsi Bali"
                            {{ old('shs_unit_kode') == 'DISBUD' ? 'selected' : '' }}>
                            Dinas Kebudayaan Provinsi Bali
                        </option>

                        <option value="UPTD-TB" data-nama="UPTD Taman Budaya"
                            {{ old('shs_unit_kode') == 'UPTD-TB' ? 'selected' : '' }}>
                            UPTD Taman Budaya
                        </option>

                        <option value="UPTD-MB" data-nama="UPTD Museum Bali"
                            {{ old('shs_unit_kode') == 'UPTD-MB' ? 'selected' : '' }}>
                            UPTD Museum Bali
                        </option>

                        <option value="UPTD-MPRB" data-nama="UPTD Monumen Perjuangan Rakyat Bali"
                            {{ old('shs_unit_kode') == 'UPTD-MPRB' ? 'selected' : '' }}>
                            UPTD Monumen Perjuangan Rakyat Bali
                        </option>

                    </select>

                </div>

                {{-- Filter Status --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status
                    </label>

                    <select id="filterStatus" class="w-full rounded-2xl border border-slate-200 px-4 py-3">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="Diajukan">
                            Diajukan
                        </option>

                        <option value="Tidak Diajukan">
                            Tidak Diajukan
                        </option>

                    </select>

                </div>

                {{-- Search --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Cari Barang
                    </label>

                    <input type="text" id="searchBarang" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        placeholder="Nama barang, spesifikasi, kelompok...">

                </div>

            </div>

            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                <div class="text-sm text-slate-500">

                    Menampilkan

                    <span id="showingInfo" class="font-semibold text-slate-800">
                        0
                    </span>

                    data.

                </div>

                <div class="flex items-center gap-2">

                    <label class="text-sm text-slate-500">
                        Per Halaman
                    </label>

                    <select id="perPage" class="rounded-xl border border-slate-200 px-3 py-2">

                        <option value="5">
                            5
                        </option>

                        <option value="10" selected>
                            10
                        </option>

                        <option value="20">
                            20
                        </option>

                    </select>

                </div>

            </div>

        </div>

        {{-- ============================================================
            LIST SHS
        ============================================================ --}}
        <div id="shsWrapper" class="space-y-6">

            @forelse($shs as $item)

                @php

                    $keywordSearch = strtolower(
                        ($item->shs_unit_kode ?? '') .
                            ' ' .
                            ($item->shs_unit_nama ?? '') .
                            ' ' .
                            ($item->shs_barang ?? '') .
                            ' ' .
                            ($item->shs_kelompok_barang ?? '') .
                            ' ' .
                            ($item->shs_spesifikasi ?? ''),
                    );
                @endphp

                <div class="shs-card
                    bg-white
                    rounded-3xl
                    border
                    border-slate-200
                    shadow-sm
                    p-6"
                    data-tahun="{{ $item->shs_tahun }}" data-status="{{ $item->shs_status }}"
                    data-unit="{{ $item->shs_unit_kode }}" data-search="{{ $keywordSearch }}">

                    {{-- ====================================================
                        HEADER CARD
                    ==================================================== --}}
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5 mb-6">

                        <div>

                            <div class="flex flex-wrap gap-2 mb-3">

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Tahun {{ $item->shs_tahun }}
                                </span>

                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">

                                    {{ $item->shs_unit_kode }}

                                    @if ($item->shs_unit_nama)
                                        - {{ $item->shs_unit_nama }}
                                    @endif

                                </span>

                                @if ($item->shs_status == 'Draft')
                                    <span
                                        class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Draft
                                    </span>
                                @elseif($item->shs_status == 'Diajukan')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Diajukan
                                    </span>
                                @elseif($item->shs_status == 'Diverifikasi')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Diverifikasi
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Tidak Diajukan
                                    </span>
                                @endif

                            </div>

                            <h3 class="text-2xl font-bold text-slate-900">
                                {{ $item->shs_barang }}
                            </h3>

                            <p class="text-sm text-slate-500 mt-2">
                                {{ $item->shs_kelompok_barang }}
                            </p>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ \Illuminate\Support\Str::limit($item->shs_spesifikasi, 180) }}
                            </p>

                        </div>

                        <div class="text-right">

                            <div class="text-xs uppercase tracking-wider text-slate-500">
                                Harga Usulan
                            </div>

                            <div class="text-3xl font-bold text-blue-700 mt-2">
                                Rp {{ number_format($item->shs_harga, 0, ',', '.') }}
                            </div>

                        </div>

                    </div>

                    {{-- ====================================================
                        INFO CARD
                    ============================================================ --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                        {{-- Operator --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">

                            <div class="text-xs text-slate-500">
                                Operator
                            </div>

                            <div class="font-semibold text-slate-800 mt-2">
                                {{ $item->shs_operator_nama }}
                            </div>

                            <div class="text-xs text-slate-500">
                                {{ $item->shs_operator_nip }}
                            </div>

                        </div>

                        {{-- TKDN --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">

                            <div class="text-xs text-slate-500">
                                TKDN
                            </div>

                            <div class="text-2xl font-bold text-slate-800 mt-2">

                                {{ $item->shs_tkdn ?? '-' }}

                                @if ($item->shs_tkdn)
                                    %
                                @endif

                            </div>

                        </div>

                        {{-- Link Survei --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">

                            <div class="text-xs text-slate-500">
                                Link Survei
                            </div>

                            <div class="text-2xl font-bold text-slate-800 mt-2">

                                {{ $item->shs_link_survei ? substr_count($item->shs_link_survei, "\n") + 1 : 0 }}

                            </div>

                        </div>

                        {{-- Tanggal --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">

                            <div class="text-xs text-slate-500">
                                Tanggal Input
                            </div>

                            <div class="font-semibold text-slate-800 mt-2">

                                {{ optional($item->created_at)->format('d/m/Y') }}

                            </div>

                        </div>

                    </div>

                    {{-- ====================================================
                        ACTION
                    ============================================================ --}}
                    <div class="flex flex-wrap gap-3">

                        <button type="button" onclick='openDetailSHS(@json($item->loadMissing('referensiHarga')))'
                            class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">

                            Detail

                        </button>

                        @if ($item->shs_operator_id == session('pegawai_id'))
                            <a href="{{ route('user.shs.edit', $item->shs_uid) }}"
                                class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold">

                                Edit

                            </a>
                        @endif

                    </div>

                </div>

            @empty

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-10 text-center text-slate-500">

                    Belum ada usulan SHS.

                </div>
            @endforelse

        </div>

        {{-- ============================================================
            EMPTY FILTER
        ============================================================ --}}
        <div id="emptyFilter" class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm p-10 text-center">

            <div class="flex flex-col items-center">

                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-5">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 9.172a4 4 0 115.656 5.656M15 15l6 6" />

                    </svg>

                </div>

                <h3 class="text-xl font-bold text-slate-700">
                    Data tidak ditemukan
                </h3>

                <p class="text-slate-500 mt-2">
                    Tidak ada usulan SHS yang sesuai dengan filter.
                </p>

            </div>

        </div>

        {{-- ============================================================
            PAGINATION
        ============================================================ --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <button type="button" id="prevPage"
                    class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">

                    ← Sebelumnya

                </button>

                <div id="paginationInfo" class="text-center text-sm text-slate-500">

                    Halaman 1 dari 1

                </div>

                <button type="button" id="nextPage"
                    class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">

                    Berikutnya →

                </button>

            </div>

        </div>

        {{-- ============================================================
            MODAL DETAIL SHS
        ============================================================ --}}
        <div id="detailModal"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">

                {{-- ====================================================
                    MODAL HEADER
                ==================================================== --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-8 py-6">

                    <div>

                        <h2 class="text-2xl font-bold text-slate-900">
                            Detail Usulan SHS
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Informasi lengkap usulan Standar Harga Satuan.
                        </p>

                    </div>

                    <button type="button" onclick="closeDetailSHS()"
                        class="w-10 h-10 rounded-full hover:bg-slate-100 text-slate-500 text-xl">

                        ✕

                    </button>

                </div>

                {{-- ====================================================
                    MODAL CONTENT
                ============================================================ --}}
                <div class="p-8 space-y-6">

                    {{-- =================================================
                        INFORMASI UTAMA
                    ================================================== --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                        {{-- Barang --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Barang
                            </label>

                            <div id="detail_barang" class="font-bold text-xl text-slate-800 mt-2">
                            </div>

                        </div>

                        {{-- Kelompok --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Kelompok Barang
                            </label>

                            <div id="detail_kelompok" class="font-semibold text-slate-700 mt-2">
                            </div>

                        </div>

                        {{-- Unit --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Unit
                            </label>

                            <div id="detail_unit" class="font-semibold text-slate-700 mt-2">
                            </div>

                        </div>

                        {{-- Operator --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Operator
                            </label>

                            <div id="detail_operator" class="font-semibold text-slate-700 mt-2">
                            </div>

                        </div>

                        {{-- Harga --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Harga
                            </label>

                            <div id="detail_harga" class="text-3xl font-bold text-blue-700 mt-2">
                            </div>

                        </div>

                        {{-- TKDN --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                TKDN
                            </label>

                            <div id="detail_tkdn" class="text-2xl font-bold text-slate-800 mt-2">
                            </div>

                        </div>

                    </div>

                    {{-- =================================================
                        REFERENSI HARGA
                    ================================================== --}}
                    <div>

                        <div class="flex items-center justify-between gap-3 mb-3">

                            <div>

                                <label class="text-xs uppercase tracking-wider text-slate-500">
                                    Referensi Harga
                                </label>

                                <p class="text-sm text-slate-500 mt-1">
                                    Sumber pembanding harga yang digunakan dalam penyusunan usulan SHS.
                                </p>

                            </div>

                        </div>

                        <div id="detail_referensi" class="mt-3 w-full">
                        </div>

                    </div>

                    {{-- =================================================
                        SPESIFIKASI
                    ================================================== --}}
                    <div>

                        <label class="text-xs uppercase tracking-wider text-slate-500">
                            Spesifikasi
                        </label>

                        <div id="detail_spesifikasi"
                            class="mt-3 rounded-2xl border border-slate-200 bg-slate-50 p-5 whitespace-pre-line">
                        </div>

                    </div>

                    {{-- =================================================
                        LINK SURVEI
                    ================================================== --}}
                    <div>

                        <label class="text-xs uppercase tracking-wider text-slate-500">
                            Link Survei
                        </label>

                        <div id="detail_link" class="mt-3 space-y-2">
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ============================================================
            JAVASCRIPT
        ============================================================ --}}
        <script>
            let currentPage = 1;
            let perPage = 10;

            /* ============================================================
               GET ALL CARDS
            ============================================================ */
            const cards = () => [
                ...document.querySelectorAll('.shs-card')
            ];

            /* ============================================================
               ESCAPE HTML
            ============================================================ */
            function escapeHtml(value) {

                if (value === null || value === undefined) {
                    return '';
                }

                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

            }

            /* ============================================================
               FORMAT RUPIAH
            ============================================================ */
            function formatRupiah(value) {

                if (
                    value === null ||
                    value === undefined ||
                    value === '' ||
                    isNaN(Number(value))
                ) {
                    return 'Rp 0';
                }

                return 'Rp ' + Number(value).toLocaleString('id-ID', {
                    maximumFractionDigits: 0
                });

            }

            /* ============================================================
               FILTER
            ============================================================ */
            function applyFilter() {

                const tahun =
                    document.getElementById('filterTahun')
                    .value
                    .toLowerCase();

                const unit =
                    document.getElementById('filterUnit')
                    .value
                    .toLowerCase();

                const status =
                    document.getElementById('filterStatus')
                    .value
                    .toLowerCase();

                const keyword =
                    document.getElementById('searchBarang')
                    .value
                    .toLowerCase();

                cards().forEach(card => {

                    const cTahun =
                        (card.dataset.tahun ?? '').toLowerCase();

                    const cUnit =
                        (card.dataset.unit ?? '').toLowerCase();

                    const cStatus =
                        (card.dataset.status ?? '').toLowerCase();

                    const cSearch =
                        (card.dataset.search ?? '').toLowerCase();

                    let show = true;

                    if (tahun !== '' && cTahun !== tahun) {
                        show = false;
                    }

                    if (unit !== '' && cUnit !== unit) {
                        show = false;
                    }

                    if (status !== '' && cStatus !== status) {
                        show = false;
                    }

                    if (keyword !== '' && !cSearch.includes(keyword)) {
                        show = false;
                    }

                    card.dataset.filtered = show ? 'true' : 'false';

                });

                currentPage = 1;

                applyPagination();

            }

            /* ============================================================
               GET FILTERED CARDS
            ============================================================ */
            function getFilteredCards() {

                return cards().filter(card =>
                    card.dataset.filtered !== 'false'
                );

            }

            /* ============================================================
               PAGINATION
            ============================================================ */
            function applyPagination() {

                perPage =
                    parseInt(
                        document.getElementById('perPage').value
                    );

                cards().forEach(card => {
                    card.style.display = 'none';
                });

                const visible = getFilteredCards();

                const total = visible.length;

                const totalPage =
                    Math.max(
                        Math.ceil(total / perPage),
                        1
                    );

                if (currentPage > totalPage) {
                    currentPage = totalPage;
                }

                const start =
                    (currentPage - 1) * perPage;

                const end =
                    start + perPage;

                visible
                    .slice(start, end)
                    .forEach(card => {
                        card.style.display = '';
                    });

                document.getElementById('showingInfo').innerHTML =
                    total;

                document.getElementById('paginationInfo').innerHTML =
                    'Halaman ' +
                    currentPage +
                    ' dari ' +
                    totalPage;

                document.getElementById('emptyFilter').style.display =
                    total === 0 ?
                    'block' :
                    'none';

            }

            /* ============================================================
               OPEN DETAIL SHS
            ============================================================ */
            function openDetailSHS(item) {

                /* --------------------------------------------------------
                   DATA UTAMA
                -------------------------------------------------------- */

                document.getElementById('detail_barang').innerHTML =
                    escapeHtml(
                        item.shs_barang ?? '-'
                    );

                document.getElementById('detail_kelompok').innerHTML =
                    escapeHtml(
                        item.shs_kelompok_barang ?? '-'
                    );

                const unitKode =
                    item.shs_unit_kode ?? '';

                const unitNama =
                    item.shs_unit_nama ?? '';

                let unitText = '-';

                if (unitKode && unitNama) {
                    unitText =
                        unitKode +
                        ' - ' +
                        unitNama;
                } else if (unitKode) {
                    unitText = unitKode;
                } else if (unitNama) {
                    unitText = unitNama;
                }

                document.getElementById('detail_unit').innerHTML =
                    escapeHtml(unitText);

                document.getElementById('detail_operator').innerHTML =
                    escapeHtml(
                        item.shs_operator_nama ?? '-'
                    );

                document.getElementById('detail_harga').innerHTML =
                    formatRupiah(
                        item.shs_harga
                    );

                document.getElementById('detail_tkdn').innerHTML =
                    item.shs_tkdn !== null &&
                    item.shs_tkdn !== undefined &&
                    item.shs_tkdn !== '' ?
                    escapeHtml(item.shs_tkdn) + ' %' :
                    '-';

                document.getElementById('detail_spesifikasi').innerHTML =
                    escapeHtml(
                        item.shs_spesifikasi ?? '-'
                    );

                /* --------------------------------------------------------
                   REFERENSI HARGA
                -------------------------------------------------------- */

                const referensi =
                    item.referensiHarga ??
                    item.referensi_harga ?? [];

                let referensiHtml = '';

                if (
                    Array.isArray(referensi) &&
                    referensi.length > 0
                ) {

                    referensiHtml = `
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>

                                        <th class="px-4 py-3 text-left font-semibold text-slate-700 whitespace-nowrap">
                                            No
                                        </th>

                                        <th class="px-4 py-3 text-left font-semibold text-slate-700">
                                            Referensi
                                        </th>

                                        <th class="px-4 py-3 text-right font-semibold text-slate-700 whitespace-nowrap">
                                            Harga
                                        </th>

                                        <th class="px-4 py-3 text-left font-semibold text-slate-700">
                                            Link
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">

                                    ${referensi.map((ref, index) => {

                                        const hargaReferensi =
                                            ref.shs_referensi_harga ??
                                            ref.harga ??
                                            0;

                                        const linkReferensi =
                                            ref.shs_referensi_link ??
                                            ref.link ??
                                            '';

                                        const nomor =
                                            index + 1;

                                        let linkHtml = '';

                                        if (linkReferensi) {

                                            const safeLink =
                                                escapeHtml(
                                                    linkReferensi
                                                );

                                            linkHtml = `
                                                        <a href="${safeLink}"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline break-all">

                                                            <i class="bi bi-box-arrow-up-right"></i>

                                                            Buka Link

                                                        </a>

                                                        <div class="text-xs text-slate-400 mt-1 break-all">
                                                            ${safeLink}
                                                        </div>
                                                    `;

                                        } else {

                                            linkHtml = ` <
                        span class = "text-slate-400" >
                        -
                        <
                        /span>
                    `;

                                        }

                                        return ` <
                    tr class = "hover:bg-slate-50" >

                    <
                    td class = "px-4 py-4 text-slate-600 align-top" >
                    $ {
                        nomor
                    } <
                    /td>

                    <
                    td class = "px-4 py-4 text-slate-700 align-top" >

                    <
                    div class = "font-semibold" >
                    Referensi Harga $ {
                        nomor
                    } <
                    /div>

                    <
                    /td>

                    <
                    td class = "px-4 py-4 text-right font-semibold text-slate-800 whitespace-nowrap align-top" >

                    $ {
                        formatRupiah(hargaReferensi)
                    }

                    <
                    /td>

                    <
                    td class = "px-4 py-4 align-top" >

                    $ {
                        linkHtml
                    }

                    <
                    /td>

                    <
                    /tr>
                    `;

                                    }).join('')}

                                </tbody>
                            </table>
                        </div>
                    `;

                } else {

                    referensiHtml = `
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                            <div class="flex items-center gap-3 text-slate-500">

                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">

                                    <i class="bi bi-info-circle text-lg"></i>

                                </div>

                                <div>

                                    <div class="font-semibold text-slate-700">
                                        Belum ada referensi harga
                                    </div>

                                    <div class="text-sm text-slate-500 mt-1">
                                        Data referensi harga belum tersedia untuk usulan ini.
                                    </div>

                                </div>

                            </div>

                        </div>
                    `;

                }

                document.getElementById('detail_referensi').innerHTML =
                    referensiHtml;

                /* --------------------------------------------------------
                   LINK SURVEI LAMA
                -------------------------------------------------------- */

                let linkHtml = '';

                if (item.shs_link_survei) {

                    const links =
                        String(item.shs_link_survei)
                        .split(/\r?\n/);

                    links.forEach(function(link) {

                        link = link.trim();

                        if (link !== '') {

                            const safeLink =
                                escapeHtml(link);

                            linkHtml += `
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                                    <a href="${safeLink}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-start gap-2 text-blue-600 hover:text-blue-800 hover:underline break-all">

                                        <i class="bi bi-box-arrow-up-right mt-0.5 flex-shrink-0"></i>

                                        <span>
                                            ${safeLink}
                                        </span>

                                    </a>

                                </div>
                            `;

                        }

                    });

                }

                if (!linkHtml) {

                    linkHtml = `
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                            Tidak ada link survei.
                        </div>
                    `;

                }

                document.getElementById('detail_link').innerHTML =
                    linkHtml;

                /* --------------------------------------------------------
                   OPEN MODAL
                -------------------------------------------------------- */

                const modal =
                    document.getElementById('detailModal');

                modal.classList.remove('hidden');

                modal.classList.add('flex');

                document.body.classList.add('overflow-hidden');

            }

            /* ============================================================
               CLOSE DETAIL
            ============================================================ */
            function closeDetailSHS() {

                const modal =
                    document.getElementById('detailModal');

                modal.classList.remove('flex');

                modal.classList.add('hidden');

                document.body.classList.remove('overflow-hidden');

            }

            /* ============================================================
               EVENT FILTER TAHUN
            ============================================================ */
            document
                .getElementById('filterTahun')
                .addEventListener(
                    'change',
                    applyFilter
                );

            /* ============================================================
               EVENT FILTER UNIT
            ============================================================ */
            document
                .getElementById('filterUnit')
                .addEventListener(
                    'change',
                    applyFilter
                );

            /* ============================================================
               EVENT FILTER STATUS
            ============================================================ */
            document
                .getElementById('filterStatus')
                .addEventListener(
                    'change',
                    applyFilter
                );

            /* ============================================================
               EVENT SEARCH
            ============================================================ */
            document
                .getElementById('searchBarang')
                .addEventListener(
                    'keyup',
                    applyFilter
                );

            /* ============================================================
               EVENT PER PAGE
            ============================================================ */
            document
                .getElementById('perPage')
                .addEventListener(
                    'change',
                    function() {

                        currentPage = 1;

                        applyPagination();

                    }
                );

            /* ============================================================
               PREVIOUS PAGE
            ============================================================ */
            document
                .getElementById('prevPage')
                .onclick = function() {

                    if (currentPage > 1) {

                        currentPage--;

                        applyPagination();

                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });

                    }

                };

            /* ============================================================
               NEXT PAGE
            ============================================================ */
            document
                .getElementById('nextPage')
                .onclick = function() {

                    perPage =
                        parseInt(
                            document.getElementById('perPage').value
                        );

                    const total =
                        Math.ceil(
                            getFilteredCards().length /
                            perPage
                        );

                    if (currentPage < total) {

                        currentPage++;

                        applyPagination();

                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });

                    }

                };

            /* ============================================================
               CLICK BACKDROP MODAL
            ============================================================ */
            document
                .getElementById('detailModal')
                .addEventListener(
                    'click',
                    function(event) {

                        if (event.target === this) {
                            closeDetailSHS();
                        }

                    }
                );

            /* ============================================================
               ESCAPE KEY
            ============================================================ */
            document.addEventListener(
                'keydown',
                function(event) {

                    if (event.key === 'Escape') {
                        closeDetailSHS();
                    }

                }
            );

            /* ============================================================
               INITIALIZE
            ============================================================ */
            document.addEventListener(
                'DOMContentLoaded',
                function() {

                    cards().forEach(card => {

                        if (
                            !card.dataset.filtered
                        ) {
                            card.dataset.filtered = 'true';
                        }

                    });

                    applyPagination();

                }
            );
        </script>

    </div>

@endsection
