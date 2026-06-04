<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Saplarin - Pengajuan BBM</title>
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
                        Pengajuan BBM
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Kelola data pengajuan BBM pegawai SAPLARIN
                    </p>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

                <table id="bbmTable" class="display w-full text-sm">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengaju</th>
                            <th>Sub Kegiatan</th>
                            <th>Liter</th>
                            <th>Uraian</th>
                            <th>Status Pengajuan</th>
                            <th>Status Nota</th>
                            <th>File</th>
                            <th width="220">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bbms as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="font-semibold text-white">
                                        {{ $item->bbm_pengaju_nama }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        {{ $item->bbm_pengaju_nip }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        {{ $item->bbm_bidang_nama }}
                                    </div>
                                </td>

                                <td>
                                    {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                </td>
                                <td>
                                    {{ number_format($item->bbm_liter, 2, ',', '.') }} L
                                </td>

                                <td>
                                    <span title="{{ $item->bbm_uraian_kegiatan }}">
                                        {{ \Illuminate\Support\Str::limit($item->bbm_uraian_kegiatan, 60) }}
                                    </span>
                                </td>

                                <td>
                                    @if ($item->bbm_status_pengajuan === 'Pengajuan Diterima')
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                            {{ $item->bbm_status_pengajuan }}
                                        </span>
                                    @elseif ($item->bbm_status_pengajuan === 'Pengajuan Ditolak')
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                            {{ $item->bbm_status_pengajuan }}
                                        </span>
                                    @else
                                        <span class="bg-yellow-600/20 text-yellow-300 px-3 py-1 rounded-full text-xs">
                                            {{ $item->bbm_status_pengajuan }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->bbm_status_laporan === 'Laporan Nota Diterima')
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @elseif ($item->bbm_status_laporan === 'Laporan Nota Ditolak')
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @elseif ($item->bbm_status_laporan === 'Menunggu Verifikasi')
                                        <span class="bg-yellow-600/20 text-yellow-300 px-3 py-1 rounded-full text-xs">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @else
                                        <span class="bg-slate-700 text-slate-300 px-3 py-1 rounded-full text-xs">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="flex flex-col gap-2">

                                        @if ($item->bbm_spt_file)
                                            <a href="{{ $item->bbm_spt_sync ? $item->bbm_spt_file : asset($item->bbm_spt_file) }}"
                                                target="_blank"
                                                class="{{ $item->bbm_spt_sync ? 'text-green-400' : 'text-blue-400' }} hover:underline">
                                                {{ $item->bbm_spt_sync ? 'SPT Drive' : 'SPT Lokal' }}
                                            </a>

                                            @if (!$item->bbm_spt_sync)
                                                <button type="button"
                                                    onclick="openSinkronModal('{{ $item->bbm_uid }}', 'spt', 'SPT')"
                                                    class="text-xs bg-purple-600 hover:bg-purple-700 px-2 py-1 rounded-lg w-fit">
                                                    Sinkron SPT
                                                </button>
                                            @endif
                                        @endif

                                        @if ($item->bbm_acc_pimpinan_file)
                                            <a href="{{ $item->bbm_acc_pimpinan_sync ? $item->bbm_acc_pimpinan_file : asset($item->bbm_acc_pimpinan_file) }}"
                                                target="_blank"
                                                class="{{ $item->bbm_acc_pimpinan_sync ? 'text-green-400' : 'text-purple-400' }} hover:underline">
                                                {{ $item->bbm_acc_pimpinan_sync ? 'ACC Drive' : 'ACC Lokal' }}
                                            </a>

                                            @if (!$item->bbm_acc_pimpinan_sync)
                                                <button type="button"
                                                    onclick="openSinkronModal('{{ $item->bbm_uid }}', 'acc_pimpinan', 'ACC Pimpinan')"
                                                    class="text-xs bg-purple-600 hover:bg-purple-700 px-2 py-1 rounded-lg w-fit">
                                                    Sinkron ACC
                                                </button>
                                            @endif
                                        @endif

                                        @if ($item->bbm_laporan_nota_file)
                                            <a href="{{ $item->bbm_laporan_nota_sync ? $item->bbm_laporan_nota_file : asset($item->bbm_laporan_nota_file) }}"
                                                target="_blank"
                                                class="{{ $item->bbm_laporan_nota_sync ? 'text-green-400' : 'text-yellow-400' }} hover:underline">
                                                {{ $item->bbm_laporan_nota_sync ? 'Nota Drive' : 'Nota Lokal' }}
                                            </a>

                                            @if (!$item->bbm_laporan_nota_sync)
                                                <button type="button"
                                                    onclick="openSinkronModal('{{ $item->bbm_uid }}', 'nota', 'Laporan Nota')"
                                                    class="text-xs bg-purple-600 hover:bg-purple-700 px-2 py-1 rounded-lg w-fit">
                                                    Sinkron Nota
                                                </button>
                                            @endif
                                        @endif

                                        @if (!$item->bbm_spt_file && !$item->bbm_acc_pimpinan_file && !$item->bbm_laporan_nota_file)
                                            <span class="text-slate-500">-</span>
                                        @endif

                                    </div>
                                </td>

                                <td>
                                    <div class="flex flex-wrap gap-2">

                                        @if ($item->bbm_status_pengajuan === 'Menunggu Verifikasi')
                                            <button type="button"
                                                onclick='openTerimaModal(@json($item))'
                                                class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-xs">
                                                Terima
                                            </button>

                                            <form method="POST"
                                                action="{{ route('admin.bbm.tolakPengajuan', $item->bbm_uid) }}"
                                                onsubmit="return confirm('Tolak pengajuan BBM ini?')">

                                                @csrf

                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        @if ($item->bbm_status_laporan === 'Menunggu Verifikasi')
                                            <form method="POST"
                                                action="{{ route('admin.bbm.terimaLaporan', $item->bbm_uid) }}"
                                                onsubmit="return confirm('Terima laporan nota ini?')">

                                                @csrf

                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-xs">
                                                    Terima Nota
                                                </button>
                                            </form>

                                            <form method="POST"
                                                action="{{ route('admin.bbm.tolakLaporan', $item->bbm_uid) }}"
                                                onsubmit="return confirm('Tolak laporan nota ini?')">

                                                @csrf

                                                <button type="submit"
                                                    class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">
                                                    Tolak Nota
                                                </button>
                                            </form>
                                        @endif

                                        @if ($item->bbm_status_pengajuan !== 'Menunggu Verifikasi' && $item->bbm_status_laporan !== 'Menunggu Verifikasi')
                                            <span class="text-slate-500 text-xs">
                                                Tidak ada aksi
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-slate-400">
                                    Belum ada data pengajuan BBM.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- MODAL TERIMA PENGAJUAN --}}
    <div id="terimaModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 mx-4">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-xl font-bold">
                        Terima Pengajuan BBM
                    </h2>
                    <p class="text-sm text-slate-400">
                        Upload dokumen ACC dari pimpinan sebelum pengajuan disetujui.
                    </p>
                </div>

                <button type="button" onclick="closeTerimaModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <form id="terimaForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Pengaju
                    </label>
                    <input type="text" id="modal_pengaju"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" readonly>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Upload Dokumen ACC Pimpinan
                    </label>

                    <input type="file" name="bbm_acc_pimpinan_file"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" required>

                    <p class="text-xs text-slate-500 mt-2">
                        Format: PDF, JPG, JPEG, PNG, DOC, DOCX. Maksimal 5 MB.
                    </p>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeTerimaModal()"
                        class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl">
                        Batal
                    </button>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-xl">
                        Upload & Setujui
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div id="sinkronModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 mx-4">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-xl font-bold">
                        Sinkron File ke Google Drive
                    </h2>
                    <p class="text-sm text-slate-400">
                        Masukkan URL file Google Drive. File lokal akan dihapus.
                    </p>
                </div>

                <button type="button" onclick="closeSinkronModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <form id="sinkronForm" method="POST">
                @csrf

                <input type="hidden" name="jenis_file" id="sinkron_jenis_file">

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        Jenis File
                    </label>

                    <input type="text" id="sinkron_label_file"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white" readonly>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm text-slate-300">
                        URL Google Drive
                    </label>

                    <input type="url" name="drive_url"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                        placeholder="https://drive.google.com/..." required>
                </div>

                <div class="bg-yellow-600/10 border border-yellow-600/30 text-yellow-300 rounded-xl px-4 py-3 text-sm">
                    Pastikan file sudah diupload ke Google Drive. Setelah disimpan, file lokal di hosting akan dihapus.
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeSinkronModal()"
                        class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl">
                        Batal
                    </button>

                    <button type="submit" onclick="return confirm('Yakin sinkron file ini dan hapus file lokal?')"
                        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-xl">
                        Simpan Sinkron
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function openTerimaModal(item) {
            $('#modal_pengaju').val(item.bbm_pengaju_nama + ' - ' + item.bbm_pengaju_nip);

            let action = "{{ url('/admin/bbm') }}/" + item.bbm_uid + "/terima-pengajuan";

            $('#terimaForm').attr('action', action);

            $('#terimaModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeTerimaModal() {
            $('#terimaModal')
                .addClass('hidden')
                .removeClass('flex');

            $('#terimaForm')[0].reset();
        }

        $(document).ready(function() {
            $('#bbmTable')
                .DataTable({
                    pageLength: 10,
                    responsive: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        emptyTable: "Belum ada data pengajuan BBM",
                        zeroRecords: "Data tidak ditemukan",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        }
                    }
                });
        });
    </script>
    <script>
        function openSinkronModal(uid, jenisFile, labelFile) {
            let action = "{{ url('/admin/bbm') }}/" + uid + "/sinkron-file";

            $('#sinkronForm').attr('action', action);
            $('#sinkron_jenis_file').val(jenisFile);
            $('#sinkron_label_file').val(labelFile);

            $('#sinkronModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeSinkronModal() {
            $('#sinkronModal')
                .addClass('hidden')
                .removeClass('flex');

            $('#sinkronForm')[0].reset();
        }
    </script>
</body>

</html>
