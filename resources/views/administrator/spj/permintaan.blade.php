<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Permintaan SPJ</title>
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
            font-size: 12px;
        }

        table.dataTable tbody td {
            vertical-align: top;
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

        <div class="flex-1 p-6 overflow-x-hidden">

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
                        Permintaan SPJ
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Menampilkan semua SPJ dari tabel saplarin_spj_realisasi.
                        SPJ nonaktif tidak dihitung pada realisasi pagu.
                    </p>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 overflow-x-auto">

                <table id="permintaanSpjTable" class="display nowrap w-full text-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Unit</th>
                            <th>Program / Kegiatan</th>
                            <th>Sub Kegiatan</th>
                            <th>Operator</th>
                            <th>Tanggal</th>
                            <th>Uraian</th>
                            <th>Nominal</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($spjs as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->pagu->spj_pagu_tahun ?? '-' }}
                                </td>

                                <td>
                                    <div class="font-semibold text-blue-300">
                                        {{ $item->pagu->unit->unit_kode ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-400 max-w-[160px] whitespace-normal">
                                        {{ $item->pagu->unit->unit_nama ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="font-semibold max-w-[220px] whitespace-normal">
                                        {{ $item->pagu->program->program_nama ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-400 max-w-[220px] whitespace-normal mt-1">
                                        {{ $item->pagu->kegiatan->kegiatan_nama ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="font-semibold max-w-[240px] whitespace-normal">
                                        {{ $item->pagu->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="font-semibold">
                                        {{ $item->spj_operator_nama ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $item->spj_operator_nip ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500 max-w-[160px] whitespace-normal">
                                        {{ $item->spj_bidang_nama ?? '-' }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap">
                                    <div>
                                        SPJ:
                                        {{ $item->spj_tanggal ? $item->spj_tanggal->format('d/m/Y') : '-' }}
                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">
                                        Input:
                                        {{ $item->spj_tanggal_input ? $item->spj_tanggal_input->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-w-[260px] whitespace-normal">
                                        {{ $item->spj_uraian }}
                                    </div>
                                </td>

                                <td class="font-semibold whitespace-nowrap">
                                    Rp {{ number_format($item->spj_nominal, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($item->spj_file)
                                        <a href="{{ filter_var($item->spj_file, FILTER_VALIDATE_URL) ? $item->spj_file : asset($item->spj_file) }}"
                                            target="_blank"
                                            class="inline-flex bg-blue-600/20 text-blue-300 px-3 py-1 rounded-lg text-xs hover:underline">
                                            Lihat File
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->spj_status === 'Aktif')
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                            Nonaktif
                                        </span>
                                    @endif

                                    @if ($item->spj_status_at)
                                        <div class="text-[10px] text-slate-500 mt-1">
                                            {{ $item->spj_status_at->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="max-w-[200px] whitespace-normal text-xs text-slate-400">
                                        {{ $item->spj_catatan_admin ?: '-' }}
                                    </div>

                                    @if ($item->spj_status_by_nama)
                                        <div class="text-[10px] text-slate-500 mt-1">
                                            Oleh: {{ $item->spj_status_by_nama }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <button type="button" onclick='openToggleModal(@json($item))'
                                        class="{{ $item->spj_status === 'Aktif' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} px-3 py-1 rounded-lg text-xs">
                                        {{ $item->spj_status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-6 text-slate-400">
                                    Belum ada data permintaan SPJ.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>

    </div>

    <div id="toggleModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 mx-4">

            <div class="flex justify-between items-start mb-5">
                <div>
                    <h2 id="toggleTitle" class="text-xl font-bold">
                        Ubah Status SPJ
                    </h2>

                    <p id="toggleInfo" class="text-sm text-slate-400 mt-1"></p>
                </div>

                <button type="button" onclick="closeToggleModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <form id="toggleForm" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm text-slate-300 mb-2">
                        Catatan Admin
                    </label>

                    <textarea name="spj_catatan_admin" id="toggleCatatan" rows="4" class="w-full rounded-xl px-4 py-3"
                        placeholder="Contoh: SPJ duplikat / file salah / dibatalkan..."></textarea>
                </div>

                <div class="bg-yellow-600/10 border border-yellow-600/30 text-yellow-300 rounded-xl p-4 text-sm mb-5">
                    Jika SPJ dinonaktifkan, nominalnya otomatis tidak dihitung pada realisasi pagu.
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeToggleModal()"
                        class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl">
                        Batal
                    </button>

                    <button id="toggleButton" type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-xl">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function openToggleModal(item) {
            const isAktif = item.spj_status === 'Aktif';

            $('#toggleTitle').text(isAktif ? 'Nonaktifkan SPJ' : 'Aktifkan SPJ');

            $('#toggleInfo').text(
                (item.spj_uraian ?? '-') +
                ' / Rp ' +
                Number(item.spj_nominal || 0).toLocaleString('id-ID')
            );

            $('#toggleCatatan').val(item.spj_catatan_admin ?? '');

            let action = "{{ url('/admin/spj/permintaan') }}/" + item.spj_uid + "/toggle";
            $('#toggleForm').attr('action', action);

            $('#toggleButton')
                .removeClass('bg-red-600 hover:bg-red-700 bg-green-600 hover:bg-green-700')
                .addClass(isAktif ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700')
                .text(isAktif ? 'Nonaktifkan' : 'Aktifkan');

            $('#toggleModal').removeClass('hidden').addClass('flex');
        }

        function closeToggleModal() {
            $('#toggleModal').addClass('hidden').removeClass('flex');
            $('#toggleForm')[0].reset();
        }

        $(document).ready(function() {
            $('#permintaanSpjTable').DataTable({
                scrollX: true,
                autoWidth: false,
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    emptyTable: "Belum ada data permintaan SPJ",
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
