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
    }

    .select2-container--default .select2-selection__rendered {
        line-height: 50px !important;
        padding-left: 18px !important;
    }

    .select2-container--default .select2-selection__arrow {
        height: 50px !important;
    }
</style>

@section('content')

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

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

            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-3xl shadow-lg p-8 text-white">

                <div class="flex justify-between items-center">

                    <div>

                        <h2 class="text-3xl font-bold">

                            Edit Usulan SHS

                        </h2>

                        <p class="text-blue-100 mt-2">

                            Perbaharui data usulan SHS.

                        </p>

                    </div>

                    <a href="{{ route('user.shs.index') }}"
                        class="bg-white text-blue-700 rounded-2xl px-6 py-3 font-semibold">

                        Kembali

                    </a>

                </div>

            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <h3 class="text-xl font-bold text-slate-800">

                    Informasi Dasar

                </h3>

                <p class="text-sm text-slate-500">

                    Lengkapi informasi barang.

                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Tahun

                        </label>

                        <input type="number" name="shs_tahun" value="{{ old('shs_tahun', $shs->shs_tahun) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Unit

                        </label>

                        <select id="unit" name="shs_unit_kode"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                            <option value="">Pilih Unit</option>

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

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Kelompok Barang

                        </label>

                        <select id="kelompok_barang" class="w-full" required>

                            <option value="">Pilih Kelompok Barang</option>

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

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Kode Kelompok

                        </label>

                        <input id="kode_kelompok" readonly
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3 bg-slate-100"
                            value="{{ old('shs_kode_kelompok', $shs->shs_kode_kelompok) }}">

                        <input type="hidden" name="shs_kode_kelompok" id="kode_kelompok_hidden"
                            value="{{ old('shs_kode_kelompok', $shs->shs_kode_kelompok) }}">

                    </div>

                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold mb-2">

                            Nama Barang

                        </label>

                        <input type="text" name="shs_barang" value="{{ old('shs_barang', $shs->shs_barang) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Satuan

                        </label>

                        <select id="satuan" name="shs_satuan" class="w-full" required>

                            @foreach ($satuans as $item)
                                <option value="{{ $item->satuan_nama }}"
                                    {{ old('shs_satuan', $shs->shs_satuan) == $item->satuan_nama ? 'selected' : '' }}>

                                    {{ $item->satuan_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Kelompok SHS

                        </label>

                        <input id="kelompok_tipe" readonly
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3 bg-slate-100"
                            value="{{ old('shs_kelompok', $shs->shs_kelompok) }}">

                        <input type="hidden" id="kelompok_tipe_hidden" name="shs_kelompok"
                            value="{{ old('shs_kelompok', $shs->shs_kelompok) }}">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Merek

                        </label>

                        <input type="text" name="shs_merek" value="{{ old('shs_merek', $shs->shs_merek) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Tipe / Model

                        </label>

                        <input type="text" name="shs_tipe" value="{{ old('shs_tipe', $shs->shs_tipe) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3">

                    </div>

                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold mb-2">

                            Spesifikasi

                        </label>

                        <textarea name="shs_spesifikasi" rows="6" class="w-full rounded-2xl border border-slate-200 px-5 py-3">{{ old('shs_spesifikasi', $shs->shs_spesifikasi) }}</textarea>

                    </div>

                </div>

            </div>
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <h3 class="text-xl font-bold text-slate-800">

                    Harga & Referensi

                </h3>

                <p class="text-sm text-slate-500 mt-1">

                    Lengkapi harga usulan dan referensi survei.

                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Harga Usulan (Rp)

                        </label>

                        <input type="text" id="shs_harga" name="shs_harga"
                            value="{{ old('shs_harga', number_format($shs->shs_harga, 0, ',', '.')) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" autocomplete="off" required>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Persentase TKDN (%)

                        </label>

                        <input type="number" name="shs_tkdn" min="0" max="100" step="0.01"
                            value="{{ old('shs_tkdn', $shs->shs_tkdn) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3">

                    </div>

                </div>

                <div class="mt-8">

                    <div class="flex justify-between items-center mb-4">

                        <div>

                            <h4 class="font-semibold text-slate-800">

                                Link Survei Harga

                            </h4>

                            <p class="text-sm text-slate-500">

                                Tambahkan satu atau lebih referensi harga.

                            </p>

                        </div>

                        <button type="button" id="btnTambahLink"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">

                            + Tambah Link

                        </button>

                    </div>

                    <div id="wrapperLink">

                        @php

                            $links = old(
                                'shs_link_survei',
                                $shs->shs_link_survei ? explode("\n", $shs->shs_link_survei) : [''],
                            );

                        @endphp

                        @foreach ($links as $link)
                            <div class="flex gap-3 mb-3 link-item">

                                <input type="url" name="shs_link_survei[]" value="{{ $link }}"
                                    class="flex-1 rounded-2xl border border-slate-200 px-5 py-3"
                                    placeholder="https://.....">

                                <button type="button"
                                    class="hapusLink bg-red-600 hover:bg-red-700 text-white px-4 rounded-xl">

                                    Hapus

                                </button>

                            </div>
                        @endforeach

                    </div>

                </div>

                <div class="mt-8">

                    <label class="block text-sm font-semibold mb-2">

                        Dasar Usulan

                    </label>

                    <textarea name="shs_dasar_usulan" rows="5" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                        placeholder="Jelaskan dasar usulan...">{{ old('shs_dasar_usulan', $shs->shs_dasar_usulan) }}</textarea>

                </div>

                <div class="mt-8">

                    <label class="block text-sm font-semibold mb-2">

                        Keterangan Tambahan

                    </label>

                    <textarea name="shs_keterangan" rows="5" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                        placeholder="Keterangan tambahan...">{{ old('shs_keterangan', $shs->shs_keterangan) }}</textarea>

                </div>

            </div>
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

    <script>
        $(document).ready(function() {

            $('#kelompok_barang').select2({

                placeholder: 'Pilih Kelompok Barang',

                width: '100%'

            });

            $('#satuan').select2({

                placeholder: 'Pilih Satuan',

                width: '100%'

            });

            // Load data awal
            $('#kelompok_barang').trigger('change');

        });

        const unitSelect = document.getElementById('unit');

        const unitNama = document.getElementById('unit_nama');

        function setUnitNama() {

            const selected = unitSelect.options[unitSelect.selectedIndex];

            unitNama.value = selected.dataset.nama ?? '';

        }

        unitSelect.addEventListener('change', setUnitNama);

        setUnitNama();

        $('#kelompok_barang').on('change', function() {

            const option = this.options[this.selectedIndex];

            $('#kode_kelompok').val(option.dataset.kode ?? '');

            $('#kode_kelompok_hidden').val(option.dataset.kode ?? '');

            $('#kelompok_barang_hidden').val(option.dataset.nama ?? '');

            $('#kelompok_tipe').val(option.dataset.tipe ?? '');

            $('#kelompok_tipe_hidden').val(option.dataset.tipe ?? '');

        });

        $('#btnTambahLink').click(function() {

            $('#wrapperLink').append(`

        <div class="flex gap-3 mb-3 link-item">

            <input
                type="url"
                name="shs_link_survei[]"
                class="flex-1 rounded-2xl border border-slate-200 px-5 py-3"
                placeholder="https://.....">

            <button
                type="button"
                class="hapusLink bg-red-600 hover:bg-red-700 text-white px-4 rounded-xl">

                Hapus

            </button>

        </div>

    `);

        });

        $(document).on('click', '.hapusLink', function() {

            if ($('.link-item').length == 1) {

                $(this).closest('.link-item').find('input').val('');

                return;

            }

            $(this).closest('.link-item').remove();

        });

        $('#shs_harga').on('input', function() {

            let angka = $(this).val().replace(/\D/g, '');

            $(this).val(

                angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.')

            );

        });
    </script>

@endsection
