<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Laporan SHS</title>

    <link rel="icon" href="{{ asset('image/pemprov.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            background: #020617;
        }

        table.dataTable {
            color: #e2e8f0 !important;
        }

        table.dataTable thead th {
            color: #cbd5e1 !important;
            white-space: nowrap;
        }

        table.dataTable tbody tr {
            background: #0f172a !important;
        }

        table.dataTable tbody tr:hover {
            background: #1e293b !important;
        }

        .dataTables_wrapper {
            color: #cbd5e1;
        }

        .dataTables_filter input,
        .dataTables_length select {

            background: #1e293b !important;

            color: white !important;

            border: 1px solid #334155 !important;

            border-radius: 10px;

            padding: 6px 10px;

        }

        .dataTables_paginate .paginate_button.current {

            background: #2563eb !important;

            color: white !important;

            border: none !important;

            border-radius: 8px;

        }

        table.dataTable {

            width: 100% !important;

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

                        Laporan SHS

                    </h1>

                    <p class="text-slate-400 text-sm">

                        Verifikasi usulan Standar Harga Satuan dari seluruh operator.

                    </p>

                </div>

            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 overflow-x-auto">

                <table id="datatable" class="display nowrap w-full">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Tahun</th>

                            <th>Unit</th>

                            <th>Barang</th>

                            <th>Kelompok</th>

                            <th>Harga</th>

                            <th>Operator</th>

                            <th>Status</th>

                            <th width="240">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($shs as $item)
                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    {{ $item->shs_tahun }}

                                </td>

                                <td>

                                    {{ $item->shs_unit_nama }}

                                </td>

                                <td>

                                    <div class="font-semibold">

                                        {{ $item->shs_barang }}

                                    </div>

                                    <div class="text-xs text-slate-400">

                                        {{ $item->shs_satuan }}

                                    </div>

                                </td>

                                <td>

                                    {{ $item->shs_kelompok_barang }}

                                </td>

                                <td>

                                    Rp {{ number_format($item->shs_harga, 0, ',', '.') }}

                                </td>

                                <td>

                                    <div class="font-semibold">

                                        {{ $item->shs_operator_nama }}

                                    </div>

                                    <div class="text-xs text-slate-400">

                                        {{ $item->shs_operator_nip }}

                                    </div>

                                </td>

                                <td>
                                    @if ($item->shs_status == 'Diajukan')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full bg-green-500/20 text-green-300 text-xs font-semibold">

                                            Diajukan

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-xs font-semibold">

                                            Tidak Diajukan

                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="flex flex-wrap gap-2">

                                        <button onclick='detailSHS(@json($item))'
                                            class="bg-slate-700 hover:bg-slate-600 px-3 py-2 rounded-lg text-sm">

                                            Detail

                                        </button>

                                        @if ($item->shs_status == 'Draft')
                                            <button onclick='verifikasiSHS(@json($item))'
                                                class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg text-sm">

                                                Verifikasi

                                            </button>
                                        @elseif($item->shs_status == 'Tidak Diajukan')
                                            <form method="POST"
                                                action="{{ route('admin.laporan.shs.aktif', $item->shs_uid) }}">

                                                @csrf

                                                <button
                                                    class="bg-green-600 hover:bg-green-700 px-3 py-2 rounded-lg text-sm">

                                                    Diajukan

                                                </button>

                                            </form>
                                        @elseif($item->shs_status == 'Diajukan')
                                            <form method="POST"
                                                action="{{ route('admin.laporan.shs.nonaktif', $item->shs_uid) }}">

                                                @csrf

                                                <button
                                                    class="bg-red-600 hover:bg-red-700 px-3 py-2 rounded-lg text-sm">

                                                    Tidak Diajukan

                                                </button>

                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ route('admin.laporan.shs.aktif', $item->shs_uid) }}">

                                                @csrf

                                                <button
                                                    class="bg-green-600 hover:bg-green-700 px-3 py-2 rounded-lg text-sm">

                                                    Diajukan

                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    </div>
    {{-- ======================= MODAL DETAIL ======================= --}}

    <div id="modalDetail" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-5">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-6xl max-h-[90vh] flex flex-col">

            <div class="flex justify-between items-center px-6 py-5 border-b border-slate-700 flex-shrink-0">

                <div>

                    <h2 class="text-2xl font-bold">

                        Detail Usulan SHS

                    </h2>

                    <p class="text-slate-400 text-sm">

                        Detail usulan yang dikirim operator.

                    </p>

                </div>

                <button onclick="closeDetail()" class="text-3xl text-slate-400 hover:text-white">

                    ×

                </button>

            </div>

            <div class="p-6 overflow-y-auto flex-1">

                <div class="grid grid-cols-2 gap-6">

                    <div>

                        <label class="text-slate-400 text-sm">

                            Tahun

                        </label>

                        <input id="d_tahun"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div>

                        <label class="text-slate-400 text-sm">

                            Unit

                        </label>

                        <input id="d_unit"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div>

                        <label class="text-slate-400 text-sm">

                            Kelompok Barang

                        </label>

                        <input id="d_kelompok"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div>

                        <label class="text-slate-400 text-sm">

                            Kode Kelompok

                        </label>

                        <input id="d_kode"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div class="col-span-2">

                        <label class="text-slate-400 text-sm">

                            Nama Barang

                        </label>

                        <input id="d_barang"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div>

                        <label class="text-slate-400 text-sm">

                            Satuan

                        </label>

                        <input id="d_satuan"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div>

                        <label class="text-slate-400 text-sm">

                            Kelompok SHS

                        </label>

                        <input id="d_tipe"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div>

                        <label class="text-slate-400 text-sm">

                            Harga

                        </label>

                        <input id="d_harga"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div>

                        <label class="text-slate-400 text-sm">

                            TKDN

                        </label>

                        <input id="d_tkdn"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly>

                    </div>

                    <div class="col-span-2">

                        <label class="text-slate-400 text-sm">

                            Spesifikasi

                        </label>

                        <textarea id="d_spesifikasi" rows="7"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" readonly></textarea>

                    </div>

                    <div class="col-span-2">

                        <label class="text-slate-400 text-sm">

                            Link Survei

                        </label>

                        <textarea id="d_link" rows="5" class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3"
                            readonly></textarea>

                    </div>

                </div>

                <div class="border-t border-slate-700 mt-8 pt-6">

                    <h3 class="font-bold text-lg">

                        Operator

                    </h3>

                    <div class="grid grid-cols-2 gap-5 mt-5">

                        <input id="d_operator" class="rounded-xl bg-slate-800 border border-slate-700 px-4 py-3"
                            readonly>

                        <input id="d_nip" class="rounded-xl bg-slate-800 border border-slate-700 px-4 py-3"
                            readonly>

                    </div>

                </div>

            </div>

        </div>

    </div>





    {{-- ======================= MODAL VERIFIKASI ======================= --}}

    <div id="modalVerifikasi" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-5">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl">

            <form id="formVerifikasi" method="POST">

                @csrf

                <div class="p-6">

                    <h2 class="text-xl font-bold">

                        Verifikasi SHS

                    </h2>

                    <p class="text-slate-400 text-sm mt-1">

                        Berikan catatan apabila diperlukan.

                    </p>

                    <div class="mt-6">

                        <label>

                            Catatan Admin

                        </label>

                        <textarea name="shs_catatan_admin" rows="6"
                            class="w-full mt-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-3" placeholder="Catatan verifikasi..."></textarea>

                    </div>

                    <div class="flex justify-end gap-3 mt-8">

                        <button type="button" onclick="closeVerifikasi()"
                            class="bg-slate-700 hover:bg-slate-600 px-5 py-3 rounded-xl">

                            Batal

                        </button>

                        <button class="bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl">

                            Verifikasi

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <script>
        $(document).ready(function() {

            $('#datatable').DataTable({

                responsive: true,

                autoWidth: false,

                scrollX: true,

                pageLength: 10,

                language: {

                    search: "Cari :",

                    lengthMenu: "Tampilkan _MENU_ data",

                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                    zeroRecords: "Data tidak ditemukan",

                    emptyTable: "Belum ada data",

                    paginate: {

                        previous: "Sebelumnya",

                        next: "Berikutnya"

                    }

                }

            });

        });



        function detailSHS(item) {

            $('#d_tahun').val(item.shs_tahun);

            $('#d_unit').val(item.shs_unit_nama);

            $('#d_kelompok').val(item.shs_kelompok_barang);

            $('#d_kode').val(item.shs_kode_kelompok);

            $('#d_barang').val(item.shs_barang);

            $('#d_satuan').val(item.shs_satuan);

            $('#d_tipe').val(item.shs_kelompok);

            $('#d_harga').val(

                'Rp ' +

                Number(item.shs_harga).toLocaleString('id-ID')

            );

            $('#d_tkdn').val(

                item.shs_tkdn == null

                ?
                '-'

                :
                item.shs_tkdn + ' %'

            );

            $('#d_spesifikasi').val(item.shs_spesifikasi);

            $('#d_link').val(item.shs_link_survei);

            $('#d_operator').val(item.shs_operator_nama);

            $('#d_nip').val(item.shs_operator_nip);

            $('#modalDetail')

                .removeClass('hidden')

                .addClass('flex');

        }



        function closeDetail() {

            $('#modalDetail')

                .removeClass('flex')

                .addClass('hidden');

        }



        function verifikasiSHS(item) {

            $('#formVerifikasi').attr(

                'action',

                '/admin/laporan-shs/' +

                item.shs_uid +

                '/verifikasi'

            );



            $('#modalVerifikasi')

                .removeClass('hidden')

                .addClass('flex');

        }



        function closeVerifikasi() {

            $('#modalVerifikasi')

                .removeClass('flex')

                .addClass('hidden');

        }



        $(window).click(function(e) {

            if ($(e.target).is('#modalDetail')) {

                closeDetail();

            }

            if ($(e.target).is('#modalVerifikasi')) {

                closeVerifikasi();

            }

        });
    </script>

</body>

</html>
