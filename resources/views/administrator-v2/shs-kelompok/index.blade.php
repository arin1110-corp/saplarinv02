@extends('administrator-v2.layouts.app')

@section('title', 'Master Kelompok Barang SHS')

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

            <div class="flex gap-3">

                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl mt-1"></i>

                <div>

                    <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">

                        Terjadi Kesalahan

                    </h4>

                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1">

                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">

        <button onclick="openModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold">

            <i class="bi bi-plus-circle"></i>

            Tambah Kelompok

        </button>

    </div>

    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table id="kelompokTable" class="display nowrap w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th>No</th>

                        <th>Kode Kelompok</th>

                        <th>Nama Kelompok</th>

                        <th>Jumlah SHS</th>

                        <th>Tipe Kelompok</th>

                        <th>Status</th>

                        <th>

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody class="bg-white dark:bg-slate-900" align="center">

                    @foreach ($kelompoks as $item)
                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                <span class="font-semibold text-blue-600 dark:text-blue-400">

                                    {{ $item->kelompok_kode }}

                                </span>

                            </td>

                            <td>

                                {{ $item->kelompok_nama }}

                            </td>

                            <td>

                                <span
                                    class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1 text-xs font-semibold">

                                    {{ $item->jumlah_shs }}

                                </span>

                            </td>

                            <td>

                                <span class="font-medium">

                                    {{ $item->kelompok_tipe }}

                                </span>

                            </td>

                            <td>

                                @if ($item->kelompok_status)
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                                        Aktif

                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 py-1 text-xs font-semibold">

                                        Nonaktif

                                    </span>
                                @endif

                            </td>

                            <td> 
                            
                                <div class="flex gap-2">

                                    <button onclick='openEditModal(@json($item))'
                                        class="w-10 h-10 rounded-xl bg-amber-500 hover:bg-amber-600 text-white">

                                        <i class="bi bi-pencil"></i>

                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.shs-kelompok.status', $item->kelompok_uid) }}">

                                        @csrf

                                        <button
                                            class="{{ $item->kelompok_status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} rounded-xl px-4 h-10 text-white text-sm">

                                            {{ $item->kelompok_status ? 'Nonaktif' : 'Aktifkan' }}

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
    {{-- MODAL TAMBAH --}}
    <div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl flex flex-col overflow-hidden">

            <div class="flex items-center justify-between px-7 py-5 border-b border-slate-200 dark:border-slate-700">

                <div>

                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                        Tambah Kelompok Barang SHS

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Master kelompok barang yang digunakan pada usulan SHS.

                    </p>

                </div>

                <button type="button" onclick="closeModal()"
                    class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.shs-kelompok.store') }}">

                @csrf

                <div class="px-7 py-6 space-y-5">

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Kode Kelompok

                        </label>

                        <input type="text" name="kelompok_kode" placeholder="Contoh : 01.01" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Nama Kelompok

                        </label>

                        <input type="text" name="kelompok_nama" placeholder="Contoh : Laptop" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Tipe Kelompok

                        </label>

                        <select name="kelompok_tipe" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="SHS">

                                SHS

                            </option>

                            <option value="HSPK">

                                HSPK

                            </option>

                            <option value="SBU">

                                SBU

                            </option>

                            <option value="ASB">

                                ASB

                            </option>

                        </select>

                    </div>

                </div>

                <div class="flex justify-end gap-3 px-7 py-5 border-t border-slate-200 dark:border-slate-700">

                    <button type="button" onclick="closeModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5">

                        <i class="bi bi-check-circle me-2"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>
    {{-- MODAL EDIT --}}
    <div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl flex flex-col overflow-hidden">

            <div class="flex items-center justify-between px-7 py-5 border-b border-slate-200 dark:border-slate-700">

                <div>

                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                        Edit Kelompok Barang SHS

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Perbarui data kelompok barang.

                    </p>

                </div>

                <button type="button" onclick="closeEditModal()"
                    class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.shs-kelompok.update') }}">

                @csrf

                <input type="hidden" id="edit_id" name="kelompok_id">

                <div class="px-7 py-6 space-y-5">

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Kode Kelompok

                        </label>

                        <input id="edit_kode" type="text" name="kelompok_kode" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Nama Kelompok

                        </label>

                        <input id="edit_nama" type="text" name="kelompok_nama" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Tipe Kelompok

                        </label>

                        <select id="edit_tipe" name="kelompok_tipe" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="SHS">

                                SHS

                            </option>

                            <option value="HSPK">

                                HSPK

                            </option>

                            <option value="SBU">

                                SBU

                            </option>

                            <option value="ASB">

                                ASB

                            </option>

                        </select>

                    </div>

                </div>

                <div class="flex justify-end gap-3 px-7 py-5 border-t border-slate-200 dark:border-slate-700">

                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5">

                        <i class="bi bi-save me-2"></i>

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>
    @push('scripts')
        <script>
            function openModal() {

                $('#modalTambah')
                    .removeClass('hidden')
                    .addClass('flex');

                $('body').addClass('overflow-hidden');

            }

            function closeModal() {

                $('#modalTambah')
                    .addClass('hidden')
                    .removeClass('flex');

                $('body').removeClass('overflow-hidden');

            }

            function openEditModal(item) {

                $('#edit_id').val(item.kelompok_id);

                $('#edit_kode').val(item.kelompok_kode);

                $('#edit_nama').val(item.kelompok_nama);

                $('#edit_tipe').val(item.kelompok_tipe);

                $('#modalEdit')
                    .removeClass('hidden')
                    .addClass('flex');

                $('body').addClass('overflow-hidden');

            }

            function closeEditModal() {

                $('#modalEdit')
                    .addClass('hidden')
                    .removeClass('flex');

                $('body').removeClass('overflow-hidden');

            }

            $('#modalTambah,#modalEdit').on('click', function(e) {

                if (e.target === this) {

                    $(this)
                        .addClass('hidden')
                        .removeClass('flex');

                    $('body').removeClass('overflow-hidden');

                }

            });

            $(document).keydown(function(e) {

                if (e.key === "Escape") {

                    closeModal();

                    closeEditModal();

                }

            });

            $(document).ready(function() {

                $('#kelompokTable').DataTable({

                    destroy: true,

                    responsive: false,

                    autoWidth: false,

                    scrollX: true,

                    pageLength: 10,

                    order: [
                        [0, 'asc']
                    ],

                    language: {

                        search: "Cari :",

                        searchPlaceholder: "Cari kelompok...",

                        lengthMenu: "Tampilkan _MENU_ data",

                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                        zeroRecords: "Data tidak ditemukan",

                        infoEmpty: "Belum ada data",

                        paginate: {

                            previous: "‹",

                            next: "›"

                        }

                    }

                });

            });
        </script>
    @endpush

    @push('styles')
        <style>
            #kelompokTable {
                border-collapse: separate !important;
                border-spacing: 0 !important;
            }

            #kelompokTable th,
            #kelompokTable td {
                display: table-cell !important;
                vertical-align: middle !important;
                white-space: nowrap;
                padding: 16px 18px;
            }

            #kelompokTable td:last-child {
                white-space: nowrap;
            }

            #kelompokTable .flex {
                display: flex;
            }

            #kelompokTable form {
                display: inline-block;
            }

            #kelompokTable {

                width: 100% !important;

            }

            #kelompokTable th {

                white-space: nowrap;

                font-weight: 700;

            }

            #kelompokTable td {

                vertical-align: middle;

            }

            table.dataTable tbody tr:hover {

                background: #f8fafc !important;

            }

            .dark table.dataTable tbody tr:hover {

                background: #1e293b !important;

            }

            .dataTables_wrapper .dataTables_filter input {

                border-radius: 14px;

                border: 1px solid rgb(203 213 225);

                padding: .65rem .9rem;

            }

            .dark .dataTables_wrapper .dataTables_filter input {

                background: #0f172a;

                border-color: #334155;

                color: #fff;

            }

            .dataTables_wrapper .dataTables_length select {

                border-radius: 14px;

                border: 1px solid rgb(203 213 225);

                padding: .55rem .75rem;

            }

            .dark .dataTables_wrapper .dataTables_length select {

                background: #0f172a;

                border-color: #334155;

                color: #fff;

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
        </style>
    @endpush

@endsection
