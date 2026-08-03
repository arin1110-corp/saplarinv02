@extends('administrator-v2.layouts.app')

@section('title','Folder Drive')

@section('page-title','Folder Drive')

@section('page-description','Kelola folder Google Drive SAPLARIN')

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

        Tambah Folder

    </button>

</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table
            id="folderTable"
            class="min-w-full text-sm">

            <thead class="bg-slate-100 dark:bg-slate-800">

                <tr>

                    <th class="px-4 py-4 text-left">No</th>

                    <th class="px-4 py-4 text-left">Nama Folder</th>

                    <th class="px-4 py-4 text-left">Prefix</th>

                    <th class="px-4 py-4 text-left">Folder ID</th>

                    <th class="px-4 py-4 text-left">Credential</th>

                    <th class="px-4 py-4 text-left">Status</th>

                    <th class="px-4 py-4 text-center">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($folders as $folder)

                <tr class="border-t border-slate-200 dark:border-slate-800">

                    <td class="px-4 py-4">

                        {{ $loop->iteration }}

                    </td>

                    <td class="px-4 py-4 font-medium">

                        {{ $folder->folder_nama }}

                    </td>

                    <td class="px-4 py-4">

                        <code class="rounded-xl bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-2">

                            {{ $folder->folder_prefix }}

                        </code>

                    </td>

                    <td class="px-4 py-4">

                        <code class="text-xs break-all">

                            {{ $folder->folder_drive_id }}

                        </code>

                    </td>

                    <td class="px-4 py-4">

                        {{ $folder->json->json_nama ?? '-' }}

                    </td>

                    <td class="px-4 py-4">

                        @if($folder->folder_status)

                        <span class="inline-flex rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">

                            Aktif

                        </span>

                        @else

                        <span class="inline-flex rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">

                            Nonaktif

                        </span>

                        @endif

                    </td>

                    <td class="px-4 py-4 text-center">

                        <button

                            onclick='openEditModal(@json($folder))'

                            class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-2 text-xs text-white">

                            <i class="bi bi-pencil-square"></i>

                            Edit

                        </button>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" class="px-4 py-10 text-center text-slate-500">

                        Belum ada Folder Drive.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-6 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6">

    <h3 class="text-lg font-semibold mb-2">

        Informasi

    </h3>

    <p class="text-slate-500 dark:text-slate-400">

        Untuk BBM gunakan prefix
        <span class="font-semibold text-blue-600">bbm</span>

    </p>

</div>
<!-- MODAL TAMBAH -->
<div
    id="folderModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Tambah Folder Drive

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Tambahkan folder Google Drive untuk sinkronisasi.

                </p>

            </div>

            <button
                type="button"
                onclick="closeModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <form
            method="POST"
            action="{{ route('admin.drive.folder.store') }}">

            @csrf

            <div class="p-6 space-y-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Nama Folder

                    </label>

                    <input
                        type="text"
                        name="folder_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        placeholder="Folder BBM"
                        required>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Prefix

                        </label>

                        <input
                            type="text"
                            name="folder_prefix"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="bbm"
                            required>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Status

                        </label>

                        <select
                            name="folder_status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="1">

                                Aktif

                            </option>

                            <option value="0">

                                Nonaktif

                            </option>

                        </select>

                    </div>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Folder ID Google Drive

                    </label>

                    <input
                        type="text"
                        name="folder_drive_id"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        placeholder="1AbCdEfGhIjKlMnOpQr..."
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        JSON Credential

                    </label>

                    <select
                        name="folder_json"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                        <option value="">

                            Pilih JSON Credential

                        </option>

                        @foreach($jsons as $json)

                        <option value="{{ $json->json_id }}">

                            {{ $json->json_nama }}

                        </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                <button
                    type="button"
                    onclick="closeModal()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                    Batal

                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-white font-semibold">

                    <i class="bi bi-check-circle me-2"></i>

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

<!-- MODAL EDIT -->
<div
    id="editFolderModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Edit Folder Drive

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Perbarui folder Google Drive.

                </p>

            </div>

            <button
                type="button"
                onclick="closeEditModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <form
            method="POST"
            action="{{ route('admin.drive.folder.update') }}">

            @csrf

            <input
                type="hidden"
                id="edit_folder_id"
                name="folder_id">

            <div class="p-6 space-y-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Nama Folder

                    </label>

                    <input
                        type="text"
                        id="edit_folder_nama"
                        name="folder_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Prefix

                        </label>

                        <input
                            type="text"
                            id="edit_folder_prefix"
                            name="folder_prefix"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            required>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">

                            Status

                        </label>

                        <select
                            id="edit_folder_status"
                            name="folder_status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="1">Aktif</option>

                            <option value="0">Nonaktif</option>

                        </select>

                    </div>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Folder ID Google Drive

                    </label>

                    <input
                        type="text"
                        id="edit_folder_drive_id"
                        name="folder_drive_id"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        JSON Credential

                    </label>

                    <select
                        id="edit_folder_json"
                        name="folder_json"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                        @foreach($jsons as $json)

                        <option value="{{ $json->json_id }}">

                            {{ $json->json_nama }}

                        </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                    Batal

                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-white font-semibold">

                    <i class="bi bi-save me-2"></i>

                    Update

                </button>

            </div>

        </form>

    </div>

</div>
@push('scripts')

<script>

    function showModal(id){

        const modal=document.getElementById(id);

        if(!modal) return;

        modal.classList.remove('hidden');

        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    }

    function hideModal(id){

        const modal=document.getElementById(id);

        if(!modal) return;

        modal.classList.add('hidden');

        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');

    }

    function openModal(){

        showModal('folderModal');

    }

    function closeModal(){

        const form=document.querySelector('#folderModal form');

        if(form){

            form.reset();

        }

        hideModal('folderModal');

    }

    function openEditModal(folder){

        document.getElementById('edit_folder_id').value =
            folder.folder_id;

        document.getElementById('edit_folder_nama').value =
            folder.folder_nama;

        document.getElementById('edit_folder_prefix').value =
            folder.folder_prefix;

        document.getElementById('edit_folder_drive_id').value =
            folder.folder_drive_id;

        document.getElementById('edit_folder_json').value =
            folder.folder_json;

        document.getElementById('edit_folder_status').value =
            folder.folder_status;

        showModal('editFolderModal');

    }

    function closeEditModal(){

        hideModal('editFolderModal');

    }

    document.addEventListener('DOMContentLoaded',function(){

        $('#folderTable').DataTable({

            responsive:true,

            autoWidth:false,

            pageLength:10,

            order:[[0,'asc']],

            language:{

                search:"Cari :",

                searchPlaceholder:"Cari data...",

                lengthMenu:"Tampilkan _MENU_ data",

                zeroRecords:"Data tidak ditemukan",

                info:"Menampilkan _START_ - _END_ dari _TOTAL_ data",

                infoEmpty:"Tidak ada data",

                infoFiltered:"(difilter dari _MAX_ data)",

                paginate:{

                    first:"Awal",

                    last:"Akhir",

                    next:"›",

                    previous:"‹"

                }

            }

        });

        ['folderModal','editFolderModal'].forEach(function(id){

            const modal=document.getElementById(id);

            if(!modal) return;

            modal.addEventListener('click',function(e){

                if(e.target===modal){

                    hideModal(id);

                }

            });

        });

    });

    document.addEventListener('keydown',function(e){

        if(e.key==='Escape'){

            hideModal('folderModal');

            hideModal('editFolderModal');

        }

    });

</script>

@endpush

@push('styles')

<style>

#folderTable_wrapper .dataTables_filter input{

    border-radius:12px;

    border:1px solid rgb(203 213 225);

    padding:.55rem .9rem;

}

.dark #folderTable_wrapper .dataTables_filter input{

    background:#0f172a;

    border-color:#334155;

    color:#fff;

}

#folderTable_wrapper .dataTables_length select{

    border-radius:12px;

    border:1px solid rgb(203 213 225);

    padding:.45rem .75rem;

}

.dark #folderTable_wrapper .dataTables_length select{

    background:#0f172a;

    border-color:#334155;

    color:#fff;

}

table.dataTable tbody tr:hover{

    background:#f8fafc;

}

.dark table.dataTable tbody tr:hover{

    background:#1e293b;

}

.dataTables_wrapper .dataTables_paginate .paginate_button.current{

    background:#2563eb !important;

    color:#fff !important;

    border:none !important;

    border-radius:10px !important;

}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover{

    background:#1d4ed8 !important;

    color:#fff !important;

    border:none !important;

}

input,
select{

    transition:.2s;

}

input:focus,
select:focus{

    outline:none;

    border-color:#2563eb;

    box-shadow:0 0 0 3px rgb(37 99 235 /.15);

}

code{

    display:inline-block;

    max-width:100%;

    white-space:normal;

    word-break:break-all;

}

</style>

@endpush

@endsection