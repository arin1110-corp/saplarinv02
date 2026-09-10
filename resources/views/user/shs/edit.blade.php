@extends('user.layouts.app')

@section('title', 'Edit SHS')

@section('page_title', 'Edit Usulan SHS')

@section('breadcrumb', 'Edit SHS')

<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        height: 52px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        display: flex !important;
        align-items: center !important;
        background: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 50px !important;
        color: #374151 !important;
        padding-left: 18px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px !important;
        right: 12px !important;
    }

    .select2-dropdown {
        border-radius: 16px !important;
        border: 1px solid #e2e8f0 !important;
        overflow: hidden;
    }

    .select2-search--dropdown {
        padding: 10px !important;
    }

    .select2-search--dropdown .select2-search__field {
        height: 42px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        padding: 8px 12px !important;
        outline: none !important;
        box-shadow: none !important;
    }

    .select2-results__option {
        padding: 10px 15px !important;
    }
</style>

@section('content')

    {{-- ==========================================================
         ERROR SESSION
    =========================================================== --}}
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- ==========================================================
         VALIDATION ERROR
    =========================================================== --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.shs.update', $shs->shs_uid) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- ==========================================================
                 HEADER
            =========================================================== --}}
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-3xl shadow-lg p-8 text-white">

                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">

                    <div>
                        <h2 class="text-3xl font-bold">
                            Edit Usulan SHS
                        </h2>

                        <p class="text-blue-100 mt-2 max-w-3xl">
                            Perbaharui data usulan Standar Harga Satuan.
                        </p>
                    </div>

                    <a href="{{ route('user.shs.index') }}"
                        class="bg-white text-blue-700 rounded-2xl px-6 py-3 font-semibold">
                        Kembali
                    </a>

                </div>

            </div>


            {{-- ==========================================================
                 INFORMASI DASAR
            =========================================================== --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <h3 class="text-xl font-bold text-slate-800">
                    Informasi Dasar
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Lengkapi informasi barang yang akan diperbarui.
                </p>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                    {{-- ==================================================
                         TAHUN
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahun
                        </label>

                        <input type="number" name="shs_tahun" value="{{ old('shs_tahun', $shs->shs_tahun) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                    </div>


                    {{-- ==================================================
                         UNIT
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Unit
                        </label>

                        <select id="unit" name="shs_unit_kode"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                            <option value="">
                                Pilih Unit
                            </option>

                            <option value="DISBUD" data-nama="Dinas Kebudayaan Provinsi Bali"
                                {{ old('shs_unit_kode', $shs->shs_unit_kode) == 'DISBUD' ? 'selected' : '' }}>
                                Dinas Kebudayaan Provinsi Bali
                            </option>

                            <option value="UPTD-TB" data-nama="UPTD Taman Budaya"
                                {{ old('shs_unit_kode', $shs->shs_unit_kode) == 'UPTD-TB' ? 'selected' : '' }}>
                                UPTD Taman Budaya
                            </option>

                            <option value="UPTD-MB" data-nama="UPTD Museum Bali"
                                {{ old('shs_unit_kode', $shs->shs_unit_kode) == 'UPTD-MB' ? 'selected' : '' }}>
                                UPTD Museum Bali
                            </option>

                            <option value="UPTD-MPRB" data-nama="UPTD Monumen Perjuangan Rakyat Bali"
                                {{ old('shs_unit_kode', $shs->shs_unit_kode) == 'UPTD-MPRB' ? 'selected' : '' }}>
                                UPTD Monumen Perjuangan Rakyat Bali
                            </option>

                        </select>

                        <input type="hidden" id="unit_nama" name="shs_unit_nama"
                            value="{{ old('shs_unit_nama', $shs->shs_unit_nama) }}">

                    </div>


                    {{-- ==================================================
                         KELOMPOK BARANG
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kelompok Barang
                        </label>

                        <select id="kelompok_barang" class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                            <option value="">
                                Pilih Kelompok Barang
                            </option>

                            @foreach ($kelompoks as $kelompok)
                                <option value="{{ $kelompok->kelompok_id }}" data-kode="{{ $kelompok->kelompok_kode }}"
                                    data-nama="{{ $kelompok->kelompok_nama }}" data-tipe="{{ $kelompok->kelompok_tipe }}"
                                    {{ old('shs_kelompok_barang', $shs->shs_kelompok_barang) == $kelompok->kelompok_nama ? 'selected' : '' }}>
                                    {{ $kelompok->kelompok_nama }}
                                </option>
                            @endforeach

                        </select>

                        <input type="hidden" name="shs_kelompok_barang" id="kelompok_barang_hidden"
                            value="{{ old('shs_kelompok_barang', $shs->shs_kelompok_barang) }}">

                    </div>


                    {{-- ==================================================
                         KODE KELOMPOK
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kode Kelompok
                        </label>

                        <input id="kode_kelompok" type="text" readonly
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3 bg-slate-100"
                            value="{{ old('shs_kode_kelompok', $shs->shs_kode_kelompok) }}">

                        <input type="hidden" name="shs_kode_kelompok" id="kode_kelompok_hidden"
                            value="{{ old('shs_kode_kelompok', $shs->shs_kode_kelompok) }}">

                    </div>


                    {{-- ==================================================
                         NAMA BARANG
                    =================================================== --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Barang
                        </label>

                        <p class="text-sm text-slate-500 mt-1 mb-3">
                            Uraian atau penjabaran nama barang sesuai dengan
                            kondisi fisik barang yang diusulkan.
                        </p>

                        <input type="text" name="shs_barang" value="{{ old('shs_barang', $shs->shs_barang) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" placeholder="Contoh : Laptop"
                            required>

                    </div>


                    {{-- ==================================================
                         SATUAN
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Satuan
                        </label>

                        <select id="satuan" name="shs_satuan" class="w-full" required>

                            <option value="">
                                Pilih Satuan
                            </option>

                            @foreach ($satuans as $item)
                                <option value="{{ $item->satuan_nama }}"
                                    {{ old('shs_satuan', $shs->shs_satuan) == $item->satuan_nama ? 'selected' : '' }}>
                                    {{ $item->satuan_nama }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- ==================================================
                         KELOMPOK SHS
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kelompok SHS
                        </label>

                        <input id="kelompok_tipe" type="text" readonly
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3 bg-slate-100"
                            value="{{ old('shs_kelompok', $shs->shs_kelompok) }}">

                        <input type="hidden" name="shs_kelompok" id="kelompok_tipe_hidden"
                            value="{{ old('shs_kelompok', $shs->shs_kelompok) }}">

                    </div>


                    {{-- ==================================================
                         MEREK
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Merek
                        </label>

                        <input type="text" name="shs_merek" value="{{ old('shs_merek', $shs->shs_merek) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" placeholder="Contoh : Lenovo">

                    </div>


                    {{-- ==================================================
                         TIPE
                    =================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tipe / Model
                        </label>

                        <input type="text" name="shs_tipe" value="{{ old('shs_tipe', $shs->shs_tipe) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                            placeholder="Contoh : ThinkPad E14">

                    </div>


                    {{-- ==================================================
                         SPESIFIKASI
                    =================================================== --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Spesifikasi Barang
                        </label>

                        <p class="text-sm text-slate-500 mt-1 mb-3">
                            Detail spesifikasi teknis dari komponen barang yang
                            diusulkan, seperti ukuran, dimensi, volume, tipe,
                            kapasitas, fitur dan sebagainya.
                        </p>

                        <textarea name="shs_spesifikasi" rows="6" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                            placeholder="Tuliskan spesifikasi lengkap barang..." required>{{ old('shs_spesifikasi', $shs->shs_spesifikasi) }}</textarea>

                    </div>

                </div>

            </div>


            {{-- ==========================================================
                 HARGA & REFERENSI
            =========================================================== --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <h3 class="text-xl font-bold text-slate-800">
                    Harga & Referensi
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Perbaharui harga usulan, TKDN, serta referensi survei harga.
                </p>


                {{-- ======================================================
                     HARGA UTAMA + TKDN
                ======================================================= --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                    {{-- HARGA USULAN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Harga Usulan (Rp)
                        </label>

                        <p class="text-sm text-slate-500 mb-3">
                            Harga per satuan barang yang diusulkan untuk
                            Tahun {{ $shs->shs_tahun }}.
                        </p>

                        <input type="text" id="shs_harga" name="shs_harga"
                            value="{{ old('shs_harga', number_format($shs->shs_harga, 0, ',', '.')) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" placeholder="0"
                            autocomplete="off" required>

                    </div>


                    {{-- TKDN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Persentase TKDN (%)
                        </label>

                        <p class="text-sm text-slate-500 mb-3">
                            Nilai persentase TKDN komponen yang diusulkan.
                        </p>

                        <input type="number" name="shs_tkdn" min="0" max="100" step="0.01"
                            value="{{ old('shs_tkdn', $shs->shs_tkdn) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" placeholder="40">

                    </div>

                </div>


                {{-- ======================================================
                     REFERENSI HARGA
                ======================================================= --}}
                <div class="mt-8">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">

                        <div>

                            <label class="block text-sm font-semibold text-slate-700">
                                Referensi Harga / Survei Harga
                            </label>

                            <p class="text-xs text-slate-500 mt-1">
                                Masukkan harga yang ditemukan pada sumber survei
                                beserta link sumbernya.
                            </p>

                        </div>

                        <button type="button" id="btnTambahReferensi"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                            + Tambah Link
                        </button>

                    </div>


                    {{-- ==================================================
                         DATA REFERENSI
                    =================================================== --}}
                    <div id="referensiContainer" class="space-y-4">

                        @php

                            /*
                             * Prioritas:
                             *
                             * 1. old() jika validasi gagal
                             * 2. relasi referensiHarga dari database
                             * 3. satu row kosong
                             */

                            $oldHargaReferensi = old('shs_harga_referensi');

                            $oldLinkReferensi = old('shs_link_survei');

                            if ($oldHargaReferensi !== null || $oldLinkReferensi !== null) {
                                $oldHargaReferensi = $oldHargaReferensi ?? [];

                                $oldLinkReferensi = $oldLinkReferensi ?? [];

                                $jumlahReferensi = max(count($oldHargaReferensi), count($oldLinkReferensi), 1);

                                $referensiData = [];

                                for ($i = 0; $i < $jumlahReferensi; $i++) {
                                    $referensiData[] = [
                                        'harga' => $oldHargaReferensi[$i] ?? '',
                                        'link' => $oldLinkReferensi[$i] ?? '',
                                    ];
                                }
                            } elseif (isset($shs->referensiHarga) && $shs->referensiHarga->count() > 0) {
                                $referensiData = $shs->referensiHarga
                                    ->map(function ($ref) {
                                        return [
                                            'harga' => $ref->shs_referensi_harga,
                                            'link' => $ref->shs_referensi_link,
                                        ];
                                    })
                                    ->values()
                                    ->toArray();
                            } else {
                                $referensiData = [
                                    [
                                        'harga' => '',
                                        'link' => '',
                                    ],
                                ];
                            }

                        @endphp


                        @foreach ($referensiData as $index => $ref)
                            <div class="referensi-row rounded-2xl border border-slate-200 bg-slate-50 p-4">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    {{-- ==================================================
                                         HARGA REFERENSI
                                    =================================================== --}}
                                    <div>

                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            Harga pada Referensi (Rp)
                                        </label>

                                        <input type="text" name="shs_harga_referensi[]"
                                            value="{{ is_numeric($ref['harga']) ? number_format((float) $ref['harga'], 0, ',', '.') : $ref['harga'] }}"
                                            class="harga-referensi w-full rounded-xl border border-slate-200 px-4 py-3"
                                            placeholder="0" autocomplete="off">

                                    </div>


                                    {{-- ==================================================
                                         LINK REFERENSI
                                    =================================================== --}}
                                    <div>

                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            Link Survei Harga
                                        </label>

                                        <div class="flex gap-2">

                                            <input type="url" name="shs_link_survei[]" value="{{ $ref['link'] }}"
                                                class="w-full rounded-xl border border-slate-200 px-4 py-3"
                                                placeholder="https://...">

                                            <button type="button"
                                                class="btnHapusReferensi rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-600 hover:bg-red-100">
                                                Hapus
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>


                {{-- ======================================================
                     DASAR USULAN
                ======================================================= --}}
                <div class="mt-8">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Dasar Usulan
                    </label>

                    <textarea name="shs_dasar_usulan" rows="5" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                        placeholder="Jelaskan dasar usulan...">{{ old('shs_dasar_usulan', $shs->shs_dasar_usulan) }}</textarea>

                </div>


                {{-- ======================================================
                     KETERANGAN
                ======================================================= --}}
                <div class="mt-8">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Keterangan Tambahan
                    </label>

                    <textarea name="shs_keterangan" rows="5" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                        placeholder="Keterangan tambahan...">{{ old('shs_keterangan', $shs->shs_keterangan) }}</textarea>

                </div>

            </div>


            {{-- ==========================================================
                 UPDATE
            =========================================================== --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-5">

                    <div>

                        <h3 class="text-xl font-bold text-slate-800">
                            Update Usulan SHS
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Pastikan seluruh data sudah benar sebelum diperbarui.
                        </p>

                    </div>


                    <div class="flex gap-3">

                        <a href="{{ route('user.shs.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold">
                            Kembali
                        </a>

                        <button type="submit"
                            class="px-8 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow">
                            Update SHS
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>


    {{-- ==============================================================
         JAVASCRIPT
    ============================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ==========================================================
               UNIT
            =========================================================== */

            const unitSelect = document.getElementById('unit');
            const unitNama = document.getElementById('unit_nama');

            function setUnitNama() {

                if (!unitSelect || !unitNama) {
                    return;
                }

                const selected =
                    unitSelect.options[unitSelect.selectedIndex];

                unitNama.value =
                    selected ? (selected.dataset.nama ?? '') : '';

            }

            if (unitSelect) {

                unitSelect.addEventListener(
                    'change',
                    setUnitNama
                );

                setUnitNama();

            }


            /* ==========================================================
               KELOMPOK BARANG
            =========================================================== */

            $('#kelompok_barang').select2({
                placeholder: 'Pilih Kelompok Barang',
                allowClear: true,
                width: '100%'
            });


            $('#kelompok_barang').on(
                'change',
                function() {

                    const option =
                        $(this).find(':selected');

                    const kode =
                        option.data('kode') ?? '';

                    const nama =
                        option.data('nama') ?? '';

                    const tipe =
                        option.data('tipe') ?? '';


                    $('#kode_kelompok')
                        .val(kode);

                    $('#kode_kelompok_hidden')
                        .val(kode);

                    $('#kelompok_barang_hidden')
                        .val(nama);

                    $('#kelompok_tipe')
                        .val(tipe);

                    $('#kelompok_tipe_hidden')
                        .val(tipe);

                }
            );


            /*
             * Trigger change setelah Select2 siap.
             *
             * Ini memastikan kode kelompok,
             * nama kelompok dan tipe SHS
             * tetap terisi saat halaman edit dibuka.
             */
            $('#kelompok_barang').trigger('change');


            /* ==========================================================
               SATUAN
            =========================================================== */

            $('#satuan').select2({
                placeholder: 'Pilih Satuan',
                allowClear: false,
                width: '100%'
            });


            /* ==========================================================
               FORMAT RUPIAH
            =========================================================== */

            function formatRupiah(value) {

                if (value === null || value === undefined) {
                    return '';
                }

                let angka =
                    String(value).replace(/\D/g, '');

                if (angka === '') {
                    return '';
                }

                return angka.replace(
                    /\B(?=(\d{3})+(?!\d))/g,
                    '.'
                );

            }


            /* ==========================================================
               HARGA UTAMA
            =========================================================== */

            const hargaUtama =
                document.getElementById('shs_harga');

            if (hargaUtama) {

                hargaUtama.addEventListener(
                    'input',
                    function() {

                        this.value =
                            formatRupiah(this.value);

                    }
                );

            }


            /* ==========================================================
               FORMAT HARGA REFERENSI
            =========================================================== */

            function aktifkanFormatHarga(element) {

                if (!element) {
                    return;
                }

                element.addEventListener(
                    'input',
                    function() {

                        this.value =
                            formatRupiah(this.value);

                    }
                );

            }


            /*
             * Aktifkan untuk seluruh referensi
             * yang sudah ada dari database.
             */

            document
                .querySelectorAll('.harga-referensi')
                .forEach(function(element) {

                    aktifkanFormatHarga(element);

                });


            /* ==========================================================
               TAMBAH REFERENSI
            =========================================================== */

            const container =
                document.getElementById(
                    'referensiContainer'
                );

            const btnTambah =
                document.getElementById(
                    'btnTambahReferensi'
                );


            if (btnTambah && container) {

                btnTambah.addEventListener(
                    'click',
                    function() {

                        const row =
                            document.createElement('div');

                        row.className =
                            'referensi-row rounded-2xl border border-slate-200 bg-slate-50 p-4';


                        row.innerHTML = `

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- HARGA REFERENSI --}}

                                <div>

                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        Harga pada Referensi (Rp)
                                    </label>

                                    <input
                                        type="text"
                                        name="shs_harga_referensi[]"
                                        class="harga-referensi w-full rounded-xl border border-slate-200 px-4 py-3"
                                        placeholder="0"
                                        autocomplete="off"
                                    >

                                </div>


                                {{-- LINK REFERENSI --}}

                                <div>

                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        Link Survei Harga
                                    </label>

                                    <div class="flex gap-2">

                                        <input
                                            type="url"
                                            name="shs_link_survei[]"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3"
                                            placeholder="https://..."
                                        >

                                        <button
                                            type="button"
                                            class="btnHapusReferensi rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-600 hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>

                                    </div>

                                </div>

                            </div>

                        `;


                        container.appendChild(row);


                        /*
                         * Aktifkan format Rupiah
                         * pada input baru.
                         */

                        const harga =
                            row.querySelector(
                                '.harga-referensi'
                            );

                        aktifkanFormatHarga(harga);

                    }
                );

            }


            /* ==========================================================
               HAPUS REFERENSI
            =========================================================== */

            if (container) {

                container.addEventListener(
                    'click',
                    function(event) {

                        const button =
                            event.target.closest(
                                '.btnHapusReferensi'
                            );


                        if (!button) {
                            return;
                        }


                        const rows =
                            container.querySelectorAll(
                                '.referensi-row'
                            );


                        /*
                         * Kalau tinggal satu row,
                         * jangan hapus elemen.
                         *
                         * Cukup kosongkan input.
                         */

                        if (rows.length === 1) {

                            const row =
                                button.closest(
                                    '.referensi-row'
                                );


                            row
                                .querySelectorAll('input')
                                .forEach(function(input) {

                                    input.value = '';

                                });

                            return;

                        }


                        /*
                         * Kalau lebih dari satu,
                         * hapus row tersebut.
                         */

                        button
                            .closest('.referensi-row')
                            .remove();

                    }
                );

            }


            /* ==========================================================
               SUBMIT FORM
            =========================================================== */

            const form =
                document.querySelector('form');


            if (form) {

                form.addEventListener(
                    'submit',
                    function() {

                        /*
                         * ----------------------------------------------
                         * HARGA UTAMA
                         * ----------------------------------------------
                         *
                         * Contoh:
                         *
                         * 10.000.000
                         *
                         * menjadi:
                         *
                         * 10000000
                         */

                        if (hargaUtama) {

                            hargaUtama.value =
                                hargaUtama.value.replace(
                                    /\./g,
                                    ''
                                );

                        }


                        /*
                         * ----------------------------------------------
                         * HARGA REFERENSI
                         * ----------------------------------------------
                         */

                        document
                            .querySelectorAll(
                                '.harga-referensi'
                            )
                            .forEach(function(input) {

                                input.value =
                                    input.value.replace(
                                        /\./g,
                                        ''
                                    );

                            });

                    }
                );

            }

        });
    </script>

@endsection
