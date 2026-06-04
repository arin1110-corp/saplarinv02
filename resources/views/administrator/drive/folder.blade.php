<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Folder Drive - SAPLARIN</title>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white">

<div class="flex min-h-screen">

    @include('administrator.partials.sidebar')

    <div class="flex-1 p-6">

        @include('administrator.partials.header')

        @if (session('success'))
            <div class="mb-5 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">
                    Folder Drive
                </h1>
                <p class="text-slate-400 text-sm">
                    Kelola folder Google Drive untuk sinkronisasi file SAPLARIN.
                </p>
            </div>

            <button onclick="openModal()"
                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">
                + Tambah Folder
            </button>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-700">
                        <th class="py-3">No</th>
                        <th>Nama Folder</th>
                        <th>Prefix</th>
                        <th>Folder ID</th>
                        <th>JSON Credential</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($folders as $folder)
                        <tr class="border-b border-slate-800 hover:bg-slate-800/60">
                            <td class="py-4">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $folder->folder_nama }}
                            </td>

                            <td>
                                <code class="bg-slate-950 px-3 py-1 rounded-lg text-blue-300">
                                    {{ $folder->folder_prefix }}
                                </code>
                            </td>

                            <td>
                                <code class="text-xs text-green-300">
                                    {{ $folder->folder_drive_id }}
                                </code>
                            </td>

                            <td>
                                {{ $folder->json->json_nama ?? '-' }}
                            </td>

                            <td>
                                @if ($folder->folder_status == 1)
                                    <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <button onclick='openEditModal(@json($folder))'
                                    class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">
                                Belum ada folder Drive.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="mt-5 bg-slate-900 border border-slate-800 rounded-2xl p-5 text-sm text-slate-300">
            Untuk BBM gunakan prefix:
            <code class="text-blue-300">bbm</code>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="folderModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 mx-4">

        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-bold">
                Tambah Folder Drive
            </h2>

            <button onclick="closeModal()" class="text-slate-400 hover:text-white text-xl">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('admin.drive.folder.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm mb-2">Nama Folder</label>
                <input name="folder_nama"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    placeholder="Folder BBM"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Prefix</label>
                <input name="folder_prefix"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    placeholder="bbm"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Folder ID Google Drive</label>
                <input name="folder_drive_id"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    placeholder="1AbCdEfGhIj..."
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">JSON Credential</label>
                <select name="folder_json"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    required>
                    <option value="">Pilih JSON</option>

                    @foreach ($jsons as $json)
                        <option value="{{ $json->json_id }}">
                            {{ $json->json_nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Status</label>
                <select name="folder_status"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl">
                    Batal
                </button>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

{{-- MODAL EDIT --}}
<div id="editFolderModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 mx-4">

        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-bold">
                Edit Folder Drive
            </h2>

            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white text-xl">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('admin.drive.folder.update') }}">
            @csrf

            <input type="hidden" id="edit_folder_id" name="folder_id">

            <div class="mb-4">
                <label class="block text-sm mb-2">Nama Folder</label>
                <input id="edit_folder_nama"
                    name="folder_nama"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Prefix</label>
                <input id="edit_folder_prefix"
                    name="folder_prefix"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Folder ID Google Drive</label>
                <input id="edit_folder_drive_id"
                    name="folder_drive_id"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">JSON Credential</label>
                <select id="edit_folder_json"
                    name="folder_json"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    required>
                    @foreach ($jsons as $json)
                        <option value="{{ $json->json_id }}">
                            {{ $json->json_nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Status</label>
                <select id="edit_folder_status"
                    name="folder_status"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                    class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-xl">
                    Batal
                </button>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl">
                    Update
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openModal() {
        document.getElementById('folderModal').classList.remove('hidden');
        document.getElementById('folderModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('folderModal').classList.add('hidden');
        document.getElementById('folderModal').classList.remove('flex');
    }

    function openEditModal(folder) {
        document.getElementById('edit_folder_id').value = folder.folder_id;
        document.getElementById('edit_folder_nama').value = folder.folder_nama;
        document.getElementById('edit_folder_prefix').value = folder.folder_prefix;
        document.getElementById('edit_folder_drive_id').value = folder.folder_drive_id;
        document.getElementById('edit_folder_json').value = folder.folder_json;
        document.getElementById('edit_folder_status').value = folder.folder_status;

        document.getElementById('editFolderModal').classList.remove('hidden');
        document.getElementById('editFolderModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editFolderModal').classList.add('hidden');
        document.getElementById('editFolderModal').classList.remove('flex');
    }
</script>

</body>
</html>