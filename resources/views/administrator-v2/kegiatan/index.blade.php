@extends('administrator-v2.layouts.app')

@section('title','Kelola Kegiatan')

@section('page-title','Kelola Kegiatan')

@section('page-description','Kelola data kegiatan SAPLARIN')

@section('content')

@if(session('success'))

<div class="mb-6 rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-5 py-4">

    <div class="flex items-center gap-3">

        <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>

        <span class="text-green-700 dark:text-green-300">

            {{ session('success') }}

        </span>

    </div>

</div>

@endif

@if(session('error'))

<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">

    <div class="flex items-center gap-3">

        <i class="bi bi-x-circle-fill text-red-600 text-xl"></i>

        <span class="text-red-700 dark:text-red-300">

            {{ session('error') }}

        </span>

    </div>

</div>

@endif

@if($errors->any())

<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">

    <div class="flex items-start gap-3">

        <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl mt-1"></i>

        <div>

            <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">

                Terjadi Kesalahan

            </h4>

            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1">

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    </div>

</div>

@endif

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

    <button
        type="button"
        onclick="openModal()"
        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold transition">

        <i class="bi bi-plus-circle"></i>

        Tambah Kegiatan

    </button>

</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table
            id="kegiatanTable"
            class="min-w-full text-sm">

            <thead class="bg-slate-100 dark:bg-slate-800">

                <tr>

                    <th class="px-4 py-4 text-left">

                        No

                    </th>

                    <th class="px-4 py-4 text-left">

                        Program

                    </th>

                    <th class="px-4 py-4 text-left">

                        Kode Kegiatan

                    </th>

                    <th class="px-4 py-4 text-left">

                        Nama Kegiatan

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

                @foreach($kegiatans as $kegiatan)

                <tr class="border-t border-slate-200 dark:border-slate-800">

                    <td class="px-4 py-4">

                        {{ $loop->iteration }}

                    </td>

                    <td class="px-4 py-4">

                        <div class="font-semibold">

                            {{ $kegiatan->program_kode }}

                        </div>

                        <div class="text-xs text-slate-500">

                            {{ $kegiatan->program_nama }}

                        </div>

                    </td>

                    <td class="px-4 py-4">

                        <span class="rounded-xl bg-slate-100 dark:bg-slate-800 px-3 py-2 inline-block font-semibold">

                            {{ $kegiatan->kegiatan_kode }}

                        </span>

                    </td>

                    <td class="px-4 py-4">

                        {{ $kegiatan->kegiatan_nama }}

                    </td>

                    <td class="px-4 py-4">

                        @if($kegiatan->kegiatan_status)

                        <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">

                            Aktif

                        </span>

                        @else

                        <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">

                            Nonaktif

                        </span>

                        @endif

                    </td>

                    <td class="px-4 py-4 text-center">

                        <button
                            onclick='openEditModal(@json($kegiatan))'
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

</div>
<!-- MODAL TAMBAH -->
<div
    id="kegiatanModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Tambah Kegiatan

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Tambahkan data kegiatan baru.

                </p>

            </div>

            <button
                type="button"
                onclick="closeModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <form
            method="POST"
            action="{{ route('admin.kegiatan.store') }}">

            @csrf

            <div class="p-6 space-y-5">

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Program

                    </label>

                    <select
                        name="kegiatan_program"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                        <option value="">

                            Pilih Program

                        </option>

                        @foreach($programs as $program)

                        <option value="{{ $program->program_id }}">

                            {{ $program->program_kode }} - {{ $program->program_nama }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Nama Kegiatan

                    </label>

                    <input
                        type="text"
                        name="kegiatan_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Kode Kegiatan

                    </label>

                    <input
                        type="text"
                        name="kegiatan_kode"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Status

                    </label>

                    <select
                        name="kegiatan_status"
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

                <button
                    type="button"
                    onclick="closeModal()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    Batal

                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-semibold text-white transition">

                    <i class="bi bi-check-circle me-2"></i>

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

<!-- MODAL EDIT -->
<div
    id="editKegiatanModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Edit Kegiatan

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Perbarui data kegiatan.

                </p>

            </div>

            <button
                type="button"
                onclick="closeEditModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <form
            method="POST"
            action="{{ route('admin.kegiatan.update') }}">

            @csrf

            <input
                type="hidden"
                id="edit_kegiatan_id"
                name="kegiatan_id">

            <div class="p-6 space-y-5">

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Program

                    </label>

                    <select
                        id="edit_kegiatan_program"
                        name="kegiatan_program"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                        @foreach($programs as $program)

                        <option value="{{ $program->program_id }}">

                            {{ $program->program_kode }} - {{ $program->program_nama }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Nama Kegiatan

                    </label>

                    <input
                        type="text"
                        id="edit_kegiatan_nama"
                        name="kegiatan_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Kode Kegiatan

                    </label>

                    <input
                        type="text"
                        id="edit_kegiatan_kode_rekening"
                        name="kegiatan_kode"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Status

                    </label>

                    <select
                        id="edit_kegiatan_status"
                        name="kegiatan_status"
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

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    Batal

                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-semibold text-white transition">

                    <i class="bi bi-save me-2"></i>

                    Update

                </button>

            </div>

        </form>

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

        document.querySelector('#kegiatanModal form').reset();

        hideModal('kegiatanModal');

    }

    function openEditModal(kegiatan) {

        document.getElementById('edit_kegiatan_id').value =
            kegiatan.kegiatan_id;

        document.getElementById('edit_kegiatan_program').value =
            kegiatan.kegiatan_program;

        document.getElementById('edit_kegiatan_nama').value =
            kegiatan.kegiatan_nama;

        document.getElementById('edit_kegiatan_kode_rekening').value =
            kegiatan.kegiatan_kode;

        document.getElementById('edit_kegiatan_status').value =
            kegiatan.kegiatan_status;

        showModal('editKegiatanModal');

    }

    function closeEditModal() {

        hideModal('editKegiatanModal');

    }

    document.addEventListener('DOMContentLoaded', function() {

        $('#kegiatanTable').DataTable({

            responsive: true,

            autoWidth: false,

            pageLength: 10,

            order: [
                [0, 'asc']
            ],

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

        ['kegiatanModal', 'editKegiatanModal'].forEach(function(id) {

            const modal = document.getElementById(id);

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
#kegiatanTable_wrapper .dataTables_filter input {

    border-radius: 12px;
    border: 1px solid rgb(203 213 225);
    padding: .55rem .9rem;

}

.dark #kegiatanTable_wrapper .dataTables_filter input {

    background: #0f172a;
    border-color: #334155;
    color: white;

}

#kegiatanTable_wrapper .dataTables_length select {

    border-radius: 12px;
    border: 1px solid rgb(203 213 225);
    padding: .45rem .75rem;

}

.dark #kegiatanTable_wrapper .dataTables_length select {

    background: #0f172a;
    border-color: #334155;
    color: white;

}

table.dataTable tbody tr:hover {

    background: #f8fafc;

}

.dark table.dataTable tbody tr:hover {

    background: #1e293b;

}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {

    background: #2563eb !important;
    color: #fff !important;
    border: none !important;
    border-radius: 10px !important;

}

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