<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Admin Saplarin - Kelola Program</title>
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

        input {
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
                        Kelola Program
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Kelola data program SAPLARIN
                    </p>
                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">

                    + Tambah Program
                </button>

            </div>

            <!-- TABLE -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

                <table id="programTable" class="display w-full text-sm">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Program</th>
                            <th>Status</th>
                            <th width="120">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($programs as $program)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $program->program_nama }}
                                </td>

                                <td>
                                    {{ $program->program_status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </td>

                                <td>

                                    <button onclick='openEditModal(@json($program))'
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
    <div id="programModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Tambah Program
                </h2>

                <button onclick="closeModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.program.store') }}">

                @csrf

                <div class="mb-4">
                    <label class="block mb-2 text-sm">
                        Nama Program
                    </label>

                    <input type="text" name="program_nama" class="w-full rounded-xl px-4 py-3" required>
                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Status Program
                    </label>

                    <select name="program_status" id="programStatus"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>

                        <option value="" disabled selected>
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
    <div id="editProgramModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Edit Program
                </h2>

                <button onclick="closeEditModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.program.update') }}">

                @csrf

                <input type="hidden" id="edit_program_id" name="program_id">

                <div class="mb-4">
                    <label class="block mb-2 text-sm">
                        Nama Program
                    </label>

                    <input type="text" id="edit_program_nama" name="program_nama" class="w-full rounded-xl px-4 py-3"
                        required>
                </div>

                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Status Program
                    </label>

                    <select id="edit_program_status" name="program_status"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>

                        <option value="" disabled selected>
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
    @include('administrator.partials.modal')

    <script>
        function openModal() {
            $('#programModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeModal() {
            $('#programModal')
                .addClass('hidden')
                .removeClass('flex');
        }

        function openEditModal(program) {

            $('#edit_program_id')
                .val(program.program_id);

            $('#edit_program_uid')
                .val(program.program_uid);

            $('#edit_program_nama')
                .val(program.program_nama);

            $('#edit_program_status')
                .val(program.program_status);

            $('#editProgramModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeEditModal() {
            $('#editProgramModal')
                .addClass('hidden')
                .removeClass('flex');
        }

        $(document).ready(function() {

            $('#programTable')
                .DataTable({
                    pageLength: 10,
                    responsive: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        emptyTable: "Belum ada data program"
                    }
                });

        });
        $(document).ready(function() {

            $('#pegawaiSelect').select2({
                dropdownParent: $('#addUserModal'),
                width: '100%',
                placeholder: 'Cari Nama / NIP / NIK',
                allowClear: true
            });

        });
    </script>

</body>

</html>
