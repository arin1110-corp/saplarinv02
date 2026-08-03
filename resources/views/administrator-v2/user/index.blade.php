@extends('administrator-v2.layouts.app')

@section('title', 'Kelola User')

@section('page-title', 'Kelola User')

@section('page-description', 'Kelola role pengguna SAPLARIN')

@section('content')

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

    <div>

        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

            Kelola User

        </h2>

        <p class="text-slate-500 dark:text-slate-400">

            Kelola role pengguna SAPLARIN

        </p>

    </div>

    <button
        type="button"
        onclick="openModal()"
        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold transition">

        <i class="bi bi-plus-circle"></i>

        Tambah User

    </button>

</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table
            id="userTable"
            class="min-w-full text-sm">

            <thead
                class="bg-slate-100 dark:bg-slate-800">

                <tr>

                    <th class="px-4 py-4 text-left">

                        Nama

                    </th>

                    <th class="px-4 py-4 text-left">

                        NIP

                    </th>

                    <th class="px-4 py-4 text-left">

                        NIK

                    </th>

                    <th class="px-4 py-4 text-left">

                        Jabatan

                    </th>

                    <th class="px-4 py-4 text-left">

                        Bidang

                    </th>

                    <th class="px-4 py-4 text-left">

                        Jenis Kerja

                    </th>

                    <th class="px-4 py-4 text-left">

                        Role

                    </th>

                    <th class="px-4 py-4 text-center">

                        Aksi

                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($users as $user)

                <tr class="border-t border-slate-200 dark:border-slate-800">

                    <td class="px-4 py-4">

                        <div class="font-semibold text-slate-800 dark:text-white">

                            {{ $user['nama'] }}

                        </div>

                    </td>

                    <td class="px-4 py-4">

                        {{ $user['nip'] }}

                    </td>

                    <td class="px-4 py-4">

                        {{ $user['nik'] }}

                    </td>

                    <td class="px-4 py-4">

                        {{ $user['jabatan'] }}

                    </td>

                    <td class="px-4 py-4">

                        {{ $user['bidang'] }}

                    </td>

                    <td class="px-4 py-4">

                        {{ $user['jeniskerja'] }}

                    </td>

                    <td class="px-4 py-4">

                        <div class="flex flex-wrap gap-2">

                            @foreach($user['roles'] as $role)

                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300">

                                {{ $role }}

                            </span>

                            @endforeach

                        </div>

                    </td>

                    <td class="px-4 py-4">

                        <div class="flex justify-center">

                            <button
                                type="button"
                                onclick='openEditModal(@json($user))'
                                class="inline-flex items-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 px-4 py-2 text-white transition">

                                <i class="bi bi-pencil-square"></i>

                                Edit

                            </button>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="8"
                        class="px-6 py-12 text-center">

                        <div class="flex flex-col items-center gap-4">

                            <div
                                class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">

                                <i class="bi bi-people text-3xl text-slate-400"></i>

                            </div>

                            <div>

                                <h3
                                    class="font-semibold text-slate-700 dark:text-slate-200">

                                    Belum Ada Data

                                </h3>

                                <p
                                    class="text-sm text-slate-500">

                                    Belum ada data user.

                                </p>

                            </div>

                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
{{-- MODAL TAMBAH USER --}}
<div
    id="addUserModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Tambah User

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Tambahkan role baru untuk pegawai.

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
            action="/admin/store">

            @csrf

            <div class="p-6 space-y-6">

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Pilih Pegawai

                    </label>

                    <select
                        name="user_uid"
                        id="pegawaiSelect"
                        class="w-full rounded-xl"
                        required>

                        <option value="">

                            Cari Nama / NIP

                        </option>

                        @foreach($pegawai as $p)

                        <option value="{{ $p['id'] }}">

                            {{ $p['nama'] }} - {{ $p['nip'] }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block mb-3 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Pilih Role

                    </label>

                    <div class="grid grid-cols-2 gap-3">

                        @foreach($availableRoles as $role)

                        <label
                            class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-700 p-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition">

                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role }}"
                                class="rounded">

                            <span>

                                {{ $role }}

                            </span>

                        </label>

                        @endforeach

                    </div>

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

{{-- MODAL EDIT USER --}}
<div
    id="editUserModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

            <div>

                <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                    Edit Role User

                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Perbarui hak akses pengguna.

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
            action="/admin/update-role">

            @csrf

            <input
                type="hidden"
                name="user_uid"
                id="edit_user_uid">

            <div class="p-6 space-y-6">

                <div>

                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Pegawai

                    </label>

                    <input
                        id="edit_nama"
                        type="text"
                        readonly
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3">

                </div>

                <div>

                    <label class="block mb-3 text-sm font-medium text-slate-700 dark:text-slate-300">

                        Role

                    </label>

                    <div class="grid grid-cols-2 gap-3">

                        @foreach($availableRoles as $role)

                        <label
                            class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-700 p-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition">

                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role }}"
                                class="edit-role-checkbox rounded">

                            <span>

                                {{ $role }}

                            </span>

                        </label>

                        @endforeach

                    </div>

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
