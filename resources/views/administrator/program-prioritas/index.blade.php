<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin SAPLARIN - Kinerja Prioritas</title>
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
                        Laporan Kinerja Prioritas
                    </h1>

                    <p class="text-slate-400 text-sm">
                        Admin mengelola program prioritas. Operator menginput rencana aksi dan capaian.
                    </p>
                </div>

                <button onclick="openPrioritasModal()"
                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl font-semibold">
                    + Tambah Prioritas
                </button>
            </div>

            <div class="space-y-6">

                @forelse ($prioritas as $item)
                    @php
                        $totalTargetPrioritas = 0;
                        $totalCapaianAktif = 0;

                        foreach ($item->rencana as $rencanaHitung) {
                            $totalTargetPrioritas += (int) $rencanaHitung->rencana_target;

                            $totalCapaianAktif += $rencanaHitung
                                ->capaian
                                ->where('capaian_status', 'Aktif')
                                ->count();
                        }

                        $persenPrioritas = $totalTargetPrioritas > 0
                            ? ($totalCapaianAktif / $totalTargetPrioritas) * 100
                            : 0;

                        if ($persenPrioritas > 100) {
                            $persenPrioritas = 100;
                        }
                    @endphp

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">

                        <div class="flex justify-between gap-4 mb-6">
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

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if ($item->prioritas_status === 'Aktif')
                                        <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                            Nonaktif
                                        </span>
                                    @endif

                                    <span class="bg-purple-600/20 text-purple-300 px-3 py-1 rounded-full text-xs">
                                        Total Target: {{ $totalTargetPrioritas }}
                                    </span>

                                    <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                        Capaian Aktif: {{ $totalCapaianAktif }}
                                    </span>

                                    <span class="bg-blue-600/20 text-blue-300 px-3 py-1 rounded-full text-xs">
                                        Persentase: {{ number_format($persenPrioritas, 2, ',', '.') }}%
                                    </span>
                                </div>
                            </div>

                            <button onclick='openEditPrioritasModal(@json($item))'
                                class="bg-amber-500 hover:bg-amber-600 px-3 py-2 rounded-xl text-sm h-fit">
                                Edit
                            </button>
                        </div>

                        <div class="space-y-5">

                            @forelse ($item->rencana as $rencana)
                                @php
                                    $capaianAktifRencana = $rencana->capaian->where('capaian_status', 'Aktif')->count();
                                    $targetRencana = (int) $rencana->rencana_target;

                                    $persenRencana = $targetRencana > 0
                                        ? ($capaianAktifRencana / $targetRencana) * 100
                                        : 0;

                                    if ($persenRencana > 100) {
                                        $persenRencana = 100;
                                    }

                                    $persenPerCapaianRencana = $targetRencana > 0
                                        ? 100 / $targetRencana
                                        : 0;
                                @endphp

                                <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5">

                                    <div class="flex justify-between items-start gap-4 mb-4">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="font-bold text-lg">
                                                    {{ $rencana->rencana_judul }}
                                                </h3>

                                                @if ($rencana->rencana_status === 'Aktif')
                                                    <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                                        Nonaktif
                                                    </span>
                                                @endif

                                                <span class="bg-blue-600/20 text-blue-300 px-3 py-1 rounded-full text-xs">
                                                    {{ number_format($persenRencana, 2, ',', '.') }}%
                                                </span>
                                            </div>

                                            <div class="text-sm text-slate-300 mt-2">
                                                <span class="font-semibold">Target:</span>
                                                {{ $targetRencana }} capaian
                                            </div>

                                            <div class="text-xs text-slate-500 mt-2">
                                                Dibuat oleh {{ $rencana->rencana_user_nama ?? '-' }}
                                                |
                                                {{ $rencana->rencana_bidang_nama ?? '-' }}
                                                |
                                                Capaian aktif: {{ $capaianAktifRencana }}
                                            </div>
                                        </div>

                                        <div>
                                            @if ($rencana->rencana_status === 'Aktif')
                                                <form method="POST"
                                                    action="{{ route('admin.program-prioritas.rencana.nonaktif', $rencana->rencana_uid) }}"
                                                    onsubmit="return confirm('Nonaktifkan rencana aksi ini?')">
                                                    @csrf

                                                    <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                                        Nonaktif
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('admin.program-prioritas.rencana.aktif', $rencana->rencana_uid) }}">
                                                    @csrf

                                                    <button class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-xs">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm">
                                            <thead>
                                                <tr class="border-b border-slate-700 text-left text-slate-400">
                                                    <th class="py-3 px-3">No</th>
                                                    <th class="py-3 px-3">Capaian</th>
                                                    <th class="py-3 px-3">Persen</th>
                                                    <th class="py-3 px-3">Deskripsi</th>
                                                    <th class="py-3 px-3">Tanggal</th>
                                                    <th class="py-3 px-3">Operator</th>
                                                    <th class="py-3 px-3">Bukti</th>
                                                    <th class="py-3 px-3">Status</th>
                                                    <th class="py-3 px-3">Aksi</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse ($rencana->capaian as $capaian)
                                                    <tr class="border-b border-slate-700">
                                                        <td class="py-4 px-3">
                                                            {{ $loop->iteration }}
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            <div class="font-semibold">
                                                                {{ $capaian->capaian_judul }}
                                                            </div>
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            @if ($capaian->capaian_status === 'Aktif')
                                                                <span class="bg-blue-600/20 text-blue-300 px-3 py-1 rounded-full text-xs">
                                                                    {{ number_format($persenPerCapaianRencana, 2, ',', '.') }}%
                                                                </span>
                                                            @else
                                                                <span class="text-slate-500 text-xs">
                                                                    0%
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            {{ $capaian->capaian_deskripsi }}
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            {{ $capaian->capaian_tanggal_mulai?->format('d/m/Y') }}
                                                            -
                                                            {{ $capaian->capaian_tanggal_selesai?->format('d/m/Y') }}
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            <div class="font-semibold">
                                                                {{ $capaian->capaian_user_nama ?? '-' }}
                                                            </div>

                                                            <div class="text-xs text-slate-400">
                                                                {{ $capaian->capaian_user_nip ?? '-' }}
                                                            </div>

                                                            <div class="text-xs text-slate-500 mt-1">
                                                                {{ $capaian->capaian_bidang_nama ?? '-' }}
                                                            </div>
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            <div class="flex flex-col gap-1">
                                                                @foreach ($capaian->files as $file)
                                                                    <a href="{{ asset($file->file_path) }}"
                                                                        target="_blank"
                                                                        class="text-blue-400 hover:underline">
                                                                        Bukti
                                                                    </a>
                                                                @endforeach

                                                                @if ($capaian->files->isEmpty())
                                                                    <span class="text-slate-500">-</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            @if ($capaian->capaian_status === 'Aktif')
                                                                <span class="bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs">
                                                                    Aktif
                                                                </span>
                                                            @else
                                                                <span class="bg-red-600/20 text-red-300 px-3 py-1 rounded-full text-xs">
                                                                    Nonaktif
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td class="py-4 px-3">
                                                            @if ($capaian->capaian_status === 'Aktif')
                                                                <form method="POST"
                                                                    action="{{ route('admin.program-prioritas.capaian.nonaktif', $capaian->capaian_uid) }}"
                                                                    onsubmit="return confirm('Nonaktifkan capaian ini?')">
                                                                    @csrf

                                                                    <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded-lg text-xs">
                                                                        Nonaktif
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form method="POST"
                                                                    action="{{ route('admin.program-prioritas.capaian.aktif', $capaian->capaian_uid) }}">
                                                                    @csrf

                                                                    <button class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-xs">
                                                                        Aktifkan
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center py-6 text-slate-500">
                                                            Belum ada capaian.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            @empty
                                <div class="border border-dashed border-slate-700 rounded-2xl p-6 text-center text-slate-500">
                                    Belum ada rencana aksi.
                                </div>
                            @endforelse

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

    {{-- MODAL TAMBAH PRIORITAS --}}
    <div id="prioritasModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl p-6">

            <div class="flex justify-between mb-5">
                <h2 class="text-xl font-bold">Tambah Program Prioritas</h2>
                <button onclick="closePrioritasModal()">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.program-prioritas.store') }}">
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
                    <button type="button" onclick="closePrioritasModal()" class="bg-slate-700 px-4 py-2 rounded-xl">
                        Batal
                    </button>

                    <button class="bg-blue-600 px-4 py-2 rounded-xl">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- MODAL EDIT PRIORITAS --}}
    <div id="editPrioritasModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl p-6">

            <div class="flex justify-between mb-5">
                <h2 class="text-xl font-bold">Edit Program Prioritas</h2>
                <button onclick="closeEditPrioritasModal()">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.program-prioritas.update') }}">
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
                    <button type="button" onclick="closeEditPrioritasModal()"
                        class="bg-slate-700 px-4 py-2 rounded-xl">
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
        function openPrioritasModal() {
            document.getElementById('prioritasModal').classList.remove('hidden');
            document.getElementById('prioritasModal').classList.add('flex');
        }

        function closePrioritasModal() {
            document.getElementById('prioritasModal').classList.add('hidden');
            document.getElementById('prioritasModal').classList.remove('flex');
        }

        function openEditPrioritasModal(item) {
            document.getElementById('edit_prioritas_id').value = item.prioritas_id;
            document.getElementById('edit_prioritas_tahun').value = item.prioritas_tahun;
            document.getElementById('edit_prioritas_judul').value = item.prioritas_judul;
            document.getElementById('edit_prioritas_deskripsi').value = item.prioritas_deskripsi ?? '';
            document.getElementById('edit_prioritas_status').value = item.prioritas_status;

            document.getElementById('editPrioritasModal').classList.remove('hidden');
            document.getElementById('editPrioritasModal').classList.add('flex');
        }

        function closeEditPrioritasModal() {
            document.getElementById('editPrioritasModal').classList.add('hidden');
            document.getElementById('editPrioritasModal').classList.remove('flex');
        }
    </script>

</body>

</html>