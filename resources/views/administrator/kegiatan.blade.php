<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Saplarin - Kelola Kegiatan</title>
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
                        Kelola Kegiatan
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Kelola data kegiatan SAPLARIN
                    </p>
                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">

                    + Tambah Kegiatan
                </button>

            </div>

            <!-- TABLE -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

                <table id="kegiatanTable" class="display w-full text-sm">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Program</th>
                            <th>Kode Rekening Kegiatan</th>
                            <th>Nama Kegiatan</th>
                            <th>Status</th>
                            <th width="120">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($kegiatans as $kegiatan)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $kegiatan->program_kode }}
                                -
                                    {{ $kegiatan->program_nama }}
                                </td>

                                <td>
                                    {{ $kegiatan->kegiatan_kode}}
                                </td>

                                <td>
                                    {{ $kegiatan->kegiatan_nama }}
                                </td>

                                <td>
                                    {{ $kegiatan->kegiatan_status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </td>

                                <td>

                                    <button onclick='openEditModal(@json($kegiatan))'
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
                    Tambah Kegiatan
                </h2>

                <button onclick="closeModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.kegiatan.store') }}">

                @csrf

                <!-- PROGRAM -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Program
                    </label>

                    <select name="kegiatan_program" class="w-full rounded-xl px-4 py-3" required>

                        <option value="">
                            Pilih Program
                        </option>

                        @foreach ($programs as $program)
                            <option value="{{ $program->program_id }}">
                                {{ $program->program_kode }} - {{ $program->program_nama }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <!-- NAMA -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Nama Kegiatan
                    </label>

                    <input type="text" name="kegiatan_nama" class="w-full rounded-xl px-4 py-3" required>

                </div>

                <!-- KODE REKENING -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kode Rekening
                    </label>

                    <input type="text" name="kegiatan_kode" class="w-full rounded-xl px-4 py-3" required>

                </div>

                <!-- STATUS -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Status
                    </label>

                    <select name="kegiatan_status" class="w-full rounded-xl px-4 py-3" required>

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
                    Edit Kegiatan
                </h2>

                <button onclick="closeEditModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.kegiatan.update') }}">

                @csrf

                <input type="hidden" id="edit_kegiatan_id" name="kegiatan_id">

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Program
                    </label>

                    <select id="edit_kegiatan_program" name="kegiatan_program" class="w-full rounded-xl px-4 py-3"
                        required>

                        @foreach ($programs as $program)
                            <option value="{{ $program->program_id }}">
                                {{ $program->program_kode }} - {{ $program->program_nama }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Nama Kegiatan
                    </label>

                    <input type="text" id="edit_kegiatan_nama" name="kegiatan_nama"
                        class="w-full rounded-xl px-4 py-3" required>

                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Kode Rekening
                    </label>

                    <input type="text" id="edit_kegiatan_kode_rekening" name="kegiatan_kode"
                        class="w-full rounded-xl px-4 py-3" required>

                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Status
                    </label>

                    <select id="edit_kegiatan_status" name="kegiatan_status" class="w-full rounded-xl px-4 py-3"
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

            $('#edit_kegiatan_id')
                .val(kegiatan.kegiatan_id);

            $('#edit_kegiatan_program')
                .val(kegiatan.kegiatan_program);

            $('#edit_kegiatan_nama')
                .val(kegiatan.kegiatan_nama);

            $('#edit_kegiatan_kode_rekening')
                .val(kegiatan.kegiatan_kode);

            $('#edit_kegiatan_status')
                .val(kegiatan.kegiatan_status);

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
