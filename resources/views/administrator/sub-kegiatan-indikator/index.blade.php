<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Indikator Sub Kegiatan</title>
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

        table.dataTable thead th {
            color: #cbd5e1 !important;
            white-space: nowrap;
        }

        table.dataTable tbody tr {
            background-color: #1e293b !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #334155 !important;
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
                        Indikator Sub Kegiatan
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Admin mengatur indikator, target, dan satuan yang nanti diisi realisasinya oleh operator.
                    </p>
                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-semibold">
                    + Tambah Indikator
                </button>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 overflow-x-auto">

                <table id="indikatorTable" class="display nowrap w-full text-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Sub Kegiatan</th>
                            <th>Indikator</th>
                            <th>Target</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($indikators as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="font-semibold text-blue-300">
                                        {{ $item->indikator_unit_kode ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-400 max-w-[180px] whitespace-normal">
                                        {{ $item->indikator_unit_nama ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="font-semibold max-w-[260px] whitespace-normal">
                                        {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        {{ $item->subKegiatan->sub_kegiatan_kode ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-w-[320px] whitespace-normal">
                                        {{ $item->indikator_nama }}
                                    </div>
                                </td>

                                <td class="font-semibold">
                                    {{ number_format($item->indikator_target, 2, ',', '.') }}
                                </td>

                                <td>
                                    {{ $item->indikator_satuan }}
                                </td>

                                <td>
                                    @if ($item->indikator_status)
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
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" onclick='openEditModal(@json($item))'
                                            class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">
                                            Edit
                                        </button>

                                        <form method="POST"
                                            action="{{ route('admin.sub-kegiatan-indikator.delete', $item->indikator_uid) }}"
                                            onsubmit="return confirm('Hapus indikator ini?')">
                                            @csrf

                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-400">
                                    Belum ada indikator sub kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>

    </div>

    {{-- MODAL TAMBAH --}}
    <div id="indikatorModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-2xl p-6">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-xl font-bold">
                        Tambah Indikator
                    </h2>
                    <p class="text-sm text-slate-400">
                        Indikator ini akan muncul pada form laporan operator.
                    </p>
                </div>

                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.sub-kegiatan-indikator.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Unit
                    </label>

                    <select name="indikator_unit_kode" id="indikator_unit_kode" onchange="setIndikatorUnitNama()"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>
                        <option value="">Pilih Unit</option>
                        <option value="DISBUD" data-nama="Dinas Kebudayaan Provinsi Bali">DISBUD</option>
                        <option value="UPTD-MB" data-nama="UPTD Museum Bali">UPTD Museum Bali</option>
                        <option value="UPTD-MPRB" data-nama="UPTD Monumen Perjuangan Rakyat Bali">UPTD Monumen
                            Perjuangan Rakyat Bali</option>
                        <option value="UPTD-TB" data-nama="UPTD Taman Budaya">UPTD Taman Budaya</option>
                    </select>

                    <input type="hidden" name="indikator_unit_nama" id="indikator_unit_nama">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Sub Kegiatan
                    </label>

                    <select name="indikator_sub_kegiatan_id"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>
                        <option value="">Pilih Sub Kegiatan</option>

                        @foreach ($subKegiatans as $sub)
                            <option value="{{ $sub->sub_kegiatan_id }}">
                                {{ $sub->sub_kegiatan_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Nama Indikator
                    </label>

                    <textarea name="indikator_nama" rows="3"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                        placeholder="Contoh: Jumlah peserta kegiatan" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-2 text-sm text-slate-300">
                            Target
                        </label>

                        <input type="number" step="0.01" name="indikator_target"
                            class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                            placeholder="Contoh: 100" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm text-slate-300">
                            Satuan
                        </label>

                        <input type="text" name="indikator_satuan"
                            class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                            placeholder="Orang / Dokumen / Kegiatan" required>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl">
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
    <div id="editIndikatorModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-2xl p-6">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-xl font-bold">
                        Edit Indikator
                    </h2>
                    <p class="text-sm text-slate-400">
                        Perbarui indikator, target, satuan, dan status.
                    </p>
                </div>

                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('admin.sub-kegiatan-indikator.update') }}">
                @csrf

                <input type="hidden" id="edit_indikator_id" name="indikator_id">

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Unit
                    </label>

                    <select id="edit_indikator_unit_kode" name="indikator_unit_kode"
                        onchange="setEditIndikatorUnitNama()"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>
                        <option value="">Pilih Unit</option>
                        <option value="DISBUD" data-nama="Dinas Kebudayaan Provinsi Bali">DISBUD</option>
                        <option value="UPTD-MB" data-nama="UPTD Museum Bali">UPTD Museum Bali</option>
                        <option value="UPTD-MPRB" data-nama="UPTD Monumen Perjuangan Rakyat Bali">UPTD Monumen
                            Perjuangan Rakyat Bali</option>
                        <option value="UPTD-TB" data-nama="UPTD Taman Budaya">UPTD Taman Budaya</option>
                    </select>

                    <input type="hidden" name="indikator_unit_nama" id="edit_indikator_unit_nama">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Sub Kegiatan
                    </label>

                    <select id="edit_indikator_sub_kegiatan_id" name="indikator_sub_kegiatan_id"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>
                        @foreach ($subKegiatans as $sub)
                            <option value="{{ $sub->sub_kegiatan_id }}">
                                {{ $sub->sub_kegiatan_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Nama Indikator
                    </label>

                    <textarea id="edit_indikator_nama" name="indikator_nama" rows="3"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-2 text-sm text-slate-300">
                            Target
                        </label>

                        <input id="edit_indikator_target" type="number" step="0.01" name="indikator_target"
                            class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                            required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm text-slate-300">
                            Satuan
                        </label>

                        <input id="edit_indikator_satuan" type="text" name="indikator_satuan"
                            class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                            required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Status
                    </label>

                    <select id="edit_indikator_status" name="indikator_status"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl">
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
            document.getElementById('indikatorModal').classList.remove('hidden');
            document.getElementById('indikatorModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('indikatorModal').classList.add('hidden');
            document.getElementById('indikatorModal').classList.remove('flex');
        }

        function openEditModal(item) {
            document.getElementById('edit_indikator_id').value = item.indikator_id;
            document.getElementById('edit_indikator_sub_kegiatan_id').value = item.indikator_sub_kegiatan_id;
            document.getElementById('edit_indikator_nama').value = item.indikator_nama;
            document.getElementById('edit_indikator_target').value = item.indikator_target;
            document.getElementById('edit_indikator_satuan').value = item.indikator_satuan;
            document.getElementById('edit_indikator_status').value = item.indikator_status;
            document.getElementById('edit_indikator_unit_kode').value = item.indikator_unit_kode ?? '';
            document.getElementById('edit_indikator_unit_nama').value = item.indikator_unit_nama ?? '';

            document.getElementById('editIndikatorModal').classList.remove('hidden');
            document.getElementById('editIndikatorModal').classList.add('flex');

        }

        function closeEditModal() {
            document.getElementById('editIndikatorModal').classList.add('hidden');
            document.getElementById('editIndikatorModal').classList.remove('flex');
        }

        $(document).ready(function() {
            $('#indikatorTable').DataTable({
                scrollX: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    emptyTable: "Belum ada data indikator",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        });

        function setIndikatorUnitNama() {
            const select = document.getElementById('indikator_unit_kode');
            const selected = select.options[select.selectedIndex];

            document.getElementById('indikator_unit_nama').value = selected.dataset.nama || '';
        }

        function setEditIndikatorUnitNama() {
            const select = document.getElementById('edit_indikator_unit_kode');
            const selected = select.options[select.selectedIndex];

            document.getElementById('edit_indikator_unit_nama').value = selected.dataset.nama || '';
        }
    </script>

</body>

</html>
