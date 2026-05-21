<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body {
            background: #0f172a;
        }

        /* DataTable Dark */
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

        .dataTables_paginate .paginate_button {
            color: white !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: #2563eb !important;
            border-radius: 8px !important;
            border: none !important;
            color: white !important;
        }

        table.dataTable thead th {
            border-bottom: 1px solid #334155 !important;
        }

        table.dataTable tbody tr {
            background-color: #1e293b !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #334155 !important;
        }


        /* SELECT2 DARK MODE */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            border-radius: 14px !important;
            height: 52px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9 !important;
            line-height: normal !important;
            padding-left: 0 !important;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
            right: 12px !important;
        }

        /* dropdown result */
        .select2-dropdown {
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            border-radius: 14px !important;
            overflow: hidden;
        }

        .select2-search__field {
            background: #1e293b !important;
            color: white !important;
            border: 1px solid #334155 !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }

        .select2-results__option {
            color: #e2e8f0 !important;
            padding: 12px !important;
            font-size: 14px;
        }

        .select2-results__option--highlighted {
            background: #2563eb !important;
            color: white !important;
        }

        /* mobile responsive */
        @media (max-width: 640px) {
            .select2-container--default .select2-selection--single {
                height: 48px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                font-size: 13px !important;
            }
        }
    </style>
</head>

<body class="text-white">

    <div class="flex min-h-screen">

        @include('administrator.partials.sidebar')

        <!-- CONTENT -->
        <div class="flex-1 p-6">

            @include('administrator.partials.header')

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">

                <div>
                    <h1 class="text-2xl font-bold">
                        Manage User
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Kelola role pengguna SAPLARIN
                    </p>
                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">
                    + Tambah User
                </button>
            </div>

            <!-- CARD TABLE -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

                <table id="userTable" class="display w-full text-sm">

                    <thead>
                        <tr class="text-slate-300">
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>NIK</th>
                            <th>Jabatan</th>
                            <th>Bidang</th>
                            <th>Jenis Kerja</th>
                            <th>Role</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>

                                <td>
                                    {{ $user['nama'] }}
                                </td>

                                <td>
                                    {{ $user['nip'] }}
                                </td>
                                <td>
                                    {{ $user['nik'] }}
                                </td>

                                <td>
                                    {{ $user['jabatan'] }}
                                </td>
                                <td>
                                    {{ $user['bidang'] }}
                                </td>
                                <td>
                                    {{ $user['jeniskerja'] }}
                                </td>

                                <td>
                                    <div class="flex flex-wrap gap-1">

                                        @foreach ($user['roles'] as $role)
                                            <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-lg">
                                                {{ $role }}
                                            </span>
                                        @endforeach

                                    </div>
                                </td>

                                <td>
                                    <button onclick='openEditModal(@json($user))'
                                        class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">

                                        Edit
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-slate-400">
                                    Tidak ada data user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="addUserModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Tambah User
                </h2>

                <button onclick="closeModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="/admin/store">
                @csrf

                <!-- Pegawai -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Pilih Pegawai
                    </label>

                    <select name="user_uid" id="pegawaiSelect" class="w-full" required>

                        <option value="">
                            Cari Nama / NIP
                        </option>

                        @foreach ($pegawai as $p)
                            <option value="{{ $p['id'] }}">
                                {{ $p['nama'] }}
                                -
                                {{ $p['nip'] }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <!-- Role -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Pilih Role
                    </label>

                    <div class="space-y-2">

                        @foreach ($availableRoles as $role)
                            <label class="flex items-center gap-2">

                                <input type="checkbox" name="roles[]" value="{{ $role }}" class="rounded">

                                {{ $role }}

                            </label>
                        @endforeach

                    </div>
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
    <div id="editUserModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Edit Role User
                </h2>

                <button onclick="closeEditModal()">
                    ✕
                </button>
            </div>

            <form method="POST" action="/admin/update-role">
                @csrf

                <input type="hidden" name="user_uid" id="edit_user_uid">

                <!-- Pegawai -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Pegawai
                    </label>

                    <input type="text" id="edit_nama"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3" readonly>

                </div>

                <!-- ROLE -->
                <div class="mb-4">

                    <label class="block mb-2 text-sm">
                        Role
                    </label>

                    <div class="space-y-2">

                        @foreach ($availableRoles as $role)
                            <label class="flex items-center gap-2">

                                <input type="checkbox" name="roles[]" value="{{ $role }}"
                                    class="edit-role-checkbox rounded">

                                {{ $role }}

                            </label>
                        @endforeach

                    </div>

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
        function openEditModal(user) {

            document
                .getElementById('edit_user_uid')
                .value = user.id;

            document
                .getElementById('edit_nama')
                .value =
                `${user.nama} - ${user.nip}`;

            // reset checkbox
            $('.edit-role-checkbox')
                .prop('checked', false);

            // centang role existing
            user.roles.forEach(role => {

                $(
                    `.edit-role-checkbox[value="${role}"]`
                ).prop('checked', true);

            });

            $('#editUserModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeEditModal() {

            $('#editUserModal')
                .addClass('hidden')
                .removeClass('flex');
        }
    </script>
    <script>
        function openModal() {
            document.getElementById('addUserModal')
                .classList.remove('hidden');

            document.getElementById('addUserModal')
                .classList.add('flex');
        }

        function closeModal() {
            document.getElementById('addUserModal')
                .classList.add('hidden');

            document.getElementById('addUserModal')
                .classList.remove('flex');
        }

        $(document).ready(function() {

            $('#pegawaiSelect').select2({
                dropdownParent: $('#addUserModal'),
                width: '100%'
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
    <script>
        $(document).ready(function() {

            $('#userTable').DataTable({
                pageLength: 10,
                responsive: true,
                autoWidth: false,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "←",
                        next: "→"
                    },
                    emptyTable: "Belum ada data user"
                }
            });

        });
    </script>

</body>

</html>
