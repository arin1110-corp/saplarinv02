@extends('administrator-v2.layouts.app')

@section('title', 'Kelola Sub Kegiatan')

@section('page-title', 'Kelola Sub Kegiatan')

@section('page-description', 'Kelola data sub kegiatan SAPLARIN')

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
                            <li>{{ $error }}</li>
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

        <button type="button" onclick="openModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold transition">

            <i class="bi bi-plus-circle"></i>

            Tambah Sub Kegiatan

        </button>

    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="px-4 py-4 text-left">

                            No

                        </th>

                        <th class="px-4 py-4 text-left">

                            Program

                        </th>

                        <th class="px-4 py-4 text-left">

                            Kegiatan

                        </th>

                        <th class="px-4 py-4 text-left">

                            Kode Sub Kegiatan

                        </th>

                        <th class="px-4 py-4 text-left">

                            Kode Rekening

                        </th>

                        <th class="px-4 py-4 text-left">

                            Nama Sub Kegiatan

                        </th>

                        <th class="px-4 py-4 text-left">

                            Status

                        </th>

                        <th class="px-4 py-4 text-center">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($subkegiatans as $subkegiatan)
                        <tr class="border-t border-slate-200 dark:border-slate-800">

                            <td class="px-4 py-4">

                                {{ $loop->iteration }}

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-medium">

                                    {{ $subkegiatan->program_kode }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $subkegiatan->program_nama }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                <div class="font-medium">

                                    {{ $subkegiatan->kegiatan_kode }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    {{ $subkegiatan->kegiatan_nama }}

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                <span
                                    class="rounded-xl bg-slate-100 dark:bg-slate-800 px-3 py-2 inline-block font-semibold">

                                    {{ $subkegiatan->sub_kegiatan_kode }}

                                </span>

                            </td>

                            <td class="px-4 py-4">

                                {{ $subkegiatan->sub_kegiatan_kode_rekening }}

                            </td>

                            <td class="px-4 py-4">

                                {{ $subkegiatan->sub_kegiatan_nama }}

                            </td>

                            <td class="px-4 py-4">

                                @if ($subkegiatan->sub_kegiatan_status)
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">

                                        Aktif

                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">

                                        Nonaktif

                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-4 text-center">

                                <button onclick='openEditModal(@json($subkegiatan))'
                                    class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-2 text-xs font-medium text-white transition">

                                    <i class="bi bi-pencil-square"></i>

                                    Edit

                                </button>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
        <div
            class="flex flex-col md:flex-row items-center justify-between gap-4 px-6 py-5 border-t border-slate-200 dark:border-slate-700">

            <div class="text-sm text-slate-500">

                Menampilkan

                <b>{{ $subkegiatans->firstItem() }}</b>

                -

                <b>{{ $subkegiatans->lastItem() }}</b>

                dari

                <b>{{ $subkegiatans->total() }}</b>

                data

            </div>

            {{ $subkegiatans->links() }}

        </div>

    </div>
    <!-- MODAL TAMBAH -->
    <div id="kegiatanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Tambah Sub Kegiatan

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Tambahkan data sub kegiatan baru.

                    </p>

                </div>

                <button type="button" onclick="closeModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.subkegiatan.store') }}">

                @csrf

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Kegiatan

                        </label>

                        <select name="sub_kegiatan_kegiatan"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="">

                                Pilih Kegiatan

                            </option>

                            @foreach ($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->kegiatan_id }}">

                                    {{ $kegiatan->kegiatan_kode }} - {{ $kegiatan->kegiatan_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>

                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                                Kode Sub Kegiatan

                            </label>

                            <input type="text" name="sub_kegiatan_kode"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                        <div>

                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                                Kode Rekening

                            </label>

                            <input type="text" name="sub_kegiatan_kode_rekening"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Nama Sub Kegiatan

                        </label>

                        <input type="text" name="sub_kegiatan_nama"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Status

                        </label>

                        <select name="sub_kegiatan_status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="">

                                Pilih Status

                            </option>

                            <option value="1">

                                Aktif

                            </option>

                            <option value="0">

                                Nonaktif

                            </option>

                        </select>

                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-semibold text-white transition">

                        <i class="bi bi-check-circle me-2"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>
    <!-- MODAL EDIT -->
    <div id="editKegiatanModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Edit Sub Kegiatan

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Perbarui data sub kegiatan.

                    </p>

                </div>

                <button type="button" onclick="closeEditModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form method="POST" action="{{ route('admin.subkegiatan.update') }}">

                @csrf

                <input type="hidden" id="edit_sub_kegiatan_id" name="sub_kegiatan_id">

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Kegiatan

                        </label>

                        <select id="edit_sub_kegiatan_program" name="sub_kegiatan_kegiatan"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            @foreach ($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->kegiatan_id }}">

                                    {{ $kegiatan->kegiatan_kode }}
                                    -
                                    {{ $kegiatan->kegiatan_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>

                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                                Kode Sub Kegiatan

                            </label>

                            <input type="text" id="edit_sub_kegiatan_kode" name="sub_kegiatan_kode"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                        <div>

                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                                Kode Rekening

                            </label>

                            <input type="text" id="edit_sub_kegiatan_kode_rekening" name="sub_kegiatan_kode_rekening"
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                                required>

                        </div>

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Nama Sub Kegiatan

                        </label>

                        <input type="text" id="edit_sub_kegiatan_nama" name="sub_kegiatan_nama"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Status

                        </label>

                        <select id="edit_sub_kegiatan_status" name="sub_kegiatan_status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                            <option value="1">

                                Aktif

                            </option>

                            <option value="0">

                                Nonaktif

                            </option>

                        </select>

                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-semibold text-white transition">

                        <i class="bi bi-save me-2"></i>

                        Update

                    </button>

                </div>

            </form>

        </div>
        <div
            class="flex flex-col md:flex-row items-center justify-between gap-4 px-6 py-5 border-t border-slate-200 dark:border-slate-700">

            <div class="text-sm text-slate-500">

                Menampilkan

                <b>{{ $subkegiatans->firstItem() }}</b>

                -

                <b>{{ $subkegiatans->lastItem() }}</b>

                dari

                <b>{{ $subkegiatans->total() }}</b>

                data

            </div>

            {{ $subkegiatans->links() }}

        </div>

    </div>
    @push('scripts')
        <script>
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

            function openModal() {

                showModal('kegiatanModal');

            }

            function closeModal() {

                const form = document.querySelector('#kegiatanModal form');

                if (form) {

                    form.reset();

                }

                hideModal('kegiatanModal');

            }

            function openEditModal(kegiatan) {

                document.getElementById('edit_sub_kegiatan_id').value =
                    kegiatan.sub_kegiatan_id;

                document.getElementById('edit_sub_kegiatan_program').value =
                    kegiatan.sub_kegiatan_kegiatan;

                document.getElementById('edit_sub_kegiatan_nama').value =
                    kegiatan.sub_kegiatan_nama;

                document.getElementById('edit_sub_kegiatan_kode').value =
                    kegiatan.sub_kegiatan_kode;

                document.getElementById('edit_sub_kegiatan_kode_rekening').value =
                    kegiatan.sub_kegiatan_kode_rekening;

                document.getElementById('edit_sub_kegiatan_status').value =
                    kegiatan.sub_kegiatan_status;

                showModal('editKegiatanModal');

            }

            function closeEditModal() {

                hideModal('editKegiatanModal');

            }

            document.addEventListener('DOMContentLoaded', function() {

                if (window.jQuery) {

                    $('#kegiatanTable').DataTable({

                        responsive: true,

                        autoWidth: false,

                        pageLength: 10,

                        language: {

                            search: "Cari :",

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

                ['kegiatanModal', 'editKegiatanModal'].forEach(function(id) {

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

                    hideModal('kegiatanModal');

                    hideModal('editKegiatanModal');

                }

            });
        </script>
    @endpush

    @push('styles')
        <style>
            input,
            select {

                transition: .2s;

            }

            input:focus,
            select:focus {

                outline: none;
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgb(37 99 235 / .15);

            }
        </style>
    @endpush

@endsection
