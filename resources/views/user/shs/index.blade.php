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
            function formatRupiah(value) {
                var number = Number(value || 0);

                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

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

            function openDetailSHS(item) {
                console.log('Detail SHS:', item);

                /*
                |--------------------------------------------------------------------------
                | DATA UTAMA
                |--------------------------------------------------------------------------
                */

                var barang = item.barang || item.nama_barang || '-';
                var kelompok = item.kelompok || item.nama_kelompok || '-';
                var unit = item.unit || item.satuan || '-';
                var operator = item.operator || item.operator_nama || '-';
                var harga = item.harga || item.shs_harga || item.harga_satuan || 0;
                var tkdn = item.tkdn || item.shs_tkdn || '-';
                var spesifikasi = item.spesifikasi || item.shs_spesifikasi || '-';

                /*
                |--------------------------------------------------------------------------
                | REFERENSI HARGA
                |--------------------------------------------------------------------------
                |
                | Laravel bisa mengirim relationship sebagai:
                | - referensiHarga
                | - referensi_harga
                |
                */

                var referensi = [];

                if (Array.isArray(item.referensiHarga)) {
                    referensi = item.referensiHarga;
                } else if (Array.isArray(item.referensi_harga)) {
                    referensi = item.referensi_harga;
                }

                /*
                |--------------------------------------------------------------------------
                | ISI DATA UTAMA
                |--------------------------------------------------------------------------
                */

                var detailBarang = document.getElementById('detail_barang');
                var detailKelompok = document.getElementById('detail_kelompok');
                var detailUnit = document.getElementById('detail_unit');
                var detailOperator = document.getElementById('detail_operator');
                var detailHarga = document.getElementById('detail_harga');
                var detailTkdn = document.getElementById('detail_tkdn');
                var detailSpesifikasi = document.getElementById('detail_spesifikasi');

                if (detailBarang) {
                    detailBarang.textContent = barang;
                }

                if (detailKelompok) {
                    detailKelompok.textContent = kelompok;
                }

                if (detailUnit) {
                    detailUnit.textContent = unit;
                }

                if (detailOperator) {
                    detailOperator.textContent = operator;
                }

                if (detailHarga) {
                    detailHarga.textContent = formatRupiah(harga);
                }

                if (detailTkdn) {
                    detailTkdn.textContent = tkdn;
                }

                if (detailSpesifikasi) {
                    detailSpesifikasi.textContent = spesifikasi;
                }

                /*
                |--------------------------------------------------------------------------
                | REFERENSI HARGA
                |--------------------------------------------------------------------------
                */

                var detailReferensi = document.getElementById('detail_referensi');

                if (detailReferensi) {

                    /*
                    |--------------------------------------------------------------------------
                    | TIDAK ADA REFERENSI
                    |--------------------------------------------------------------------------
                    */

                    if (!referensi || referensi.length === 0) {

                        detailReferensi.innerHTML =
                            '<div class="rounded-xl border border-slate-200 bg-slate-50 p-4">' +
                            '<div class="flex items-center gap-3">' +
                            '<div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-200 text-slate-500">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />' +
                            '</svg>' +
                            '</div>' +
                            '<div>' +
                            '<div class="font-semibold text-slate-700">Belum ada referensi harga</div>' +
                            '<div class="text-sm text-slate-500">Tidak terdapat data referensi harga untuk SHS ini.</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | BANGUN ROW TANPA TEMPLATE LITERAL
                        |--------------------------------------------------------------------------
                        */

                        var rows = '';

                        referensi.forEach(function(ref, index) {

                            var nomor = index + 1;

                            var hargaReferensi =
                                ref.shs_referensi_harga ||
                                ref.referensi_harga ||
                                ref.harga ||
                                0;

                            var link =
                                ref.shs_referensi_link ||
                                ref.referensi_link ||
                                ref.link ||
                                '';

                            /*
                            |--------------------------------------------------------------------------
                            | LINK
                            |--------------------------------------------------------------------------
                            */

                            var linkHtml = '-';

                            if (link && String(link).trim() !== '') {

                                var safeLink = escapeHtml(link);

                                linkHtml =
                                    '<a href="' + safeLink + '"' +
                                    ' target="_blank"' +
                                    ' rel="noopener noreferrer"' +
                                    ' class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 hover:text-blue-800 transition">' +

                                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />' +
                                    '</svg>' +

                                    'Buka Link' +
                                    '</a>';

                            }

                            /*
                            |--------------------------------------------------------------------------
                            | ROW
                            |--------------------------------------------------------------------------
                            */

                            rows +=
                                '<tr class="hover:bg-slate-50">' +

                                '<td class="px-4 py-4 text-slate-600 align-top">' +
                                nomor +
                                '</td>' +

                                '<td class="px-4 py-4 text-slate-700 align-top">' +
                                '<div class="font-semibold">' +
                                'Referensi Harga ' + nomor +
                                '</div>' +
                                '</td>' +

                                '<td class="px-4 py-4 text-right font-semibold text-slate-800 whitespace-nowrap align-top">' +
                                formatRupiah(hargaReferensi) +
                                '</td>' +

                                '<td class="px-4 py-4 align-top">' +
                                linkHtml +
                                '</td>' +

                                '</tr>';
                        });

                        /*
                        |--------------------------------------------------------------------------
                        | TABLE REFERENSI
                        |--------------------------------------------------------------------------
                        */

                        detailReferensi.innerHTML =
                            '<div class="overflow-hidden rounded-xl border border-slate-200 bg-white">' +

                            '<div class="border-b border-slate-200 bg-slate-50 px-5 py-4">' +
                            '<div class="flex items-center justify-between gap-3">' +

                            '<div>' +
                            '<h4 class="font-bold text-slate-800">' +
                            'Referensi Harga' +
                            '</h4>' +

                            '<p class="mt-1 text-sm text-slate-500">' +
                            referensi.length +
                            ' referensi harga tersedia' +
                            '</p>' +
                            '</div>' +

                            '<div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2zm0 0V5m0 14v-3" />' +
                            '</svg>' +
                            '</div>' +

                            '</div>' +
                            '</div>' +

                            '<div class="overflow-x-auto">' +

                            '<table class="min-w-full divide-y divide-slate-200">' +

                            '<thead class="bg-slate-50">' +
                            '<tr>' +

                            '<th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">' +
                            'No' +
                            '</th>' +

                            '<th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">' +
                            'Referensi' +
                            '</th>' +

                            '<th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-500">' +
                            'Harga' +
                            '</th>' +

                            '<th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">' +
                            'Link' +
                            '</th>' +

                            '</tr>' +
                            '</thead>' +

                            '<tbody class="divide-y divide-slate-100 bg-white">' +
                            rows +
                            '</tbody>' +

                            '</table>' +

                            '</div>' +

                            '</div>';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | LINK UTAMA SHS
                |--------------------------------------------------------------------------
                */

                var detailLink = document.getElementById('detail_link');

                if (detailLink) {

                    var mainLink =
                        item.link ||
                        item.shs_link ||
                        item.url ||
                        '';

                    if (mainLink && String(mainLink).trim() !== '') {

                        detailLink.innerHTML =
                            '<a href="' + escapeHtml(mainLink) + '"' +
                            ' target="_blank"' +
                            ' rel="noopener noreferrer"' +
                            ' class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-2 font-semibold text-blue-700 hover:bg-blue-100">' +

                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />' +
                            '</svg>' +

                            'Buka Referensi' +

                            '</a>';

                    } else {

                        detailLink.innerHTML =
                            '<span class="text-slate-400">Tidak ada link</span>';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | BUKA MODAL
                |--------------------------------------------------------------------------
                */

                var modal = document.getElementById('detailModal');

                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            }


            /*
            |--------------------------------------------------------------------------
            | TUTUP MODAL
            |--------------------------------------------------------------------------
            */

            function closeDetailSHS() {

                var modal = document.getElementById('detailModal');

                if (modal) {
                    modal.classList.add('hidden');
                }

                document.body.classList.remove('overflow-hidden');
            }


            /*
            |--------------------------------------------------------------------------
            | TUTUP KLIK BACKDROP
            |--------------------------------------------------------------------------
            */

            document.addEventListener('click', function(event) {

                var modal = document.getElementById('detailModal');

                if (!modal) {
                    return;
                }

                if (event.target === modal) {
                    closeDetailSHS();
                }
            });


            /*
            |--------------------------------------------------------------------------
            | TUTUP DENGAN ESC
            |--------------------------------------------------------------------------
            */

            document.addEventListener('keydown', function(event) {

                if (event.key === 'Escape') {
                    closeDetailSHS();
                }
            });
        </script>

    </div>

@endsection
