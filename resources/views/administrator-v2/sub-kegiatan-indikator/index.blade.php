@extends('administrator-v2.layouts.app')

@section('title', 'Indikator Sub Kegiatan')

@section('page-title', 'Indikator Sub Kegiatan')

@section('page-description', 'Kelola indikator sub kegiatan')

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

        <form method="GET">

            <div class="relative w-full lg:w-80">

                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Pengaju / NIP / Plat..."
                    class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 pl-11 pr-4 py-3">

            </div>

        </form>
        <button type="button" onclick="openModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold transition">

            <i class="bi bi-plus-circle"></i>

            Tambah Indikator

        </button>

    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="px-4 py-4">No</th>

                        <th class="px-4 py-4">Unit</th>

                        <th class="px-4 py-4">Sub Kegiatan</th>

                        <th class="px-4 py-4">Indikator</th>

                        <th class="px-4 py-4">Target</th>

                        <th class="px-4 py-4">Satuan</th>

                        <th class="px-4 py-4">Status</th>

                        <th class="px-4 py-4 text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($indikators as $item)
                        <tr class="border-t border-slate-200 dark:border-slate-800">

                            <td class="px-4 py-4">

                                {{ $loop->iteration }}

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-semibold text-blue-600">

                                    {{ $item->indikator_unit_kode }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $item->indikator_unit_nama }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-semibold">

                                    {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $item->subKegiatan->sub_kegiatan_kode ?? '-' }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                {{ $item->indikator_nama }}

                            </td>

                            <td class="px-4 py-4 font-semibold">

                                {{ number_format($item->indikator_target, 2, ',', '.') }}

                            </td>

                            <td class="px-4 py-4">

                                {{ $item->indikator_satuan }}

                            </td>

                            <td class="px-4 py-4">

                                @if ($item->indikator_status)
                                    <span
                                        class="inline-flex rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">

                                        Aktif

                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">

                                        Nonaktif

                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-4">

                                <div class="flex justify-center gap-2">

                                    <button onclick='openEditModal(@json($item))'
                                        class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-2 text-xs text-white">

                                        <i class="bi bi-pencil-square"></i>

                                        Edit

                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.sub-kegiatan-indikator.delete', $item->indikator_uid) }}"
                                        onsubmit="return confirm('Hapus indikator ini?')">

                                        @csrf

                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-3 py-2 text-xs text-white">

                                            <i class="bi bi-trash"></i>

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="py-10 text-center text-slate-500">

                                Belum ada indikator.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div
            class="flex flex-col md:flex-row items-center justify-between gap-4 px-6 py-5 border-t border-slate-200 dark:border-slate-700">

            <div class="text-sm text-slate-500">

                Menampilkan

                <b>{{ $indikators->firstItem() }}</b>

                -

                <b>{{ $indikators->lastItem() }}</b>

                dari

                <b>{{ $indikators->total() }}</b>

                data

            </div>

            {{ $indikators->links() }}

        </div>

    </div>
    <!-- ======================= MODAL TAMBAH ======================= -->
    <div id="indikatorModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Tambah Indikator

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Indikator akan digunakan pada laporan operator.

                    </p>

                </div>

                <button type="button" onclick="closeModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.sub-kegiatan-indikator.store') }}"
                class="flex flex-col flex-1 overflow-hidden">

                @csrf

                <div class="flex-1 overflow-y-auto p-6 space-y-5">

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Unit

                        </label>

                        <select id="indikator_unit_kode" name="indikator_unit_kode" onchange="setIndikatorUnitNama()"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="">Pilih Unit</option>

                            <option value="DISBUD" data-nama="Dinas Kebudayaan Provinsi Bali">

                                DISBUD

                            </option>

                            <option value="UPTD-MB" data-nama="UPTD Museum Bali">

                                UPTD Museum Bali

                            </option>

                            <option value="UPTD-MPRB" data-nama="UPTD Monumen Perjuangan Rakyat Bali">

                                UPTD Monumen Perjuangan Rakyat Bali

                            </option>

                            <option value="UPTD-TB" data-nama="UPTD Taman Budaya">

                                UPTD Taman Budaya

                            </option>

                        </select>

                        <input type="hidden" id="indikator_unit_nama" name="indikator_unit_nama">

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Sub Kegiatan

                        </label>

                        <select name="indikator_sub_kegiatan_id"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="">Pilih Sub Kegiatan</option>

                            @foreach ($subKegiatans as $sub)
                                <option value="{{ $sub->sub_kegiatan_id }}">

                                    {{ $sub->sub_kegiatan_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Nama Indikator

                        </label>

                        <textarea name="indikator_nama" rows="4"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required></textarea>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Target

                            </label>

                            <input type="number" step="0.01" name="indikator_target"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Satuan

                            </label>

                            <input type="text" name="indikator_satuan"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-white font-semibold">

                        <i class="bi bi-check-circle me-2"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>
    <!-- ======================= MODAL EDIT ======================= -->
    <div id="editIndikatorModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Edit Indikator

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Perbarui indikator, target, satuan dan status.

                    </p>

                </div>

                <button type="button" onclick="closeEditModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.sub-kegiatan-indikator.update') }}"
                class="flex flex-col flex-1 overflow-hidden">

                @csrf

                <input type="hidden" id="edit_indikator_id" name="indikator_id">

                <div class="flex-1 overflow-y-auto p-6 space-y-5">

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Unit

                        </label>

                        <select id="edit_indikator_unit_kode" name="indikator_unit_kode"
                            onchange="setEditIndikatorUnitNama()"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="">Pilih Unit</option>

                            <option value="DISBUD" data-nama="Dinas Kebudayaan Provinsi Bali">

                                DISBUD

                            </option>

                            <option value="UPTD-MB" data-nama="UPTD Museum Bali">

                                UPTD Museum Bali

                            </option>

                            <option value="UPTD-MPRB" data-nama="UPTD Monumen Perjuangan Rakyat Bali">

                                UPTD Monumen Perjuangan Rakyat Bali

                            </option>

                            <option value="UPTD-TB" data-nama="UPTD Taman Budaya">

                                UPTD Taman Budaya

                            </option>

                        </select>

                        <input type="hidden" id="edit_indikator_unit_nama" name="indikator_unit_nama">

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Sub Kegiatan

                        </label>

                        <select id="edit_indikator_sub_kegiatan_id" name="indikator_sub_kegiatan_id"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            @foreach ($subKegiatans as $sub)
                                <option value="{{ $sub->sub_kegiatan_id }}">

                                    {{ $sub->sub_kegiatan_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Nama Indikator

                        </label>

                        <textarea id="edit_indikator_nama" name="indikator_nama" rows="4"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required></textarea>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Target

                            </label>

                            <input type="number" step="0.01" id="edit_indikator_target" name="indikator_target"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Satuan

                            </label>

                            <input type="text" id="edit_indikator_satuan" name="indikator_satuan"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Status

                        </label>

                        <select id="edit_indikator_status" name="indikator_status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="1">

                                Aktif

                            </option>

                            <option value="0">

                                Nonaktif

                            </option>

                        </select>

                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-white font-semibold">

                        <i class="bi bi-save me-2"></i>

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>
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

        function openModal() {

            showModal('indikatorModal');

        }

        function closeModal() {

            document.querySelector('#indikatorModal form').reset();

            hideModal('indikatorModal');

        }

        function openEditModal(item) {

            document.getElementById('edit_indikator_id').value = item.indikator_id;

            document.getElementById('edit_indikator_unit_kode').value = item.indikator_unit_kode;

            document.getElementById('edit_indikator_unit_nama').value = item.indikator_unit_nama;

            document.getElementById('edit_indikator_sub_kegiatan_id').value = item.indikator_sub_kegiatan_id;

            document.getElementById('edit_indikator_nama').value = item.indikator_nama;

            document.getElementById('edit_indikator_target').value = item.indikator_target;

            document.getElementById('edit_indikator_satuan').value = item.indikator_satuan;

            document.getElementById('edit_indikator_status').value = item.indikator_status;

            showModal('editIndikatorModal');

        }

        function closeEditModal() {

            hideModal('editIndikatorModal');

        }

        function setIndikatorUnitNama() {

            const select = document.getElementById('indikator_unit_kode');

            const selected = select.options[select.selectedIndex];

            document.getElementById('indikator_unit_nama').value = selected.dataset.nama ?? '';

        }

        function setEditIndikatorUnitNama() {

            const select = document.getElementById('edit_indikator_unit_kode');

            const selected = select.options[select.selectedIndex];

            document.getElementById('edit_indikator_unit_nama').value = selected.dataset.nama ?? '';

        }

        document.addEventListener('DOMContentLoaded', function() {

            $('#indikatorTable').DataTable({

                responsive: true,

                autoWidth: false,

                pageLength: 25,

                order: [
                    [0, 'asc']
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

                'indikatorModal',

                'editIndikatorModal'

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

                closeModal();

                closeEditModal();

            }

        });
    </script>

    <style>
        #indikatorTable_wrapper .dataTables_filter input {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .55rem .9rem;

        }

        .dark #indikatorTable_wrapper .dataTables_filter input {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        #indikatorTable_wrapper .dataTables_length select {

            border-radius: 12px;

            border: 1px solid rgb(203 213 225);

            padding: .45rem .75rem;

        }

        .dark #indikatorTable_wrapper .dataTables_length select {

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

@endsection
