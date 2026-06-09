@extends('user.layouts.app')

@section('title', 'Kinerja Prioritas')
@section('page_title', 'Kinerja Prioritas')
@section('breadcrumb', 'Kinerja Prioritas')

@section('content')

<div class="space-y-6">

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold">
                    Laporan Kinerja Prioritas
                </h2>

                <p class="text-purple-100 text-sm mt-2">
                    Pilih prioritas, lalu input bukti kegiatan yang menunjang prioritas tersebut.
                </p>
            </div>

            <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4">
                <p class="text-xs text-purple-100">
                    Operator
                </p>

                <p class="font-semibold">
                    {{ session('pegawai_nama') ?? '-' }}
                </p>

                <p class="text-xs text-purple-100 mt-1">
                    {{ session('pegawai_bidang') ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    @forelse ($prioritas as $item)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                <div>
                    <div class="text-sm text-slate-500">
                        Tahun {{ $item->prioritas_tahun }}
                    </div>

                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $item->prioritas_judul }}
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $item->prioritas_deskripsi ?: '-' }}
                    </p>
                </div>

                <button type="button"
                    onclick='openBuktiModal(@json($item))'
                    class="px-5 py-3 rounded-2xl bg-purple-600 text-white font-semibold hover:bg-purple-700">
                    + Input Bukti
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Operator</th>
                            <th class="py-3 px-3">Deskripsi Kegiatan</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Bukti</th>
                            <th class="py-3 px-3">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($item->bukti->sortByDesc('bukti_tanggal_kegiatan') as $bukti)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-4 px-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="py-4 px-3">
                                    <div class="font-semibold text-slate-900">
                                        {{ $bukti->bukti_user_nama ?? '-' }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $bukti->bukti_user_nip ?? '-' }}
                                    </div>

                                    <div class="text-xs text-slate-400 mt-1">
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
                                    <div class="flex flex-col gap-1">
                                        @foreach ($bukti->files as $file)
                                            <a href="{{ asset($file->file_path) }}"
                                                target="_blank"
                                                class="text-blue-600 hover:underline">
                                                Bukti
                                            </a>
                                        @endforeach

                                        @if ($bukti->files->isEmpty())
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-3">
                                    @if ($bukti->bukti_status === 'Aktif')
                                        <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">
                                    Belum ada bukti dukung.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @empty
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            Belum ada prioritas aktif.
        </div>
    @endforelse

</div>

<div id="buktiModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Input Bukti Prioritas
                </h2>

                <p id="modal_prioritas_judul" class="text-sm text-slate-500"></p>
            </div>

            <button type="button"
                onclick="closeBuktiModal()"
                class="text-slate-400 hover:text-slate-800 text-xl">
                ✕
            </button>
        </div>

        <form id="buktiForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs text-slate-500">
                        Operator
                    </p>

                    <p class="font-semibold text-slate-900 mt-1">
                        {{ session('pegawai_nama') ?? '-' }}
                    </p>

                    <p class="text-xs text-slate-500 mt-1">
                        {{ session('pegawai_nip') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs text-slate-500">
                        Bidang
                    </p>

                    <p class="font-semibold text-slate-900 mt-1">
                        {{ session('pegawai_bidang') ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Deskripsi Kegiatan
                </label>

                <textarea name="bukti_deskripsi_kegiatan"
                    rows="4"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="Tuliskan kegiatan yang menunjang prioritas ini"
                    required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tanggal Kegiatan
                </label>

                <input type="date"
                    name="bukti_tanggal_kegiatan"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Bukti Kegiatan Maksimal 5 File
                </label>

                <input type="file"
                    name="bukti_file[]"
                    multiple
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white"
                    required>

                <p class="text-xs text-slate-500 mt-2">
                    Format: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX. Maksimal 5 file.
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                    onclick="closeBuktiModal()"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-3 rounded-2xl bg-purple-600 text-white font-semibold hover:bg-purple-700">
                    Simpan Bukti
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    function openBuktiModal(item) {
        document.getElementById('modal_prioritas_judul').innerText = item.prioritas_judul;

        let action = "{{ url('/user/prioritas') }}/" + item.prioritas_uid + "/bukti/store";

        document.getElementById('buktiForm').setAttribute('action', action);

        document.getElementById('buktiModal').classList.remove('hidden');
        document.getElementById('buktiModal').classList.add('flex');
    }

    function closeBuktiModal() {
        document.getElementById('buktiModal').classList.add('hidden');
        document.getElementById('buktiModal').classList.remove('flex');

        document.getElementById('buktiForm').reset();
    }
</script>

@endsection