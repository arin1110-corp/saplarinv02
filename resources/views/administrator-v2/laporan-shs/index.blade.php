@extends('administrator-v2.layouts.app')

@section('title', 'Laporan SHS')

@section('page-title', 'Laporan SHS')

@section('page-description', 'Verifikasi usulan Standar Harga Satuan')

@section('content')

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

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

        <button onclick="openExportModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-white font-semibold transition">

            <i class="bi bi-file-earmark-excel"></i>

            Export Excel

        </button>

    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table id="datatable" class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="px-4 py-4 text-left">

                            Aksi

                        </th>

                        <th class="px-4 py-4 text-left">

                            No

                        </th>

                        <th class="px-4 py-4 text-left">

                            Unit

                        </th>

                        <th class="px-4 py-4 text-left">

                            Barang

                        </th>

                        <th class="px-4 py-4 text-left">

                            Kelompok

                        </th>

                        <th class="px-4 py-4 text-left">

                            Harga

                        </th>

                        <th class="px-4 py-4 text-left">

                            Operator

                        </th>

                        <th class="px-4 py-4 text-left">

                            Status

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($shs as $item)
                        <tr class="border-t border-slate-200 dark:border-slate-800">

                            <td class="px-4 py-4">

                                <div class="flex flex-wrap gap-2">

                                    <button onclick='detailSHS(@json($item))'
                                        class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-800 px-3 py-2 text-xs font-medium text-white">

                                        <i class="bi bi-eye"></i>

                                        Detail

                                    </button>

                                    @if ($item->shs_status == 'Draft')
                                        <button onclick='verifikasiSHS(@json($item))'
                                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-2 text-xs font-medium text-white">

                                            <i class="bi bi-check-circle"></i>

                                            Verifikasi

                                        </button>
                                    @elseif($item->shs_status == 'Tidak Diajukan')
                                        <form method="POST"
                                            action="{{ route('admin.laporan-shs.aktif', $item->shs_uid) }}">

                                            @csrf

                                            <button
                                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 hover:bg-green-700 px-3 py-2 text-xs text-white">

                                                <i class="bi bi-send"></i>

                                                Diajukan

                                            </button>

                                        </form>
                                    @else
                                        <form method="POST"
                                            action="{{ route('admin.laporan-shs.nonaktif', $item->shs_uid) }}">

                                            @csrf

                                            <button
                                                class="inline-flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-3 py-2 text-xs text-white">

                                                <i class="bi bi-x-circle"></i>

                                                Tidak Diajukan

                                            </button>

                                        </form>
                                    @endif

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                {{ $loop->iteration }}

                            </td>

                            <td class="px-4 py-4">

                                {{ $item->shs_unit_nama }}

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-semibold">

                                    {{ $item->shs_barang }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $item->shs_satuan }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                {{ $item->shs_kelompok_barang }}

                            </td>

                            <td class="px-4 py-4 font-semibold">

                                Rp {{ number_format($item->shs_harga, 0, ',', '.') }}

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-semibold">

                                    {{ $item->shs_operator_nama }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $item->shs_operator_nip }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                @if ($item->shs_status == 'Diajukan')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">

                                        Diajukan

                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">

                                        Tidak Diajukan

                                    </span>
                                @endif

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <!-- ======================= MODAL DETAIL ======================= -->
    <div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-6xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white">

                        Detail Usulan SHS

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Detail usulan yang dikirim operator.

                    </p>

                </div>

                <button type="button" onclick="closeDetail()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <div class="flex-1 overflow-y-auto p-6">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Tahun

                        </label>

                        <input id="d_tahun"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Unit

                        </label>

                        <input id="d_unit"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Kelompok Barang

                        </label>

                        <input id="d_kelompok"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Kode Kelompok

                        </label>

                        <input id="d_kode"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div class="lg:col-span-2">

                        <label class="block mb-2 text-sm font-medium">

                            Nama Barang

                        </label>

                        <input id="d_barang"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Satuan

                        </label>

                        <input id="d_satuan"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Kelompok SHS

                        </label>

                        <input id="d_tipe"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Harga

                        </label>

                        <input id="d_harga"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            TKDN

                        </label>

                        <input id="d_tkdn"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div class="lg:col-span-2">

                        <label class="block mb-2 text-sm font-medium">

                            Spesifikasi

                        </label>

                        <textarea id="d_spesifikasi" rows="6"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly></textarea>

                    </div>

                    <div class="lg:col-span-2">

                        <label class="block mb-2 text-sm font-medium">

                            Link Survei

                        </label>

                        <textarea id="d_link" rows="5"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly></textarea>

                    </div>

                </div>

                <div class="mt-8 border-t border-slate-200 dark:border-slate-700 pt-6">

                    <h4 class="text-lg font-semibold mb-4">

                        Data Operator

                    </h4>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                        <input id="d_operator"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                        <input id="d_nip"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- ======================= MODAL VERIFIKASI ======================= -->
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

                            Harga

                        </label>

                        <input id="v_harga"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Keputusan

                        </label>

                        <select id="verifikasi_status" name="status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="">

                                Pilih Keputusan

                            </option>

                            <option value="Disetujui">

                                Disetujui

                            </option>

                            <option value="Ditolak">

                                Ditolak

                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Catatan Verifikator

                        </label>

                        <textarea name="catatan" rows="5"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="Masukkan catatan verifikasi..."></textarea>

                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeVerifikasi()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-semibold text-white">

                        <i class="bi bi-check-circle me-2"></i>

                        Simpan Verifikasi

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- ======================= MODAL EXPORT ======================= -->
    <div id="modalExport" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-5xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

            <!-- HEADER -->
            <div
                class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5 flex-shrink-0">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Export Usulan SHS

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Pilih kolom yang ingin diexport ke Excel.

                    </p>

                </div>

                <button type="button" onclick="closeExportModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form action="{{ route('admin.laporan-shs.export') }}" method="GET"
                class="flex flex-col flex-1 overflow-hidden">

                <!-- BODY -->
                <div class="flex-1 overflow-y-auto p-6">

                    <div class="mb-6">

                        <label class="block mb-2 text-sm font-semibold">

                            Filter Status

                        </label>

                        <select name="status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="">
                                Semua Data
                            </option>

                            <option value="Diajukan">
                                Diajukan
                            </option>

                            <option value="Tidak Diajukan">
                                Tidak Diajukan
                            </option>

                        </select>

                    </div>

                    <div class="flex flex-wrap gap-3 mb-6">

                        <button type="button" id="checkAll"
                            class="rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2 text-white">

                            <i class="bi bi-check2-square me-2"></i>

                            Centang Semua

                        </button>

                        <button type="button" id="uncheckAll"
                            class="rounded-xl bg-slate-600 hover:bg-slate-700 px-4 py-2 text-white">

                            <i class="bi bi-square me-2"></i>

                            Hapus Semua

                        </button>

                    </div>

                    <div class="max-h-[420px] overflow-y-auto pr-2">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

                            @php

                                $fields = [
                                    ['shs_tahun', 'Tahun', true],
                                    ['shs_unit_nama', 'Unit', true],
                                    ['shs_kode_kelompok', 'Kode Kelompok', true],
                                    ['shs_kelompok_barang', 'Kelompok Barang', true],
                                    ['shs_barang', 'Nama Barang', true],
                                    ['shs_merek', 'Merek', false],
                                    ['shs_tipe', 'Tipe / Model', false],
                                    ['shs_spesifikasi', 'Spesifikasi', false],
                                    ['shs_satuan', 'Satuan', false],
                                    ['shs_harga', 'Harga', false],
                                    ['shs_tkdn', 'TKDN', false],
                                    ['shs_link_survei', 'Link Survei', false],
                                    ['shs_kelompok', 'Kelompok SHS', false],
                                    ['shs_dasar_usulan', 'Dasar Usulan', false],
                                    ['shs_keterangan', 'Keterangan', false],
                                    ['shs_status', 'Status', false],
                                    ['shs_operator_nama', 'Operator', false],
                                    ['created_at', 'Tanggal Input', false],
                                ];

                            @endphp

                            @foreach ($fields as $field)
                                <label
                                    class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-slate-700 p-3 cursor-pointer transition">

                                    <input type="checkbox" class="field h-4 w-4 rounded text-blue-600" name="field[]"
                                        value="{{ $field[0] }}" {{ $field[2] ? 'checked' : '' }}>

                                    <span class="text-sm">

                                        {{ $field[1] }}

                                    </span>

                                </label>
                            @endforeach

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div
                    class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5 flex-shrink-0">

                    <button type="button" onclick="closeExportModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

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
    <!-- ======================= MODAL HISTORY ======================= -->
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

                <div id="historyContent" class="space-y-4">

                </div>

            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
        function showModal(id) {

            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.remove('hidden');

            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

        }

        function hideModal(id) {

            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.add('hidden');

            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }

        function detailSHS(item) {

            document.getElementById('d_tahun').value = item.shs_tahun ?? '';

            document.getElementById('d_unit').value = item.shs_unit_nama ?? '';

            document.getElementById('d_kelompok').value = item.shs_kelompok_barang ?? '';

            document.getElementById('d_kode').value = item.shs_kode_kelompok ?? '';

            document.getElementById('d_barang').value = item.shs_barang ?? '';

            document.getElementById('d_satuan').value = item.shs_satuan ?? '';

            document.getElementById('d_tipe').value = item.shs_kelompok_shs ?? '';

            document.getElementById('d_harga').value = 'Rp ' + Number(item.shs_harga ?? 0).toLocaleString('id-ID');

            document.getElementById('d_tkdn').value = item.shs_tkdn ?? '';

            document.getElementById('d_spesifikasi').value = item.shs_spesifikasi ?? '';

            document.getElementById('d_link').value = item.shs_link_survei ?? '';

            document.getElementById('d_operator').value = item.shs_operator_nama ?? '';

            document.getElementById('d_nip').value = item.shs_operator_nip ?? '';

            showModal('modalDetail');

        }

        function closeDetail() {

            hideModal('modalDetail');

        }

        function verifikasiSHS(item) {

            document.getElementById('v_barang').value = item.shs_barang ?? '';

            document.getElementById('v_harga').value = 'Rp ' + Number(item.shs_harga ?? 0).toLocaleString('id-ID');

            document.getElementById('formVerifikasi').action = '/administrator/laporan/shs/verifikasi/' + item.shs_uid;

            showModal('modalVerifikasi');

        }

        function closeVerifikasi() {

            document.getElementById('formVerifikasi').reset();

            hideModal('modalVerifikasi');

        }

        function openExportModal() {

            showModal('modalExport');

        }

        function closeExportModal() {

            hideModal('modalExport');

        }

        document.addEventListener('DOMContentLoaded', function() {

            $('#datatable').DataTable({

                responsive: true,

                autoWidth: false,

                pageLength: 25,

                order: [
                    [1, 'desc']
                ],

                language: {

                    search: "Cari :",

                    searchPlaceholder: "Cari data...",

                    lengthMenu: "Tampilkan _MENU_ data",

                    zeroRecords: "Data tidak ditemukan",

                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                    infoEmpty: "Tidak ada data",

                    infoFiltered: "(difilter dari _MAX_ data)",

                    paginate: {

                        first: "Awal",

                        last: "Akhir",

                        next: "›",

                        previous: "‹"

                    }

                }

            });

            [

                'modalDetail',

                'modalVerifikasi',

                'modalExport'

            ].forEach(function(id) {

                const modal = document.getElementById(id);

                if (!modal) return;

                modal.addEventListener('click', function(e) {

                    if (e.target === modal) {

                        hideModal(id);

                    }

                });

            });

        });

        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                closeDetail();

                closeVerifikasi();

                closeExportModal();

            }

        });
    </script>

    <script>
        function historySHS(data) {

            let html = '';

            if (data.history && data.history.length) {

                data.history.forEach(function(item, index) {

                    html += `
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5">

                    <div class="flex items-center justify-between mb-3">

                        <div class="font-semibold">

                            ${item.user}

                        </div>

                        <span class="text-xs text-slate-500">

                            ${item.tanggal}

                        </span>

                    </div>

                    <div class="mb-2">

                        <span class="inline-flex rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 px-3 py-1 text-xs">

                            ${item.status}

                        </span>

                    </div>

                    <div class="text-sm text-slate-600 dark:text-slate-300">

                        ${item.catatan ?? '-'}

                    </div>

                </div>
                `;

                });

            } else {

                html = `
            <div class="text-center py-16 text-slate-500">

                Belum ada riwayat verifikasi.

            </div>
            `;

            }

            document.getElementById('historyContent').innerHTML = html;

            showModal('modalHistory');

        }

        function closeHistory() {

            hideModal('modalHistory');

        }
    </script>

    <script>
        function showModal(id) {

            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.remove('hidden');

            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

        }

        function hideModal(id) {

            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.add('hidden');

            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }

        function detailSHS(item) {

            document.getElementById('d_tahun').value = item.shs_tahun ?? '';

            document.getElementById('d_unit').value = item.shs_unit_nama ?? '';

            document.getElementById('d_kelompok').value = item.shs_kelompok_barang ?? '';

            document.getElementById('d_kode').value = item.shs_kode_kelompok ?? '';

            document.getElementById('d_barang').value = item.shs_barang ?? '';

            document.getElementById('d_satuan').value = item.shs_satuan ?? '';

            document.getElementById('d_tipe').value = item.shs_kelompok_shs ?? '';

            document.getElementById('d_harga').value = 'Rp ' + Number(item.shs_harga ?? 0).toLocaleString('id-ID');

            document.getElementById('d_tkdn').value = item.shs_tkdn ?? '';

            document.getElementById('d_spesifikasi').value = item.shs_spesifikasi ?? '';

            document.getElementById('d_link').value = item.shs_link_survei ?? '';

            document.getElementById('d_operator').value = item.shs_operator_nama ?? '';

            document.getElementById('d_nip').value = item.shs_operator_nip ?? '';

            showModal('modalDetail');

        }

        function closeDetail() {

            hideModal('modalDetail');

        }

        function verifikasiSHS(item) {

            document.getElementById('v_barang').value = item.shs_barang ?? '';

            document.getElementById('v_harga').value = 'Rp ' + Number(item.shs_harga ?? 0).toLocaleString('id-ID');

            document.getElementById('formVerifikasi').action = '/administrator/laporan/shs/verifikasi/' + item.shs_uid;

            showModal('modalVerifikasi');

        }

        function closeVerifikasi() {

            document.getElementById('formVerifikasi').reset();

            hideModal('modalVerifikasi');

        }
        document.getElementById('checkAll').addEventListener('click', function() {
            document.querySelectorAll('.field').forEach(el => el.checked = true);
        });

        document.getElementById('uncheckAll').addEventListener('click', function() {
            document.querySelectorAll('.field').forEach(el => el.checked = false);
        });

        function openExportModal() {

            showModal('modalExport');

        }

        function closeExportModal() {

            hideModal('modalExport');

        }

        document.addEventListener('DOMContentLoaded', function() {

            $('#datatable').DataTable({

                responsive: true,

                autoWidth: false,

                pageLength: 25,

                order: [
                    [1, 'desc']
                ],

                language: {

                    search: "Cari :",

                    searchPlaceholder: "Cari data...",

                    lengthMenu: "Tampilkan _MENU_ data",

                    zeroRecords: "Data tidak ditemukan",

                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                    infoEmpty: "Tidak ada data",

                    infoFiltered: "(difilter dari _MAX_ data)",

                    paginate: {

                        first: "Awal",

                        last: "Akhir",

                        next: "›",

                        previous: "‹"

                    }

                }

            });

            [

                'modalDetail',

                'modalVerifikasi',

                'modalExport'

            ].forEach(function(id) {

                const modal = document.getElementById(id);

                if (!modal) return;

                modal.addEventListener('click', function(e) {

                    if (e.target === modal) {

                        hideModal(id);

                    }

                });

            });

        });

        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                closeDetail();

                closeVerifikasi();

                closeExportModal();

            }

        });
    </script>
@endpush

@push('styles')
    <style>
        #datatable_wrapper .dataTables_filter input {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .55rem .9rem;

        }

        .dark #datatable_wrapper .dataTables_filter input {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        #datatable_wrapper .dataTables_length select {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .45rem .75rem;

        }

        .dark #datatable_wrapper .dataTables_length select {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        table.dataTable tbody tr:hover {

            background: #f8fafc;

        }

        .dark table.dataTable tbody tr:hover {

            background: #1e293b;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {

            background: #2563eb !important;

            color: #fff !important;

            border: none !important;

            border-radius: 10px !important;

        }

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

            box-shadow: 0 0 0 3px rgb(37 99 235 /.15);

        }
    </style>

    <style>
        #datatable_wrapper .dataTables_filter input {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .55rem .9rem;

        }

        .dark #datatable_wrapper .dataTables_filter input {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        #datatable_wrapper .dataTables_length select {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .45rem .75rem;

        }

        .dark #datatable_wrapper .dataTables_length select {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        table.dataTable tbody tr:hover {

            background: #f8fafc;

        }

        .dark table.dataTable tbody tr:hover {

            background: #1e293b;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {

            background: #2563eb !important;

            color: #fff !important;

            border: none !important;

            border-radius: 10px !important;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {

            background: #1d4ed8 !important;

            color: #fff !important;

            border: none !important;

        }

        .dataTables_wrapper .dataTables_info {

            color: #64748b;

            margin-top: 12px;

        }

        .dark .dataTables_wrapper .dataTables_info {

            color: #94a3b8;

        }

        .dataTables_wrapper .dataTables_processing {

            border-radius: 16px;

            border: none;

            box-shadow: 0 15px 35px rgba(0, 0, 0, .15);

        }

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

            box-shadow: 0 0 0 3px rgb(37 99 235 /.15);

        }

        textarea {

            resize: vertical;

        }

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
    </style>

    <style>
        #datatable_wrapper .dataTables_filter input {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .55rem .9rem;

        }

        .dark #datatable_wrapper .dataTables_filter input {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        #datatable_wrapper .dataTables_length select {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .45rem .75rem;

        }

        .dark #datatable_wrapper .dataTables_length select {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        table.dataTable tbody tr:hover {

            background: #f8fafc;

        }

        .dark table.dataTable tbody tr:hover {

            background: #1e293b;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {

            background: #2563eb !important;

            color: #fff !important;

            border: none !important;

            border-radius: 10px !important;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {

            background: #1d4ed8 !important;

            color: #fff !important;

            border: none !important;

        }

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

            box-shadow: 0 0 0 3px rgb(37 99 235 /.15);

        }

        textarea {

            resize: vertical;

        }
    </style>
@endpush
