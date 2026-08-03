@extends('administrator-v2.layouts.app')

@section('title', 'Kelola User')

@section('page-title', 'Kelola User')

@section('page-description', 'Kelola role pengguna SAPLARIN')

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


        <div>

            <button type="button" onclick="openModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold transition">

                <i class="bi bi-plus-circle"></i>

                Tambah User

            </button>

        </div>

    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table id="userTable" class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

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

                        <th class="px-4 py-4 text-left">

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

                                    @foreach ($user['roles'] as $role)
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/20 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300">

                                            {{ $role }}

                                        </span>
                                    @endforeach

                                </div>

                            </td>

                            <td class="px-4 py-4">

                                <button type="button" onclick='openEditModal(@json($user))'
                                    class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-2 text-xs font-medium text-white transition">

                                    <i class="bi bi-pencil-square"></i>

                                    Edit

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-12 text-center">

                                <div class="flex flex-col items-center gap-4">

                                    <div
                                        class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">

                                        <i class="bi bi-people text-3xl text-slate-400"></i>

                                    </div>

                                    <div>

                                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">

                                            Belum Ada Data

                                        </h3>

                                        <p class="text-sm text-slate-500">

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
    <!-- MODAL TAMBAH USER -->
    <div id="addUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Tambah User

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Tambahkan role pengguna SAPLARIN.

                    </p>

                </div>

                <button type="button" onclick="closeModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form method="POST" action="/admin/store">

                @csrf

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Pilih Pegawai

                        </label>

                        <select id="pegawaiSelect" name="user_uid" class="w-full" required>

                            <option value="">

                                Cari Nama / NIP

                            </option>

                            @foreach ($pegawai as $p)
                                <option value="{{ $p['id'] }}">

                                    {{ $p['nama'] }} - {{ $p['nip'] }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">

                            Pilih Role

                        </label>

                        <div class="grid grid-cols-2 gap-3">

                            @foreach ($availableRoles as $role)
                                <label
                                    class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-700 p-3 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition">

                                    <input type="checkbox" name="roles[]" value="{{ $role }}"
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                                    <span class="text-sm text-slate-700 dark:text-slate-300">

                                        {{ $role }}

                                    </span>

                                </label>
                            @endforeach

                        </div>

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

    <!-- MODAL EDIT USER -->
    <div id="editUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">

                <div>

                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">

                        Edit Role User

                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Perbarui role pengguna SAPLARIN.

                    </p>

                </div>

                <button type="button" onclick="closeEditModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <form method="POST" action="/admin/update-role">

                @csrf

                <input type="hidden" id="edit_user_uid" name="user_uid">

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">

                            Pegawai

                        </label>

                        <input id="edit_nama" type="text" readonly
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3">

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">

                            Role

                        </label>

                        <div class="grid grid-cols-2 gap-3">

                            @foreach ($availableRoles as $role)
                                <label
                                    class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-700 p-3 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition">

                                    <input type="checkbox" name="roles[]" value="{{ $role }}"
                                        class="edit-role-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                                    <span class="text-sm text-slate-700 dark:text-slate-300">

                                        {{ $role }}

                                    </span>

                                </label>
                            @endforeach

                        </div>

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

                showModal('addUserModal');

            }

            function closeModal() {

                const form = document.querySelector('#addUserModal form');

                if (form) {

                    form.reset();

                }

                hideModal('addUserModal');

            }

            function openEditModal(user) {

                document.getElementById('edit_user_uid').value =
                    user.id ?? user.user_uid ?? '';

                document.getElementById('edit_nama').value =
                    (user.nama ?? '') + ' - ' + (user.nip ?? '');

                document.querySelectorAll('.edit-role-checkbox').forEach(function(item) {

                    item.checked = false;

                });

                if (Array.isArray(user.roles)) {

                    user.roles.forEach(function(role) {

                        const checkbox = document.querySelector(
                            '.edit-role-checkbox[value="' + role + '"]'
                        );

                        if (checkbox) {

                            checkbox.checked = true;

                        }

                    });

                }

                showModal('editUserModal');

            }

            function closeEditModal() {

                const form = document.querySelector('#editUserModal form');

                if (form) {

                    form.reset();

                }

                hideModal('editUserModal');

            }

            document.addEventListener('DOMContentLoaded', function() {

                if (window.jQuery) {

                    $('#pegawaiSelect').select2({
                        dropdownParent: $('#addUserModal'),
                        width: '100%',
                        placeholder: 'Cari Nama / NIP / NIK',
                        allowClear: true,
                        minimumInputLength: 0,
                        matcher: function(params, data) {

                            if ($.trim(params.term) === '') {
                                return data;
                            }

                            if (typeof data.text === 'undefined') {
                                return null;
                            }

                            const term = params.term.toLowerCase();
                            const text = data.text.toLowerCase();

                            if (text.indexOf(term) > -1) {
                                return data;
                            }

                            return null;
                        }
                    });

                }

                if (typeof DataTable !== 'undefined') {

                    new DataTable('#userTable', {

                        responsive: true,

                        pageLength: 10,

                        autoWidth: false,

                        language: {

                            search: "Cari:",

                            searchPlaceholder: "Cari user...",

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

                ['addUserModal', 'editUserModal'].forEach(function(id) {

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

                    hideModal('addUserModal');

                    hideModal('editUserModal');

                }

            });
        </script>
    @endpush

    @push('styles')
        <style>
            .select2-search--dropdown {
                display: block !important;
                padding: 10px;
                background: #fff;
            }

            .dark .select2-search--dropdown {
                background: #0f172a;
            }

            .select2-search__field {
                width: 100% !important;
                height: 42px !important;
                border-radius: 10px !important;
                border: 1px solid #cbd5e1 !important;
                padding: 0 12px !important;
                outline: none !important;
            }

            .dark .select2-search__field {
                background: #1e293b !important;
                color: #fff !important;
                border-color: #334155 !important;
            }

            .select2-results {
                max-height: 320px;
            }

            .select2-results__options {
                max-height: 320px !important;
            }

            #userTable_wrapper .dataTables_filter input {

                border-radius: 12px;

                border: 1px solid rgb(203 213 225);

                padding: .55rem .9rem;

                background: white;

            }

            .dark #userTable_wrapper .dataTables_filter input {

                background: #0f172a;

                border-color: #334155;

                color: white;

            }

            #userTable_wrapper .dataTables_length select {

                border-radius: 12px;

                border: 1px solid rgb(203 213 225);

                padding: .45rem .75rem;

                background: white;

            }

            .dark #userTable_wrapper .dataTables_length select {

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

            .dataTables_wrapper .dataTables_paginate .paginate_button {

                border-radius: 10px !important;

                margin: 0 2px;

            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {

                background: #2563eb !important;

                color: white !important;

                border: 0 !important;

            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {

                background: #1d4ed8 !important;

                color: white !important;

                border: 0 !important;

            }

            .dataTables_processing {

                border-radius: 18px;

                border: 1px solid #e2e8f0;

                box-shadow: 0 15px 40px rgba(0, 0, 0, .08);

            }

            .dark .dataTables_processing {

                background: #0f172a;

                border-color: #334155;

                color: white;

            }

            .select2-container {

                width: 100% !important;

            }

            .select2-container--default .select2-selection--single {

                height: 50px;

                border-radius: 12px;

                border: 1px solid rgb(203 213 225);

            }

            .dark .select2-container--default .select2-selection--single {

                background: #0f172a;

                border-color: #334155;

            }

            .select2-selection__rendered {

                line-height: 48px !important;

            }

            .select2-selection__arrow {

                height: 48px !important;

            }
        </style>
    @endpush

@endsection
