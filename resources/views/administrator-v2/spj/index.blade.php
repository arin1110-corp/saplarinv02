@extends('administrator-v2.layouts.app')

@section('title','Data Pagu SPJ')

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

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

            Data Pagu SPJ

        </h1>

        <p class="text-slate-500 dark:text-slate-400 mt-1">

            Kelola pagu SPJ berdasarkan Unit, Program, Kegiatan dan Sub Kegiatan.

        </p>

    </div>

    <div class="flex gap-3">

        <button
            onclick="openModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold">

            <i class="bi bi-plus-circle"></i>

            Tambah Data

        </button>

    </div>

</div>

<div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table
            id="spjTable"
            class="min-w-full text-sm">

            <thead class="bg-slate-100 dark:bg-slate-800">

                <tr>

                    <th>No</th>

                    <th>Tahun</th>

                    <th>Unit</th>

                    <th>Program</th>

                    <th>Kegiatan</th>

                    <th>Sub Kegiatan</th>

                    <th>Pagu Final</th>

                    <th>Realisasi</th>

                    <th>Sisa</th>

                    <th>Serapan</th>

                    <th>Status</th>

                    <th width="180">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @foreach($pagus as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->spj_pagu_tahun }}</td>

                    <td>{{ $item->spj_unit_nama }}</td>

                    <td>{{ $item->spj_program_nama }}</td>

                    <td>{{ $item->spj_kegiatan_nama }}</td>

                    <td>{{ $item->spj_sub_kegiatan_nama }}</td>

                    <td>

                        Rp {{ number_format($item->spj_pagu_final,0,',','.') }}

                    </td>

                    <td>

                        Rp {{ number_format($item->spj_realisasi,0,',','.') }}

                    </td>

                    <td>

                        Rp {{ number_format($item->spj_sisa,0,',','.') }}

                    </td>

                    <td>

                        <div class="flex items-center gap-2">

                            <div class="w-24 h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">

                                <div
                                    class="h-full bg-blue-600"
                                    style="width: {{ $item->spj_persen }}%"></div>

                            </div>

                            <span class="text-xs font-semibold">

                                {{ number_format($item->spj_persen,2) }}%

                            </span>

                        </div>

                    </td>

                    <td>

                        @if($item->spj_status==1)

                        <span class="inline-flex rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                            Aktif

                        </span>

                        @else

                        <span class="inline-flex rounded-full bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 py-1 text-xs font-semibold">

                            Nonaktif

                        </span>

                        @endif

                    </td>

                    <td>

                        <div class="flex justify-center gap-2">

                            <button
                                onclick='openDetailModal(@json($item))'
                                class="rounded-lg bg-slate-600 hover:bg-slate-700 p-2 text-white">

                                <i class="bi bi-eye"></i>

                            </button>

                            <button
                                onclick='openEditModal(@json($item))'
                                class="rounded-lg bg-amber-500 hover:bg-amber-600 p-2 text-white">

                                <i class="bi bi-pencil-square"></i>

                            </button>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
<!-- ======================= MODAL TAMBAH ======================= -->
<div
    id="addModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-6xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-700">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Tambah Data Pagu SPJ

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Input data pagu SPJ.

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
            action="{{ route('admin.spj.store') }}"
            class="flex flex-col flex-1 overflow-hidden">

            @csrf

            <div class="flex-1 overflow-y-auto p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                    <div>

                        <label class="block mb-2 font-medium">

                            Tahun

                        </label>

                        <select
                            name="spj_pagu_tahun"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">
                            @php
                                $tahun = range(date('Y'), 2020);
                            @endphp
                            @foreach($tahun as $t)

                            <option value="{{ $t }}">

                                {{ $t }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Unit

                        </label>

                        <select
                            name="spj_unit_uid"
                            id="spj_unit_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($units as $u)

                            <option value="{{ $u->unit_uid }}">

                                {{ $u->unit_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Program

                        </label>

                        <select
                            name="spj_program_uid"
                            id="spj_program_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($programs as $p)

                            <option value="{{ $p->program_uid }}">

                                {{ $p->program_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Kegiatan

                        </label>

                        <select
                            name="spj_kegiatan_uid"
                            id="spj_kegiatan_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($kegiatans as $k)

                            <option value="{{ $k->kegiatan_uid }}">

                                {{ $k->kegiatan_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="xl:col-span-2">

                        <label class="block mb-2 font-medium">

                            Sub Kegiatan

                        </label>

                        <select
                            name="spj_sub_kegiatan_uid"
                            id="spj_sub_kegiatan_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($subKegiatans as $s)

                            <option value="{{ $s->sub_kegiatan_uid }}">

                                {{ $s->sub_kegiatan_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Pagu Induk

                        </label>

                        <input
                            type="number"
                            name="spj_pagu_induk"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Pagu Pergeseran

                        </label>

                        <input
                            type="number"
                            name="spj_pagu_pergeseran"
                            value="0"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Pagu Final

                        </label>

                        <input
                            type="number"
                            name="spj_pagu_final"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3">

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-200 dark:border-slate-700">

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
<!-- ======================= MODAL EDIT ======================= -->
<div
    id="editModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-6xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-700">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Edit Data Pagu SPJ

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Perbarui data pagu SPJ.

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
            action="{{ route('admin.spj.update') }}"
            class="flex flex-col flex-1 overflow-hidden">

            @csrf

            <input
                type="hidden"
                name="spj_pagu_uid"
                id="edit_spj_pagu_uid">

            <div class="flex-1 overflow-y-auto p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                    <div>

                        <label class="block mb-2 font-medium">

                            Tahun

                        </label>

                        <select
                            id="edit_spj_pagu_tahun"
                            name="spj_pagu_tahun"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($tahun as $t)

                            <option value="{{ $t }}">

                                {{ $t }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Unit

                        </label>

                        <select
                            id="edit_spj_unit_uid"
                            name="spj_unit_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($units as $u)

                            <option value="{{ $u->unit_uid }}">

                                {{ $u->unit_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Program

                        </label>

                        <select
                            id="edit_spj_program_uid"
                            name="spj_program_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($programs as $p)

                            <option value="{{ $p->program_uid }}">

                                {{ $p->program_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Kegiatan

                        </label>

                        <select
                            id="edit_spj_kegiatan_uid"
                            name="spj_kegiatan_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($kegiatans as $k)

                            <option value="{{ $k->kegiatan_uid }}">

                                {{ $k->kegiatan_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="xl:col-span-2">

                        <label class="block mb-2 font-medium">

                            Sub Kegiatan

                        </label>

                        <select
                            id="edit_spj_sub_kegiatan_uid"
                            name="spj_sub_kegiatan_uid"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            @foreach($subKegiatans as $s)

                            <option value="{{ $s->sub_kegiatan_uid }}">

                                {{ $s->sub_kegiatan_nama }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Pagu Induk

                        </label>

                        <input
                            id="edit_spj_pagu_induk"
                            type="number"
                            name="spj_pagu_induk"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Pagu Pergeseran

                        </label>

                        <input
                            id="edit_spj_pagu_pergeseran"
                            type="number"
                            name="spj_pagu_pergeseran"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            Pagu Final

                        </label>

                        <input
                            id="edit_spj_pagu_final"
                            type="number"
                            name="spj_pagu_final"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                            readonly>

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-200 dark:border-slate-700">

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
<!-- ======================= MODAL DETAIL ======================= -->
<div
    id="detailModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-6xl max-h-[90vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-700">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Detail Data Pagu SPJ

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Informasi lengkap data pagu.

                </p>

            </div>

            <button
                type="button"
                onclick="closeDetailModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        <!-- BODY -->
        <div class="flex-1 overflow-y-auto p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Tahun

                    </label>

                    <input
                        id="detail_spj_pagu_tahun"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Unit

                    </label>

                    <input
                        id="detail_spj_unit_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Program

                    </label>

                    <input
                        id="detail_spj_program_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Kegiatan

                    </label>

                    <input
                        id="detail_spj_kegiatan_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div class="xl:col-span-2">

                    <label class="block mb-2 text-sm font-medium">

                        Sub Kegiatan

                    </label>

                    <input
                        id="detail_spj_sub_kegiatan_nama"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Pagu Induk

                    </label>

                    <input
                        id="detail_spj_pagu_induk"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Pagu Pergeseran

                    </label>

                    <input
                        id="detail_spj_pagu_pergeseran"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Pagu Final

                    </label>

                    <input
                        id="detail_spj_pagu_final"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3 font-semibold"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Realisasi

                    </label>

                    <input
                        id="detail_spj_realisasi"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Sisa Anggaran

                    </label>

                    <input
                        id="detail_spj_sisa"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Persentase Serapan

                    </label>

                    <input
                        id="detail_spj_persen"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">

                        Status

                    </label>

                    <input
                        id="detail_spj_status"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3"
                        readonly>

                </div>

            </div>

        </div>

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

        showModal('addModal');

    }

    function closeModal(){

        document.querySelector('#addModal form').reset();

        hideModal('addModal');

    }

    function openEditModal(item){

        $('#edit_spj_pagu_uid').val(item.spj_pagu_uid);

        $('#edit_spj_pagu_tahun').val(item.spj_pagu_tahun);

        $('#edit_spj_unit_uid').val(item.spj_unit_uid);

        $('#edit_spj_program_uid').val(item.spj_program_uid);

        $('#edit_spj_kegiatan_uid').val(item.spj_kegiatan_uid);

        $('#edit_spj_sub_kegiatan_uid').val(item.spj_sub_kegiatan_uid);

        $('#edit_spj_pagu_induk').val(item.spj_pagu_induk);

        $('#edit_spj_pagu_pergeseran').val(item.spj_pagu_pergeseran);

        $('#edit_spj_pagu_final').val(item.spj_pagu_final);

        showModal('editModal');

    }

    function closeEditModal(){

        hideModal('editModal');

    }

    function openDetailModal(item){

        $('#detail_spj_pagu_tahun').val(item.spj_pagu_tahun);

        $('#detail_spj_unit_nama').val(item.spj_unit_nama);

        $('#detail_spj_program_nama').val(item.spj_program_nama);

        $('#detail_spj_kegiatan_nama').val(item.spj_kegiatan_nama);

        $('#detail_spj_sub_kegiatan_nama').val(item.spj_sub_kegiatan_nama);

        $('#detail_spj_pagu_induk').val(item.spj_pagu_induk);

        $('#detail_spj_pagu_pergeseran').val(item.spj_pagu_pergeseran);

        $('#detail_spj_pagu_final').val(item.spj_pagu_final);

        $('#detail_spj_realisasi').val(item.spj_realisasi);

        $('#detail_spj_sisa').val(item.spj_sisa);

        $('#detail_spj_persen').val(item.spj_persen+' %');

        $('#detail_spj_status').val(item.spj_status==1?'Aktif':'Nonaktif');

        showModal('detailModal');

    }

    function closeDetailModal(){

        hideModal('detailModal');

    }

    $(function(){

        $('#spjTable').DataTable({

            responsive:true,

            autoWidth:false,

            pageLength:25,

            order:[[0,'desc']],

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

        ['addModal','editModal','detailModal'].forEach(function(id){

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

            closeModal();

            closeEditModal();

            closeDetailModal();

        }

    });

</script>

@endpush

@push('styles')

<style>

#spjTable_wrapper .dataTables_filter input{

    border-radius:12px;

    border:1px solid rgb(203 213 225);

    padding:.55rem .9rem;

}

.dark #spjTable_wrapper .dataTables_filter input{

    background:#0f172a;

    border-color:#334155;

    color:#fff;

}

#spjTable_wrapper .dataTables_length select{

    border-radius:12px;

    border:1px solid rgb(203 213 225);

    padding:.45rem .75rem;

}

.dark #spjTable_wrapper .dataTables_length select{

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

.dataTables_wrapper .dataTables_info{

    color:#64748b;

    margin-top:12px;

}

.dark .dataTables_wrapper .dataTables_info{

    color:#94a3b8;

}

input,
textarea,
select{

    transition:.2s;

}

input:focus,
textarea:focus,
select:focus{

    outline:none;

    border-color:#2563eb;

    box-shadow:0 0 0 3px rgb(37 99 235 /.15);

}

textarea{

    resize:vertical;

}

</style>

@endpush