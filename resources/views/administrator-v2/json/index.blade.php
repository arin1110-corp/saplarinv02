@extends('administrator-v2.layouts.app')

@section('title', 'JSON Credential')

@section('page-title', 'JSON Credential')

@section('page-description', 'Kelola path credential Google Drive SAPLARIN')

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

        Tambah JSON

    </button>

</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table
            id="jsonTable"
            class="min-w-full text-sm">

            <thead class="bg-slate-100 dark:bg-slate-800">

                <tr>

                    <th class="px-4 py-4 text-left">

                        No

                    </th>

                    <th class="px-4 py-4 text-left">

                        Nama

                    </th>

                    <th class="px-4 py-4 text-left">

                        Path JSON

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

                @forelse($jsons as $json)

                <tr class="border-t border-slate-200 dark:border-slate-800">

                    <td class="px-4 py-4">

                        {{ $loop->iteration }}

                    </td>

                    <td class="px-4 py-4 font-medium">

                        {{ $json->json_nama }}

                    </td>

                    <td class="px-4 py-4">

                        <code class="rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 px-3 py-2 inline-block text-blue-600 dark:text-blue-300">

                            {{ $json->json_file }}

                        </code>

                    </td>

                    <td class="px-4 py-4">

                        @if($json->json_status)

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
                            onclick='openEditModal(@json($json))'
                            class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-2 text-xs font-medium text-white">

                            <i class="bi bi-pencil-square"></i>

                            Edit

                        </button>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="px-4 py-10 text-center text-slate-500">

                        Belum ada JSON Credential.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-6 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6">

    <h3 class="text-lg font-semibold mb-3">

        Contoh Path Credential

    </h3>

    <div class="space-y-2 text-sm">

        <div>

            <span class="font-semibold">

                Path JSON :

            </span>

            <code class="text-blue-600 dark:text-blue-300">

                google-drive/bbm-service-account.json

            </code>

        </div>

        <div>

            <span class="font-semibold">

                Lokasi File :

            </span>

            <code class="text-blue-600 dark:text-blue-300">

                storage/app/google-drive/bbm-service-account.json

            </code>

        </div>

    </div>

</div>
<!-- MODAL TAMBAH -->
<div
    id="jsonModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Tambah JSON Credential

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Tambahkan path credential Google Drive.

                </p>

            </div>

            <button
                type="button"
                onclick="closeModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <form method="POST" action="{{ route('admin.drive.json.store') }}">

            @csrf

            <div class="p-6 space-y-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Nama

                    </label>

                    <input
                        type="text"
                        name="json_nama"
                        placeholder="Google Drive BBM"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Path JSON

                    </label>

                    <input
                        type="text"
                        name="json_file"
                        placeholder="google-drive/bbm-service-account.json"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Status

                    </label>

                    <select
                        name="json_status"
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
    id="editJsonModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Edit JSON Credential

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Perbarui path credential Google Drive.

                </p>

            </div>

            <button
                type="button"
                onclick="closeEditModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <form method="POST" action="{{ route('admin.drive.json.update') }}">

            @csrf

            <input
                type="hidden"
                id="edit_json_id"
                name="json_id">

            <div class="p-6 space-y-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Nama

                    </label>

                    <input
                        type="text"
                        id="edit_json_nama"
                        name="json_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Path JSON

                    </label>

                    <input
                        type="text"
                        id="edit_json_file"
                        name="json_file"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        required>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Status

                    </label>

                    <select
                        id="edit_json_status"
                        name="json_status"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="1">Aktif</option>

                        <option value="0">Nonaktif</option>

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

        showModal('jsonModal');

    }

    function closeModal(){

        const form=document.querySelector('#jsonModal form');

        if(form){

            form.reset();

        }

        hideModal('jsonModal');

    }

    function openEditModal(json){

        document.getElementById('edit_json_id').value =
            json.json_id;

        document.getElementById('edit_json_nama').value =
            json.json_nama;

        document.getElementById('edit_json_file').value =
            json.json_file;

        document.getElementById('edit_json_status').value =
            json.json_status;

        showModal('editJsonModal');

    }

    function closeEditModal(){

        hideModal('editJsonModal');

    }

    document.addEventListener('DOMContentLoaded',function(){

        $('#jsonTable').DataTable({

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

        ['jsonModal','editJsonModal'].forEach(function(id){

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

            hideModal('jsonModal');

            hideModal('editJsonModal');

        }

    });

</script>

@endpush

@push('styles')

<style>

#jsonTable_wrapper .dataTables_filter input{

    border-radius:12px;

    border:1px solid rgb(203 213 225);

    padding:.55rem .9rem;

}

.dark #jsonTable_wrapper .dataTables_filter input{

    background:#0f172a;

    border-color:#334155;

    color:white;

}

#jsonTable_wrapper .dataTables_length select{

    border-radius:12px;

    border:1px solid rgb(203 213 225);

    padding:.45rem .75rem;

}

.dark #jsonTable_wrapper .dataTables_length select{

    background:#0f172a;

    border-color:#334155;

    color:white;

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

input,
select{

    transition:.2s;

}

input:focus,
select:focus{

    outline:none;

    border-color:#2563eb;

    box-shadow:0 0 0 3px rgb(37 99 235 / .15);

}

code{

    white-space:break-spaces;

    word-break:break-word;

}

</style>

@endpush

@endsection
