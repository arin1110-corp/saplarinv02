<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Data Kinerja</title>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <style>
        body { background: #0f172a; }
        table.dataTable { color: #e2e8f0 !important; }
        .dataTables_wrapper { color: #cbd5e1; }
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
        table.dataTable tbody tr { background-color: #1e293b !important; }
        table.dataTable tbody tr:hover { background-color: #334155 !important; }
        input, select, textarea {
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
                    Data Kinerja Bidang
                </h1>
                <p class="text-slate-400 text-sm">
                    Admin menambahkan tahun anggaran, bidang, dan kegiatan. Progress diisi oleh user bidang terkait.
                </p>
            </div>

            <button onclick="openModal()"
                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">
                + Tambah Kinerja
            </button>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

            <table id="kinerjaTable" class="display w-full text-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Bidang</th>
                        <th>Kegiatan</th>
                        <th>Capaian TW</th>
                        <th>Progress Terbaru</th>
                        <th>Status</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kinerjas as $item)
                        @php
                            $tw1 = $item->progress->where('progress_triwulan', 'TW I')->max('progress_persentase');
                            $tw2 = $item->progress->where('progress_triwulan', 'TW II')->max('progress_persentase');
                            $tw3 = $item->progress->where('progress_triwulan', 'TW III')->max('progress_persentase');
                            $tw4 = $item->progress->where('progress_triwulan', 'TW IV')->max('progress_persentase');
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->kinerja_tahun }}</td>

                            <td>
                                <div class="font-semibold">
                                    {{ $item->kinerja_bidang_nama }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    ID: {{ $item->kinerja_bidang_id ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="font-semibold">
                                    {{ $item->kinerja_kegiatan }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ \Illuminate\Support\Str::limit($item->kinerja_deskripsi, 70) ?: '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="text-xs space-y-1">
                                    <div>TW I: {{ $tw1 !== null ? number_format($tw1, 2, ',', '.') . '%' : '-' }}</div>
                                    <div>TW II: {{ $tw2 !== null ? number_format($tw2, 2, ',', '.') . '%' : '-' }}</div>
                                    <div>TW III: {{ $tw3 !== null ? number_format($tw3, 2, ',', '.') . '%' : '-' }}</div>
                                    <div>TW IV: {{ $tw4 !== null ? number_format($tw4, 2, ',', '.') . '%' : '-' }}</div>
                                </div>
                            </td>

                            <td>
                                @if ($item->progressTerbaru)
                                    <div class="font-semibold">
                                        {{ number_format($item->progressTerbaru->progress_persentase, 2, ',', '.') }}%
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $item->progressTerbaru->progress_triwulan }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Oleh: {{ $item->progressTerbaru->progress_user_nama ?? '-' }}
                                    </div>
                                @else
                                    <span class="text-slate-500">
                                        Belum ada
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($item->kinerja_status === 'Aktif')
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
                                    <button onclick='openEditModal(@json($item))'
                                        class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">
                                        Edit
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.kinerja.delete', $item->kinerja_uid) }}"
                                        onsubmit="return confirm('Hapus data kinerja ini?')">
                                        @csrf

                                        <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-slate-400">
                                Belum ada data kinerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="kinerjaModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-2xl p-6 mx-4 max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-xl font-bold">
                    Tambah Kinerja
                </h2>
                <p class="text-sm text-slate-400">
                    Isi tahun anggaran, bidang, dan kegiatan.
                </p>
            </div>

            <button onclick="closeModal()" class="text-slate-400 hover:text-white text-xl">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.kinerja.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Tahun Anggaran
                </label>

                <input type="number"
                    name="kinerja_tahun"
                    value="{{ date('Y') }}"
                    class="w-full rounded-xl px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Bidang
                </label>

                <select name="bidang_select"
                    id="bidang_select"
                    onchange="setBidangTambah()"
                    class="w-full rounded-xl px-4 py-3"
                    required>

                    <option value="">
                        Pilih Bidang
                    </option>

                    @foreach ($bidangs as $bidang)
                        @php
                            $bidangId = $bidang['bidang_id'] ?? $bidang['id'] ?? '';
                            $bidangNama = $bidang['bidang_nama'] ?? $bidang['nama'] ?? '';
                        @endphp

                        <option value="{{ $bidangId }}" data-nama="{{ $bidangNama }}">
                            {{ $bidangNama }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="kinerja_bidang_id" id="kinerja_bidang_id">
                <input type="hidden" name="kinerja_bidang_nama" id="kinerja_bidang_nama">
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Kegiatan
                </label>

                <input type="text"
                    name="kinerja_kegiatan"
                    class="w-full rounded-xl px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Deskripsi
                </label>

                <textarea name="kinerja_deskripsi"
                    rows="3"
                    class="w-full rounded-xl px-4 py-3"></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Status
                </label>

                <select name="kinerja_status" class="w-full rounded-xl px-4 py-3" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal()"
                    class="bg-slate-700 px-4 py-2 rounded-xl">
                    Batal
                </button>

                <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

{{-- MODAL EDIT --}}
<div id="editKinerjaModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-2xl p-6 mx-4 max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-xl font-bold">
                    Edit Kinerja
                </h2>
                <p class="text-sm text-slate-400">
                    Perbarui data kinerja.
                </p>
            </div>

            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white text-xl">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.kinerja.update') }}">
            @csrf

            <input type="hidden" id="edit_kinerja_id" name="kinerja_id">

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Tahun Anggaran
                </label>

                <input type="number"
                    id="edit_kinerja_tahun"
                    name="kinerja_tahun"
                    class="w-full rounded-xl px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Bidang
                </label>

                <select name="bidang_select"
                    id="edit_bidang_select"
                    onchange="setBidangEdit()"
                    class="w-full rounded-xl px-4 py-3"
                    required>

                    <option value="">
                        Pilih Bidang
                    </option>

                    @foreach ($bidangs as $bidang)
                        @php
                            $bidangId = $bidang['bidang_id'] ?? $bidang['id'] ?? '';
                            $bidangNama = $bidang['bidang_nama'] ?? $bidang['nama'] ?? '';
                        @endphp

                        <option value="{{ $bidangId }}" data-nama="{{ $bidangNama }}">
                            {{ $bidangNama }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="kinerja_bidang_id" id="edit_kinerja_bidang_id">
                <input type="hidden" name="kinerja_bidang_nama" id="edit_kinerja_bidang_nama">
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Kegiatan
                </label>

                <input type="text"
                    id="edit_kinerja_kegiatan"
                    name="kinerja_kegiatan"
                    class="w-full rounded-xl px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Deskripsi
                </label>

                <textarea id="edit_kinerja_deskripsi"
                    name="kinerja_deskripsi"
                    rows="3"
                    class="w-full rounded-xl px-4 py-3"></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">
                    Status
                </label>

                <select id="edit_kinerja_status"
                    name="kinerja_status"
                    class="w-full rounded-xl px-4 py-3"
                    required>
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeEditModal()"
                    class="bg-slate-700 px-4 py-2 rounded-xl">
                    Batal
                </button>

                <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl">
                    Update
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openModal() {
        $('#kinerjaModal').removeClass('hidden').addClass('flex');
    }

    function closeModal() {
        $('#kinerjaModal').addClass('hidden').removeClass('flex');
    }

    function openEditModal(item) {
        $('#edit_kinerja_id').val(item.kinerja_id);
        $('#edit_kinerja_tahun').val(item.kinerja_tahun);
        $('#edit_kinerja_bidang_id').val(item.kinerja_bidang_id);
        $('#edit_kinerja_bidang_nama').val(item.kinerja_bidang_nama);
        $('#edit_kinerja_kegiatan').val(item.kinerja_kegiatan);
        $('#edit_kinerja_deskripsi').val(item.kinerja_deskripsi);
        $('#edit_kinerja_status').val(item.kinerja_status);

        $('#edit_bidang_select').val(item.kinerja_bidang_id);

        $('#editKinerjaModal').removeClass('hidden').addClass('flex');
    }

    function closeEditModal() {
        $('#editKinerjaModal').addClass('hidden').removeClass('flex');
    }

    function setBidangTambah() {
        const select = document.getElementById('bidang_select');
        const selected = select.options[select.selectedIndex];

        document.getElementById('kinerja_bidang_id').value = select.value;
        document.getElementById('kinerja_bidang_nama').value = selected.getAttribute('data-nama') || '';
    }

    function setBidangEdit() {
        const select = document.getElementById('edit_bidang_select');
        const selected = select.options[select.selectedIndex];

        document.getElementById('edit_kinerja_bidang_id').value = select.value;
        document.getElementById('edit_kinerja_bidang_nama').value = selected.getAttribute('data-nama') || '';
    }

    $(document).ready(function() {
        $('#kinerjaTable').DataTable({
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                emptyTable: "Belum ada data kinerja",
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