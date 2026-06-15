<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Data Pagu SPJ</title>
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
                    Data Pagu SPJ
                </h1>

                <p class="text-slate-400 text-sm">
                    Kelola pagu induk dan pagu pergeseran berdasarkan program, kegiatan, dan sub kegiatan.
                </p>
            </div>

            <button onclick="openModal()"
                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">
                + Tambah Pagu
            </button>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

            <table id="spjTable" class="display w-full text-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Program</th>
                        <th>Kegiatan</th>
                        <th>Sub Kegiatan</th>
                        <th>Pagu Final</th>
                        <th>Realisasi</th>
                        <th>Sisa</th>
                        <th>Serapan</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pagus as $item)
                        @php
                            $realisasi = $item->realisasi
                                ->where('spj_status', 'Aktif')
                                ->sum('spj_nominal');

                            $sisa = $item->spj_pagu_final - $realisasi;

                            $serapan = $item->spj_pagu_final > 0
                                ? ($realisasi / $item->spj_pagu_final) * 100
                                : 0;

                            if ($serapan > 100) {
                                $serapan = 100;
                            }
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->spj_pagu_tahun }}</td>

                            <td>
                                <div class="font-semibold">
                                    {{ $item->program->program_kode ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $item->program->program_nama ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="font-semibold">
                                    {{ $item->kegiatan->kegiatan_kode ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $item->kegiatan->kegiatan_nama ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="font-semibold">
                                    {{ $item->subKegiatan->sub_kegiatan_kode ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                </div>
                            </td>

                            <td class="font-semibold">
                                Rp {{ number_format($item->spj_pagu_final, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($realisasi, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ number_format($serapan, 2, ',', '.') }}%
                            </td>

                            <td>
                                @if ($item->spj_pagu_status == 1)
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
                                    <button type="button"
                                        onclick='openDetailModal(@json($item))'
                                        class="bg-slate-700 hover:bg-slate-600 px-3 py-1 rounded-lg text-xs">
                                        Detail
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.spj.status', $item->spj_pagu_uid) }}"
                                        onsubmit="return confirm('Ubah status pagu ini?')">
                                        @csrf

                                        @if ($item->spj_pagu_status == 1)
                                            <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                                Nonaktif
                                            </button>
                                        @else
                                            <button class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-xs">
                                                Aktifkan
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-6 text-slate-400">
                                Belum ada data pagu SPJ.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modalPagu" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-3xl p-6 mx-4 max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-xl font-bold">
                    Tambah Pagu SPJ
                </h2>
                <p class="text-sm text-slate-400">
                    Pilih program, kegiatan, sub kegiatan, lalu input pagu induk dan pergeseran.
                </p>
            </div>

            <button onclick="closeModal()" class="text-slate-400 hover:text-white text-xl">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.spj.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 text-sm">
                        Tahun Anggaran
                    </label>

                    <input type="number"
                        name="spj_pagu_tahun"
                        value="{{ date('Y') }}"
                        class="w-full rounded-xl px-4 py-3"
                        required>
                </div>

                <div>
                    <label class="block mb-2 text-sm">
                        Program
                    </label>

                    <select id="programSelect"
                        onchange="filterKegiatan()"
                        class="w-full rounded-xl px-4 py-3"
                        required>
                        <option value="">Pilih Program</option>

                        @foreach ($programs as $program)
                            <option value="{{ $program->program_id }}">
                                {{ $program->program_kode ? $program->program_kode . ' - ' : '' }}
                                {{ $program->program_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 text-sm">
                        Kegiatan
                    </label>

                    <select id="kegiatanSelect"
                        onchange="filterSubKegiatan()"
                        class="w-full rounded-xl px-4 py-3"
                        required>
                        <option value="">Pilih Kegiatan</option>

                        @foreach ($kegiatans as $kegiatan)
                            <option value="{{ $kegiatan->kegiatan_id }}"
                                data-program="{{ $kegiatan->kegiatan_program }}">
                                {{ $kegiatan->kegiatan_kode ? $kegiatan->kegiatan_kode . ' - ' : '' }}
                                {{ $kegiatan->kegiatan_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm">
                        Sub Kegiatan
                    </label>

                    <select name="spj_pagu_sub_kegiatan_id"
                        id="subKegiatanSelect"
                        onchange="setMasterHidden()"
                        class="w-full rounded-xl px-4 py-3"
                        required>
                        <option value="">Pilih Sub Kegiatan</option>

                        @foreach ($subKegiatans as $sub)
                            <option value="{{ $sub->sub_kegiatan_id }}"
                                data-kegiatan="{{ $sub->sub_kegiatan_kegiatan }}">
                                {{ $sub->sub_kegiatan_kode ? $sub->sub_kegiatan_kode . ' - ' : '' }}
                                {{ $sub->sub_kegiatan_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="hidden" name="spj_pagu_program_id" id="spj_pagu_program_id">
            <input type="hidden" name="spj_pagu_kegiatan_id" id="spj_pagu_kegiatan_id">

            <div class="mt-6 mb-3">
                <h3 class="font-semibold">
                    Riwayat Pagu
                </h3>
                <p class="text-xs text-slate-400">
                    Pagu final akan mengikuti nominal pagu paling akhir.
                </p>
            </div>

            <div id="paguContainer">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                    <input type="text"
                        name="pagu_jenis[]"
                        value="Pagu Induk"
                        class="rounded-xl px-4 py-3"
                        required>

                    <input type="number"
                        name="pagu_nominal[]"
                        class="rounded-xl px-4 py-3"
                        placeholder="Nominal"
                        required>

                    <input type="date"
                        name="pagu_tanggal[]"
                        class="rounded-xl px-4 py-3">

                    <input type="text"
                        name="pagu_keterangan[]"
                        class="rounded-xl px-4 py-3"
                        placeholder="Keterangan">
                </div>
            </div>

            <button type="button"
                onclick="tambahPagu()"
                class="mb-5 text-blue-400 hover:text-blue-300 font-semibold">
                + Tambah Pergeseran
            </button>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button"
                    onclick="closeModal()"
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

{{-- MODAL DETAIL --}}
<div id="detailModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-3xl p-6 mx-4 max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-xl font-bold">
                    Detail Pagu SPJ
                </h2>
                <p id="detailSubKegiatan" class="text-sm text-slate-400"></p>
            </div>

            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-white text-xl">✕</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            <div class="bg-slate-800 rounded-2xl p-4 border border-slate-700">
                <p class="text-xs text-slate-400">Pagu Final</p>
                <p id="detailPaguFinal" class="font-bold text-lg text-blue-300"></p>
            </div>

            <div class="bg-slate-800 rounded-2xl p-4 border border-slate-700">
                <p class="text-xs text-slate-400">Total Realisasi</p>
                <p id="detailRealisasi" class="font-bold text-lg text-green-300"></p>
            </div>

            <div class="bg-slate-800 rounded-2xl p-4 border border-slate-700">
                <p class="text-xs text-slate-400">Sisa Pagu</p>
                <p id="detailSisa" class="font-bold text-lg text-amber-300"></p>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold mb-3">Riwayat Pagu</h3>
            <div id="detailPaguList" class="space-y-2"></div>
        </div>

        <div>
            <h3 class="font-bold mb-3">Riwayat SPJ</h3>
            <div id="detailRealisasiList" class="space-y-2"></div>
        </div>

    </div>
</div>

<script>
    function openModal() {
        $('#modalPagu').removeClass('hidden').addClass('flex');
        filterKegiatan();
    }

    function closeModal() {
        $('#modalPagu').addClass('hidden').removeClass('flex');
    }

    function filterKegiatan() {
        const programId = $('#programSelect').val();

        $('#kegiatanSelect').val('');
        $('#subKegiatanSelect').val('');

        $('#spj_pagu_program_id').val(programId);
        $('#spj_pagu_kegiatan_id').val('');

        $('#kegiatanSelect option').each(function () {
            const optionProgram = $(this).data('program');

            if (!$(this).val()) {
                $(this).prop('hidden', false);
            } else {
                $(this).prop('hidden', String(optionProgram) !== String(programId));
            }
        });

        $('#subKegiatanSelect option').each(function () {
            if (!$(this).val()) {
                $(this).prop('hidden', false);
            } else {
                $(this).prop('hidden', true);
            }
        });
    }

    function filterSubKegiatan() {
        const kegiatanId = $('#kegiatanSelect').val();

        $('#subKegiatanSelect').val('');
        $('#spj_pagu_kegiatan_id').val(kegiatanId);

        $('#subKegiatanSelect option').each(function () {
            const optionKegiatan = $(this).data('kegiatan');

            if (!$(this).val()) {
                $(this).prop('hidden', false);
            } else {
                $(this).prop('hidden', String(optionKegiatan) !== String(kegiatanId));
            }
        });
    }

    function setMasterHidden() {
        $('#spj_pagu_program_id').val($('#programSelect').val());
        $('#spj_pagu_kegiatan_id').val($('#kegiatanSelect').val());
    }

    let nomorPergeseran = 1;

    function tambahPagu() {
        nomorPergeseran++;

        $('#paguContainer').append(`
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                <input type="text"
                    name="pagu_jenis[]"
                    value="Pergeseran ${nomorPergeseran}"
                    class="rounded-xl px-4 py-3"
                    required>

                <input type="number"
                    name="pagu_nominal[]"
                    class="rounded-xl px-4 py-3"
                    placeholder="Nominal"
                    required>

                <input type="date"
                    name="pagu_tanggal[]"
                    class="rounded-xl px-4 py-3">

                <input type="text"
                    name="pagu_keterangan[]"
                    class="rounded-xl px-4 py-3"
                    placeholder="Keterangan">
            </div>
        `);
    }

    function rupiah(angka) {
        angka = Number(angka || 0);

        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function openDetailModal(item) {
        const realisasi = (item.realisasi || [])
            .filter(spj => spj.spj_status === 'Aktif')
            .reduce((total, spj) => total + Number(spj.spj_nominal || 0), 0);

        const sisa = Number(item.spj_pagu_final || 0) - realisasi;

        $('#detailSubKegiatan').text(item.sub_kegiatan?.sub_kegiatan_nama ?? item.sub_kegiatan?.sub_kegiatan_nama ?? '-');
        $('#detailPaguFinal').text(rupiah(item.spj_pagu_final));
        $('#detailRealisasi').text(rupiah(realisasi));
        $('#detailSisa').text(rupiah(sisa));

        let paguHtml = '';

        if (item.detail && item.detail.length > 0) {
            item.detail.forEach((detail, index) => {
                paguHtml += `
                    <div class="bg-slate-800 border border-slate-700 rounded-xl p-3">
                        <div class="flex justify-between gap-3">
                            <div>
                                <div class="font-semibold">${index + 1}. ${detail.spj_pagu_detail_jenis ?? '-'}</div>
                                <div class="text-xs text-slate-400">${detail.spj_pagu_detail_keterangan ?? '-'}</div>
                            </div>
                            <div class="font-bold text-blue-300">${rupiah(detail.spj_pagu_detail_nominal)}</div>
                        </div>
                    </div>
                `;
            });
        } else {
            paguHtml = `<div class="text-slate-400">Belum ada riwayat pagu.</div>`;
        }

        $('#detailPaguList').html(paguHtml);

        let realisasiHtml = '';

        if (item.realisasi && item.realisasi.length > 0) {
            item.realisasi
                .filter(spj => spj.spj_status === 'Aktif')
                .forEach((spj, index) => {
                    realisasiHtml += `
                        <div class="bg-slate-800 border border-slate-700 rounded-xl p-3">
                            <div class="flex justify-between gap-3">
                                <div>
                                    <div class="font-semibold">${index + 1}. ${spj.spj_uraian ?? '-'}</div>
                                    <div class="text-xs text-slate-400">Operator: ${spj.spj_operator_nama ?? '-'}</div>
                                </div>
                                <div class="font-bold text-green-300">${rupiah(spj.spj_nominal)}</div>
                            </div>
                        </div>
                    `;
                });
        }

        if (!realisasiHtml) {
            realisasiHtml = `<div class="text-slate-400">Belum ada realisasi SPJ.</div>`;
        }

        $('#detailRealisasiList').html(realisasiHtml);

        $('#detailModal').removeClass('hidden').addClass('flex');
    }

    function closeDetailModal() {
        $('#detailModal').addClass('hidden').removeClass('flex');
    }

    $(document).ready(function () {
        $('#spjTable').DataTable({
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                emptyTable: "Belum ada data pagu SPJ",
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
$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#spjTable')) {
        $('#spjTable').DataTable().destroy();
    }

    $('#spjTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[1, 'desc']],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            emptyTable: "Belum ada data pagu SPJ",
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