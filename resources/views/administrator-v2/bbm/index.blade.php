@extends('administrator-v2.layouts.app')

@section('title', 'Pengajuan BBM')

@section('page-title', 'Pengajuan BBM')

@section('page-description', 'Kelola data pengajuan BBM pegawai SAPLARIN')

@section('content')

    @if (session('success'))
        <div
            class="mb-6 rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-5 py-4">

            <div class="flex items-center gap-3">

                <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>

                <span class="text-green-700 dark:text-green-300">

                    {{ session('success') }}

                </span>

            </div>

        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">

            <div class="flex items-center gap-3">

                <i class="bi bi-x-circle-fill text-red-600 text-xl"></i>

                <span class="text-red-700 dark:text-red-300">

                    {{ session('error') }}

                </span>

            </div>

        </div>
    @endif

    @if ($errors->any())

        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">

            <div class="flex items-start gap-3">

                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl mt-1"></i>

                <div>

                    <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">

                        Terjadi Kesalahan

                    </h4>

                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>

                                {{ $error }}

                            </li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

        <form method="GET">

            <div class="relative w-full lg:w-80">

                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Pengaju / NIP / Plat..."
                    class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 pl-11 pr-4 py-3">

            </div>

        </form>

        <button type="button" onclick="openExportModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-white font-semibold">

            <i class="bi bi-file-earmark-excel"></i>

            Export Excel

        </button>

    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="px-4 py-4 text-left">No</th>

                        <th class="px-4 py-4 text-left">Pengaju</th>

                        <th class="px-4 py-4 text-left">No Plat</th>

                        <th class="px-4 py-4 text-left">Liter</th>

                        <th class="px-4 py-4 text-left">Uraian</th>

                        <th class="px-4 py-4 text-left">Status Pengajuan</th>

                        <th class="px-4 py-4 text-left">Status Nota</th>

                        <th class="px-4 py-4 text-left">File</th>

                        <th class="px-4 py-4 text-left">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($bbms as $item)

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

                        <tr class="border-t border-slate-200 dark:border-slate-800">

                            <td class="px-4 py-4">

                                {{ $loop->iteration }}

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-semibold text-slate-800 dark:text-white">

                                    {{ $item->bbm_pengaju_nama }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $item->bbm_pengaju_nip }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $item->bbm_bidang_nama }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                <span
                                    class="inline-flex rounded-lg bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold">

                                    {{ $item->bbm_no_plat ?? '-' }}

                                </span>

                            </td>

                            <td class="px-4 py-4">

                                {{ number_format($item->bbm_liter, 2, ',', '.') }} L

                            </td>

                            <td class="px-4 py-4">

                                <div class="max-w-xs">

                                    {{ \Illuminate\Support\Str::limit($item->bbm_uraian_kegiatan, 70) }}

                                </div>

                            </td>
                            <td class="px-4 py-4">

                                @if ($item->bbm_status_pengajuan === 'Pengajuan Diterima')
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">

                                        {{ $item->bbm_status_pengajuan }}

                                    </span>
                                @elseif($item->bbm_status_pengajuan === 'Pengajuan Ditolak')
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">

                                        {{ $item->bbm_status_pengajuan }}

                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900/20 px-3 py-1 text-xs font-semibold text-yellow-700 dark:text-yellow-300">

                                        {{ $item->bbm_status_pengajuan }}

                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-4">

                                @if ($item->bbm_status_laporan === 'Laporan Nota Diterima')
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">

                                        {{ $item->bbm_status_laporan }}

                                    </span>
                                @elseif($item->bbm_status_laporan === 'Laporan Nota Ditolak')
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">

                                        {{ $item->bbm_status_laporan }}

                                    </span>
                                @elseif($item->bbm_status_laporan === 'Menunggu Verifikasi')
                                    <span
                                        class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900/20 px-3 py-1 text-xs font-semibold text-yellow-700 dark:text-yellow-300">

                                        {{ $item->bbm_status_laporan }}

                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-600 dark:text-slate-300">

                                        {{ $item->bbm_status_laporan }}

                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-4">

                                <button type="button"
                                    onclick='openFileModal(@json($item), @json($buktiTambahan))'
                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-3 py-2 text-xs font-semibold text-white transition">

                                    <i class="bi bi-folder2-open"></i>

                                    Lihat File

                                </button>

                                @if (count($buktiTambahan))
                                    <div class="mt-2 text-xs text-cyan-600 dark:text-cyan-400">

                                        {{ count($buktiTambahan) }} Bukti Tambahan

                                    </div>
                                @endif

                            </td>

                            <td class="px-4 py-4">

                                <div class="flex flex-wrap gap-2">

                                    @if ($item->bbm_status_pengajuan === 'Menunggu Verifikasi')
                                        <button type="button" onclick='openTerimaModal(@json($item))'
                                            class="inline-flex items-center rounded-lg bg-green-600 hover:bg-green-700 px-3 py-2 text-xs font-medium text-white transition">

                                            Terima

                                        </button>

                                        <form method="POST"
                                            action="{{ route('admin.bbm.tolakPengajuan', $item->bbm_uid) }}"
                                            onsubmit="return confirm('Tolak pengajuan BBM ini?')">

                                            @csrf

                                            <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-red-600 hover:bg-red-700 px-3 py-2 text-xs font-medium text-white transition">

                                                Tolak

                                            </button>

                                        </form>
                                    @endif

                                    @if ($item->bbm_status_laporan === 'Menunggu Verifikasi')
                                        <form method="POST"
                                            action="{{ route('admin.bbm.terimaLaporan', $item->bbm_uid) }}"
                                            onsubmit="return confirm('Terima laporan nota ini?')">

                                            @csrf

                                            <button
                                                class="inline-flex items-center rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-2 text-xs font-medium text-white transition">

                                                Terima Nota

                                            </button>

                                        </form>

                                        <form method="POST" action="{{ route('admin.bbm.tolakLaporan', $item->bbm_uid) }}"
                                            onsubmit="return confirm('Tolak laporan nota ini?')">

                                            @csrf

                                            <button
                                                class="inline-flex items-center rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-2 text-xs font-medium text-white transition">

                                                Tolak Nota

                                            </button>

                                        </form>
                                    @endif
                                    @if ($item->bbm_status_pengajuan === 'Pengajuan Diterima')
                                        @if ($perluSinkron)
                                            <form method="POST" action="{{ route('admin.bbm.sinkron', $item->bbm_uid) }}"
                                                onsubmit="return confirm('Sinkron semua file pengajuan ini ke Google Drive? File lokal akan dihapus jika file Drive ditemukan.')">

                                                @csrf

                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-purple-600 hover:bg-purple-700 px-3 py-2 text-xs font-medium text-white transition">

                                                    <i class="bi bi-arrow-repeat"></i>

                                                    Sinkron Drive

                                                </button>

                                            </form>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-2 rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-2 text-xs font-semibold text-green-700 dark:text-green-300">

                                                <i class="bi bi-check-circle-fill"></i>

                                                Sudah Sinkron

                                            </span>
                                        @endif
                                    @endif

                                    @if (
                                        $item->bbm_status_pengajuan !== 'Menunggu Verifikasi' &&
                                            $item->bbm_status_pengajuan !== 'Pengajuan Diterima' &&
                                            $item->bbm_status_laporan !== 'Menunggu Verifikasi')
                                        <span class="text-xs text-slate-500">

                                            Tidak ada aksi

                                        </span>
                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="px-6 py-12 text-center">

                                <div class="flex flex-col items-center gap-4">

                                    <div
                                        class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">

                                        <i class="bi bi-inbox text-3xl text-slate-400"></i>

                                    </div>

                                    <div>

                                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">

                                            Belum Ada Data

                                        </h3>

                                        <p class="text-sm text-slate-500">

                                            Belum ada data pengajuan BBM.

                                        </p>

                                    </div>

                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
        <div
            class="flex flex-col md:flex-row items-center justify-between gap-4 px-6 py-5 border-t border-slate-200 dark:border-slate-700">

            <div class="text-sm text-slate-500">

                Menampilkan

                <b>{{ $bbms->firstItem() }}</b>

                -

                <b>{{ $bbms->lastItem() }}</b>

                dari

                <b>{{ $bbms->total() }}</b>

                data

            </div>

            {{ $bbms->links() }}

        </div>

    </div>

    {{-- MODAL FILE --}}
    <div id="fileModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-4xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Detail File BBM

                    </h3>

                    <p id="fileModalInfo" class="text-sm text-slate-500 dark:text-slate-400">

                    </p>

                </div>

                <button type="button" onclick="closeFileModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <div id="fileList" class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 max-h-[70vh] overflow-y-auto">

            </div>

        </div>

    </div>
    {{-- MODAL TERIMA PENGAJUAN --}}
    <div id="terimaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Terima Pengajuan BBM

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Upload dokumen ACC pimpinan sebelum pengajuan disetujui.

                    </p>

                </div>

                <button type="button" onclick="closeTerimaModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form id="terimaForm" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Pengaju

                        </label>

                        <input type="text" id="modal_pengaju" readonly
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Nomor Plat

                        </label>

                        <input type="text" id="modal_no_plat" readonly
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Upload ACC Pimpinan

                        </label>

                        <input type="file" name="bbm_acc_pimpinan_file" required
                            class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white">

                        <p class="mt-2 text-xs text-slate-500">

                            Format: PDF, JPG, JPEG, PNG, DOC, DOCX (maks. 5 MB)

                        </p>

                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeTerimaModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-green-600 hover:bg-green-700 px-5 py-2.5 font-semibold text-white transition">

                        <i class="bi bi-check-circle me-2"></i>

                        Upload & Setujui

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- MODAL EXPORT --}}
    <div id="exportModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-3xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Export Excel

                </h3>

                <button type="button" onclick="closeExportModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form action="{{ route('admin.bbm.export') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 border-b border-slate-200 dark:border-slate-700">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Bulan
                        </label>

                        <select name="bulan"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                   bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="">Semua Bulan</option>

                            @foreach ([
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
                            ] as $nomor => $nama)
                                <option value="{{ $nomor }}">
                                    {{ $nama }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Tahun
                        </label>

                        <select name="tahun"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                   bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="">Semua Tahun</option>

                            @for ($tahun = now()->year; $tahun >= 2025; $tahun--)
                                <option value="{{ $tahun }}" {{ $tahun == now()->year ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endfor

                        </select>
                    </div>

                </div>


                <div class="px-6 pt-5">
                    <div
                        class="rounded-2xl bg-green-50 dark:bg-green-900/20
                border border-green-200 dark:border-green-800
                p-4">

                        <div class="flex items-start gap-3">

                            <i class="bi bi-check-circle-fill text-green-600 text-lg mt-0.5"></i>

                            <div>
                                <div class="font-semibold text-green-700 dark:text-green-300">
                                    Data yang diexport
                                </div>

                                <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                                    Hanya BBM dengan pengajuan diterima dan laporan nota sudah diterima.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-6">
                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="pengaju_nama" checked>

                        Nama Pengaju

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="pengaju_nip" checked>

                        NIP

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="bidang">

                        Bidang

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="plat" checked>

                        No Plat

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="liter" checked>

                        Liter

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="uraian">

                        Uraian

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="status_pengajuan">

                        Status Pengajuan

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="status_laporan">

                        Status Laporan

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="tanggal_pengajuan">

                        Tanggal Pengajuan

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="file_spt">

                        File SPT

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="file_acc">

                        File ACC Pimpinan

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="file_nota">

                        File Nota

                    </label>

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="fields[]" value="bukti_tambahan">

                        Bukti Tambahan

                    </label>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeExportModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 font-semibold text-white transition">

                        <i class="bi bi-download me-2"></i>

                        Download Excel

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        function safeValue(value) {
            return value ?? '';
        }

        function showModal(id) {
            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }

        function hideModal(id) {
            const modal = document.getElementById(id);

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }

        function fileButton(label, url, colorClass) {

            if (!url) return '';

            return `
            <a
                href="${url}"
                target="_blank"
                class="block rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-4 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-slate-700 transition">

                <div class="${colorClass} font-semibold">

                    <i class="bi bi-file-earmark-arrow-down me-2"></i>

                    ${label}

                </div>

                <div class="text-xs text-slate-500 mt-2">

                    Klik untuk membuka file

                </div>

            </a>
        `;
        }

        function openFileModal(item, buktiTambahan) {

            let html = '';

            html += fileButton(
                'SPT',
                safeValue(item.bbm_spt_file),
                'text-green-600'
            );

            html += fileButton(
                'ACC Pimpinan',
                safeValue(item.bbm_acc_pimpinan_file),
                'text-purple-600'
            );

            html += fileButton(
                'Nota BBM',
                safeValue(item.bbm_laporan_nota_file),
                'text-yellow-600'
            );

            html += fileButton(
                'Foto Kendaraan',
                safeValue(item.bbm_foto_mobil_file),
                'text-blue-600'
            );

            if (Array.isArray(buktiTambahan)) {

                buktiTambahan.forEach((file, index) => {

                    let url = '';

                    let nama = 'Bukti Tambahan ' + (index + 1);

                    if (typeof file === 'string') {

                        url = file;

                    } else if (typeof file === 'object' && file !== null) {

                        url = file.file ?? file.url ?? '';

                        nama = file.nama ?? nama;

                    }

                    if (!url) return;

                    html += `
                    <a
                        href="${url}"
                        target="_blank"
                        class="block rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-4 hover:border-cyan-500 hover:bg-cyan-50 dark:hover:bg-slate-700 transition">

                        <div class="text-cyan-600 font-semibold">

                            <i class="bi bi-paperclip me-2"></i>

                            ${nama}

                        </div>

                        <div class="text-xs text-slate-500 mt-2">

                            Klik untuk membuka file

                        </div>

                    </a>
                `;
                });

            }

            if (html === '') {

                html = `
                <div class="col-span-2 text-center py-12 text-slate-500">

                    Tidak ada file yang tersedia.

                </div>
            `;

            }

            document.getElementById('fileModalInfo').textContent =
                (item.bbm_pengaju_nama ?? '-') +
                ' / ' +
                (item.bbm_no_plat ?? '-');

            document.getElementById('fileList').innerHTML = html;

            showModal('fileModal');

        }

        function closeFileModal() {

            document.getElementById('fileList').innerHTML = '';

            hideModal('fileModal');

        }

        function openExportModal() {

            showModal('exportModal');

        }

        function closeExportModal() {

            hideModal('exportModal');

        }

        function openTerimaModal(item) {

            document.getElementById('modal_pengaju').value =
                (item.bbm_pengaju_nama ?? '') +
                ' - ' +
                (item.bbm_pengaju_nip ?? '');

            document.getElementById('modal_no_plat').value =
                item.bbm_no_plat ?? '-';

            document.getElementById('terimaForm').action =
                "{{ url('/admin/bbm') }}/" + item.bbm_uid + "/terima-pengajuan";

            showModal('terimaModal');

        }

        function closeTerimaModal() {

            document.getElementById('terimaForm').reset();

            hideModal('terimaModal');

        }

        document.addEventListener('DOMContentLoaded', function() {

            if (typeof DataTable !== 'undefined') {

                new DataTable('#bbmTable', {

                    responsive: true,

                    pageLength: 25,

                    order: [
                        [0, 'desc']
                    ],

                    language: {

                        search: "Cari:",

                        searchPlaceholder: "Cari data...",

                        lengthMenu: "Tampilkan _MENU_ data",

                        zeroRecords: "Data tidak ditemukan",

                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                        infoEmpty: "Tidak ada data",

                        infoFiltered: "(difilter dari _MAX_ data)",

                        paginate: {

                            first: "Awal",

                            last: "Akhir",

                            next: "›",

                            previous: "‹"

                        }

                    }

                });

            }

            ['fileModal', 'exportModal', 'terimaModal'].forEach(function(id) {

                const modal = document.getElementById(id);

                if (!modal) return;

                modal.addEventListener('click', function(e) {

                    if (e.target === modal) {

                        hideModal(id);

                    }

                });

            });

        });

        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                hideModal('fileModal');

                hideModal('exportModal');

                hideModal('terimaModal');

            }

        });
    </script>
@endpush
