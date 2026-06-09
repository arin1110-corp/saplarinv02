<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Saplarin - Kinerja Prioritas</title>
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
                        Laporan Kinerja Prioritas
                    </h1>
                    <p class="text-slate-400 text-sm">
                        Admin membuat judul prioritas. Operator menginput bukti dukung.
                    </p>
                </div>

                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-semibold">
                    + Tambah Prioritas
                </button>
            </div>

            <div class="space-y-6">

                @forelse ($prioritas as $item)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">

                        <div class="flex justify-between gap-4 mb-5">
                            <div>
                                <div class="text-xs text-slate-400">
                                    Tahun {{ $item->prioritas_tahun }}
                                </div>

                                <h2 class="text-xl font-bold">
                                    {{ $item->prioritas_judul }}
                                </h2>

                                <p class="text-sm text-slate-400 mt-1">
                                    {{ $item->prioritas_deskripsi ?: '-' }}
                                </p>

                                <div class="mt-2">
                                    @if ($item->prioritas_status === 'Aktif')
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <button onclick='openEditModal(@json($item))'
                                class="bg-amber-500 hover:bg-amber-600 px-3 py-2 rounded-xl text-sm h-fit">
                                Edit
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-700 text-left text-slate-400">
                                        <th class="py-3 px-3">No</th>
                                        <th class="py-3 px-3">Operator</th>
                                        <th class="py-3 px-3">Deskripsi Kegiatan</th>
                                        <th class="py-3 px-3">Tanggal</th>
                                        <th class="py-3 px-3">Input Oleh</th>
                                        <th class="py-3 px-3">File</th>
                                        <th class="py-3 px-3">Status</th>
                                        <th class="py-3 px-3">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($item->bukti as $bukti)
                                        <tr class="border-b border-slate-800">
                                            <td class="py-4 px-3">{{ $loop->iteration }}</td>

                                            <td class="py-4 px-3">
                                                <div class="font-semibold">
                                                    {{ $bukti->bukti_user_nama ?? '-' }}
                                                </div>

                                                <div class="text-xs text-slate-400">
                                                    {{ $bukti->bukti_user_nip ?? '-' }}
                                                </div>

                                                <div class="text-xs text-slate-500 mt-1">
                                                    {{ $bukti->bukti_bidang_nama ?? '-' }}
                                                </div>
                                            </td>

                                            <td class="py-4 px-3">
                                                {{ $bukti->bukti_deskripsi_kegiatan }}
                                            </td>

                                            <td class="py-4 px-3">
                                                {{ $bukti->bukti_tanggal_kegiatan?->format('d/m/Y') }}
                                            </td>

                                            <td class="py-4 px-3">
                                                <div>{{ $bukti->bukti_user_nama }}</div>
                                                <div class="text-xs text-slate-400">
                                                    {{ $bukti->bukti_user_nip }}
                                                </div>
                                            </td>

                                            <td class="py-4 px-3">
                                                <div class="flex flex-col gap-1">
                                                    @foreach ($bukti->files as $file)
                                                        <a href="{{ asset($file->file_path) }}" target="_blank"
                                                            class="text-blue-400 hover:underline">
                                                            Bukti
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </td>

                                            <td class="py-4 px-3">
                                                {{ $bukti->bukti_status }}
                                            </td>

                                            <td class="py-4 px-3">
                                                @if ($bukti->bukti_status === 'Aktif')
                                                    <form method="POST"
                                                        action="{{ route('admin.prioritas.bukti.nonaktif', $bukti->bukti_uid) }}"
                                                        onsubmit="return confirm('Nonaktifkan bukti ini?')">
                                                        @csrf
                                                        <button
                                                            class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                                            Nonaktif
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('admin.prioritas.bukti.aktif', $bukti->bukti_uid) }}">
                                                        @csrf
                                                        <button
                                                            class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-xs">
                                                            Aktif
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-6 text-slate-500">
                                                Belum ada bukti dukung.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                @empty
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center text-slate-400">
                        Belum ada data prioritas.
                    </div>
                @endforelse

            </div>

        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="prioritasModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl p-6">

            <div class="flex justify-between mb-5">
                <h2 class="text-xl font-bold">Tambah Prioritas</h2>
                <button onclick="closeModal()">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.prioritas.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Tahun</label>
                    <input type="number" name="prioritas_tahun" value="{{ date('Y') }}"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Judul Prioritas</label>
                    <input type="text" name="prioritas_judul"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Deskripsi</label>
                    <textarea name="prioritas_deskripsi" rows="3"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Status</label>
                    <select name="prioritas_status"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="bg-slate-700 px-4 py-2 rounded-xl">
                        Batal
                    </button>

                    <button class="bg-blue-600 px-4 py-2 rounded-xl">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editPrioritasModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl p-6">

            <div class="flex justify-between mb-5">
                <h2 class="text-xl font-bold">Edit Prioritas</h2>
                <button onclick="closeEditModal()">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.prioritas.update') }}">
                @csrf

                <input type="hidden" id="edit_prioritas_id" name="prioritas_id">

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Tahun</label>
                    <input type="number" id="edit_prioritas_tahun" name="prioritas_tahun"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Judul Prioritas</label>
                    <input type="text" id="edit_prioritas_judul" name="prioritas_judul"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Deskripsi</label>
                    <textarea id="edit_prioritas_deskripsi" name="prioritas_deskripsi" rows="3"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm">Status</label>
                    <select id="edit_prioritas_status" name="prioritas_status"
                        class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="bg-slate-700 px-4 py-2 rounded-xl">
                        Batal
                    </button>

                    <button class="bg-blue-600 px-4 py-2 rounded-xl">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('prioritasModal').classList.remove('hidden');
            document.getElementById('prioritasModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('prioritasModal').classList.add('hidden');
            document.getElementById('prioritasModal').classList.remove('flex');
        }

        function openEditModal(item) {
            document.getElementById('edit_prioritas_id').value = item.prioritas_id;
            document.getElementById('edit_prioritas_tahun').value = item.prioritas_tahun;
            document.getElementById('edit_prioritas_judul').value = item.prioritas_judul;
            document.getElementById('edit_prioritas_deskripsi').value = item.prioritas_deskripsi ?? '';
            document.getElementById('edit_prioritas_status').value = item.prioritas_status;

            document.getElementById('editPrioritasModal').classList.remove('hidden');
            document.getElementById('editPrioritasModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editPrioritasModal').classList.add('hidden');
            document.getElementById('editPrioritasModal').classList.remove('flex');
        }
    </script>

</body>

</html>
