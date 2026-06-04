<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>JSON Credential - SAPLARIN</title>
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
                    JSON Credential
                </h1>
                <p class="text-slate-400 text-sm">
                    Kelola path file credential Google Drive SAPLARIN.
                </p>
            </div>

            <button onclick="openModal()"
                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-medium">
                + Tambah JSON
            </button>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-700">
                        <th class="py-3">No</th>
                        <th>Nama</th>
                        <th>Path JSON</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jsons as $json)
                        <tr class="border-b border-slate-800 hover:bg-slate-800/60">
                            <td class="py-4">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $json->json_nama }}
                            </td>

                            <td>
                                <code class="bg-slate-950 px-3 py-1 rounded-lg text-blue-300">
                                    {{ $json->json_file }}
                                </code>
                            </td>

                            <td>
                                @if ($json->json_status == 1)
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
                                <button onclick='openEditModal(@json($json))'
                                    class="bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">
                                Belum ada JSON Credential.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="mt-5 bg-slate-900 border border-slate-800 rounded-2xl p-5 text-sm text-slate-300">
            Contoh path:
            <code class="text-blue-300">google-drive/bbm-service-account.json</code>
            <br>
            File asli disimpan di:
            <code class="text-blue-300">storage/app/google-drive/bbm-service-account.json</code>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="jsonModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 mx-4">

        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-bold">
                Tambah JSON Credential
            </h2>

            <button onclick="closeModal()" class="text-slate-400 hover:text-white text-xl">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('admin.drive.json.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm mb-2">Nama</label>
                <input name="json_nama"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    placeholder="Google Drive BBM"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Path JSON</label>
                <input name="json_file"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    placeholder="google-drive/bbm-service-account.json"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Status</label>
                <select name="json_status"
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
<div id="editJsonModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 mx-4">

        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-bold">
                Edit JSON Credential
            </h2>

            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white text-xl">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('admin.drive.json.update') }}">
            @csrf

            <input type="hidden" id="edit_json_id" name="json_id">

            <div class="mb-4">
                <label class="block text-sm mb-2">Nama</label>
                <input id="edit_json_nama"
                    name="json_nama"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Path JSON</label>
                <input id="edit_json_file"
                    name="json_file"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700 text-white"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-2">Status</label>
                <select id="edit_json_status"
                    name="json_status"
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
        document.getElementById('jsonModal').classList.remove('hidden');
        document.getElementById('jsonModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('jsonModal').classList.add('hidden');
        document.getElementById('jsonModal').classList.remove('flex');
    }

    function openEditModal(json) {
        document.getElementById('edit_json_id').value = json.json_id;
        document.getElementById('edit_json_nama').value = json.json_nama;
        document.getElementById('edit_json_file').value = json.json_file;
        document.getElementById('edit_json_status').value = json.json_status;

        document.getElementById('editJsonModal').classList.remove('hidden');
        document.getElementById('editJsonModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editJsonModal').classList.add('hidden');
        document.getElementById('editJsonModal').classList.remove('flex');
    }
</script>

</body>
</html>