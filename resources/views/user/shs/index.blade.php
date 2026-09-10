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

                        <option value="DISBUD">
                            Dinas Kebudayaan Provinsi Bali
                        </option>

                        <option value="UPTD-TB">
                            UPTD Taman Budaya
                        </option>

                        <option value="UPTD-MB">
                            UPTD Museum Bali
                        </option>

                        <option value="UPTD-MPRB">
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
                                {{ $item->shs_operator_nama ?? '-' }}
                            </div>

                            <div class="text-xs text-slate-500 mt-1">
                                {{ $item->shs_operator_nip ?? '-' }}
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
            class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">

            <div
                class="relative flex w-full max-w-5xl max-h-[90vh] flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">

                {{-- ====================================================
                    MODAL HEADER
                ==================================================== --}}
                <div class="flex shrink-0 items-center justify-between border-b border-slate-200 px-8 py-6">

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
                ==================================================== --}}
                <div class="min-h-0 flex-1 overflow-y-auto p-8 space-y-6">

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
                                -
                            </div>

                        </div>


                        {{-- Kelompok --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Kelompok Barang
                            </label>

                            <div id="detail_kelompok" class="font-semibold text-slate-700 mt-2">
                                -
                            </div>

                        </div>


                        {{-- Unit --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Unit
                            </label>

                            <div id="detail_unit" class="font-semibold text-slate-700 mt-2">
                                -
                            </div>

                        </div>


                        {{-- Operator --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Operator
                            </label>

                            <div id="detail_operator" class="font-semibold text-slate-700 mt-2">
                                -
                            </div>

                        </div>


                        {{-- NIP Operator --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                NIP Operator
                            </label>

                            <div id="detail_operator_nip" class="font-semibold text-slate-700 mt-2">
                                -
                            </div>

                        </div>


                        {{-- Harga --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Harga Usulan
                            </label>

                            <div id="detail_harga" class="text-3xl font-bold text-blue-700 mt-2">
                                -
                            </div>

                        </div>


                        {{-- TKDN --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                TKDN
                            </label>

                            <div id="detail_tkdn" class="text-2xl font-bold text-slate-800 mt-2">
                                -
                            </div>

                        </div>


                        {{-- Tahun --}}
                        <div>

                            <label class="text-xs uppercase tracking-wider text-slate-500">
                                Tahun
                            </label>

                            <div id="detail_tahun" class="font-semibold text-slate-700 mt-2">
                                -
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
                            -
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


                {{-- ====================================================
                    MODAL FOOTER
                ==================================================== --}}
                <div class="flex shrink-0 justify-end border-t border-slate-200 bg-slate-50 px-8 py-4">

                    <button type="button" onclick="closeDetailSHS()"
                        class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold">

                        Tutup

                    </button>

                </div>

            </div>

        </div>


        {{-- ============================================================
            JAVASCRIPT
        ============================================================ --}}
        <script>
            /*
                    |--------------------------------------------------------------------------
                    | FORMAT RUPIAH
                    |--------------------------------------------------------------------------
                    */

            function formatRupiah(value) {

                var number = Number(value || 0);

                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }


            /*
            |--------------------------------------------------------------------------
            | ESCAPE HTML
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | OPEN DETAIL
            |--------------------------------------------------------------------------
            */

            function openDetailSHS(item) {

                console.log('Detail SHS:', item);


                /*
                |--------------------------------------------------------------------------
                | DATA SHS
                |--------------------------------------------------------------------------
                */

                var barang = item.shs_barang || '-';

                var kelompok = item.shs_kelompok_barang || '-';

                var unit = item.shs_unit_nama || item.shs_unit_kode || '-';

                var operator = item.shs_operator_nama || '-';

                var operatorNip = item.shs_operator_nip || '-';

                var harga = item.shs_harga || 0;

                var tkdn = item.shs_tkdn;

                var tahun = item.shs_tahun || '-';

                var spesifikasi = item.shs_spesifikasi || '-';

                var linkSurvei = item.shs_link_survei || '';


                /*
                |--------------------------------------------------------------------------
                | REFERENSI HARGA
                |--------------------------------------------------------------------------
                */

                var referensi = [];

                if (Array.isArray(item.referensiHarga)) {

                    referensi = item.referensiHarga;

                } else if (Array.isArray(item.referensi_harga)) {

                    referensi = item.referensi_harga;

                }


                /*
                |--------------------------------------------------------------------------
                | SET DATA UTAMA
                |--------------------------------------------------------------------------
                */

                document.getElementById('detail_barang').textContent = barang;

                document.getElementById('detail_kelompok').textContent = kelompok;

                document.getElementById('detail_unit').textContent = unit;

                document.getElementById('detail_operator').textContent = operator;

                document.getElementById('detail_operator_nip').textContent = operatorNip;

                document.getElementById('detail_harga').textContent = formatRupiah(harga);

                document.getElementById('detail_tahun').textContent = tahun;

                document.getElementById('detail_spesifikasi').textContent = spesifikasi;


                /*
                |--------------------------------------------------------------------------
                | TKDN
                |--------------------------------------------------------------------------
                */

                if (
                    tkdn !== null &&
                    tkdn !== undefined &&
                    tkdn !== ''
                ) {

                    document.getElementById('detail_tkdn').textContent = tkdn + '%';

                } else {

                    document.getElementById('detail_tkdn').textContent = '-';

                }


                /*
                |--------------------------------------------------------------------------
                | REFERENSI HARGA
                |--------------------------------------------------------------------------
                */

                var detailReferensi =
                    document.getElementById('detail_referensi');


                var rows = '';


                if (!referensi || referensi.length === 0) {

                    detailReferensi.innerHTML =
                        '<div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">' +

                        '<div class="flex items-center gap-3">' +

                        '<div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-200 text-slate-500">' +

                        '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +

                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />' +

                        '</svg>' +

                        '</div>' +

                        '<div>' +

                        '<div class="font-semibold text-slate-700">' +
                        'Belum ada referensi harga' +
                        '</div>' +

                        '<div class="text-sm text-slate-500 mt-1">' +
                        'Tidak terdapat data referensi harga untuk SHS ini.' +
                        '</div>' +

                        '</div>' +

                        '</div>' +

                        '</div>';

                } else {


                    /*
                    |--------------------------------------------------------------------------
                    | LOOP REFERENSI
                    |--------------------------------------------------------------------------
                    */

                    referensi.forEach(function(ref, index) {

                        var nomor = index + 1;


                        var hargaReferensi =
                            ref.shs_referensi_harga !== null &&
                            ref.shs_referensi_harga !== undefined ?
                            ref.shs_referensi_harga :
                            0;


                        var link =
                            ref.shs_referensi_link ||
                            '';


                        var linkHtml =
                            '<span class="text-slate-400">Tidak ada link</span>';


                        if (
                            link &&
                            String(link).trim() !== ''
                        ) {

                            var safeLink = escapeHtml(link);


                            linkHtml =
                                '<a href="' +
                                safeLink +
                                '"' +

                                ' target="_blank"' +

                                ' rel="noopener noreferrer"' +

                                ' class="inline-flex items-center gap-2 rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition">' +

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

                            '<td class="px-4 py-4 text-slate-600 align-top whitespace-nowrap">' +

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


                            '<td class="px-4 py-4 align-top whitespace-nowrap">' +

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

                        '<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">' +


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


                /*
                |--------------------------------------------------------------------------
                | LINK SURVEI
                |--------------------------------------------------------------------------
                */

                var detailLink =
                    document.getElementById('detail_link');


                detailLink.innerHTML = '';


                if (
                    linkSurvei &&
                    String(linkSurvei).trim() !== ''
                ) {

                    var links =
                        String(linkSurvei)
                        .split(/\r?\n/)
                        .map(function(link) {
                            return link.trim();
                        })
                        .filter(function(link) {
                            return link !== '';
                        });


                    if (links.length > 0) {

                        links.forEach(function(link, index) {

                            var safeSurveyLink =
                                escapeHtml(link);


                            var linkElement =

                                '<a href="' +
                                safeSurveyLink +
                                '"' +

                                ' target="_blank"' +

                                ' rel="noopener noreferrer"' +

                                ' class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-50 transition">' +

                                '<span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-xs font-bold text-blue-700">' +

                                (index + 1) +

                                '</span>' +

                                '<span class="truncate">' +

                                safeSurveyLink +

                                '</span>' +

                                '</a>';


                            detailLink.innerHTML += linkElement;

                        });

                    } else {

                        detailLink.innerHTML =
                            '<span class="text-slate-400">' +
                            'Tidak ada link survei' +
                            '</span>';

                    }

                } else {

                    detailLink.innerHTML =
                        '<span class="text-slate-400">' +
                        'Tidak ada link survei' +
                        '</span>';

                }


                /*
                |--------------------------------------------------------------------------
                | BUKA MODAL
                |--------------------------------------------------------------------------
                */

                var modal =
                    document.getElementById('detailModal');


                if (modal) {

                    modal.classList.remove('hidden');

                    modal.classList.add('flex');

                    document.body.classList.add('overflow-hidden');

                }

            }


            /*
            |--------------------------------------------------------------------------
            | TUTUP MODAL
            |--------------------------------------------------------------------------
            */

            function closeDetailSHS() {

                var modal =
                    document.getElementById('detailModal');


                if (modal) {

                    modal.classList.add('hidden');

                    modal.classList.remove('flex');

                }


                document.body.classList.remove('overflow-hidden');

            }


            /*
            |--------------------------------------------------------------------------
            | KLIK BACKDROP
            |--------------------------------------------------------------------------
            */

            document.addEventListener('click', function(event) {

                var modal =
                    document.getElementById('detailModal');


                if (!modal) {
                    return;
                }


                if (event.target === modal) {

                    closeDetailSHS();

                }

            });


            /*
            |--------------------------------------------------------------------------
            | ESC
            |--------------------------------------------------------------------------
            */

            document.addEventListener('keydown', function(event) {

                if (event.key === 'Escape') {

                    closeDetailSHS();

                }

            });


            /*
            |--------------------------------------------------------------------------
            | FILTER + PAGINATION
            |--------------------------------------------------------------------------
            */

            document.addEventListener('DOMContentLoaded', function() {

                var cards =
                    Array.from(
                        document.querySelectorAll('.shs-card')
                    );


                var filterTahun =
                    document.getElementById('filterTahun');

                var filterUnit =
                    document.getElementById('filterUnit');

                var filterStatus =
                    document.getElementById('filterStatus');

                var searchBarang =
                    document.getElementById('searchBarang');

                var perPage =
                    document.getElementById('perPage');

                var showingInfo =
                    document.getElementById('showingInfo');

                var paginationInfo =
                    document.getElementById('paginationInfo');

                var prevPage =
                    document.getElementById('prevPage');

                var nextPage =
                    document.getElementById('nextPage');

                var emptyFilter =
                    document.getElementById('emptyFilter');


                var currentPage = 1;


                function getFilteredCards() {

                    var tahun =
                        filterTahun ?
                        filterTahun.value.toLowerCase() :
                        '';


                    var unit =
                        filterUnit ?
                        filterUnit.value.toLowerCase() :
                        '';


                    var status =
                        filterStatus ?
                        filterStatus.value.toLowerCase() :
                        '';


                    var search =
                        searchBarang ?
                        searchBarang.value.toLowerCase().trim() :
                        '';


                    return cards.filter(function(card) {

                        var cardTahun =
                            String(card.dataset.tahun || '')
                            .toLowerCase();


                        var cardUnit =
                            String(card.dataset.unit || '')
                            .toLowerCase();


                        var cardStatus =
                            String(card.dataset.status || '')
                            .toLowerCase();


                        var cardSearch =
                            String(card.dataset.search || '')
                            .toLowerCase();


                        var matchTahun = !tahun ||
                            cardTahun === tahun;


                        var matchUnit = !unit ||
                            cardUnit === unit;


                        var matchStatus = !status ||
                            cardStatus === status;


                        var matchSearch = !search ||
                            cardSearch.indexOf(search) !== -1;


                        return (
                            matchTahun &&
                            matchUnit &&
                            matchStatus &&
                            matchSearch
                        );

                    });

                }


                function renderPagination() {

                    var filtered =
                        getFilteredCards();


                    var limit =
                        parseInt(
                            perPage.value,
                            10
                        ) || 10;


                    var total =
                        filtered.length;


                    var totalPages =
                        Math.max(
                            1,
                            Math.ceil(total / limit)
                        );


                    if (currentPage > totalPages) {
                        currentPage = totalPages;
                    }


                    var start =
                        (currentPage - 1) * limit;


                    var end =
                        start + limit;


                    cards.forEach(function(card) {

                        card.classList.add('hidden');

                    });


                    filtered
                        .slice(start, end)
                        .forEach(function(card) {

                            card.classList.remove('hidden');

                        });


                    if (showingInfo) {

                        showingInfo.textContent =
                            total;

                    }


                    if (paginationInfo) {

                        paginationInfo.textContent =
                            'Halaman ' +
                            currentPage +
                            ' dari ' +
                            totalPages;

                    }


                    if (emptyFilter) {

                        if (total === 0) {

                            emptyFilter.classList.remove('hidden');

                        } else {

                            emptyFilter.classList.add('hidden');

                        }

                    }


                    if (prevPage) {

                        prevPage.disabled =
                            currentPage <= 1;

                        prevPage.classList.toggle(
                            'opacity-50',
                            currentPage <= 1
                        );

                        prevPage.classList.toggle(
                            'cursor-not-allowed',
                            currentPage <= 1
                        );

                    }


                    if (nextPage) {

                        nextPage.disabled =
                            currentPage >= totalPages;

                        nextPage.classList.toggle(
                            'opacity-50',
                            currentPage >= totalPages
                        );

                        nextPage.classList.toggle(
                            'cursor-not-allowed',
                            currentPage >= totalPages
                        );

                    }

                }


                if (filterTahun) {

                    filterTahun.addEventListener(
                        'change',
                        function() {

                            currentPage = 1;

                            renderPagination();

                        }
                    );

                }


                if (filterUnit) {

                    filterUnit.addEventListener(
                        'change',
                        function() {

                            currentPage = 1;

                            renderPagination();

                        }
                    );

                }


                if (filterStatus) {

                    filterStatus.addEventListener(
                        'change',
                        function() {

                            currentPage = 1;

                            renderPagination();

                        }
                    );

                }


                if (searchBarang) {

                    searchBarang.addEventListener(
                        'input',
                        function() {

                            currentPage = 1;

                            renderPagination();

                        }
                    );

                }


                if (perPage) {

                    perPage.addEventListener(
                        'change',
                        function() {

                            currentPage = 1;

                            renderPagination();

                        }
                    );

                }


                if (prevPage) {

                    prevPage.addEventListener(
                        'click',
                        function() {

                            if (currentPage > 1) {

                                currentPage--;

                                renderPagination();

                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });

                            }

                        }
                    );

                }


                if (nextPage) {

                    nextPage.addEventListener(
                        'click',
                        function() {

                            var filtered =
                                getFilteredCards();


                            var limit =
                                parseInt(
                                    perPage.value,
                                    10
                                ) || 10;


                            var totalPages =
                                Math.max(
                                    1,
                                    Math.ceil(
                                        filtered.length / limit
                                    )
                                );


                            if (currentPage < totalPages) {

                                currentPage++;

                                renderPagination();

                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });

                            }

                        }
                    );

                }


                renderPagination();

            });
        </script>

    </div>

@endsection
