@extends('user.layouts.app')

@section('title', 'Laporan Aktivitas')
@section('page_title', 'Laporan Aktivitas')
@section('breadcrumb', 'Laporan Aktivitas')

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

    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-blue-100 text-sm">Bidang</p>

                <h2 class="text-2xl font-bold">
                    {{ session('pegawai_bidang') ?? '-' }}
                </h2>

                <p class="text-blue-100 text-sm mt-1">
                    Pilih Program, Kegiatan, Sub Kegiatan, lalu input aktivitas. Persentase capaian dihitung otomatis dari jumlah aktivitas aktif.
                </p>
            </div>

            <button type="button"
                onclick="openKegiatanModal()"
                class="bg-white text-blue-700 px-5 py-3 rounded-2xl font-semibold hover:bg-blue-50">
                + Tambah Kegiatan
            </button>
        </div>
    </div>

    @forelse ($kegiatans as $kegiatan)
        @php
            $aktivitasAktif = $kegiatan->aktivitas->where('aktivitas_status', 'Aktif');

            $totalAktivitas = $aktivitasAktif->count();

            $tw1Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW I')->count();
            $tw2Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW II')->count();
            $tw3Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW III')->count();
            $tw4Count = $aktivitasAktif->where('aktivitas_triwulan', 'TW IV')->count();

            $tw1Persen = $totalAktivitas > 0 ? ($tw1Count / $totalAktivitas) * 100 : 0;
            $tw2Persen = $totalAktivitas > 0 ? ($tw2Count / $totalAktivitas) * 100 : 0;
            $tw3Persen = $totalAktivitas > 0 ? ($tw3Count / $totalAktivitas) * 100 : 0;
            $tw4Persen = $totalAktivitas > 0 ? ($tw4Count / $totalAktivitas) * 100 : 0;

            $totalPersen = $totalAktivitas > 0 ? 100 : 0;
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                <div>
                    <div class="text-sm text-slate-500">
                        Tahun Anggaran {{ $kegiatan->laporan_kegiatan_tahun }}
                    </div>

                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $kegiatan->laporan_kegiatan_sub_kegiatan_nama ?? $kegiatan->laporan_kegiatan_nama }}
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $kegiatan->laporan_kegiatan_deskripsi ?: '-' }}
                    </p>
                </div>

                <button type="button"
                    onclick='openAktivitasModal(@json($kegiatan))'
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    + Tambah Aktivitas
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">

                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                    <p class="text-xs text-slate-500">Total Aktivitas</p>
                    <p class="text-2xl font-bold text-slate-900">
                        {{ $totalAktivitas }}
                    </p>
                    <p class="text-xs text-slate-400">
                        Total {{ number_format($totalPersen, 2, ',', '.') }}%
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs text-blue-600">TW I</p>
                    <p class="text-xl font-bold text-blue-800">
                        {{ number_format($tw1Persen, 2, ',', '.') }}%
                    </p>
                    <p class="text-xs text-blue-500">
                        {{ $tw1Count }} aktivitas
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs text-blue-600">TW II</p>
                    <p class="text-xl font-bold text-blue-800">
                        {{ number_format($tw2Persen, 2, ',', '.') }}%
                    </p>
                    <p class="text-xs text-blue-500">
                        {{ $tw2Count }} aktivitas
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs text-blue-600">TW III</p>
                    <p class="text-xl font-bold text-blue-800">
                        {{ number_format($tw3Persen, 2, ',', '.') }}%
                    </p>
                    <p class="text-xs text-blue-500">
                        {{ $tw3Count }} aktivitas
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs text-blue-600">TW IV</p>
                    <p class="text-xl font-bold text-blue-800">
                        {{ number_format($tw4Persen, 2, ',', '.') }}%
                    </p>
                    <p class="text-xs text-blue-500">
                        {{ $tw4Count }} aktivitas
                    </p>
                </div>

            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Aktivitas</th>
                            <th class="py-3 px-3">Rentang Waktu</th>
                            <th class="py-3 px-3">TW</th>
                            <th class="py-3 px-3">Bukti</th>
                            <th class="py-3 px-3">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($kegiatan->aktivitas->sortByDesc('aktivitas_tanggal_selesai') as $aktivitas)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-4 px-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="py-4 px-3">
                                    <div class="font-semibold text-slate-900">
                                        {{ $aktivitas->aktivitas_nama }}
                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $aktivitas->aktivitas_uraian ?: '-' }}
                                    </div>
                                </td>

                                <td class="py-4 px-3">
                                    {{ $aktivitas->aktivitas_tanggal_mulai?->format('d/m/Y') }}
                                    -
                                    {{ $aktivitas->aktivitas_tanggal_selesai?->format('d/m/Y') }}
                                </td>

                                <td class="py-4 px-3">
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs">
                                        {{ $aktivitas->aktivitas_triwulan }}
                                    </span>
                                </td>

                                <td class="py-4 px-3">
                                    <div class="flex flex-col gap-1">
                                        @foreach ($aktivitas->bukti as $bukti)
                                            <a href="{{ asset($bukti->bukti_file) }}"
                                                target="_blank"
                                                class="text-blue-600 hover:underline">
                                                Bukti
                                            </a>
                                        @endforeach

                                        @if ($aktivitas->bukti->isEmpty())
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-3">
                                    @if ($aktivitas->aktivitas_status === 'Aktif')
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
                                    Belum ada aktivitas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @empty
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            Belum ada kegiatan yang diinput.
        </div>
    @endforelse

</div>

{{-- MODAL TAMBAH KEGIATAN --}}
<div id="kegiatanModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-6 max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Tambah Kegiatan
                </h2>

                <p class="text-sm text-slate-500">
                    Pilih Program, Kegiatan, lalu Sub Kegiatan. Data yang disimpan adalah Sub Kegiatan.
                </p>
            </div>

            <button type="button"
                onclick="closeKegiatanModal()"
                class="text-slate-400 hover:text-slate-800 text-xl">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('user.laporan-aktivitas.kegiatan.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tahun Anggaran
                </label>

                <input type="number"
                    name="laporan_kegiatan_tahun"
                    value="{{ date('Y') }}"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Program
                </label>

                <select id="program_select"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    onchange="filterKegiatan()"
                    required>
                    <option value="">Pilih Program</option>

                    @foreach ($programs as $program)
                        <option value="{{ $program->program_id }}">
                            {{ $program->program_nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Kegiatan
                </label>

                <select id="kegiatan_select"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    onchange="filterSubKegiatan()"
                    required>
                    <option value="">Pilih Kegiatan</option>

                    @foreach ($masterKegiatans as $masterKegiatan)
                        <option value="{{ $masterKegiatan->kegiatan_id }}"
                            data-program="{{ $masterKegiatan->kegiatan_program }}">
                            {{ $masterKegiatan->kegiatan_nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Sub Kegiatan
                </label>

                <select id="sub_kegiatan_select"
                    name="laporan_kegiatan_sub_kegiatan_id"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    required>
                    <option value="">Pilih Sub Kegiatan</option>

                    @foreach ($subKegiatans as $sub)
                        <option value="{{ $sub->sub_kegiatan_id }}"
                            data-kegiatan="{{ $sub->sub_kegiatan_kegiatan }}">
                            {{ $sub->sub_kegiatan_nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Aktivitas Kegiatan Pendukung Sub Kegiatan
                </label>

                <textarea name="laporan_kegiatan_deskripsi"
                    rows="3"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="Cth : Pesta Kesenian Bali XLVII, Festival Seni Bali Jani X, Bulan Bahasa X, Jelajah Cagar Budaya"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                    onclick="closeKegiatanModal()"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Simpan Kegiatan
                </button>
            </div>

        </form>

    </div>
</div>

{{-- MODAL TAMBAH AKTIVITAS --}}
<div id="aktivitasModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Tambah Aktivitas
                </h2>

                <p id="modal_kegiatan_nama" class="text-sm text-slate-500"></p>
            </div>

            <button type="button"
                onclick="closeAktivitasModal()"
                class="text-slate-400 hover:text-slate-800 text-xl">
                ✕
            </button>
        </div>

        <form id="aktivitasForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama Aktivitas
                </label>

                <input type="text"
                    name="aktivitas_nama"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="Contoh: Rapat membahas persiapan kegiatan"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Uraian Aktivitas
                </label>

                <textarea name="aktivitas_uraian"
                    rows="3"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="Opsional"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tanggal Mulai
                    </label>

                    <input type="date"
                        name="aktivitas_tanggal_mulai"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tanggal Selesai
                    </label>

                    <input type="date"
                        name="aktivitas_tanggal_selesai"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Bukti Dukung Maksimal 5
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

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-sm text-blue-700">
                Triwulan akan ditentukan otomatis berdasarkan tanggal selesai aktivitas.
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                    onclick="closeAktivitasModal()"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Simpan Aktivitas
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    function filterKegiatan() {
        const programId = document.getElementById('program_select').value;
        const kegiatanSelect = document.getElementById('kegiatan_select');
        const subSelect = document.getElementById('sub_kegiatan_select');

        kegiatanSelect.value = '';
        subSelect.value = '';

        Array.from(kegiatanSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = option.getAttribute('data-program') !== programId;
        });

        Array.from(subSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = true;
        });
    }

    function filterSubKegiatan() {
        const kegiatanId = document.getElementById('kegiatan_select').value;
        const subSelect = document.getElementById('sub_kegiatan_select');

        subSelect.value = '';

        Array.from(subSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = option.getAttribute('data-kegiatan') !== kegiatanId;
        });
    }

    function openKegiatanModal() {
        document.getElementById('kegiatanModal').classList.remove('hidden');
        document.getElementById('kegiatanModal').classList.add('flex');

        filterKegiatan();
    }

    function closeKegiatanModal() {
        document.getElementById('kegiatanModal').classList.add('hidden');
        document.getElementById('kegiatanModal').classList.remove('flex');

        document.getElementById('program_select').value = '';
        document.getElementById('kegiatan_select').value = '';
        document.getElementById('sub_kegiatan_select').value = '';
    }

    function openAktivitasModal(kegiatan) {
        const label = kegiatan.laporan_kegiatan_sub_kegiatan_nama ?? kegiatan.laporan_kegiatan_nama;

        document.getElementById('modal_kegiatan_nama').innerText = label;

        let action = "{{ url('/user/laporan-aktivitas') }}/" + kegiatan.laporan_kegiatan_uid + "/aktivitas/store";

        document.getElementById('aktivitasForm').setAttribute('action', action);

        document.getElementById('aktivitasModal').classList.remove('hidden');
        document.getElementById('aktivitasModal').classList.add('flex');
    }

    function closeAktivitasModal() {
        document.getElementById('aktivitasModal').classList.add('hidden');
        document.getElementById('aktivitasModal').classList.remove('flex');

        document.getElementById('aktivitasForm').reset();
    }
</script>

@endsection