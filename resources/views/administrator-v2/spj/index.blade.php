@extends('administrator-v2.layouts.app')

@section('title', 'Data Pagu SPJ')

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
            <div class="flex gap-3">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl mt-1"></i>

                <div>
                    <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">
                        Terjadi Kesalahan
                    </h4>

                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">


        <button onclick="openModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold">

            <i class="bi bi-plus-circle"></i>

            Tambah Pagu

        </button>

    </div>

    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table id="spjTable" class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="col-aksi" width="150">Aksi</th>

                        <th>Tahun</th>

                        <th class="col-unit">Unit</th>

                        <th class="col-program">Program</th>

                        <th class="col-kegiatan">Kegiatan</th>

                        <th class="col-sub">Sub Kegiatan</th>

                        <th class="col-rp">Pagu Final</th>

                        <th class="col-rp">Realisasi</th>

                        <th class="col-rp">Sisa</th>

                        <th class="col-serapan">Serapan</th>

                        <th class="col-status">Status</th>


                    </tr>

                </thead>

                <tbody>

                    @forelse ($pagus as $item)
                        @php

                            $realisasi = $item->realisasi->where('spj_status', 'Aktif')->sum('spj_nominal');

                            $sisa = $item->spj_pagu_final - $realisasi;

                            $serapan = $item->spj_pagu_final > 0 ? ($realisasi / $item->spj_pagu_final) * 100 : 0;

                            if ($serapan > 100) {
                                $serapan = 100;
                            }

                        @endphp

                        <tr>

                            <td>

                                <div class="flex gap-2">

                                    <button type="button" onclick='openEditModal(@json($item))'
                                        class="h-9 w-9 rounded-xl bg-amber-500 hover:bg-amber-600 text-white">

                                        <i class="bi bi-pencil"></i>

                                    </button>

                                    <button type="button" onclick='openDetailModal(@json($item))'
                                        class="h-9 w-9 rounded-xl bg-slate-600 hover:bg-slate-700 text-white">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                    <form method="POST" action="{{ route('admin.spj.status', $item->spj_pagu_uid) }}"
                                        onsubmit="return confirm('Ubah status pagu ini?')">

                                        @csrf

                                        @if ($item->spj_pagu_status == 1)
                                            <button
                                                class="h-9 px-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs">

                                                Nonaktif

                                            </button>
                                        @else
                                            <button
                                                class="h-9 px-3 rounded-xl bg-green-600 hover:bg-green-700 text-white text-xs">

                                                Aktifkan

                                            </button>
                                        @endif

                                    </form>

                                </div>

                            </td>

                            <td>{{ $item->spj_pagu_tahun }}</td>

                            <td>

                                @if ($item->unit)
                                    <div class="font-semibold text-blue-600 dark:text-blue-400">

                                        {{ $item->unit->unit_kode }}

                                    </div>

                                    <div class="text-xs text-slate-500 dark:text-slate-400 max-w-[180px] whitespace-normal">

                                        {{ $item->unit->unit_nama }}

                                    </div>
                                @else
                                    -
                                @endif

                            </td>

                            <td>

                                <div class="font-semibold">

                                    {{ $item->program->program_kode ?? '-' }}

                                </div>

                                <div class="text-xs text-slate-500 dark:text-slate-400 max-w-[220px] whitespace-normal">

                                    {{ $item->program->program_nama ?? '-' }}

                                </div>

                            </td>

                            <td>

                                <div class="font-semibold">

                                    {{ $item->kegiatan->kegiatan_kode ?? '-' }}

                                </div>

                                <div class="text-xs text-slate-500 dark:text-slate-400 max-w-[220px] whitespace-normal">

                                    {{ $item->kegiatan->kegiatan_nama ?? '-' }}

                                </div>

                            </td>

                            <td>

                                <div class="font-semibold">

                                    {{ $item->subKegiatan->sub_kegiatan_kode ?? '-' }}

                                </div>

                                <div class="text-xs text-slate-500 dark:text-slate-400 max-w-[240px] whitespace-normal">

                                    {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}

                                </div>

                            </td>

                            <td class="font-semibold whitespace-nowrap">

                                Rp {{ number_format($item->spj_pagu_final, 0, ',', '.') }}

                            </td>

                            <td class="whitespace-nowrap">

                                Rp {{ number_format($realisasi, 0, ',', '.') }}

                            </td>

                            <td class="whitespace-nowrap">

                                Rp {{ number_format($sisa, 0, ',', '.') }}

                            </td>

                            <td>

                                <div class="flex items-center gap-2">

                                    <div class="w-20 h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">

                                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ $serapan }}%">
                                        </div>

                                    </div>

                                    <span class="text-xs font-semibold">

                                        {{ number_format($serapan, 2, ',', '.') }}%

                                    </span>

                                </div>

                            </td>

                            <td>

                                @if ($item->spj_pagu_status == 1)
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                                        Aktif

                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 py-1 text-xs font-semibold">

                                        Nonaktif

                                    </span>
                                @endif

                            </td>


                        </tr>

                    @empty

                        <tr>

                            <td colspan="12" class="py-10 text-center text-slate-500">

                                Belum ada data pagu SPJ.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
    <!-- ========================= MODAL TAMBAH ========================= -->

    <div id="modalPagu" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-5xl max-h-[92vh] flex flex-col overflow-hidden">

            <div class="flex items-center justify-between px-7 py-5 border-b border-slate-200 dark:border-slate-700">

                <div>

                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                        Tambah Pagu SPJ

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Pilih Unit, Program, Kegiatan, Sub Kegiatan lalu input riwayat pagu.

                    </p>

                </div>

                <button onclick="closeModal()" class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.spj.store') }}" class="flex flex-col flex-1 overflow-hidden">

                @csrf

                <div class="flex-1 overflow-y-auto px-7 py-6">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Tahun Anggaran

                            </label>

                            <input type="number" name="spj_pagu_tahun" value="{{ date('Y') }}"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Unit Pengampu

                            </label>

                            <select name="spj_pagu_unit_id"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                                <option value="">

                                    Pilih Unit

                                </option>

                                @foreach ($units as $unit)
                                    <option value="{{ $unit->unit_id }}">

                                        {{ $unit->unit_kode }} -
                                        {{ $unit->unit_nama }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Program

                            </label>

                            <select id="programSelect" onchange="filterKegiatan()"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                                <option value="">

                                    Pilih Program

                                </option>

                                @foreach ($programs as $program)
                                    <option value="{{ $program->program_id }}">

                                        {{ $program->program_kode }}
                                        {{ $program->program_nama }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Kegiatan

                            </label>

                            <select id="kegiatanSelect" onchange="filterSubKegiatan()"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                                <option value="">

                                    Pilih Kegiatan

                                </option>

                                @foreach ($kegiatans as $kegiatan)
                                    <option value="{{ $kegiatan->kegiatan_id }}"
                                        data-program="{{ $kegiatan->kegiatan_program }}">

                                        {{ $kegiatan->kegiatan_kode }}
                                        {{ $kegiatan->kegiatan_nama }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-semibold mb-2">

                            Sub Kegiatan

                        </label>

                        <select id="subKegiatanSelect" name="spj_pagu_sub_kegiatan_id" onchange="setMasterHidden()"
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="">

                                Pilih Sub Kegiatan

                            </option>

                            @foreach ($subKegiatans as $sub)
                                <option value="{{ $sub->sub_kegiatan_id }}"
                                    data-kegiatan="{{ $sub->sub_kegiatan_kegiatan }}">

                                    {{ $sub->sub_kegiatan_kode }}
                                    {{ $sub->sub_kegiatan_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <input type="hidden" id="spj_pagu_program_id" name="spj_pagu_program_id">

                    <input type="hidden" id="spj_pagu_kegiatan_id" name="spj_pagu_kegiatan_id">
                    <div class="mt-6">

                        <label class="block text-sm font-semibold mb-3">

                            Riwayat Pagu

                        </label>

                        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">

                            <table class="min-w-full text-sm" id="tablePagu">

                                <thead class="bg-slate-100 dark:bg-slate-800">

                                    <tr>

                                        <th class="px-4 py-3 text-left">
                                            Jenis
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Nominal
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Keterangan
                                        </th>

                                        <th width="70" class="text-center">
                                            Aksi
                                        </th>

                                    </tr>

                                </thead>

                                <tbody id="tbodyPagu">

                                    <tr>

                                        <td class="px-4 py-3">

                                            <select name="jenis[]"
                                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">

                                                <option value="Induk">

                                                    Pagu Induk

                                                </option>

                                                <option value="Pergeseran">

                                                    Pergeseran

                                                </option>

                                            </select>

                                        </td>

                                        <td class="px-4 py-3">

                                            <input type="number" name="nominal[]"
                                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2"
                                                required>

                                        </td>

                                        <td class="px-4 py-3">

                                            <input type="text" name="keterangan[]"
                                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2"
                                                placeholder="Keterangan">

                                        </td>

                                        <td class="text-center">

                                            <button type="button" onclick="addRowPagu()"
                                                class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-600 hover:bg-blue-700 text-white">

                                                <i class="bi bi-plus-lg"></i>

                                            </button>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-200 dark:border-slate-700">

                    <button type="button" onclick="closeModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-6 py-2.5 text-white font-semibold">

                        <i class="bi bi-check-circle me-2"></i>

                        Simpan Data

                    </button>

                </div>

            </form>

        </div>

    </div>
    <!-- ========================= MODAL EDIT ========================= -->

    <div id="modalEditPagu"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-6xl max-h-[92vh] flex flex-col overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-7 py-5 border-b border-slate-200 dark:border-slate-700">

                <div>

                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                        Edit Pagu SPJ

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Perbarui unit, program, kegiatan, sub kegiatan, pagu dan status.

                    </p>

                </div>

                <button type="button" onclick="closeEditModal()"
                    class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.spj.update') }}" class="flex flex-col flex-1 overflow-hidden">

                @csrf

                <input type="hidden" name="spj_pagu_id" id="edit_spj_pagu_id">

                <div class="flex-1 overflow-y-auto px-7 py-6">

                    {{-- Row 1 --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Tahun Anggaran

                            </label>

                            <input type="number" name="spj_pagu_tahun" id="edit_spj_pagu_tahun" required
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Unit Pengampu

                            </label>

                            <select name="spj_pagu_unit_id" id="edit_spj_pagu_unit_id" required
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                                <option value="">

                                    Pilih Unit

                                </option>

                                @foreach ($units as $unit)
                                    <option value="{{ $unit->unit_id }}">

                                        {{ $unit->unit_kode }}
                                        -
                                        {{ $unit->unit_nama }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Status

                            </label>

                            <select name="spj_pagu_status" id="edit_spj_pagu_status" required
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                                <option value="1">

                                    Aktif

                                </option>

                                <option value="0">

                                    Nonaktif

                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- Row 2 --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Program

                            </label>

                            <select id="editProgramSelect" onchange="filterEditKegiatan()" required
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                                <option value="">

                                    Pilih Program

                                </option>

                                @foreach ($programs as $program)
                                    <option value="{{ $program->program_id }}">

                                        {{ $program->program_kode ? $program->program_kode . ' - ' : '' }}

                                        {{ $program->program_nama }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div>

                            <label class="block text-sm font-semibold mb-2">

                                Kegiatan

                            </label>

                            <select id="editKegiatanSelect" onchange="filterEditSubKegiatan()" required
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                                <option value="">

                                    Pilih Kegiatan

                                </option>

                                @foreach ($kegiatans as $kegiatan)
                                    <option value="{{ $kegiatan->kegiatan_id }}"
                                        data-program="{{ $kegiatan->kegiatan_program }}">

                                        {{ $kegiatan->kegiatan_kode ? $kegiatan->kegiatan_kode . ' - ' : '' }}

                                        {{ $kegiatan->kegiatan_nama }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>
                    {{-- Sub Kegiatan --}}
                    <div class="mb-5">

                        <label class="block text-sm font-semibold mb-2">

                            Sub Kegiatan

                        </label>

                        <select name="spj_pagu_sub_kegiatan_id" id="editSubKegiatanSelect"
                            onchange="setEditMasterHidden()" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="">

                                Pilih Sub Kegiatan

                            </option>

                            @foreach ($subKegiatans as $sub)
                                <option value="{{ $sub->sub_kegiatan_id }}"
                                    data-kegiatan="{{ $sub->sub_kegiatan_kegiatan }}">

                                    {{ $sub->sub_kegiatan_kode ? $sub->sub_kegiatan_kode . ' - ' : '' }}

                                    {{ $sub->sub_kegiatan_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <input type="hidden" name="spj_pagu_program_id" id="edit_spj_pagu_program_id">

                    <input type="hidden" name="spj_pagu_kegiatan_id" id="edit_spj_pagu_kegiatan_id">

                    {{-- Riwayat Pagu --}}
                    <div class="mt-7">

                        <div class="flex items-center justify-between mb-3">

                            <div>

                                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">

                                    Riwayat Pagu

                                </h3>

                                <p class="text-sm text-slate-500 dark:text-slate-400">

                                    Pagu final mengikuti nominal pagu terakhir.

                                </p>

                            </div>

                        </div>

                        <div id="editPaguContainer" class="space-y-4">

                        </div>

                        <button type="button" onclick="tambahEditPagu()"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl border border-dashed border-blue-400 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 px-5 py-3 font-semibold">

                            <i class="bi bi-plus-circle"></i>

                            Tambah Pergeseran

                        </button>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 px-7 py-5 border-t border-slate-200 dark:border-slate-700">

                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5">

                        <i class="bi bi-save me-2"></i>

                        Update Data

                    </button>

                </div>

            </form>

        </div>

    </div>
    <!-- ========================= MODAL DETAIL ========================= -->

    <div id="modalDetailPagu"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden">

            {{-- Header --}}
            <div class="flex items-start justify-between px-7 py-6 border-b border-slate-200 dark:border-slate-700">

                <div>

                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                        Detail Pagu SPJ

                    </h2>

                    <p id="detail_sub_kegiatan" class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    </p>

                    <p id="detail_unit" class="mt-1 text-sm font-medium text-blue-600 dark:text-blue-400">
                    </p>

                </div>

                <button type="button" onclick="closeDetailModal()"
                    class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto px-7 py-6">

                {{-- Card Summary --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                    <div
                        class="rounded-2xl border border-blue-200 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/20 p-5">

                        <div class="text-sm text-blue-600 dark:text-blue-300">

                            Pagu Final

                        </div>

                        <div id="detail_total_pagu" class="mt-2 text-2xl font-bold text-blue-700 dark:text-blue-300">

                        </div>

                    </div>

                    <div
                        class="rounded-2xl border border-green-200 dark:border-green-900 bg-green-50 dark:bg-green-900/20 p-5">

                        <div class="text-sm text-green-600 dark:text-green-300">

                            Total Realisasi

                        </div>

                        <div id="detail_realisasi" class="mt-2 text-2xl font-bold text-green-700 dark:text-green-300">

                        </div>

                    </div>

                    <div
                        class="rounded-2xl border border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-900/20 p-5">

                        <div class="text-sm text-amber-600 dark:text-amber-300">

                            Sisa Pagu

                        </div>

                        <div id="detail_sisa" class="mt-2 text-2xl font-bold text-amber-700 dark:text-amber-300">

                        </div>

                    </div>

                </div>

                {{-- Riwayat Pagu --}}
                <div class="mb-8">

                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">

                        Riwayat Pagu

                    </h3>

                    <div id="detailPaguList" class="space-y-3">

                    </div>

                </div>

                {{-- Riwayat SPJ --}}
                <div>

                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">

                        Riwayat SPJ

                    </h3>

                    <div id="detailRealisasiList" class="space-y-3">

                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="border-t border-slate-200 dark:border-slate-700 px-7 py-5 flex justify-end">

                <button type="button" onclick="closeDetailModal()"
                    class="rounded-xl bg-slate-700 hover:bg-slate-800 text-white px-6 py-2.5">

                    Tutup

                </button>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#spjTable').DataTable({

                responsive: true,

                autoWidth: false,

                pageLength: 25,

                order: [
                    [0, 'desc']
                ],

                language: {

                    search: "Cari :",

                    searchPlaceholder: "Cari data pagu...",

                    lengthMenu: "Tampilkan _MENU_ data",

                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                    infoEmpty: "Belum ada data",

                    zeroRecords: "Data tidak ditemukan",

                    paginate: {

                        previous: "‹",

                        next: "›"

                    }

                }

            });

        });

        function openModal() {

            $('#modalPagu').removeClass('hidden').addClass('flex');

            $('body').addClass('overflow-hidden');

        }

        function closeModal() {

            $('#modalPagu').removeClass('flex').addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }

        function openEditModal(item) {

            $('#edit_spj_pagu_id').val(item.spj_pagu_id);

            $('#edit_spj_pagu_tahun').val(item.spj_pagu_tahun);

            $('#edit_spj_pagu_unit_id').val(item.spj_pagu_unit_id);

            $('#edit_spj_pagu_status').val(item.spj_pagu_status);

            $('#editProgramSelect').val(item.spj_pagu_program_id);

            $('#edit_spj_pagu_program_id').val(item.spj_pagu_program_id);

            filterEditKegiatan();

            $('#editKegiatanSelect').val(item.spj_pagu_kegiatan_id);

            $('#edit_spj_pagu_kegiatan_id').val(item.spj_pagu_kegiatan_id);

            filterEditSubKegiatan();

            $('#editSubKegiatanSelect').val(item.spj_pagu_sub_kegiatan_id);

            $('#editPaguContainer').html('');

            nomorEditPergeseran = 0;

            if (item.detail && item.detail.length > 0) {

                item.detail
                    .sort((a, b) => Number(a.spj_pagu_detail_urutan) - Number(b.spj_pagu_detail_urutan))
                    .forEach((detail) => {

                        nomorEditPergeseran++;

                        let tanggal = detail.spj_pagu_detail_tanggal ?? '';

                        if (tanggal && tanggal.includes('T')) {
                            tanggal = tanggal.split('T')[0];
                        }

                        tambahEditPaguRow(
                            detail.spj_pagu_detail_jenis ?? 'Pagu Induk',
                            detail.spj_pagu_detail_nominal ?? '',
                            tanggal,
                            detail.spj_pagu_detail_keterangan ?? ''
                        );

                    });

            } else {

                nomorEditPergeseran = 1;

                tambahEditPaguRow(
                    'Pagu Induk',
                    item.spj_pagu_final ?? '',
                    '',
                    ''
                );

            }

            $('#modalEditPagu')
                .removeClass('hidden')
                .addClass('flex');

            $('body').addClass('overflow-hidden');

        }

        function filterEditKegiatan() {

            const programId = $('#editProgramSelect').val();

            $('#editKegiatanSelect').val('');

            $('#editSubKegiatanSelect').val('');

            $('#edit_spj_pagu_program_id').val(programId);

            $('#edit_spj_pagu_kegiatan_id').val('');

            $('#editKegiatanSelect option').each(function() {

                const optionProgram = $(this).data('program');

                if ($(this).val() === '') {

                    $(this).prop('hidden', false);

                    return;

                }

                $(this).prop(
                    'hidden',
                    String(optionProgram) !== String(programId)
                );

            });

            $('#editSubKegiatanSelect option').each(function() {

                if ($(this).val() === '') {

                    $(this).prop('hidden', false);

                    return;

                }

                $(this).prop('hidden', true);

            });

            $('#editKegiatanSelect').trigger('change');

        }

        function filterSubKegiatan() {

            const kegiatanId = $('#kegiatanSelect').val();

            $('#subKegiatanSelect').val('');

            $('#spj_pagu_kegiatan_id').val(kegiatanId);

            $('#subKegiatanSelect option').each(function() {

                const optionKegiatan = $(this).data('kegiatan');

                if ($(this).val() === '') {

                    $(this).prop('hidden', false);

                    return;

                }

                $(this).prop(
                    'hidden',
                    String(optionKegiatan) !== String(kegiatanId)
                );

            });

        }

        function filterEditSubKegiatan() {

            const kegiatanId = $('#editKegiatanSelect').val();

            $('#editSubKegiatanSelect').val('');

            $('#edit_spj_pagu_kegiatan_id').val(kegiatanId);

            $('#editSubKegiatanSelect option').each(function() {

                const optionKegiatan = $(this).data('kegiatan');

                if ($(this).val() === '') {

                    $(this).prop('hidden', false);

                    return;

                }

                $(this).prop(
                    'hidden',
                    String(optionKegiatan) !== String(kegiatanId)
                );

            });

        }

        function setEditMasterHidden() {

            $('#edit_spj_pagu_program_id').val($('#editProgramSelect').val());

            $('#edit_spj_pagu_kegiatan_id').val($('#editKegiatanSelect').val());

        }

        let nomorEditPergeseran = 1;

        function tambahEditPagu() {

            nomorEditPergeseran++;

            tambahEditPaguRow(
                `Pergeseran ${nomorEditPergeseran}`,
                '',
                '',
                ''
            );

        }

        function tambahEditPaguRow(jenis = '', nominal = '', tanggal = '', keterangan = '') {

            $('#editPaguContainer').append(`

        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5 edit-pagu-row">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Jenis

                    </label>

                    <input
                        type="text"
                        name="pagu_jenis[]"
                        value="${escapeHtml(jenis)}"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Nominal

                    </label>

                    <input
                        type="text"
                        name="pagu_nominal[]"
                        value="${formatRupiahJs(nominal)}"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 nominal-rupiah"
                        placeholder="Nominal"
                        required>

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Tanggal

                    </label>

                    <input
                        type="date"
                        name="pagu_tanggal[]"
                        value="${tanggal ?? ''}"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Keterangan

                    </label>

                    <div class="flex gap-2">

                        <input
                            type="text"
                            name="pagu_keterangan[]"
                            value="${escapeHtml(keterangan)}"
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="Keterangan">

                        <button
                            type="button"
                            onclick="$(this).closest('.edit-pagu-row').remove()"
                            class="w-12 rounded-2xl bg-red-600 hover:bg-red-700 text-white flex items-center justify-center">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    `);

        }
         function formatRupiahJs(angka) {
            angka = parseInt(angka || 0);

            return angka.toLocaleString('id-ID');
        }

        function setMasterHidden() {

            $('#spj_pagu_program_id').val($('#programSelect').val());

            $('#spj_pagu_kegiatan_id').val($('#kegiatanSelect').val());

        }

        let nomorPergeseran = 1;

        function tambahPagu() {

            nomorPergeseran++;

            $('#paguContainer').append(`

        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5 mb-4">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Jenis

                    </label>

                    <input
                        type="text"
                        name="pagu_jenis[]"
                        value="Pergeseran ${nomorPergeseran}"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Nominal

                    </label>

                    <input
                        type="text"
                        name="pagu_nominal[]"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 nominal-rupiah"
                        placeholder="Nominal"
                        required>

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Tanggal

                    </label>

                    <input
                        type="date"
                        name="pagu_tanggal[]"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                </div>

                <div>

                    <label class="block text-sm font-medium mb-2">

                        Keterangan

                    </label>

                    <input
                        type="text"
                        name="pagu_keterangan[]"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        placeholder="Keterangan">

                </div>

            </div>

        </div>

    `);

        }

        function closeEditModal() {

            $('#modalEditPagu').removeClass('flex').addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }
        function escapeHtml(text) {
            if (text === null || text === undefined) {
                return '';
            }

            return String(text)
                .replaceAll('&', '&amp;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;');
        }

        function openDetailModal(item) {

            const realisasi = (item.realisasi || [])
                .filter(spj => spj.spj_status === 'Aktif')
                .reduce((total, spj) => total + Number(spj.spj_nominal || 0), 0);

            const sisa = Number(item.spj_pagu_final || 0) - realisasi;

            // =======================
            // HEADER
            // =======================

            $('#detail_sub_kegiatan').text(
                item.subKegiatan?.sub_kegiatan_nama ??
                item.sub_kegiatan?.sub_kegiatan_nama ??
                '-'
            );

            $('#detail_unit').text(
                (item.unit?.unit_kode ?? '-') +
                ' - ' +
                (item.unit?.unit_nama ?? '-')
            );

            // =======================
            // CARD
            // =======================

            $('#detail_total_pagu').text(rupiah(item.spj_pagu_final));

            $('#detail_realisasi').text(rupiah(realisasi));

            $('#detail_sisa').text(rupiah(sisa));

            // =======================
            // RIWAYAT PAGU
            // =======================

            let paguHtml = '';

            if (item.detail && item.detail.length > 0) {

                item.detail.forEach((detail, index) => {

                    paguHtml += `
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-4">

                    <div class="flex justify-between items-start gap-4">

                        <div>

                            <div class="font-semibold text-slate-800 dark:text-white">

                                ${index + 1}. ${detail.spj_pagu_detail_jenis ?? '-'}

                            </div>

                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                                ${detail.spj_pagu_detail_keterangan ?? '-'}

                            </div>

                        </div>

                        <div class="font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap">

                            ${rupiah(detail.spj_pagu_detail_nominal)}

                        </div>

                    </div>

                </div>
            `;

                });

            } else {

                paguHtml = `
            <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-500">
                Belum ada riwayat pagu.
            </div>
        `;

            }

            $('#detailPaguList').html(paguHtml);

            // =======================
            // RIWAYAT REALISASI
            // =======================

            let realisasiHtml = '';

            const list = (item.realisasi || []).filter(spj => spj.spj_status === 'Aktif');

            if (list.length > 0) {

                list.forEach((spj, index) => {

                    realisasiHtml += `
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-4">

                    <div class="flex justify-between items-start gap-4">

                        <div>

                            <div class="font-semibold text-slate-800 dark:text-white">

                                ${index + 1}. ${spj.spj_uraian ?? '-'}

                            </div>

                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                                Operator : ${spj.spj_operator_nama ?? '-'}

                            </div>

                        </div>

                        <div class="font-bold text-green-600 dark:text-green-400 whitespace-nowrap">

                            ${rupiah(spj.spj_nominal)}

                        </div>

                    </div>

                </div>
            `;

                });

            } else {

                realisasiHtml = `
            <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-500">
                Belum ada realisasi SPJ.
            </div>
        `;

            }

            $('#detailRealisasiList').html(realisasiHtml);

            // =======================
            // SHOW MODAL
            // =======================

            $('#modalDetailPagu')
                .removeClass('hidden')
                .addClass('flex');

            $('body').addClass('overflow-hidden');

        }

        function rupiah(angka) {

            if (angka === null || angka === undefined || angka === '') {
                angka = 0;
            }

            return 'Rp ' + Number(angka).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

        }

        function closeDetailModal() {

            $('#modalDetailPagu').removeClass('flex').addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }

        $('#modalPagu,#modalEditPagu,#modalDetailPagu').on('click', function(e) {

            if (e.target === this) {

                $(this).removeClass('flex').addClass('hidden');

                $('body').removeClass('overflow-hidden');

            }

        });

        $(document).keydown(function(e) {

            if (e.key === "Escape") {

                closeModal();

                closeEditModal();

                closeDetailModal();

            }

        });
    </script>
@endpush

@push('styles')
    <style>
        #spjTable {
            width: 100% !important;
            border-collapse: separate;
        }

        #spjTable th {
            white-space: nowrap;
            padding: 14px 16px;
            font-weight: 700;
        }

        #spjTable td {
            padding: 16px;
            vertical-align: top;
        }

        .col-unit {
            min-width: 240px;
        }

        .col-program {
            min-width: 330px;
        }

        .col-kegiatan {
            min-width: 340px;
        }

        .col-sub {
            min-width: 380px;
        }

        .col-rp {
            min-width: 150px;
            white-space: nowrap;
            font-weight: 600;
        }

        .col-serapan {
            min-width: 170px;
        }

        .col-status {
            min-width: 110px;
        }

        .col-aksi {
            min-width: 170px;
            white-space: nowrap;
        }

        #spjTable_wrapper .dataTables_filter input {

            border-radius: 14px;

            border: 1px solid rgb(203 213 225);

            padding: .65rem .9rem;

        }

        .dark #spjTable_wrapper .dataTables_filter input {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        #spjTable_wrapper .dataTables_length select {

            border-radius: 14px;

            border: 1px solid rgb(203 213 225);

            padding: .55rem .75rem;

        }

        .dark #spjTable_wrapper .dataTables_length select {

            background: #0f172a;

            border-color: #334155;

            color: #fff;

        }

        table.dataTable tbody tr:hover {

            background: #f8fafc !important;

        }

        .dark table.dataTable tbody tr:hover {

            background: #1e293b !important;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {

            background: #2563eb !important;

            border: none !important;

            color: #fff !important;

            border-radius: 10px !important;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {

            background: #1d4ed8 !important;

            color: #fff !important;

            border: none !important;

        }

        input:focus,
        select:focus,
        textarea:focus {

            outline: none;

            border-color: #2563eb;

            box-shadow: 0 0 0 3px rgb(37 99 235 / .15);

        }
    </style>
@endpush
