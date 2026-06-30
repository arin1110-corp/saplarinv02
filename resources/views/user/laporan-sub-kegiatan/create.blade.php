@extends('user.layouts.app')

@section('title', 'Input Laporan Sub Kegiatan')
@section('page_title', 'Input Laporan Sub Kegiatan')
@section('breadcrumb', 'Laporan Sub Kegiatan / Input')

@section('content')

    <div class="max-w-6xl space-y-6">

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">
            <h2 class="text-2xl font-bold">Input Laporan Sub Kegiatan</h2>
            <p class="text-blue-100 text-sm mt-2">
                Pilih unit dan sub kegiatan. Indikator akan muncul sesuai master yang dibuat admin.
            </p>
        </div>

        <form method="POST" action="{{ route('user.laporan-sub-kegiatan.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Unit</label>

                        <select name="laporan_unit_kode" id="unitSelect" onchange="setUnitNama(); loadSubKegiatanByUnit();"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                            <option value="">Pilih Unit</option>
                            <option value="DISBUD" data-nama="Dinas Kebudayaan Provinsi Bali">DISBUD</option>
                            <option value="UPTD-MB" data-nama="UPTD Museum Bali">UPTD Museum Bali</option>
                            <option value="UPTD-MPRB" data-nama="UPTD Monumen Perjuangan Rakyat Bali">UPTD Monumen
                                Perjuangan Rakyat Bali</option>
                            <option value="UPTD-TB" data-nama="UPTD Taman Budaya">UPTD Taman Budaya</option>
                        </select>

                        <input type="hidden" name="laporan_unit_nama" id="unitNama">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sub Kegiatan</label>

                        <select name="laporan_sub_kegiatan_id" id="subKegiatanSelect" onchange="loadIndikator()"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                            <option value="">Pilih unit terlebih dahulu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Bulan</label>

                        <select name="laporan_bulan" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                            @php
                                $bulanList = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                            @endphp

                            @foreach ($bulanList as $angka => $nama)
                                <option value="{{ $angka }}" {{ date('n') == $angka ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>

                        <input type="number" name="laporan_tahun" value="{{ date('Y') }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                    </div>

                </div>

            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Realisasi Indikator</h3>
                    <p class="text-sm text-slate-500">
                        Pilih unit dan sub kegiatan untuk menampilkan indikator.
                    </p>
                </div>

                <div id="indikatorContainer" class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 text-yellow-700">
                        Silakan pilih unit dan sub kegiatan terlebih dahulu.
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Permasalahan</h3>
                        <p class="text-sm text-slate-500">Bisa menambahkan lebih dari satu permasalahan.</p>
                    </div>

                    <button type="button" onclick="addPermasalahan()"
                        class="px-4 py-2 rounded-xl bg-red-50 text-red-600 font-semibold hover:bg-red-100">
                        + Tambah
                    </button>
                </div>

                <div id="permasalahanContainer" class="space-y-3">
                    <div class="flex gap-3 item-row">
                        <textarea name="permasalahan[]" rows="2" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                            placeholder="Tuliskan permasalahan..."></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Solusi</h3>
                        <p class="text-sm text-slate-500">Bisa menambahkan lebih dari satu solusi.</p>
                    </div>

                    <button type="button" onclick="addSolusi()"
                        class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100">
                        + Tambah
                    </button>
                </div>

                <div id="solusiContainer" class="space-y-3">
                    <div class="flex gap-3 item-row">
                        <textarea name="solusi[]" rows="2" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                            placeholder="Tuliskan solusi..."></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tindak Lanjut</h3>
                        <p class="text-sm text-slate-500">Bisa menambahkan lebih dari satu tindak lanjut.</p>
                    </div>

                    <button type="button" onclick="addTindakLanjut()"
                        class="px-4 py-2 rounded-xl bg-green-50 text-green-600 font-semibold hover:bg-green-100">
                        + Tambah
                    </button>
                </div>

                <div id="tindakLanjutContainer" class="space-y-3">
                    <div class="flex gap-3 item-row">
                        <textarea name="tindak_lanjut[]" rows="2" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                            placeholder="Tuliskan tindak lanjut..."></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('user.laporan-sub-kegiatan.index') }}"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Batal
                </a>

                <button type="submit" class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Simpan Laporan
                </button>
            </div>

        </form>

    </div>

    <script>
        function setUnitNama() {
            const select = document.getElementById('unitSelect');
            const selected = select.options[select.selectedIndex];
            document.getElementById('unitNama').value = selected.dataset.nama || '';
        }

        function loadSubKegiatanByUnit() {
            const unit = document.getElementById('unitSelect').value;
            const subSelect = document.getElementById('subKegiatanSelect');
            const container = document.getElementById('indikatorContainer');

            subSelect.innerHTML = `<option value="">Memuat sub kegiatan...</option>`;

            container.innerHTML = `
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 text-yellow-700">
            Silakan pilih sub kegiatan terlebih dahulu.
        </div>
    `;

            if (!unit) {
                subSelect.innerHTML = `<option value="">Pilih unit terlebih dahulu</option>`;
                return;
            }

            fetch("{{ route('user.laporan-sub-kegiatan.sub-by-unit') }}?unit=" + encodeURIComponent(unit))
                .then(response => response.json())
                .then(data => {
                    if (!data.length) {
                        subSelect.innerHTML = `<option value="">Tidak ada sub kegiatan untuk unit ini</option>`;

                        container.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-red-700">
                        Belum ada indikator aktif untuk unit ini. Hubungi admin.
                    </div>
                `;
                        return;
                    }

                    let options = `<option value="">Pilih Sub Kegiatan</option>`;

                    data.forEach(item => {
                        options += `
                    <option value="${item.sub_kegiatan_id}">
                        ${item.sub_kegiatan_nama}
                    </option>
                `;
                    });

                    subSelect.innerHTML = options;
                })
                .catch(() => {
                    subSelect.innerHTML = `<option value="">Gagal memuat sub kegiatan</option>`;
                });
        }

        function loadIndikator() {
            const unit = document.getElementById('unitSelect').value;
            const subKegiatanId = document.getElementById('subKegiatanSelect').value;
            const container = document.getElementById('indikatorContainer');

            if (!unit || !subKegiatanId) {
                container.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 text-yellow-700">
                    Silakan pilih unit dan sub kegiatan terlebih dahulu.
                </div>
            `;
                return;
            }

            container.innerHTML = `
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-slate-500">
                Memuat indikator...
            </div>
        `;

            fetch("{{ route('user.laporan-sub-kegiatan.indikator') }}?unit=" + encodeURIComponent(unit) +
                    "&sub_kegiatan_id=" + encodeURIComponent(subKegiatanId))
                .then(response => response.json())
                .then(data => {
                    if (!data.length) {
                        container.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-red-700">
                            Belum ada indikator aktif untuk unit dan sub kegiatan ini. Hubungi admin.
                        </div>
                    `;
                        return;
                    }

                    let html = '';

                    data.forEach(item => {
                        html += `
                        <div class="border border-slate-200 rounded-2xl p-5">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <h4 class="font-bold text-slate-900">
                                        ${item.indikator_nama}
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Unit:
                                        <span class="font-semibold text-blue-700">
                                            ${item.indikator_unit_kode}
                                        </span>
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Realisasi Sebelumnya:
                                        <span class="font-semibold text-green-700">
                                            ${Number(item.realisasi_sebelumnya || 0).toLocaleString('id-ID')}
                                            ${item.indikator_satuan}
                                        </span>
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Sisa Target:
                                        <span class="font-semibold text-orange-600">
                                            ${Number(item.sisa_target || 0).toLocaleString('id-ID')}
                                            ${item.indikator_satuan}
                                        </span>
                                    </p>
                                </div>

                                <div class="w-full md:w-64">
                                    <label class="block text-xs font-semibold text-slate-500 mb-2">
                                        Realisasi
                                    </label>

                                    <input type="number"
                                        step="0.01"
                                        min="0"
                                        name="realisasi[${item.indikator_id}]"
                                        oninput="hitungPersen(this, ${item.indikator_target})"
                                        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                                        placeholder="Isi realisasi"
                                        required>

                                    <div class="text-xs text-slate-500 mt-2">
                                        Capaian:
                                        <span class="font-bold text-green-600 persen-capaian">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    });

                    container.innerHTML = html;
                })
                .catch(() => {
                    container.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-red-700">
                        Gagal memuat indikator.
                    </div>
                `;
                });
        }

        function hitungPersen(input, target) {
            const wrapper = input.closest('.border');
            const output = wrapper.querySelector('.persen-capaian');
            const realisasi = Number(input.value || 0);

            let persen = 0;

            if (Number(target) > 0) {
                persen = (realisasi / Number(target)) * 100;
            }

            output.innerText = persen.toFixed(2).replace('.', ',') + '%';
        }

        function addPermasalahan() {
            addDynamicTextarea('permasalahanContainer', 'permasalahan[]', 'Tuliskan permasalahan...');
        }

        function addSolusi() {
            addDynamicTextarea('solusiContainer', 'solusi[]', 'Tuliskan solusi...');
        }

        function addTindakLanjut() {
            addDynamicTextarea('tindakLanjutContainer', 'tindak_lanjut[]', 'Tuliskan tindak lanjut...');
        }

        function addDynamicTextarea(containerId, inputName, placeholder) {
            const container = document.getElementById(containerId);

            container.insertAdjacentHTML('beforeend', `
            <div class="flex gap-3 item-row">
                <textarea name="${inputName}"
                    rows="2"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="${placeholder}"></textarea>

                <button type="button"
                    onclick="this.closest('.item-row').remove()"
                    class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">
                    ✕
                </button>
            </div>
        `);
        }
    </script>

@endsection
