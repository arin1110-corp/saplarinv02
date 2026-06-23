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

        table.dataTable thead th {
            color: #cbd5e1 !important;
            font-size: 12px;
            white-space: nowrap;
        }

        table.dataTable tbody td {
            vertical-align: top;
            padding-top: 14px !important;
            padding-bottom: 14px !important;
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
                        Pengajuan BBM
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Kelola data pengajuan BBM pegawai SAPLARIN
                    </p>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 overflow-x-auto">

                <table id="bbmTable" class="display nowrap w-full text-sm">

                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Pengaju</th>
                            <th>No Plat</th>
                            <th>Liter</th>
                            <th>Uraian</th>
                            <th>Status Pengajuan</th>
                            <th>Status Nota</th>
                            <th width="120">File</th>
                            <th width="240">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bbms as $item)
                            @php
                                $perluSinkron =
                                    !$item->bbm_spt_sync ||
                                    !$item->bbm_acc_pimpinan_sync ||
                                    ($item->bbm_laporan_nota_file && !$item->bbm_laporan_nota_sync);

                                $buktiTambahan = $item->bbm_bukti_tambahan_file;

                                if (is_string($buktiTambahan)) {
                                    $buktiTambahan = json_decode($buktiTambahan, true);
                                }

                                if (!is_array($buktiTambahan)) {
                                    $buktiTambahan = [];
                                }
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="font-semibold text-white whitespace-nowrap">
                                        {{ $item->bbm_pengaju_nama }}
                                    </div>

                                    <div class="text-xs text-slate-400 whitespace-nowrap">
                                        {{ $item->bbm_pengaju_nip }}
                                    </div>

                                    <div class="text-xs text-slate-400 max-w-[180px] truncate">
                                        {{ $item->bbm_bidang_nama }}
                                    </div>
                                </td>

                                <td>
                                    <span class="bg-slate-700 text-slate-100 px-3 py-1 rounded-lg text-xs font-semibold whitespace-nowrap">
                                        {{ $item->bbm_no_plat ?? '-' }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap">
                                    {{ number_format($item->bbm_liter, 2, ',', '.') }} L
                                </td>

                                <td>
                                    <div class="max-w-[240px] whitespace-normal leading-relaxed" title="{{ $item->bbm_uraian_kegiatan }}">
                                        {{ \Illuminate\Support\Str::limit($item->bbm_uraian_kegiatan, 70) }}
                                    </div>
                                </td>

                                <td>
                                    @if ($item->bbm_status_pengajuan === 'Pengajuan Diterima')
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                            {{ $item->bbm_status_pengajuan }}
                                        </span>
                                    @elseif ($item->bbm_status_pengajuan === 'Pengajuan Ditolak')
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                            {{ $item->bbm_status_pengajuan }}
                                        </span>
                                    @else
                                        <span class="bg-yellow-600/20 text-yellow-300 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                            {{ $item->bbm_status_pengajuan }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->bbm_status_laporan === 'Laporan Nota Diterima')
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @elseif ($item->bbm_status_laporan === 'Laporan Nota Ditolak')
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @elseif ($item->bbm_status_laporan === 'Menunggu Verifikasi')
                                        <span class="bg-yellow-600/20 text-yellow-300 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @else
                                        <span class="bg-slate-700 text-slate-300 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                            {{ $item->bbm_status_laporan }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <button type="button"
                                        onclick='openFileModal(@json($item), @json($buktiTambahan))'
                                        class="bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap">
                                        Lihat File
                                    </button>

                                    @if (!empty($buktiTambahan))
                                        <div class="text-[10px] text-cyan-300 mt-1 whitespace-nowrap">
                                            {{ count($buktiTambahan) }} bukti tambahan
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="flex flex-wrap gap-2 max-w-[240px]">

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

                                        @if ($item->bbm_status_pengajuan === 'Pengajuan Diterima')
                                            @if ($perluSinkron)
                                                <form method="POST"
                                                    action="{{ route('admin.bbm.sinkron', $item->bbm_uid) }}"
                                                    onsubmit="return confirm('Sinkron semua file pengajuan ini ke Google Drive? File lokal di hosting akan dihapus jika file Drive ditemukan.')">
                                                    @csrf

                                                    <button type="submit"
                                                        class="bg-purple-600 hover:bg-purple-700 px-3 py-1 rounded-lg text-xs">
                                                        Sinkron Drive
                                                    </button>
                                                </form>
                                            @else
                                                <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                                    Sudah Sinkron
                                                </span>
                                            @endif
                                        @endif

                                        @if (
                                            $item->bbm_status_pengajuan !== 'Menunggu Verifikasi' &&
                                                $item->bbm_status_pengajuan !== 'Pengajuan Diterima' &&
                                                $item->bbm_status_laporan !== 'Menunggu Verifikasi')
                                            <span class="text-slate-500 text-xs">
                                                Tidak ada aksi
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-6 text-slate-400">
                                    Belum ada data pengajuan BBM.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- MODAL FILE --}}
    <div id="fileModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-xl font-bold">
                        Detail File BBM
                    </h2>
                    <p id="fileModalInfo" class="text-sm text-slate-400"></p>
                </div>

                <button type="button" onclick="closeFileModal()" class="text-slate-400 hover:text-white text-xl">
                    ✕
                </button>
            </div>

            <div id="fileList" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>

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
                        No Plat
                    </label>

                    <input type="text" id="modal_no_plat"
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

    <script>
        function safeValue(value) {
            return value ?? '';
        }

        function fileButton(label, url, colorClass) {
            if (!url) {
                return '';
            }

            return `
                <a href="${url}" target="_blank"
                    class="block bg-slate-800 border border-slate-700 rounded-xl p-4 hover:bg-slate-700">
                    <div class="${colorClass} font-semibold text-sm">
                        📎 ${label}
                    </div>
                    <div class="text-xs text-slate-500 mt-1 truncate">
                        Klik untuk membuka file
                    </div>
                </a>
            `;
        }

        function openFileModal(item, buktiTambahan) {
            let html = '';

            html += fileButton('SPT', safeValue(item.bbm_spt_file), 'text-green-300');
            html += fileButton('ACC Pimpinan', safeValue(item.bbm_acc_pimpinan_file), 'text-purple-300');
            html += fileButton('Nota BBM', safeValue(item.bbm_laporan_nota_file), 'text-yellow-300');
            html += fileButton('Foto Mobil / Kendaraan', safeValue(item.bbm_foto_mobil_file), 'text-blue-300');

            if (buktiTambahan && buktiTambahan.length > 0) {
                buktiTambahan.forEach(function(file, index) {
                    let url = file.file ?? '#';
                    let nama = file.nama ?? 'Bukti Tambahan ' + (index + 1);
                    let jenis = file.jenis ? ' - ' + file.jenis : '';

                    html += `
                        <a href="${url}" target="_blank"
                            class="block bg-slate-800 border border-slate-700 rounded-xl p-4 hover:bg-slate-700">
                            <div class="text-cyan-300 font-semibold text-sm">
                                📎 ${nama}
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                                Bukti Tambahan${jenis}
                            </div>
                        </a>
                    `;
                });
            }

            if (!html) {
                html = `
                    <div class="col-span-2 text-center text-slate-500 py-8">
                        Tidak ada file.
                    </div>
                `;
            }

            $('#fileModalInfo').text((item.bbm_pengaju_nama ?? '-') + ' / ' + (item.bbm_no_plat ?? '-'));
            $('#fileList').html(html);

            $('#fileModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeFileModal() {
            $('#fileModal')
                .addClass('hidden')
                .removeClass('flex');

            $('#fileList').html('');
        }

        function openTerimaModal(item) {
            $('#modal_pengaju').val(item.bbm_pengaju_nama + ' - ' + item.bbm_pengaju_nip);
            $('#modal_no_plat').val(item.bbm_no_plat ?? '-');

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
            $('#bbmTable').DataTable({
                pageLength: 10,
                autoWidth: false,
                scrollX: true,
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
                },
                columnDefs: [{
                        targets: 0,
                        width: "40px"
                    },
                    {
                        targets: 4,
                        width: "240px"
                    },
                    {
                        targets: 7,
                        width: "120px",
                        orderable: false
                    },
                    {
                        targets: 8,
                        width: "240px",
                        orderable: false
                    }
                ]
            });
        });
    </script>

</body>

</html>