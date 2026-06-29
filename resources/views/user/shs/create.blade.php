@extends('user.layouts.app')

@section('title', 'Tambah SHS')
@section('page_title', 'Tambah Usulan SHS')
@section('breadcrumb', 'Tambah SHS')
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

    .select2-container--default .select2-selection__rendered {

        line-height: 50px !important;

        color: #374151 !important;

        padding-left: 18px !important;

    }

    .select2-container--default .select2-selection__arrow {

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

    <form action="{{ route('user.shs.store') }}" method="POST">

        @csrf

        <div class="space-y-6">

            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-3xl shadow-lg p-8 text-white">

                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">

                    <div>

                        <h2 class="text-3xl font-bold">

                            Tambah Usulan SHS

                        </h2>

                        <p class="text-blue-100 mt-2 max-w-3xl">

                            Isi seluruh informasi barang yang akan diusulkan
                            sebagai Standar Harga Satuan Tahun 2028.

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

                <p class="text-sm text-slate-500 mt-1">

                    Lengkapi informasi umum usulan SHS.

                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Tahun

                        </label>

                        <input type="number" name="shs_tahun" value="{{ old('shs_tahun', 2028) }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                    </div>

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

                        <input type="hidden" id="unit_nama" name="shs_unit_nama" value="{{ old('shs_unit_nama') }}">

                    </div>

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
                                    data-nama="{{ $kelompok->kelompok_nama }}" data-tipe="{{ $kelompok->kelompok_tipe }}">

                                    {{ $kelompok->kelompok_nama }}

                                </option>
                            @endforeach

                        </select>
                        <input type="hidden" name="shs_kelompok_barang" id="kelompok_barang_hidden">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Kode Kelompok

                        </label>

                        <input id="kode_kelompok" type="text"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3 bg-slate-100" readonly>

                        <input type="hidden" name="shs_kode_kelompok" id="kode_kelompok_hidden">

                    </div>

                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Nama Barang

                        </label>
                        <p class="text-sm text-slate-500 mt-1">Uraian atau penjabaran nama barang (sesuai fisik barangnya)
                            yang diusulkan.</p>
                        <br>

                        <input type="text" name="shs_barang" value="{{ old('shs_barang') }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" placeholder="Contoh : Laptop"
                            required>

                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Satuan
                        </label>

                        <select id="satuan" name="shs_satuan"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" required>

                            <option value="">Pilih Satuan</option>

                            @foreach ($satuans as $item)
                                <option value="{{ $item->satuan_nama }}">

                                    {{ $item->satuan_nama }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Kelompok SHS

                        </label>

                        <input id="kelompok_tipe" type="text"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3 bg-slate-100" readonly>

                        <input type="hidden" name="shs_kelompok" id="kelompok_tipe_hidden">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Merek

                        </label>

                        <input type="text" name="shs_merek" value="{{ old('shs_merek') }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" placeholder="Contoh : Lenovo">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Tipe / Model

                        </label>

                        <input type="text" name="shs_tipe" value="{{ old('shs_tipe') }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                            placeholder="Contoh : ThinkPad E14">

                    </div>

                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Spesifikasi Barang

                        </label>
                        <p class="text-sm text-slate-500 mt-1">Detail spesifikasi teknis dari komponen barang yang
                            diusulkan, harus lengkap mencantumkan spesfikasi teknis seperti ukuran, dimensi, volume, tipe,
                            kapasitas, fitur dan sebagainya.</p>
                        <br>

                        <textarea name="shs_spesifikasi" rows="6" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                            placeholder="Tuliskan spesifikasi lengkap barang...">{{ old('shs_spesifikasi') }}</textarea>

                    </div>

                </div>

            </div>
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <h3 class="text-xl font-bold text-slate-800">

                    Harga & Referensi

                </h3>

                <p class="text-sm text-slate-500 mt-1">

                    Isi harga usulan, TKDN, serta referensi survei harga.

                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Harga Usulan (Rp)

                        </label>

                        <p class="text-sm text-slate-500">Harga per satuan barang yang diusulkan (proyeksi harga komponen
                            di
                            Tahun 2028 yang sudah all in, termasuk pajak dan ongkir dan lain lain. Data ini
                            digunakan sebagai pembanding untuk diberikan kepada tim pelaksana swakelola/tim survei harga).
                        </p>
                        <br>

                        <input type="text" id="shs_harga" name="shs_harga" value="{{ old('shs_harga') }}"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-3" placeholder="0"
                            autocomplete="off" required>

                    </div>

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">

                            Persentase TKDN (%)

                        </label>
                        <p class="text-sm text-slate-500">Nilai persentase TKDN komponen yang diusulkan (sebagai data
                            pembanding untuk diberikan kepada tim pelaksana swakelola/tim survei harga).</p>
                        <br>
                        <input type="number" min="0" max="100" step="0.01" name="shs_tkdn"
                            value="{{ old('shs_tkdn') }}" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                            placeholder="40">

                    </div>

                </div>

                <div class="mt-8">

                    <div class="flex items-center justify-between mb-4">

                        <div>

                            <h4 class="font-semibold text-slate-800">

                                Link Survei Harga

                            </h4>

                            <p class="text-sm text-slate-500">

                                Tambahkan minimal satu referensi harga. Bisa berupa link (bisa katalog elektronik,
                                e-commerce, daftar harga dari pihak ketiga, dan/atau sumber harga lain yang kredibel)
                                sebagai gambaran
                                detail usulan komponen kepada tim pelaksana swakelola/tim survei harga (dalam rangka
                                penyelarasan persepsi/maksud usulan komponen shs agar tidak
                                salah survei)

                            </p>

                        </div>

                        <button type="button" id="btnTambahLink"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">

                            + Tambah Link

                        </button>

                    </div>

                    <div id="wrapperLink">

                        @forelse ((array) old('shs_link_survei') as $link)
                            <div class="flex gap-3 mb-3 link-item">

                                <input type="url" name="shs_link_survei[]" value="{{ $link }}"
                                    class="flex-1 rounded-2xl border border-slate-200 px-5 py-3"
                                    placeholder="https://.....">

                                <button type="button"
                                    class="hapusLink bg-red-600 hover:bg-red-700 text-white px-4 rounded-xl">

                                    Hapus

                                </button>

                            </div>
                        @empty
                            <div class="flex gap-3 mb-3 link-item">

                                <input type="url" name="shs_link_survei[]"
                                    class="flex-1 rounded-2xl border border-slate-200 px-5 py-3"
                                    placeholder="https://.....">

                                <button type="button"
                                    class="hapusLink bg-red-600 hover:bg-red-700 text-white px-4 rounded-xl">

                                    Hapus

                                </button>

                            </div>
                        @endforelse

                    </div>

                </div>

                <div class="mt-8">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">

                        Dasar Usulan

                    </label>

                    <textarea name="shs_dasar_usulan" rows="5" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                        placeholder="Jelaskan alasan barang ini diusulkan menjadi SHS...">{{ old('shs_dasar_usulan') }}</textarea>

                </div>

                <div class="mt-8">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">

                        Keterangan Tambahan

                    </label>

                    <textarea name="shs_keterangan" rows="5" class="w-full rounded-2xl border border-slate-200 px-5 py-3"
                        placeholder="Keterangan tambahan (opsional)...">{{ old('shs_keterangan') }}</textarea>

                </div>

            </div>
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-5">

                    <div>

                        <h3 class="text-xl font-bold text-slate-800">

                            Simpan Usulan SHS

                        </h3>

                        <p class="text-sm text-slate-500 mt-1">

                            Pastikan seluruh data telah benar sebelum disimpan.

                        </p>

                    </div>

                    <div class="flex gap-3">

                        <a href="{{ route('user.shs.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold">

                            Kembali

                        </a>

                        <button type="submit"
                            class="px-8 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow">

                            Simpan Usulan SHS

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

    <script>
        const unitSelect = document.getElementById('unit');

        const unitNama = document.getElementById('unit_nama');

        function setUnitNama() {

            const selected = unitSelect.options[unitSelect.selectedIndex];

            unitNama.value = selected.dataset.nama ?? '';

        }

        unitSelect.addEventListener('change', setUnitNama);

        setUnitNama();

        document
            .getElementById('btnTambahLink')
            .addEventListener('click', function() {

                let html = `

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

    `;

                document
                    .getElementById('wrapperLink')
                    .insertAdjacentHTML('beforeend', html);

            });

        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('hapusLink')) {

                const total = document.querySelectorAll('.link-item').length;

                if (total == 1) {

                    e.target.parentElement
                        .querySelector('input')
                        .value = '';

                    return;

                }

                e.target.parentElement.remove();

            }

        });
        const harga = document.getElementById('shs_harga');

        harga.addEventListener('input', function() {

            let angka = this.value.replace(/\D/g, '');

            this.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        });
        $('form').on('submit', function() {
            let harga = $('#shs_harga').val();

            harga = harga.replace(/\./g, '');

            $('#shs_harga').val(harga);
        });
        $('#satuan').select2({

            placeholder: 'Pilih Satuan',

            width: '100%'

        });
        $('#kelompok_barang').select2({
            placeholder: 'Pilih Kelompok Barang',
            allowClear: true,
            width: '100%'
        });
        $('#kelompok_barang').trigger('change');

        $('#kelompok_barang').on('change', function() {

            let option = $(this).find(':selected');

            $('#kode_kelompok').val(option.data('kode') ?? '');
            $('#kode_kelompok_hidden').val(option.data('kode') ?? '');

            $('#kelompok_barang_hidden').val(option.data('nama') ?? '');

            $('#kelompok_tipe').val(option.data('tipe') ?? '');
            $('#kelompok_tipe_hidden').val(option.data('tipe') ?? '');

        });
    </script>


@endsection
