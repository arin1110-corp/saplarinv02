@extends('administrator-v2.layouts.app')

@section('title', 'Master Satuan SHS')

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

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
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

            Tambah Satuan

        </button>

    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table id="satuanTable" class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="px-4 py-4 text-left">No</th>

                        <th class="px-4 py-4 text-left">Satuan</th>

                        <th class="pth4 py-4 text-left">Status</th>

                        <th width="170">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($satuan as $item)
                        <tr>

                            <td  class="px-4 py-4">

                                {{ $loop->iteration }}

                            </td>

                            <td class="px-4 py-4">

                                <span class="font-semibold text-blue-600 dark:text-blue-400">

                                    {{ $item->satuan_nama }}

                                </span>

                            </td>

                            <td class="px-4 py-4">

                                @if ($item->satuan_status)
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

                                <div class="flex items-center gap-2">

                                    <button onclick='openEditModal(@json($item))'
                                        class="w-10 h-10 rounded-xl bg-amber-500 hover:bg-amber-600 text-white flex items-center justify-center">

                                        <i class="bi bi-pencil"></i>

                                    </button>

                                    <form method="POST" action="{{ route('admin.shs-satuan.status', $item->satuan_uid) }}">

                                        @csrf

                                        <button
                                            class="{{ $item->satuan_status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} rounded-xl px-4 h-10 text-white text-sm">

                                            {{ $item->satuan_status ? 'Nonaktif' : 'Aktifkan' }}

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

                        Tambah Satuan SHS

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Tambahkan master satuan yang digunakan operator.

                    </p>

                </div>

                <button type="button" onclick="closeModal()"
                    class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.shs-satuan.store') }}">

                @csrf

                <div class="px-7 py-6">

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Nama Satuan

                        </label>

                        <input type="text" name="satuan_nama" placeholder="Contoh : Unit" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

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

                        Edit Satuan SHS

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Perbarui nama satuan.

                    </p>

                </div>

                <button type="button" onclick="closeEditModal()"
                    class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            <form method="POST" id="formEdit">

                @csrf
                @method('PUT')

                <div class="px-7 py-6">

                    <div>

                        <label class="block text-sm font-semibold mb-2">

                            Nama Satuan

                        </label>

                        <input id="edit_nama" type="text" name="satuan_nama" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

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
                    .removeClass('flex')
                    .addClass('hidden');

                $('body').removeClass('overflow-hidden');

            }

            function openEditModal(item) {

                $('#edit_nama').val(item.satuan_nama);

                let url = "{{ route('admin.shs-satuan.update', ':uid') }}";

                url = url.replace(':uid', item.satuan_uid);

                $('#formEdit').attr('action', url);

                $('#modalEdit')
                    .removeClass('hidden')
                    .addClass('flex');

                $('body').addClass('overflow-hidden');

            }

            function closeEditModal() {

                $('#modalEdit')
                    .removeClass('flex')
                    .addClass('hidden');

                $('body').removeClass('overflow-hidden');

            }

            $('#modalTambah,#modalEdit').on('click', function(e) {

                if (e.target === this) {

                    $(this)
                        .removeClass('flex')
                        .addClass('hidden');

                    $('body').removeClass('overflow-hidden');

                }

            });

            $(document).keydown(function(e) {

                if (e.key === 'Escape') {

                    closeModal();

                    closeEditModal();

                }

            });

            $(document).ready(function() {

                $('#satuanTable').DataTable({

                    responsive: true,

                    autoWidth: false,

                    pageLength: 10,

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

            });
        </script>
    @endpush

    @push('styles')
        <style>
            #satuanTable_wrapper .dataTables_filter input {

                border-radius: 12px;

                border: 1px solid rgb(203 213 225);

                padding: .55rem .9rem;

            }

            .dark #satuanTable_wrapper .dataTables_filter input {

                background: #0f172a;

                border-color: #334155;

                color: white;

            }

            #satuanTable_wrapper .dataTables_length select {

                border-radius: 12px;

                border: 1px solid rgb(203 213 225);

                padding: .45rem .75rem;

            }

            .dark #satuanTable_wrapper .dataTables_length select {

                background: #0f172a;

                border-color: #334155;

                color: white;

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
            select {

                transition: .2s;

            }

            input:focus,
            select:focus {

                outline: none;

                border-color: #2563eb;

                box-shadow: 0 0 0 3px rgb(37 99 235 / .15);

            }
        </style>
    @endpush

@endsection
