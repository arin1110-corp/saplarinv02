@extends('user.layouts.app')

@section('title', 'Detail Pengajuan BBM')
@section('page_title', 'Detail Pengajuan BBM')
@section('breadcrumb', 'Pengajuan BBM / Detail')

@section('content')

    <div class="max-w-5xl space-y-6">

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

        {{-- DETAIL PENGAJUAN --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Detail Pengajuan
                    </h2>

                    <p class="text-sm text-slate-500">
                        {{ $bbm->bbm_uid }}
                    </p>
                </div>

                <div class="text-right space-y-2">
                    <div>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-blue-50 text-blue-700">
                            {{ $bbm->bbm_status_pengajuan }}
                        </span>
                    </div>

                    <div>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">
                            {{ $bbm->bbm_status_laporan }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">

                <div>
                    <p class="text-slate-500">Nama Pengaju</p>
                    <p class="font-semibold text-slate-800">
                        {{ $bbm->bbm_pengaju_nama }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">NIP</p>
                    <p class="font-semibold text-slate-800">
                        {{ $bbm->bbm_pengaju_nip }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Bidang</p>
                    <p class="font-semibold text-slate-800">
                        {{ $bbm->bbm_bidang_nama ?? (session('pegawai_bidang') ?? '-') }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">No Plat</p>
                    <p class="font-semibold text-slate-800">
                        {{ $bbm->bbm_no_plat }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Jumlah BBM</p>
                    <p class="font-semibold text-slate-800">
                        {{ $bbm->bbm_liter }} Liter
                    </p>
                </div>

                <div>
                    <p class="text-slate-500 text-sm">Surat SPT</p>

                    @if ($bbm->bbm_spt_file)
                        <a href="{{ $bbm->bbm_spt_file }}"
                            target="_blank"
                            class="inline-block mt-2 text-blue-600 font-semibold hover:underline">
                            Lihat SPT
                        </a>
                    @else
                        <p class="text-slate-400 mt-2">
                            Belum ada file SPT.
                        </p>
                    @endif
                </div>

            </div>

            <div class="mt-5">
                <p class="text-slate-500 text-sm">Uraian Kegiatan</p>

                <p class="mt-2 text-slate-800 leading-relaxed">
                    {{ $bbm->bbm_uraian_kegiatan }}
                </p>
            </div>

            {{-- INFORMASI TAMBAHAN --}}
            <div class="mt-6 border-t border-slate-200 pt-6">

                <h3 class="font-bold text-slate-900 mb-4">
                    Informasi Tambahan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- FOTO KENDARAAN --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">

                        <p class="text-slate-500 text-sm mb-3">
                            Foto Kendaraan
                        </p>

                        @if ($bbm->bbm_foto_mobil_file)
                            @php
                                $fotoMobil = $bbm->bbm_foto_mobil_file;

                                $isImage =
                                    str_contains(strtolower($fotoMobil), '.jpg') ||
                                    str_contains(strtolower($fotoMobil), '.jpeg') ||
                                    str_contains(strtolower($fotoMobil), '.png') ||
                                    str_contains(strtolower($fotoMobil), '.webp');
                            @endphp

                            @if ($isImage)
                                <a href="{{ $fotoMobil }}" target="_blank">
                                    <img src="{{ $fotoMobil }}"
                                        class="w-full max-w-sm rounded-2xl border border-slate-200 shadow-sm"
                                        alt="Foto Kendaraan">
                                </a>

                                <a href="{{ $fotoMobil }}"
                                    target="_blank"
                                    class="inline-block mt-3 text-blue-600 font-semibold hover:underline">
                                    Buka Foto
                                </a>
                            @else
                                <a href="{{ $fotoMobil }}"
                                    target="_blank"
                                    class="text-blue-600 font-semibold hover:underline">
                                    Lihat Foto Kendaraan
                                </a>
                            @endif
                        @else
                            <p class="text-slate-400">
                                Belum ada foto kendaraan.
                            </p>
                        @endif

                    </div>

                    {{-- BUKTI TAMBAHAN PENGAJUAN --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">

                        <p class="text-slate-500 text-sm mb-3">
                            Bukti Tambahan Pengajuan
                        </p>

                        @if ($bbm->bbm_bukti_tambahan_file && count($bbm->bbm_bukti_tambahan_file) > 0)
                            <div class="space-y-2">
                                @foreach ($bbm->bbm_bukti_tambahan_file as $index => $file)
                                    <a href="{{ $file['file'] ?? '#' }}"
                                        target="_blank"
                                        class="block bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-blue-700 hover:bg-blue-100">

                                        📎 {{ $file['nama'] ?? 'Bukti Tambahan ' . ($index + 1) }}

                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-400">
                                Tidak ada bukti tambahan.
                            </p>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- LAPORAN NOTA --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="mb-5">
                <h3 class="text-lg font-bold text-slate-900">
                    Laporan Nota
                </h3>

                <p class="text-sm text-slate-500">
                    Upload laporan nota hanya bisa dilakukan setelah pengajuan diterima.
                </p>
            </div>

            @if ($bbm->bbm_laporan_nota_file)
                <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-5">
                    <p class="text-green-700 font-semibold">
                        File laporan nota tersedia.
                    </p>

                    <p class="text-sm text-green-700 mt-1">
                        Tanggal Nota:
                        {{ $bbm->bbm_tanggal_nota ? $bbm->bbm_tanggal_nota->format('d/m/Y') : '-' }}
                    </p>

                    <a href="{{ $bbm->bbm_laporan_nota_file }}"
                        target="_blank"
                        class="inline-block mt-3 text-blue-600 font-semibold hover:underline">
                        Lihat Nota
                    </a>
                </div>
            @endif

            @if ($bbm->bbm_status_laporan === 'Laporan Nota Diterima')
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-slate-600">
                    Laporan nota sudah diterima. Upload data sudah dikunci.
                </div>
            @elseif ($bbm->bbm_status_pengajuan === 'Pengajuan Diterima')
                <form method="POST"
                    action="{{ route('user.bbm.laporan', $bbm->bbm_uid) }}"
                    enctype="multipart/form-data"
                    class="space-y-5">

                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Nota
                        </label>

                        <input type="date"
                            name="bbm_tanggal_nota"
                            value="{{ old('bbm_tanggal_nota', optional($bbm->bbm_tanggal_nota)->format('Y-m-d')) }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Upload Laporan Nota
                        </label>

                        <input type="file"
                            name="bbm_laporan_nota_file"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white"
                            required>

                        <p class="text-xs text-slate-500 mt-2">
                            Format: PDF, JPG, PNG, DOC, DOCX. Maksimal 5 MB.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Bukti Tambahan Nota
                        </label>

                        <input type="file"
                            name="bbm_bukti_tambahan[]"
                            multiple
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white">

                        <p class="text-xs text-slate-500 mt-2">
                            Opsional. Upload foto bukti isi bensin dan pastikan plat mobil terlihat.
                            Format PDF, JPG, PNG, WEBP, DOC, DOCX. Maksimal 5 MB per file.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            Upload Data
                        </button>
                    </div>

                </form>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 text-yellow-700">
                    Upload laporan nota belum tersedia. Pengajuan harus diterima admin terlebih dahulu.
                </div>
            @endif

        </div>

    </div>

@endsection