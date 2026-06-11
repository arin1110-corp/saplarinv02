<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Saplarin - Kelola Sub Kegiatan</title>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DATATABLE -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            background: #0f172a;
        }

        table.dataTable {
            color: #e2e8f0 !important;
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
            border-radius: 8px !important;
            color: white !important;
        }

        table.dataTable tbody tr {
            background-color: #1e293b !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #334155 !important;
        }

        input,
        select {
            background: #1e293b;
            border: 1px solid #334155;
            color: white;
        }
    </style>
</head>

<body class="text-white">

    <div class="flex min-h-screen">

        @include('administrator.partials.sidebar')

        <div class="flex-1 p-6">

            @include('administrator.partials.header')

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">

                <div>
                    <h1 class="text-2xl font-bold">
                        Kelola Sub Kegiatan
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Kelola data sub kegiatan SAPLARIN
                    </p>
                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">

                    + Tambah Sub Kegiatan
                </button>

            </div>

            <!-- TABLE -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

                <table id="kegiatanTable" class="display w-full text-sm">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Program</th>
                            <th>Kegiatan</th>
                            <th>Kode Sub Kegiatan</th>
                            <th>Kode Rekening Sub Kegiatan</th>
                            <th>Nama Sub Kegiatan</th>
                            <th>Status</th>
                            <th width="120">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($subkegiatans as $subkegiatan)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $subkegiatan->program_kode }} - {{ $subkegiatan->program_nama }}
                                </td>
                                <td>
                                    {{ $subkegiatan->kegiatan_kode }} -{{ $subkegiatan->kegiatan_nama }}
                                </td>

                                <td>
                                    {{ $subkegiatan->sub_kegiatan_kode }}
                                </td>

                                <td>
                                    {{ $subkegiatan->sub_kegiatan_kode_rekening }}
                                </td>

                                <td>
                                    {{ $subkegiatan->sub_kegiatan_nama }}
                                </td>

                                <td>
                                    {{ $subkegiatan->sub_kegiatan_status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </td>

                                <td>

                                    <button onclick='openEditModal(@json($subkegiatan))'
                                        class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">

                                        Edit
                                    </button>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="kegiatanModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Tambah Sub Kegiatan
                </h2>

                <button onclick="closeModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.subkegiatan.store') }}">

                @csrf

                <!-- KEGIATAN -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kegiatan
                    </label>

                    <select name="sub_kegiatan_kegiatan" class="w-full rounded-xl px-4 py-3" required>

                        <option value="">
                            Pilih Kegiatan
                        </option>

                        @foreach ($kegiatans as $kegiatan)
                            <option value="{{ $kegiatan->kegiatan_id }}">
                                {{ $kegiatan->kegiatan_kode }} - {{ $kegiatan->kegiatan_nama }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <!-- KODE SUB KEGIATAN -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kode Sub Kegiatan
                    </label>

                    <input type="text" name="sub_kegiatan_kode" class="w-full rounded-xl px-4 py-3" required>

                </div>

                <!-- KODE REKENING -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kode Rekening Sub Kegiatan
                    </label>

                    <input type="text" name="sub_kegiatan_kode_rekening" class="w-full rounded-xl px-4 py-3" required>

                </div>

                <!-- NAMA -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Nama Sub Kegiatan
                    </label>

                    <input type="text" name="sub_kegiatan_nama" class="w-full rounded-xl px-4 py-3" required>

                </div>

                <!-- STATUS -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Status
                    </label>

                    <select name="sub_kegiatan_status" class="w-full rounded-xl px-4 py-3" required>

                        <option value="">
                            Pilih Status
                        </option>

                        <option value="1">
                            Aktif
                        </option>

                        <option value="0">
                            Nonaktif
                        </option>

                    </select>

                </div>

                <div class="flex justify-end gap-2 mt-6">

                    <button type="button" onclick="closeModal()" class="bg-slate-700 px-4 py-2 rounded-xl">

                        Batal
                    </button>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl">

                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editKegiatanModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Edit Sub Kegiatan
                </h2>

                <button onclick="closeEditModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.subkegiatan.update') }}">

                @csrf

                <input type="hidden" id="edit_sub_kegiatan_id" name="sub_kegiatan_id">

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kegiatan
                    </label>

                    <select id="edit_sub_kegiatan_program" name="sub_kegiatan_kegiatan"
                        class="w-full rounded-xl px-4 py-3" required>

                        @foreach ($kegiatans as $kegiatan)
                            <option value="{{ $kegiatan->kegiatan_id }}">
                                {{ $kegiatan->kegiatan_kode }} - {{ $kegiatan->kegiatan_nama }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kode Sub Kegiatan
                    </label>

                    <input type="text" id="edit_sub_kegiatan_kode" name="sub_kegiatan_kode"
                        class="w-full rounded-xl px-4 py-3" required>

                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kode Rekening Sub Kegiatan
                    </label>

                    <input type="text" id="edit_sub_kegiatan_kode_rekening" name="sub_kegiatan_kode_rekening"
                        class="w-full rounded-xl px-4 py-3" required>

                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Nama Sub Kegiatan
                    </label>

                    <input type="text" id="edit_sub_kegiatan_nama" name="sub_kegiatan_nama"
                        class="w-full rounded-xl px-4 py-3" required>

                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Status
                    </label>

                    <select id="edit_sub_kegiatan_status" name="sub_kegiatan_status" class="w-full rounded-xl px-4 py-3"
                        required>

                        <option value="1">
                            Aktif
                        </option>

                        <option value="0">
                            Nonaktif
                        </option>

                    </select>

                </div>

                <div class="flex justify-end gap-2 mt-6">

                    <button type="button" onclick="closeEditModal()" class="bg-slate-700 px-4 py-2 rounded-xl">
                        Batal
                    </button>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>

    <script>
        function openModal() {
            $('#kegiatanModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeModal() {
            $('#kegiatanModal')
                .addClass('hidden')
                .removeClass('flex');
        }

        function openEditModal(kegiatan) {

            $('#edit_sub_kegiatan_id')
                .val(kegiatan.sub_kegiatan_id);

            $('#edit_sub_kegiatan_program')
                .val(kegiatan.sub_kegiatan_kegiatan);

            $('#edit_sub_kegiatan_nama')
                .val(kegiatan.sub_kegiatan_nama);

            $('#edit_sub_kegiatan_kode')
                .val(kegiatan.sub_kegiatan_kode);

            $('#edit_sub_kegiatan_kode_rekening')
                .val(kegiatan.sub_kegiatan_kode_rekening);

            $('#edit_sub_kegiatan_status')
                .val(kegiatan.sub_kegiatan_status);

            $('#editKegiatanModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeEditModal() {
            $('#editKegiatanModal')
                .addClass('hidden')
                .removeClass('flex');
        }

        $(document).ready(function() {

            $('#kegiatanTable')
                .DataTable({
                    pageLength: 10,
                    responsive: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        emptyTable: "Belum ada data kegiatan"
                    }
                });

        });
    </script>

</body>

</html>
