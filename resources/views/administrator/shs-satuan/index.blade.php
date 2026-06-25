<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Master Satuan SHS</title>

    <link rel="icon" href="{{ asset('image/pemprov.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>


    <style>
        body {
            background: #020617;
        }

        table.dataTable {
            color: #e2e8f0 !important;
        }

        table.dataTable thead th {
            color: #cbd5e1 !important;
            white-space: nowrap;
        }

        table.dataTable tbody tr {
            background: #0f172a !important;
        }

        table.dataTable tbody tr:hover {
            background: #1e293b !important;
        }

        .dataTables_wrapper {
            color: #cbd5e1;
        }

        .dataTables_filter input,
        .dataTables_length select {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            color: white !important;
            border-radius: 10px;
            padding: 6px 10px;
        }

        .dataTables_paginate .paginate_button.current {
            background: #2563eb !important;
            border: none !important;
            color: white !important;
            border-radius: 8px;
        }

        input,
        select,
        textarea {
            background: #1e293b;
            border: 1px solid #334155;
            color: white;
        }
    </style>

</head>

<body class="text-white">

    <div class="flex min-h-screen">

        @include('administrator.partials.sidebar')

        <div class="flex-1 p-6 overflow-x-hidden">

            @include('administrator.partials.header')

            @if (session('success'))
                <div class="mb-5 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-xl">

                    {{ session('success') }}

                </div>
            @endif

            @if ($errors->any())

                <div class="mb-5 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-xl">

                    <ul class="list-disc list-inside text-sm">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <div class="flex justify-between items-center mb-6">

                <div>

                    <h1 class="text-2xl font-bold">

                        Master Satuan SHS

                    </h1>

                    <p class="text-slate-400 text-sm">

                        Kelola master satuan yang digunakan operator.

                    </p>

                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl font-semibold">

                    + Tambah Satuan

                </button>

            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 overflow-x-auto">

                <table id="satuanTable" class="display nowrap w-full text-sm">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Satuan</th>

                            <th>Status</th>

                            <th width="170">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($satuan as $item)
                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    <span class="font-semibold text-blue-300">

                                        {{ $item->satuan_nama }}

                                    </span>

                                </td>



                                <td>

                                    @if ($item->satuan_status)
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full">

                                            Aktif

                                        </span>
                                    @else
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full">

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="flex gap-2">

                                        <button onclick='openEditModal(@json($item))'
                                            class="bg-amber-500 hover:bg-amber-600 px-3 py-2 rounded-lg text-sm">

                                            Edit

                                        </button>

                                        <form method="POST"
                                            action="{{ route('admin.shs.satuan.status', $item->satuan_uid) }}">

                                            @csrf

                                            <button
                                                class="{{ $item->satuan_status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} px-3 py-2 rounded-lg text-sm">

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

    </div>
    {{-- ================= MODAL TAMBAH ================= --}}

    <div id="modalTambah" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-6">

                <div>

                    <h2 class="text-xl font-bold">

                        Tambah Satuan

                    </h2>

                    <p class="text-sm text-slate-400">

                        Tambahkan master satuan yang dapat dipilih operator.

                    </p>

                </div>

                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-white text-2xl">

                    ×

                </button>

            </div>

            <form method="POST" action="{{ route('admin.shs.satuan.store') }}">

                @csrf

                <div>

                    <label class="block text-sm mb-2">

                        Nama Satuan

                    </label>

                    <input type="text" name="satuan_nama"
                        class="w-full rounded-xl bg-slate-800 border border-slate-700 px-4 py-3"
                        placeholder="Contoh : Unit" required>

                </div>

                <div class="flex justify-end gap-3 mt-8">

                    <button type="button" onclick="closeModal()"
                        class="bg-slate-700 hover:bg-slate-600 px-5 py-2 rounded-xl">

                        Batal

                    </button>

                    <button class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-xl">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ================= MODAL EDIT ================= --}}

    <div id="modalEdit" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-6">

                <div>

                    <h2 class="text-xl font-bold">

                        Edit Satuan

                    </h2>

                    <p class="text-sm text-slate-400">

                        Perbarui nama satuan.

                    </p>

                </div>

                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-white text-2xl">

                    ×

                </button>

            </div>

            <form method="POST" id="formEdit">

                @csrf

                @method('PUT')

                <div>

                    <label class="block text-sm mb-2">

                        Nama Satuan

                    </label>

                    <input id="edit_nama" type="text" name="satuan_nama"
                        class="w-full rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" required>

                </div>

                <div class="flex justify-end gap-3 mt-8">

                    <button type="button" onclick="closeEditModal()"
                        class="bg-slate-700 hover:bg-slate-600 px-5 py-2 rounded-xl">

                        Batal

                    </button>

                    <button class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-xl">

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        $(document).ready(function() {

            $('#satuanTable').DataTable({

                responsive: true,

                scrollX: true,

                autoWidth: false,

                pageLength: 10,

                language: {

                    search: "Cari :",

                    lengthMenu: "Tampilkan _MENU_ data",

                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                    zeroRecords: "Data tidak ditemukan",

                    emptyTable: "Belum ada data kelompok barang",

                    paginate: {

                        previous: "Sebelumnya",

                        next: "Berikutnya"

                    }

                }

            });
        });

        function openModal() {

            $('#modalTambah').removeClass('hidden').addClass('flex');

        }

        function closeModal() {

            $('#modalTambah').removeClass('flex').addClass('hidden');

        }

        function openEditModal(item) {

            $('#edit_nama').val(item.satuan_nama);

            let url = "{{ route('admin.shs.satuan.update', ':uid') }}";

            url = url.replace(':uid', item.satuan_uid);

            $('#formEdit').attr('action', url);

            $('#modalEdit').removeClass('hidden').addClass('flex');

        }

        function closeEditModal() {

            $('#modalEdit').removeClass('flex').addClass('hidden');

        }
    </script>

</body>

</html>
