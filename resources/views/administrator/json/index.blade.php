<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Saplarin - Pengelolaan JSON</title>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>

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

        <div class="flex-1 p-6">

            @include('administrator.partials.header')

            @if (session('success'))
                <div class="mb-5 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-xl">
                    {{ session('error') }}
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
                        Pengelolaan JSON
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Kelola konfigurasi JSON SAPLARIN, termasuk folder Google Drive BBM.
                    </p>
                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">
                    + Tambah JSON
                </button>

            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

                <table id="jsonTable" class="display w-full text-sm">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Key</th>
                            <th>Nama</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($jsons as $json)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <span class="bg-blue-600/20 text-blue-300 px-3 py-1 rounded-lg text-xs">
                                        {{ $json->json_key }}
                                    </span>
                                </td>

                                <td>
                                    {{ $json->json_nama ?? '-' }}
                                </td>

                                <td>
                                    <pre class="text-xs bg-slate-950 border border-slate-800 rounded-xl p-3 max-w-xl overflow-auto">{{ $json->json_value }}</pre>
                                </td>

                                <td>
                                    @if ($json->json_status == 1)
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="flex gap-2">
                                        <button onclick='openEditModal(@json($json))'
                                            class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">
                                            Edit
                                        </button>

                                        <a href="{{ route('admin.json.delete', $json->json_id) }}"
                                            onclick="return confirm('Hapus JSON ini?')"
                                            class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

            <div class="mt-6 bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <h2 class="text-lg font-bold mb-2">
                    Contoh JSON BBM Drive
                </h2>

                <p class="text-sm text-slate-400 mb-3">
                    Buat data dengan key <b>bbm_drive</b> dan value seperti ini.
                </p>

                <pre class="bg-slate-950 border border-slate-800 rounded-xl p-4 text-sm text-green-300 overflow-auto">{
    "folder_bbm": "ISI_FOLDER_ID_GOOGLE_DRIVE_BBM"
}</pre>
            </div>

        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="jsonModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-2xl p-6 mx-4">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Tambah JSON
                </h2>

                <button onclick="closeModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.json.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Key</label>
                    <input type="text"
                        name="json_key"
                        class="w-full rounded-xl px-4 py-3"
                        placeholder="Contoh: bbm_drive"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Nama</label>
                    <input type="text"
                        name="json_nama"
                        class="w-full rounded-xl px-4 py-3"
                        placeholder="Contoh: Konfigurasi Google Drive BBM">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">JSON Value</label>
                    <textarea name="json_value"
                        rows="8"
                        class="w-full rounded-xl px-4 py-3 font-mono text-sm"
                        required>{
    "folder_bbm": ""
}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Status</label>
                    <select name="json_status" class="w-full rounded-xl px-4 py-3" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
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

    {{-- MODAL EDIT --}}
    <div id="editJsonModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-2xl p-6 mx-4">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold">
                    Edit JSON
                </h2>

                <button onclick="closeEditModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.json.update') }}">
                @csrf

                <input type="hidden" id="edit_json_id" name="json_id">

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Key</label>
                    <input type="text"
                        id="edit_json_key"
                        name="json_key"
                        class="w-full rounded-xl px-4 py-3"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Nama</label>
                    <input type="text"
                        id="edit_json_nama"
                        name="json_nama"
                        class="w-full rounded-xl px-4 py-3">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">JSON Value</label>
                    <textarea id="edit_json_value"
                        name="json_value"
                        rows="8"
                        class="w-full rounded-xl px-4 py-3 font-mono text-sm"
                        required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Status</label>
                    <select id="edit_json_status" name="json_status" class="w-full rounded-xl px-4 py-3" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
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
            $('#jsonModal').removeClass('hidden').addClass('flex');
        }

        function closeModal() {
            $('#jsonModal').addClass('hidden').removeClass('flex');
        }

        function openEditModal(json) {
            $('#edit_json_id').val(json.json_id);
            $('#edit_json_key').val(json.json_key);
            $('#edit_json_nama').val(json.json_nama);
            $('#edit_json_value').val(json.json_value);
            $('#edit_json_status').val(json.json_status);

            $('#editJsonModal').removeClass('hidden').addClass('flex');
        }

        function closeEditModal() {
            $('#editJsonModal').addClass('hidden').removeClass('flex');
        }

        $(document).ready(function() {
            $('#jsonTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    emptyTable: "Belum ada data JSON",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        });
    </script>

</body>

</html>