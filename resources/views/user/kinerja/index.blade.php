@extends('user.layouts.app')

@section('title', 'Progress Kinerja Bidang')
@section('page_title', 'Progress Kinerja Bidang')
@section('breadcrumb', 'Progress Kinerja Bidang')

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

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-xl font-bold text-slate-900">
                Progress Kinerja Bidang
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Menampilkan data kinerja untuk bidang:
                <span class="font-semibold text-slate-800">
                    {{ session('pegawai_bidang') ?? '-' }}
                </span>
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Tahun</th>
                            <th class="py-3 px-3">Kegiatan</th>
                            <th class="py-3 px-3">Capaian TW</th>
                            <th class="py-3 px-3">Progress Terbaru</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($kinerjas as $item)
                            @php
                                $tw1 = $item->progress
                                    ->where('progress_triwulan', 'TW I')
                                    ->where('progress_status', 'Diterima')
                                    ->sum('progress_persentase');
                                $tw2 = $item->progress
                                    ->where('progress_triwulan', 'TW II')
                                    ->where('progress_status', 'Diterima')
                                    ->sum('progress_persentase');
                                $tw3 = $item->progress
                                    ->where('progress_triwulan', 'TW III')
                                    ->where('progress_status', 'Diterima')
                                    ->sum('progress_persentase');
                                $tw4 = $item->progress
                                    ->where('progress_triwulan', 'TW IV')
                                    ->where('progress_status', 'Diterima')
                                    ->sum('progress_persentase');

                                $totalProgress = $item->progress
                                    ->where('progress_status', 'Diterima')
                                    ->sum('progress_persentase');
                                $totalMenunggu = $item->progress
                                    ->where('progress_status', 'Menunggu Verifikasi')
                                    ->sum('progress_persentase');
                            @endphp

                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-4 px-3">{{ $loop->iteration }}</td>

                                <td class="py-4 px-3">
                                    {{ $item->kinerja_tahun }}
                                </td>

                                <td class="py-4 px-3">
                                    <div class="font-semibold text-slate-900">
                                        {{ $item->kinerja_kegiatan }}
                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $item->kinerja_deskripsi ?: '-' }}
                                    </div>
                                </td>

                                <td class="py-4 px-3">
                                    <div class="text-xs space-y-1">
                                        <div>TW I: {{ $tw1 !== null ? number_format($tw1, 2, ',', '.') . '%' : '-' }}</div>
                                        <div>TW II: {{ $tw2 !== null ? number_format($tw2, 2, ',', '.') . '%' : '-' }}</div>
                                        <div>TW III: {{ $tw3 !== null ? number_format($tw3, 2, ',', '.') . '%' : '-' }}
                                        </div>
                                        <div>TW IV: {{ $tw4 !== null ? number_format($tw4, 2, ',', '.') . '%' : '-' }}
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-3">
                                    @if ($item->progressTerbaru)
                                        <div class="font-semibold text-slate-800">
                                            {{ number_format($item->progressTerbaru->progress_persentase, 2, ',', '.') }}%
                                        </div>

                                        <div class="text-xs text-slate-500">
                                            {{ $item->progressTerbaru->progress_triwulan }}
                                        </div>

                                        <div class="text-xs text-slate-400 mt-1">
                                            {{ $item->progressTerbaru->progress_tanggal_mulai?->format('d/m/Y') }}
                                            -
                                            {{ $item->progressTerbaru->progress_tanggal_selesai?->format('d/m/Y') }}
                                        </div>

                                        <div class="text-xs text-slate-400 mt-1">
                                            Oleh: {{ $item->progressTerbaru->progress_user_nama ?? '-' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">
                                            Belum ada
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-3 text-right">
                                    <button type="button" onclick='openProgressModal(@json($item))'
                                        class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                                        Isi Progress
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">
                                    Belum ada data kinerja untuk bidang Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <div class="font-semibold text-amber-800 mb-1">
                    Informasi
                </div>

                <div class="text-sm text-amber-700 leading-relaxed">
                    Apabila terdapat kegiatan yang belum muncul pada daftar Progress Kinerja Bidang,
                    silakan menghubungi
                    <b>Pranata Komputer Ahli Pertama Dinas Kebudayaan Provinsi Bali</b>
                    untuk dilakukan penambahan data kinerja pada sistem SAPLARIN.
                </div>
            </div>

        </div>

    </div>

    <div id="progressModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-6">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Isi Progress Kinerja
                    </h2>

                    <p id="modal_kegiatan" class="text-sm text-slate-500"></p>
                </div>

                <button type="button" onclick="closeProgressModal()" class="text-slate-400 hover:text-slate-800 text-xl">
                    ✕
                </button>
            </div>

            <form id="progressForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Mulai Progress
                        </label>

                        <input type="date" name="progress_tanggal_mulai"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Selesai Progress
                        </label>

                        <input type="date" name="progress_tanggal_selesai"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Progress Kinerja (%)
                    </label>

                    <input type="number" name="progress_persentase" step="0.01" min="0" max="100"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: 25" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Keterangan
                    </label>

                    <textarea name="progress_keterangan" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        placeholder="Tuliskan keterangan progress bila diperlukan"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Bukti Dukung Maksimal 5
                    </label>

                    <input type="file" name="bukti_file[]" multiple
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white" required>

                    <p class="text-xs text-slate-500 mt-2">
                        Format: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX. Maksimal 5 file.
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-sm text-blue-700">
                    Capaian triwulan akan ditentukan otomatis berdasarkan
                    <b>Tanggal Selesai Progress</b>.
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeProgressModal()"
                        class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Simpan Progress
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function openProgressModal(item) {
            document.getElementById('modal_kegiatan').innerText = item.kinerja_kegiatan;

            let action = "{{ url('/user/kinerja') }}/" + item.kinerja_uid + "/progress/store";

            document.getElementById('progressForm').setAttribute('action', action);

            document.getElementById('progressModal').classList.remove('hidden');
            document.getElementById('progressModal').classList.add('flex');
        }

        function closeProgressModal() {
            document.getElementById('progressModal').classList.add('hidden');
            document.getElementById('progressModal').classList.remove('flex');
            document.getElementById('progressForm').reset();
        }
    </script>

@endsection
