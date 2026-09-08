@extends('user.layouts.app')

@section('title', 'SPJ')
@section('page_title', 'Input SPJ')
@section('breadcrumb', 'Input SPJ')

@php
    $tahunList = $pagus->pluck('spj_pagu_tahun')->unique()->sortDesc()->values();

    $canInputSPJ = session('active_role') === 'Operator SPJ';
@endphp

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

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h2 class="text-2xl font-bold">
                        Input SPJ
                    </h2>

                    <p class="text-blue-100 text-sm mt-2">
                        Operator menginput uraian SPJ, nominal, tanggal SPJ, dan file bukti SPJ berdasarkan unit pengampu
                        pagu.
                    </p>
                </div>

                <button type="button" onclick="bukaTutorialSPJ()"
                    class="inline-flex items-center justify-center gap-2
                       px-5 py-3
                       rounded-2xl
                       bg-white/15
                       border border-white/30
                       text-white
                       font-semibold
                       hover:bg-white/25
                       transition
                       whitespace-nowrap">

                    <span class="text-lg">📖</span>
                    Panduan SPJ

                </button>

            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Filter Tahun Anggaran
                    </label>

                    <select id="filterTahun"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white text-slate-800">
                        <option value="">Semua Tahun</option>

                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Filter Unit
                    </label>

                    <select id="filterUnit"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white text-slate-800">
                        <option value="">Semua Unit</option>

                        @foreach ($units as $unit)
                            <option value="{{ $unit->unit_id }}">
                                {{ $unit->unit_kode }} - {{ $unit->unit_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Cari Program / Kegiatan / Sub Kegiatan / Unit
                    </label>

                    <input type="text" id="searchPagu" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        placeholder="Ketik nama unit, program, kegiatan, sub kegiatan, atau kode...">
                </div>

            </div>

            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="text-sm text-slate-500">
                    Menampilkan
                    <span id="showingInfo" class="font-semibold text-slate-800">0</span>
                    data.
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-slate-500">Per halaman</label>

                    <select id="perPage" class="rounded-xl border border-slate-200 px-3 py-2 bg-white text-slate-800">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="paguWrapper" class="space-y-6">

            @forelse ($pagus as $item)

                @php
                    $totalRealisasi = $item->realisasi->where('spj_status', 'Aktif')->sum('spj_nominal');

                    $sisaPagu = $item->spj_pagu_final - $totalRealisasi;

                    $persenSerapan = $item->spj_pagu_final > 0 ? ($totalRealisasi / $item->spj_pagu_final) * 100 : 0;

                    if ($persenSerapan > 100) {
                        $persenSerapan = 100;
                    }

                    $keywordSearch = strtolower(
                        ($item->unit->unit_kode ?? '') .
                            ' ' .
                            ($item->unit->unit_nama ?? '') .
                            ' ' .
                            ($item->spj_pagu_tahun ?? '') .
                            ' ' .
                            ($item->program->program_kode ?? '') .
                            ' ' .
                            ($item->program->program_nama ?? '') .
                            ' ' .
                            ($item->kegiatan->kegiatan_kode ?? '') .
                            ' ' .
                            ($item->kegiatan->kegiatan_nama ?? '') .
                            ' ' .
                            ($item->subKegiatan->sub_kegiatan_kode ?? '') .
                            ' ' .
                            ($item->subKegiatan->sub_kegiatan_nama ?? ''),
                    );
                @endphp
                @php
                    $tw1 = 0;
                    $tw2 = 0;
                    $tw3 = 0;
                    $tw4 = 0;

                    foreach ($item->realisasi->where('spj_status', 'Aktif') as $spj) {
                        $bulan = $spj->spj_tanggal?->month;

                        if ($bulan >= 1 && $bulan <= 3) {
                            $tw1 += $spj->spj_nominal;
                        } elseif ($bulan >= 4 && $bulan <= 6) {
                            $tw2 += $spj->spj_nominal;
                        } elseif ($bulan >= 7 && $bulan <= 9) {
                            $tw3 += $spj->spj_nominal;
                        } elseif ($bulan >= 10 && $bulan <= 12) {
                            $tw4 += $spj->spj_nominal;
                        }
                    }
                @endphp

                <div class="pagu-card bg-white rounded-3xl border border-slate-200 shadow-sm p-6"
                    data-unit="{{ $item->spj_pagu_unit_id }}" data-tahun="{{ $item->spj_pagu_tahun }}"
                    data-search="{{ $keywordSearch }}">

                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">

                        <div>
                            <div class="flex flex-wrap gap-2 mb-3">

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Tahun {{ $item->spj_pagu_tahun }}
                                </span>

                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $item->unit->unit_kode ?? '-' }} - {{ $item->unit->unit_nama ?? '-' }}
                                </span>

                            </div>

                            <h3 class="text-xl font-bold text-slate-900">
                                {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $item->program->program_kode ?? '' }}
                                {{ $item->program->program_nama ?? '-' }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $item->kegiatan->kegiatan_kode ?? '' }}
                                {{ $item->kegiatan->kegiatan_nama ?? '-' }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $item->subKegiatan->sub_kegiatan_kode ?? '' }}
                                {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                            </p>
                        </div>

                        @if ($canInputSPJ)
                            <button type="button" onclick='openSPJModal(@json($item))'
                                class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                                + Input SPJ
                            </button>
                        @endif

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                            <p class="text-xs text-blue-600">Pagu Final</p>
                            <p class="text-xl font-bold text-blue-800">
                                Rp {{ number_format($item->spj_pagu_final, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-green-50 border border-green-100 rounded-2xl p-4">
                            <p class="text-xs text-green-600">Total SPJ</p>
                            <p class="text-xl font-bold text-green-700">
                                Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
                            <p class="text-xs text-amber-600">Sisa Pagu</p>
                            <p class="text-xl font-bold text-amber-700">
                                Rp {{ number_format($sisaPagu, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4">
                            <p class="text-xs text-purple-600">Serapan</p>
                            <p class="text-xl font-bold text-purple-700">
                                {{ number_format($persenSerapan, 2, ',', '.') }}%
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">

                        <div class="bg-cyan-50 border border-cyan-100 rounded-2xl p-4">
                            <p class="text-xs text-cyan-600">TW I</p>

                            <p class="text-xl font-bold text-cyan-700">
                                Rp {{ number_format($tw1, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-cyan-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw1 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4">
                            <p class="text-xs text-indigo-600">TW II</p>

                            <p class="text-xl font-bold text-indigo-700">
                                Rp {{ number_format($tw2, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-indigo-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw2 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4">
                            <p class="text-xs text-purple-600">TW III</p>

                            <p class="text-xl font-bold text-purple-700">
                                Rp {{ number_format($tw3, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-purple-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw3 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div class="bg-pink-50 border border-pink-100 rounded-2xl p-4">
                            <p class="text-xs text-pink-600">TW IV</p>

                            <p class="text-xl font-bold text-pink-700">
                                Rp {{ number_format($tw4, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-pink-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw4 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                    </div>

                    <div class="mb-6">
                        <h4 class="font-bold text-slate-800 mb-3">
                            Riwayat Pagu
                        </h4>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-slate-500">
                                        <th class="py-3 px-3">No</th>
                                        <th class="py-3 px-3">Jenis</th>
                                        <th class="py-3 px-3">Tanggal</th>
                                        <th class="py-3 px-3 text-right">Nominal</th>
                                        <th class="py-3 px-3">Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($item->detail->sortBy('spj_pagu_detail_urutan') as $detail)
                                        <tr class="border-b hover:bg-slate-50">
                                            <td class="py-3 px-3">{{ $loop->iteration }}</td>

                                            <td class="py-3 px-3">
                                                {{ $detail->spj_pagu_detail_jenis }}
                                            </td>

                                            <td class="py-3 px-3">
                                                {{ $detail->spj_pagu_detail_tanggal?->format('d/m/Y') ?? '-' }}
                                            </td>

                                            <td class="py-3 px-3 text-right font-semibold">
                                                Rp {{ number_format($detail->spj_pagu_detail_nominal, 0, ',', '.') }}
                                            </td>

                                            <td class="py-3 px-3">
                                                {{ $detail->spj_pagu_detail_keterangan ?: '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-6 text-center text-slate-500">
                                                Belum ada riwayat pagu.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3">
                            Riwayat SPJ
                        </h4>

                        <div class="overflow-x-auto">
                            <table class="spjRiwayatTable w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-slate-500">
                                        <th class="py-3 px-3">No</th>
                                        <th class="py-3 px-3">Tanggal SPJ</th>
                                        <th class="py-3 px-3">Uraian</th>
                                        <th class="py-3 px-3 text-right">Nominal</th>
                                        <th class="py-3 px-3">Operator</th>
                                        <th class="py-3 px-3">File</th>
                                        <th class="py-3 px-3">Tanggal Input</th>

                                        @if ($canInputSPJ)
                                            <th class="py-3 px-3 text-center">
                                                Aksi
                                            </th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($item->realisasi->where('spj_status', 'Aktif')->sortByDesc('spj_tanggal') as $spj)
                                        <tr class="border-b hover:bg-slate-50">
                                            <td class="py-3 px-3">{{ $loop->iteration }}</td>

                                            <td class="py-3 px-3">
                                                {{ $spj->spj_tanggal?->format('d/m/Y') }}
                                            </td>

                                            <td class="py-3 px-3">
                                                {{ $spj->spj_uraian }}
                                            </td>

                                            <td class="py-3 px-3 text-right font-semibold">
                                                Rp {{ number_format($spj->spj_nominal, 0, ',', '.') }}
                                            </td>

                                            <td class="py-3 px-3">
                                                <div class="font-semibold text-slate-800">
                                                    {{ $spj->spj_operator_nama ?? '-' }}
                                                </div>

                                                <div class="text-xs text-slate-500">
                                                    {{ $spj->spj_operator_nip ?? '-' }}
                                                </div>
                                            </td>

                                            <td class="py-3 px-3">
                                                @if ($spj->spj_file)
                                                    <button type="button" onclick="bukaBukuTamu('{{ $spj->spj_uid }}')"
                                                        class="inline-flex items-center gap-2 px-3 py-2
               bg-blue-600 hover:bg-blue-700
               text-white text-sm font-medium
               rounded-lg transition">

                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M2.458 12C3.732 7.943
                                                                       7.523 5 12 5c4.478 0
                                                                       8.268 2.943 9.542 7
                                                                       -1.274 4.057-5.064
                                                                       7-9.542 7-4.477
                                                                       0-8.268-2.943-9.542-7z" />

                                                        </svg>

                                                        Lihat File

                                                    </button>
                                                @else
                                                    <span class="text-slate-400">
                                                        -
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3">
                                                {{ $spj->spj_tanggal_input?->format('d/m/Y H:i') ?? '-' }}
                                            </td>

                                            @if ($canInputSPJ)
                                                <td class="py-3 px-3">

                                                    @if ((string) $spj->spj_operator_id === (string) session('pegawai_id'))
                                                        <div class="flex gap-2">

                                                            <button type="button"
                                                                onclick="editSPJ('{{ $spj->spj_uid }}')"
                                                                class="px-3 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600">
                                                                Edit
                                                            </button>

                                                            <button type="button"
                                                                onclick="hapusSPJ('{{ $spj->spj_uid }}')"
                                                                class="px-3 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                                                                Hapus
                                                            </button>

                                                        </div>
                                                    @else
                                                        <span class="text-xs text-slate-400">
                                                            -
                                                        </span>
                                                    @endif

                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-6 text-center text-slate-500">
                                                Belum ada SPJ.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
                    Belum ada data pagu SPJ aktif.
                </div>
            @endforelse

        </div>

        <div id="emptyFilter"
            class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            Data tidak ditemukan.
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <button type="button" id="prevPage"
                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Sebelumnya
                </button>

                <div id="paginationInfo" class="text-sm text-slate-500 text-center">
                    Halaman 1 dari 1
                </div>

                <button type="button" id="nextPage"
                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Berikutnya
                </button>
            </div>
        </div>

    </div>

    {{-- MODAL INPUT SPJ --}}
    <div id="spjModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-6">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 id="modalTitle" class="text-xl font-bold text-slate-900">

                        Input SPJ

                    </h2>

                    <p id="modal_spj_subkegiatan" class="text-sm text-slate-500"></p>

                    <p id="modal_spj_unit" class="text-sm font-semibold text-blue-600 mt-1"></p>

                    <p id="modal_spj_tahun" class="text-sm font-semibold text-blue-600 mt-1"></p>
                </div>

                <button type="button" onclick="closeSPJModal()" class="text-slate-400 hover:text-slate-800 text-xl">
                    ✕
                </button>
            </div>

            <form id="spjForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Uraian SPJ
                    </label>

                    <textarea name="spj_uraian" id="spj_uraian" rows="4"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Pembayaran konsumsi rapat..."
                        required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nominal SPJ
                    </label>

                    <input type="number" name="spj_nominal" id="spj_nominal" min="1"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: 1500000"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tanggal SPJ
                    </label>

                    <input type="date" name="spj_tanggal" id="spj_tanggal"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        File SPJ
                    </label>

                    <input type="file" name="spj_file" id="spj_file"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white">
                    <div id="oldFileArea" class="mt-2 hidden">
                        File sekarang :
                        <a id="oldFileLink" href="" target="_blank" class="text-blue-600 underline">

                            Lihat File

                        </a>
                    </div>

                    <p class="text-xs text-slate-500 mt-2">
                        Format: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX. Maksimal 200 MB.
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-sm text-blue-700">
                    Tanggal input dan identitas operator akan direkam otomatis dari akun login.
                </div>

                <div class="flex justify-end gap-3 pt-5">
                    <button type="button" onclick="closeSPJModal()"
                        class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Simpan SPJ
                    </button>
                </div>

            </form>

        </div>
    </div>
    {{-- ============================================================
     MODAL BUKU TAMU SPJ
     OPERATOR - LIGHT MODE
     ============================================================ --}}

    <div id="modalBukuTamu"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[9999] p-4">

        <div
            class="bg-white rounded-3xl shadow-2xl
        w-full max-w-xl
        max-h-[90vh]
        overflow-y-auto
        p-6">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-6">

                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Buku Tamu SPJ
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Silakan isi tujuan sebelum melihat file SPJ.
                    </p>
                </div>

                <button type="button" onclick="tutupBukuTamu()"
                    class="text-slate-400 hover:text-slate-800 text-2xl transition">
                    &times;
                </button>

            </div>


            <form id="formBukuTamu" method="POST">

                @csrf

                {{-- NAMA --}}
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama
                    </label>

                    <input type="text" value="{{ session('pegawai_nama') }}" readonly
                        class="w-full
                        rounded-2xl
                        border border-slate-200
                        bg-slate-50
                        text-slate-700
                        px-4 py-3
                        cursor-not-allowed">

                    <input type="hidden" name="buku_tamu_nama" value="{{ session('pegawai_nama') }}">

                </div>


                {{-- NIP --}}
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        NIP
                    </label>

                    <input type="text" value="{{ session('pegawai_nip') }}" readonly
                        class="w-full
                        rounded-2xl
                        border border-slate-200
                        bg-slate-50
                        text-slate-700
                        px-4 py-3
                        cursor-not-allowed">

                    <input type="hidden" name="buku_tamu_nip" value="{{ session('pegawai_nip') }}">

                </div>


                {{-- UNIT / BIDANG --}}
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Unit / Bidang
                    </label>

                    <input type="text"
                        value="{{ session('pegawai_bidang') ?? (session('pegawai_bidang_nama') ?? '-') }}" readonly
                        class="w-full
                        rounded-2xl
                        border border-slate-200
                        bg-slate-50
                        text-slate-700
                        px-4 py-3
                        cursor-not-allowed">

                    <input type="hidden" name="buku_tamu_unit"
                        value="{{ session('pegawai_bidang') ?? (session('pegawai_bidang_nama') ?? '-') }}">

                </div>


                {{-- TUJUAN --}}
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tujuan <span class="text-red-500">*</span>
                    </label>

                    <textarea name="buku_tamu_tujuan" maxlength="255" rows="4" required
                        placeholder="Contoh: Melihat dokumen SPJ untuk keperluan verifikasi"
                        class="w-full
                        rounded-2xl
                        border border-slate-200
                        bg-white
                        text-slate-800
                        px-4 py-3
                        outline-none
                        resize-none
                        placeholder-slate-400
                        focus:border-blue-500
                        focus:ring-2
                        focus:ring-blue-500/20"></textarea>

                </div>


                {{-- INFO --}}
                <div
                    class="bg-blue-50
                border border-blue-100
                rounded-2xl
                p-4
                text-sm
                text-blue-700">

                    Data kunjungan akan direkam sebagai buku tamu
                    sebelum file SPJ dibuka.

                </div>


                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 pt-5">

                    <button type="button" onclick="tutupBukuTamu()"
                        class="px-5 py-3
                        rounded-2xl
                        bg-slate-100
                        text-slate-700
                        font-semibold
                        hover:bg-slate-200
                        transition">

                        Batal

                    </button>


                    <button type="submit"
                        class="px-5 py-3
                        rounded-2xl
                        bg-blue-600
                        text-white
                        font-semibold
                        hover:bg-blue-700
                        transition">

                        Lihat File

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ============================================================
     MODAL TUTORIAL SPJ
     OPERATOR SPJ
     4 HALAMAN
     ============================================================ --}}

    <div id="modalTutorialSPJ"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-[10000] p-3 md:p-6">

        <div
            class="relative bg-white rounded-3xl shadow-2xl
               w-full max-w-5xl
               h-[95vh] md:h-[92vh]
               overflow-hidden
               flex flex-col">

            {{-- HEADER --}}
            <div
                class="flex items-center justify-between
                   px-4 md:px-6 py-3
                   border-b border-slate-200
                   bg-white
                   shrink-0">

                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10 rounded-xl
                           bg-blue-50
                           flex items-center justify-center
                           text-blue-600 text-xl">
                        📖
                    </div>

                    <div>
                        <h2 class="font-bold text-slate-900">
                            Panduan Penginputan SPJ
                        </h2>

                        <p class="text-xs text-slate-500">
                            SAPLARIN · Operator SPJ
                        </p>
                    </div>

                </div>

                <button type="button" onclick="tutupTutorialSPJ()"
                    class="w-10 h-10 rounded-xl
                       flex items-center justify-center
                       text-slate-400
                       hover:bg-slate-100
                       hover:text-slate-700
                       text-2xl
                       transition">

                    &times;

                </button>

            </div>


            {{-- ============================================================
     CONTENT TUTORIAL
     ============================================================ --}}
            <div id="tutorialViewer"
                class="flex-1 min-h-0
           bg-slate-100
           overflow-auto
           relative">

                {{-- ZOOM CONTROL --}}
                <div class="sticky top-3 z-20
               flex justify-center
               pointer-events-none">

                    <div
                        class="inline-flex items-center gap-1
                   bg-white/95
                   backdrop-blur
                   border border-slate-200
                   shadow-lg
                   rounded-2xl
                   p-1.5
                   pointer-events-auto">

                        {{-- ZOOM OUT --}}
                        <button type="button" onclick="zoomTutorial(-0.1)"
                            class="w-9 h-9 rounded-xl
                       flex items-center justify-center
                       text-slate-700
                       hover:bg-slate-100
                       transition"
                            title="Perkecil">

                            −

                        </button>


                        {{-- ZOOM PERCENT --}}
                        <button type="button" onclick="resetZoomTutorial()" id="tutorialZoomText"
                            class="min-w-[65px]
                       px-2 py-2
                       rounded-xl
                       text-sm
                       font-bold
                       text-blue-600
                       hover:bg-blue-50
                       transition">

                            60%

                        </button>


                        {{-- ZOOM IN --}}
                        <button type="button" onclick="zoomTutorial(0.1)"
                            class="w-9 h-9 rounded-xl
                       flex items-center justify-center
                       text-slate-700
                       hover:bg-slate-100
                       transition"
                            title="Perbesar">

                            +

                        </button>

                    </div>

                </div>


                {{-- IMAGE --}}
                <div id="tutorialImageWrapper"
                    class="min-w-full min-h-full
               flex items-start justify-center
               p-4 md:p-6">

                    <img id="tutorialSPJImage" src="{{ asset('assets/tutorial/spj/halaman-1.png') }}"
                        alt="Panduan Penginputan SPJ" draggable="false"
                        class="block
                   rounded-xl
                   shadow-lg
                   select-none
                   transition-all duration-200"
                        style="width: 60%; height: auto;">

                </div>

            </div>


            {{-- FOOTER --}}
            <div
                class="shrink-0
                   bg-white
                   border-t border-slate-200
                   px-4 md:px-6 py-3">

                <div class="flex items-center justify-between gap-3">

                    {{-- PREV --}}
                    <button type="button" id="tutorialPrev" onclick="tutorialSebelumnya()"
                        class="px-4 md:px-5 py-2.5
                           rounded-xl
                           bg-slate-100
                           text-slate-700
                           font-semibold
                           hover:bg-slate-200
                           transition
                           disabled:opacity-40
                           disabled:cursor-not-allowed">

                        ←
                        <span class="hidden sm:inline">
                            Sebelumnya
                        </span>

                    </button>


                    {{-- INDICATOR --}}
                    <div class="flex flex-col items-center">

                        <div id="tutorialIndicator" class="text-sm font-semibold text-slate-700">
                            Halaman 1 dari 4
                        </div>

                        <div class="flex items-center gap-1.5 mt-1">

                            <span class="tutorial-dot w-2 h-2 rounded-full bg-blue-600 transition">
                            </span>

                            <span class="tutorial-dot w-2 h-2 rounded-full bg-slate-300 transition">
                            </span>

                            <span class="tutorial-dot w-2 h-2 rounded-full bg-slate-300 transition">
                            </span>

                            <span class="tutorial-dot w-2 h-2 rounded-full bg-slate-300 transition">
                            </span>

                        </div>

                    </div>


                    {{-- NEXT --}}
                    <button type="button" id="tutorialNext" onclick="tutorialBerikutnya()"
                        class="px-4 md:px-5 py-2.5
                           rounded-xl
                           bg-blue-600
                           text-white
                           font-semibold
                           hover:bg-blue-700
                           transition">

                        <span id="tutorialNextText">
                            Berikutnya
                        </span>

                        <span id="tutorialNextIcon">
                            →
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        function bukaBukuTamu(uid) {
            const modal = document.getElementById('modalBukuTamu');
            const form = document.getElementById('formBukuTamu');

            if (!modal || !form) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | SET ACTION
            |--------------------------------------------------------------------------
            */

            form.action =
                "{{ url('/user/spj') }}/" +
                uid +
                "/buku-tamu";


            /*
            |--------------------------------------------------------------------------
            | RESET FORM
            |--------------------------------------------------------------------------
            */

            form.reset();


            /*
            |--------------------------------------------------------------------------
            | BUKA MODAL
            |--------------------------------------------------------------------------
            */

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');


            /*
            |--------------------------------------------------------------------------
            | FOCUS
            |--------------------------------------------------------------------------
            */

            setTimeout(function() {

                const nama =
                    form.querySelector(
                        '[name="buku_tamu_nama"]'
                    );

                if (nama) {
                    nama.focus();
                }

            }, 100);
        }


        function tutupBukuTamu() {
            const modal =
                document.getElementById('modalBukuTamu');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {
                tutupBukuTamu();
            }

        });
    </script>
    <script>
        function openSPJModal(item) {

            let subKegiatan = item.sub_kegiatan ?
                item.sub_kegiatan.sub_kegiatan_nama :
                '-';

            let unit = item.unit ?
                item.unit.unit_nama :
                '-';
            let tahun = item.spj_pagu_tahun ?
                item.spj_pagu_tahun :
                '-';

            document.getElementById('modal_spj_subkegiatan').innerText = subKegiatan;
            document.getElementById('modal_spj_unit').innerText = 'Unit Pengampu: ' + unit;
            document.getElementById('modal_spj_tahun').innerText = 'Tahun Anggaran: ' + tahun;

            // Reset form
            document.getElementById('spjForm').reset();

            // Judul modal
            document.getElementById('modalTitle').innerText = 'Input SPJ';

            // Method POST
            document.getElementById('methodField').value = 'POST';
            document.getElementById("oldFileArea")
                .classList.add("hidden");

            // Action form
            document.getElementById('spjForm').action =
                "{{ url('/user/spj') }}/" + item.spj_pagu_uid + "/store";

            document.getElementById('spjModal').classList.remove('hidden');
            document.getElementById('spjModal').classList.add('flex');
        }

        function closeSPJModal() {
            document.getElementById('spjModal').classList.add('hidden');
            document.getElementById('spjModal').classList.remove('flex');
            document.getElementById('spjForm').reset();
        }

        function editSPJ(uid) {

            fetch("/user/spj/" + uid + "/edit")

                .then(res => res.json())

                .then(res => {

                    let spj = res.data;

                    document.getElementById("modalTitle").innerHTML = "Edit SPJ";

                    document.getElementById("spj_uraian").value = spj.spj_uraian;

                    document.getElementById("spj_nominal").value = spj.spj_nominal;

                    document.getElementById("spj_tanggal").value = spj.spj_tanggal;
                    if (spj.spj_file) {

                        document.getElementById("oldFileArea")
                            .classList.remove("hidden");

                        document.getElementById("oldFileLink")
                            .href = spj.spj_file;

                    } else {

                        document.getElementById("oldFileArea")
                            .classList.add("hidden");

                    }

                    document.getElementById("methodField").value = "PUT";

                    document.getElementById("spjForm")
                        .action = "/user/spj/" + uid;

                    document
                        .getElementById("spjModal")
                        .classList.remove("hidden");

                    document
                        .getElementById("spjModal")
                        .classList.add("flex");

                });

        }

        function hapusSPJ(uid) {

            Swal.fire({

                    title: "Hapus SPJ?",

                    text: "File Google Drive juga akan dihapus.",

                    icon: "warning",

                    showCancelButton: true,

                    confirmButtonText: "Ya Hapus"

                })

                .then((result) => {

                    if (result.isConfirmed) {

                        let form = document.createElement("form");

                        form.method = "POST";

                        form.action = "/user/spj/" + uid;

                        form.innerHTML = `
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="_method" value="DELETE">
`;

                        document.body.appendChild(form);

                        form.submit();

                    }

                });

        }

        const cards = Array.from(document.querySelectorAll('.pagu-card'));
        const filterTahun = document.getElementById('filterTahun');
        const filterUnit = document.getElementById('filterUnit');
        const searchPagu = document.getElementById('searchPagu');
        const perPageSelect = document.getElementById('perPage');
        const emptyFilter = document.getElementById('emptyFilter');
        const showingInfo = document.getElementById('showingInfo');
        const paginationInfo = document.getElementById('paginationInfo');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');

        let currentPage = 1;

        function getFilteredCards() {
            const tahun = filterTahun.value;
            const unit = filterUnit.value;
            const keyword = searchPagu.value.toLowerCase().trim();

            return cards.filter(card => {
                const cardTahun = card.dataset.tahun || '';
                const cardUnit = card.dataset.unit || '';
                const cardSearch = card.dataset.search || '';

                const matchTahun = !tahun || cardTahun === tahun;
                const matchUnit = !unit || cardUnit === unit;
                const matchSearch = !keyword || cardSearch.includes(keyword);

                return matchTahun && matchUnit && matchSearch;
            });
        }

        function renderPagination() {
            const filteredCards = getFilteredCards();
            const perPage = parseInt(perPageSelect.value || 10);
            const totalPage = Math.max(1, Math.ceil(filteredCards.length / perPage));

            if (currentPage > totalPage) {
                currentPage = totalPage;
            }

            cards.forEach(card => card.classList.add('hidden'));

            const start = (currentPage - 1) * perPage;
            const end = start + perPage;

            filteredCards.slice(start, end).forEach(card => {
                card.classList.remove('hidden');
            });

            emptyFilter.classList.toggle('hidden', filteredCards.length > 0);

            showingInfo.innerText = filteredCards.length;
            paginationInfo.innerText = `Halaman ${currentPage} dari ${totalPage}`;

            prevPage.disabled = currentPage <= 1;
            nextPage.disabled = currentPage >= totalPage;

            prevPage.classList.toggle('opacity-50', prevPage.disabled);
            nextPage.classList.toggle('opacity-50', nextPage.disabled);
            prevPage.classList.toggle('cursor-not-allowed', prevPage.disabled);
            nextPage.classList.toggle('cursor-not-allowed', nextPage.disabled);
        }

        filterTahun.addEventListener('change', function() {
            currentPage = 1;
            renderPagination();
        });

        filterUnit.addEventListener('change', function() {
            currentPage = 1;
            renderPagination();
        });

        searchPagu.addEventListener('input', function() {
            currentPage = 1;
            renderPagination();
        });

        perPageSelect.addEventListener('change', function() {
            currentPage = 1;
            renderPagination();
        });

        prevPage.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderPagination();
            }
        });

        nextPage.addEventListener('click', function() {
            const perPage = parseInt(perPageSelect.value || 10);
            const totalPage = Math.max(1, Math.ceil(getFilteredCards().length / perPage));

            if (currentPage < totalPage) {
                currentPage++;
                renderPagination();
            }
        });

        renderPagination();
    </script>
    <script>
        /* ============================================================
                       TUTORIAL SPJ
                       ============================================================ */

        const tutorialSPJPages = [
            "{{ asset('assets/tutorial/spj/halaman-1.png') }}",
            "{{ asset('assets/tutorial/spj/halaman-2.png') }}",
            "{{ asset('assets/tutorial/spj/halaman-3.png') }}",
            "{{ asset('assets/tutorial/spj/halaman-4.png') }}"
        ];

        let tutorialSPJCurrentPage = 0;


        function bukaTutorialSPJ() {

            tutorialSPJCurrentPage = 0;

            const modal = document.getElementById('modalTutorialSPJ');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

            tampilkanTutorialSPJ();

        }


        function tutupTutorialSPJ() {

            const modal = document.getElementById('modalTutorialSPJ');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }


        function tampilkanTutorialSPJ() {

            const image = document.getElementById('tutorialSPJImage');
            const indicator = document.getElementById('tutorialIndicator');
            const prev = document.getElementById('tutorialPrev');
            const next = document.getElementById('tutorialNext');
            const nextText = document.getElementById('tutorialNextText');
            const nextIcon = document.getElementById('tutorialNextIcon');

            if (!image) {
                return;
            }


            /* IMAGE */

            image.src = tutorialSPJPages[tutorialSPJCurrentPage];


            /* INDICATOR */

            indicator.innerText =
                'Halaman ' +
                (tutorialSPJCurrentPage + 1) +
                ' dari ' +
                tutorialSPJPages.length;


            /* PREVIOUS */

            prev.disabled = tutorialSPJCurrentPage === 0;


            /* NEXT / FINISH */

            if (tutorialSPJCurrentPage === tutorialSPJPages.length - 1) {

                nextText.innerText = 'Selesai';
                nextIcon.innerText = '✓';

            } else {

                nextText.innerText = 'Berikutnya';
                nextIcon.innerText = '→';

            }


            /* DOT */

            document
                .querySelectorAll('.tutorial-dot')
                .forEach((dot, index) => {

                    if (index === tutorialSPJCurrentPage) {

                        dot.classList.remove('bg-slate-300');
                        dot.classList.add('bg-blue-600');

                    } else {

                        dot.classList.remove('bg-blue-600');
                        dot.classList.add('bg-slate-300');

                    }

                });

        }


        function tutorialBerikutnya() {

            if (
                tutorialSPJCurrentPage <
                tutorialSPJPages.length - 1
            ) {

                tutorialSPJCurrentPage++;

                resetZoomTutorial();

                tampilkanTutorialSPJ();

            } else {

                tutupTutorialSPJ();

            }

        }


        function tutorialSebelumnya() {

            if (tutorialSPJCurrentPage > 0) {

                tutorialSPJCurrentPage--;

                resetZoomTutorial();

                tampilkanTutorialSPJ();

            }

        }


        /* ============================================================
           ESC
           ============================================================ */

        document.addEventListener('keydown', function(event) {

            if (event.key !== 'Escape') {
                return;
            }

            const modal = document.getElementById('modalTutorialSPJ');

            if (
                modal &&
                !modal.classList.contains('hidden')
            ) {

                tutupTutorialSPJ();

            }

        });


        /* ============================================================
           KLIK AREA GELAP UNTUK TUTUP
           ============================================================ */

        document
            .getElementById('modalTutorialSPJ')
            ?.addEventListener('click', function(event) {

                if (event.target === this) {
                    tutupTutorialSPJ();
                }

            });
    </script>
    <script>
        /* ============================================================
               ZOOM TUTORIAL SPJ
               ============================================================ */

        let tutorialZoom = 0.6;

        const tutorialZoomMin = 0.4;
        const tutorialZoomMax = 2.0;
        const tutorialZoomStep = 0.1;


        function updateTutorialZoom() {

            const image = document.getElementById('tutorialSPJImage');
            const zoomText = document.getElementById('tutorialZoomText');

            if (!image) {
                return;
            }

            image.style.width = (tutorialZoom * 100) + '%';

            if (zoomText) {
                zoomText.innerText =
                    Math.round(tutorialZoom * 100) + '%';
            }

        }


        function zoomTutorial(value) {

            tutorialZoom += value;

            if (tutorialZoom < tutorialZoomMin) {
                tutorialZoom = tutorialZoomMin;
            }

            if (tutorialZoom > tutorialZoomMax) {
                tutorialZoom = tutorialZoomMax;
            }

            updateTutorialZoom();

        }


        function resetZoomTutorial() {

            tutorialZoom = 0.6;

            updateTutorialZoom();

        }


        /* ============================================================
           MOUSE WHEEL ZOOM
           CTRL + SCROLL
           ============================================================ */

        document
            .getElementById('tutorialViewer')
            ?.addEventListener('wheel', function(event) {

                if (!event.ctrlKey) {
                    return;
                }

                event.preventDefault();

                if (event.deltaY < 0) {
                    zoomTutorial(tutorialZoomStep);
                } else {
                    zoomTutorial(-tutorialZoomStep);
                }

            }, {
                passive: false
            });


        /* ============================================================
           PINCH ZOOM HP / TABLET
           ============================================================ */

        let tutorialInitialDistance = null;


        document
            .getElementById('tutorialViewer')
            ?.addEventListener('touchmove', function(event) {

                if (event.touches.length !== 2) {
                    return;
                }

                const touch1 = event.touches[0];
                const touch2 = event.touches[1];

                const distance = Math.hypot(
                    touch2.clientX - touch1.clientX,
                    touch2.clientY - touch1.clientY
                );


                if (tutorialInitialDistance === null) {

                    tutorialInitialDistance = distance;

                    return;

                }


                const difference =
                    distance - tutorialInitialDistance;


                if (Math.abs(difference) > 10) {

                    if (difference > 0) {

                        zoomTutorial(0.05);

                    } else {

                        zoomTutorial(-0.05);

                    }

                    tutorialInitialDistance = distance;

                }

            }, {
                passive: true
            });


        document
            .getElementById('tutorialViewer')
            ?.addEventListener('touchend', function(event) {

                if (event.touches.length < 2) {

                    tutorialInitialDistance = null;

                }

            });
    </script>

@endsection
