@extends('user.layouts.app')

@section('title', 'Laporan Sub Kegiatan')
@section('page_title', 'Laporan Sub Kegiatan')
@section('breadcrumb', 'Laporan Sub Kegiatan')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Laporan Sub Kegiatan
                    </h2>
                    <p class="text-sm text-slate-500">
                        Input realisasi indikator, permasalahan, solusi, dan tindak lanjut.
                    </p>
                </div>

                <a href="{{ route('user.laporan-sub-kegiatan.create') }}"
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    + Input Laporan
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 overflow-x-auto">

            <table id="tableLaporan" class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-slate-500">
                        <th class="py-3 px-3">No</th>
                        <th class="py-3 px-3">Bulan/Tahun</th>
                        <th class="py-3 px-3">Sub Kegiatan</th>
                        <th class="py-3 px-3">Capaian</th>
                        <th class="py-3 px-3">Operator</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Ringkasan</th>
                        <th class="py-3 px-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($laporans as $laporan)
                        @php
                            $totalTarget = $laporan->detail->sum('detail_target');
                            $totalRealisasi = $laporan->detail->sum('detail_realisasi');
                            $persen = $totalTarget > 0 ? ($totalRealisasi / $totalTarget) * 100 : 0;
                            if ($persen > 100) {
                                $persen = 100;
                            }

                            $bulanNama = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp

                        <tr class="border-b hover:bg-slate-50">
                            <td class="py-3 px-3">
                                {{ $loop->iteration }}
                            </td>

                            <td class="py-3 px-3">
                                {{ $bulanNama[$laporan->laporan_bulan] ?? '-' }} {{ $laporan->laporan_tahun }}
                            </td>

                            <td class="py-3 px-3">
                                <div class="font-semibold text-slate-800">
                                    {{ $laporan->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $laporan->subKegiatan->sub_kegiatan_kode ?? '-' }}
                                </div>
                            </td>

                            <td class="py-3 px-3">
                                <div class="font-bold text-blue-700">
                                    {{ number_format($persen, 2, ',', '.') }}%
                                </div>
                                <div class="w-36 bg-slate-100 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $persen }}%">
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-3">
                                <div class="font-semibold text-slate-800">
                                    {{ $laporan->laporan_created_by_nama ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $laporan->laporan_created_by_nip ?? '-' }}
                                </div>
                            </td>

                            <td class="py-3 px-3">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    {{ $laporan->laporan_status }}
                                </span>
                            </td>

                            <td class="py-3 px-3">
                                <div class="text-xs text-slate-600">
                                    Indikator: {{ $laporan->detail->count() }}
                                </div>
                                <div class="text-xs text-slate-600">
                                    Masalah: {{ $laporan->permasalahan->count() }}
                                </div>
                                <div class="text-xs text-slate-600">
                                    Solusi: {{ $laporan->solusi->count() }}
                                </div>
                                <div class="text-xs text-slate-600">
                                    Tindak lanjut: {{ $laporan->tindakLanjut->count() }}
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex gap-2">

                                    <button onclick="lihatData('{{ $laporan->laporan_uid }}')"
                                        class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">

                                        Lihat Data

                                    </button>

                                    @if ($laporan->laporan_catatan_admin)
                                        <button
                                            onclick="lihatCatatan(
                    @js($laporan->laporan_catatan_admin),
                    @js($laporan->laporan_catatan_by),
                    @js(optional($laporan->laporan_catatan_at)->format('d-m-Y H:i'))
                )"
                                            class="px-3 py-2 rounded-xl bg-amber-100 text-amber-700 text-xs font-semibold">

                                            💬 Catatan

                                        </button>
                                    @endif

                                    @if ($laporan->laporan_created_by == session('pegawai_id'))
                                        <a href="{{ route('user.laporan-sub-kegiatan.edit', $laporan->laporan_uid) }}"
                                            class="px-3 py-2 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold">

                                            Edit

                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        <script>
                            window.laporanData = window.laporanData || {};

                            window.laporanData['{{ $laporan->laporan_uid }}'] = {
                                sub: @json($laporan->subKegiatan->sub_kegiatan_nama ?? '-'),
                                indikator: @json($laporan->detail),
                                masalah: @json($laporan->permasalahan),
                                solusi: @json($laporan->solusi),
                                tindak: @json($laporan->tindakLanjut)
                            };
                        </script>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">
                                Belum ada laporan sub kegiatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div id="modalLihat" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

            <div class="bg-white rounded-3xl shadow-xl w-full max-w-3xl p-6">

                <div class="flex justify-between mb-5">
                    <h3 class="text-lg font-bold">
                        Detail Laporan
                    </h3>

                    <button onclick="closeLihat()" class="text-slate-500">
                        ✕
                    </button>
                </div>

                <div id="detailLaporan" class="space-y-3 max-h-[70vh] overflow-y-auto">
                </div>

            </div>

        </div>

        <div id="modalCatatan" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

            <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6">

                <div class="flex justify-between mb-5">
                    <h3 class="text-lg font-bold">
                        Catatan Admin
                    </h3>

                    <button onclick="closeCatatan()" class="text-slate-500">
                        ✕
                    </button>
                </div>

                <div class="space-y-4">

                    <div id="catatanIsi" class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                    </div>

                    <div id="catatanInfo" class="text-xs text-slate-500">
                    </div>

                </div>

            </div>

        </div>

    </div>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

        <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if ($.fn.DataTable.isDataTable('#tableLaporan')) {
                    $('#tableLaporan').DataTable().destroy();
                }

                $('#tableLaporan').DataTable({
                    pageLength: 10,
                    responsive: true,
                    order: [
                        [1, 'asc']
                    ],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        zeroRecords: "Data tidak ditemukan",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "→",
                            previous: "←"
                        }
                    }
                });
            });

            function lihatCatatan(catatan, by, tanggal) {

                document.getElementById('catatanIsi').innerHTML =
                    catatan ?? '-';

                document.getElementById('catatanInfo').innerHTML =
                    'Dikirim oleh ' +
                    (by ?? '-') +
                    ' pada ' +
                    (tanggal ?? '-');

                const modal =
                    document.getElementById('modalCatatan');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeCatatan() {
                const modal =
                    document.getElementById('modalCatatan');

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        </script>
        <script>
            function lihatData(uid) {
                let data = window.laporanData[uid];

                let html = '';

                html += `
        <div>
            <h4 class="font-bold text-blue-700 mb-3">
                ${data.sub}
            </h4>
        </div>
    `;

                html += `
        <div class="mb-5">
            <div class="font-semibold mb-2">
                Indikator
            </div>
    `;

                data.indikator.forEach(item => {
                    html += `
            <div class="border rounded-xl p-3 mb-2">
                <div>${item.detail_indikator_nama}</div>
                <div class="text-sm text-slate-500">
                    Target :
                    ${item.detail_target}
                    ${item.detail_satuan}
                </div>
                <div class="text-sm text-green-600">
                    Realisasi :
                    ${item.detail_realisasi}
                    ${item.detail_satuan}
                </div>
            </div>
        `;
                });

                html += `</div>`;

                if (data.masalah.length > 0) {
                    html += `<div class="mb-4">
            <div class="font-semibold mb-2">
                Permasalahan
            </div>`;

                    data.masalah.forEach(item => {
                        html += `
                <div class="bg-red-50 rounded-xl p-3 mb-2">
                    ${item.permasalahan_uraian}
                </div>
            `;
                    });

                    html += `</div>`;
                }

                if (data.solusi.length > 0) {
                    html += `<div class="mb-4">
            <div class="font-semibold mb-2">
                Solusi
            </div>`;

                    data.solusi.forEach(item => {
                        html += `
                <div class="bg-green-50 rounded-xl p-3 mb-2">
                    ${item.solusi_uraian}
                </div>
            `;
                    });

                    html += `</div>`;
                }

                if (data.tindak.length > 0) {
                    html += `<div>
            <div class="font-semibold mb-2">
                Tindak Lanjut
            </div>`;

                    data.tindak.forEach(item => {
                        html += `
                <div class="bg-blue-50 rounded-xl p-3 mb-2">
                    ${item.tindak_lanjut_uraian}
                </div>
            `;
                    });

                    html += `</div>`;
                }

                document.getElementById('detailLaporan')
                    .innerHTML = html;

                document.getElementById('modalLihat')
                    .classList.remove('hidden');

                document.getElementById('modalLihat')
                    .classList.add('flex');
            }

            function closeLihat() {
                document.getElementById('modalLihat')
                    .classList.add('hidden');

                document.getElementById('modalLihat')
                    .classList.remove('flex');
            }
        </script>
    @endpush
@endsection
