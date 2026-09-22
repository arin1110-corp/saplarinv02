@extends('user.layouts.app')

@section('title', 'Permintaan KAK')
@section('page_title', 'Permintaan KAK')
@section('breadcrumb', 'Permintaan KAK')

@section('content')

    @php
        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */
        $tahunList = range(2020, date('Y') + 5);
        $tahunList = array_reverse($tahunList);

        /*
        |--------------------------------------------------------------------------
        | DATA PROGRAM
        |--------------------------------------------------------------------------
        */
        $programData = $programs
            ->map(function ($program) {
                return [
                    'program_id' => $program->program_id,
                    'program_kode' => $program->program_kode,
                    'program_nama' => $program->program_nama,

                    'kegiatan' => $program->kegiatan
                        ->map(function ($kegiatan) {
                            return [
                                'kegiatan_id' => $kegiatan->kegiatan_id,
                                'kegiatan_kode' => $kegiatan->kegiatan_kode,
                                'kegiatan_nama' => $kegiatan->kegiatan_nama,

                                'sub_kegiatan' => $kegiatan->subKegiatan
                                    ->map(function ($subKegiatan) {
                                        return [
                                            'sub_kegiatan_id' => $subKegiatan->sub_kegiatan_id,
                                            'sub_kegiatan_kode' => $subKegiatan->sub_kegiatan_kode,
                                            'sub_kegiatan_nama' => $subKegiatan->sub_kegiatan_nama,
                                        ];
                                    })
                                    ->values()
                                    ->toArray(),
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | UNIT
        |--------------------------------------------------------------------------
        */
        $unitList = $unitList ?? [
            'Sekretariat',
            'Bidang Kesenian',
            'Bidang Tradisi dan Warisan Budaya',
            'Bidang Sejarah dan Dokumentasi Kebudayaan',
            'Bidang Cagar Budaya dan Permuseuman',
            'UPTD Taman Budaya',
            'UPTD Monumen Perjuangan Rakyat Bali',
            'UPTD Museum Bali',
        ];
    @endphp


    <div class="space-y-6">

        {{-- ============================================================
            SUCCESS
        ============================================================= --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif


        {{-- ============================================================
            ERROR
        ============================================================= --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif


        {{-- ============================================================
            VALIDATION ERROR
        ============================================================= --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">

                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        {{-- ============================================================
            HEADER
        ============================================================= --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div>
                    <h2 class="text-2xl font-bold text-slate-800">
                        Permintaan KAK
                    </h2>

                    <p class="text-slate-500 mt-1">
                        Pengajuan dan daftar permintaan Kerangka Acuan Kerja (KAK).
                    </p>
                </div>


                <button type="button" onclick="openKakModal()"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition">

                    <span class="text-lg leading-none">
                        +
                    </span>

                    Upload KAK

                </button>

            </div>

        </div>


        {{-- ============================================================
            INFO
        ============================================================= --}}
        <div class="bg-blue-50 border border-blue-100 rounded-3xl p-5">

            <div class="flex items-start gap-3">

                <div class="flex-shrink-0">

                    <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />

                        </svg>

                    </div>

                </div>


                <div>

                    <h3 class="font-semibold text-blue-800">
                        Informasi Permintaan KAK
                    </h3>

                    <p class="text-sm text-blue-700 mt-1 leading-relaxed">
                        Silakan upload dokumen Kerangka Acuan Kerja (KAK)
                        sesuai sub kegiatan, unit dan tahapan yang dipilih.
                        File harus dalam format PDF dengan ukuran maksimal 200 MB.
                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
            FILTER
        ============================================================= --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <div class="lg:col-span-2">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Pencarian
                    </label>

                    <div class="relative">

                        <input type="text" id="kakSearch" placeholder="Cari sub kegiatan, tahapan atau unit..."
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-10 focus:border-blue-500 focus:ring-blue-500">

                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />

                            </svg>

                        </div>

                    </div>

                </div>


                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tahun
                    </label>

                    <select id="kakYearFilter"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}">
                                {{ $tahun }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>

        </div>


        {{-- ============================================================
            DESKTOP TABLE
        ============================================================= --}}
        <div class="hidden md:block bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600 w-16">
                                No
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                Sub Kegiatan
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                Unit
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600 w-24">
                                Tahun
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                Tahapan
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                Pengusul
                            </th>

                            <th class="px-5 py-4 text-left font-semibold text-slate-600">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-center font-semibold text-slate-600">
                                Berkas
                            </th>

                            <th class="px-5 py-4 text-center font-semibold text-slate-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($kaks as $index => $kak)
                            <tr class="kak-row border-b border-slate-100 hover:bg-slate-50 transition"
                                data-sub-kegiatan="{{ strtolower($kak->subKegiatan->sub_kegiatan_nama ?? '') }}"
                                data-tahapan="{{ strtolower($kak->kak_tahapan ?? '') }}"
                                data-unit="{{ strtolower($kak->kak_unit ?? '') }}" data-tahun="{{ $kak->kak_tahun }}">

                                {{-- NO --}}
                                <td class="px-5 py-4 text-slate-500">
                                    {{ $index + 1 }}
                                </td>


                                {{-- SUB KEGIATAN --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-slate-800">
                                        {{ $kak->subKegiatan->sub_kegiatan_nama ?? '-' }}
                                    </div>

                                    @if (!empty($kak->subKegiatan->sub_kegiatan_kode))
                                        <div class="text-xs text-slate-400 mt-1">
                                            {{ $kak->subKegiatan->sub_kegiatan_kode }}
                                        </div>
                                    @endif

                                </td>


                                {{-- UNIT --}}
                                <td class="px-5 py-4">

                                    <span class="text-sm text-slate-700">
                                        {{ $kak->kak_unit ?? '-' }}
                                    </span>

                                </td>


                                {{-- TAHUN --}}
                                <td class="px-5 py-4 text-slate-700">
                                    {{ $kak->kak_tahun ?? '-' }}
                                </td>


                                {{-- TAHAPAN --}}
                                <td class="px-5 py-4">

                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold">
                                        {{ $kak->kak_tahapan ?? '-' }}
                                    </span>

                                </td>


                                {{-- PENGUSUL --}}
                                <td class="px-5 py-4 text-slate-700">
                                    {{ $kak->kak_created_by_nama ?? '-' }}
                                </td>


                                {{-- TANGGAL --}}
                                <td class="px-5 py-4 text-slate-500 whitespace-nowrap">

                                    @if (!empty($kak->created_at))
                                        {{ \Carbon\Carbon::parse($kak->created_at)->translatedFormat('d F Y') }}
                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- BERKAS --}}
                                <td class="px-5 py-4 text-center">

                                    @if (!empty($kak->kak_file))
                                        <a href="{{ $kak->kak_file }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition font-semibold text-xs">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V7.5L14.5 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 3v5h5" />

                                            </svg>

                                            PDF

                                        </a>
                                    @else
                                        <span class="text-slate-400 text-xs">
                                            Tidak ada
                                        </span>
                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- CATATAN --}}
                                        @if (!empty($kak->kak_catatan_admin))
                                            <button type="button" onclick="openCatatanModal(@js($kak->kak_catatan_admin))"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 text-xs font-semibold">

                                                Catatan

                                            </button>
                                        @endif


                                        {{-- EDIT --}}
                                        <button type="button" onclick="openEditKakModal('{{ $kak->kak_uid }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-semibold">

                                            Edit

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="px-5 py-12 text-center">

                                    <div class="text-slate-400">

                                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v12a2 2 0 01-2 2z" />

                                        </svg>

                                        <p class="font-semibold text-slate-500">
                                            Belum ada permintaan KAK
                                        </p>

                                        <p class="text-sm mt-1">
                                            Silakan upload KAK melalui tombol di atas.
                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse


                        <tr id="desktopFilterEmpty" class="hidden">

                            <td colspan="9" class="px-5 py-12 text-center">

                                <p class="font-semibold text-slate-500">
                                    Data tidak ditemukan
                                </p>

                                <p class="text-sm mt-1 text-slate-400">
                                    Coba ubah kata pencarian atau tahun.
                                </p>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ============================================================
            MOBILE
        ============================================================= --}}
        <div class="md:hidden space-y-4">

            @forelse ($kaks as $index => $kak)
                <div class="kak-mobile-card bg-white rounded-3xl border border-slate-200 shadow-sm p-5"
                    data-sub-kegiatan="{{ strtolower($kak->subKegiatan->sub_kegiatan_nama ?? '') }}"
                    data-tahapan="{{ strtolower($kak->kak_tahapan ?? '') }}"
                    data-unit="{{ strtolower($kak->kak_unit ?? '') }}" data-tahun="{{ $kak->kak_tahun }}">

                    <div class="flex items-start justify-between gap-3">

                        <div class="flex-1 min-w-0">

                            <div class="text-xs text-slate-400 mb-1">
                                #{{ $index + 1 }}
                            </div>

                            <h3 class="font-bold text-slate-800 leading-snug">
                                {{ $kak->subKegiatan->sub_kegiatan_nama ?? '-' }}
                            </h3>

                            @if (!empty($kak->subKegiatan->sub_kegiatan_kode))
                                <div class="text-xs text-slate-400 mt-1">
                                    {{ $kak->subKegiatan->sub_kegiatan_kode }}
                                </div>
                            @endif

                        </div>


                        <span
                            class="flex-shrink-0 inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold">

                            {{ $kak->kak_tahun ?? '-' }}

                        </span>

                    </div>


                    <div class="mt-4 space-y-3">

                        <div>

                            <div class="text-xs text-slate-400">
                                Unit
                            </div>

                            <div class="mt-1 text-sm font-semibold text-slate-700">
                                {{ $kak->kak_unit ?? '-' }}
                            </div>

                        </div>


                        <div>

                            <div class="text-xs text-slate-400">
                                Tahapan
                            </div>

                            <div class="mt-1 text-sm font-semibold text-slate-700">
                                {{ $kak->kak_tahapan ?? '-' }}
                            </div>

                        </div>


                        <div>

                            <div class="text-xs text-slate-400">
                                Pengusul
                            </div>

                            <div class="mt-1 text-sm text-slate-700">
                                {{ $kak->kak_created_by_nama ?? '-' }}
                            </div>

                        </div>


                        <div>

                            <div class="text-xs text-slate-400">
                                Tanggal
                            </div>

                            <div class="mt-1 text-sm text-slate-700">

                                @if (!empty($kak->created_at))
                                    {{ \Carbon\Carbon::parse($kak->created_at)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- CATATAN --}}
                    @if (!empty($kak->kak_catatan_admin))
                        <div class="mt-4 rounded-2xl bg-red-50 border border-red-100 p-4">

                            <div class="text-xs font-semibold text-red-600 mb-1">
                                Catatan Admin
                            </div>

                            <div class="text-sm text-red-700 line-clamp-3">
                                {{ $kak->kak_catatan_admin }}
                            </div>

                            <button type="button" onclick="openCatatanModal(@js($kak->kak_catatan_admin))"
                                class="mt-2 text-xs font-semibold text-red-700 hover:underline">

                                Lihat catatan lengkap

                            </button>

                        </div>
                    @endif


                    <div class="mt-5 pt-4 border-t border-slate-100">

                        <div class="grid grid-cols-2 gap-2">

                            @if (!empty($kak->kak_file))
                                <a href="{{ $kak->kak_file }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center gap-2 px-3 py-3 rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 transition font-semibold text-sm">

                                    PDF

                                </a>
                            @endif


                            <button type="button" onclick="openEditKakModal('{{ $kak->kak_uid }}')"
                                class="inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-amber-50 text-amber-700 hover:bg-amber-100 text-sm font-semibold">

                                Edit

                            </button>

                        </div>

                    </div>

                </div>

            @empty

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center">

                    <p class="font-semibold text-slate-500">
                        Belum ada permintaan KAK
                    </p>

                    <p class="text-sm text-slate-400 mt-1">
                        Silakan upload KAK melalui tombol di atas.
                    </p>

                </div>
            @endforelse


            <div id="mobileFilterEmpty"
                class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center">

                <p class="font-semibold text-slate-500">
                    Data tidak ditemukan
                </p>

                <p class="text-sm text-slate-400 mt-1">
                    Coba ubah kata pencarian atau tahun.
                </p>

            </div>

        </div>

    </div>


    {{-- ================================================================
        MODAL UPLOAD
    ================================================================= --}}
    <div id="kakModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-slate-900">
                            Upload KAK
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Upload dokumen Kerangka Acuan Kerja
                        </p>

                    </div>


                    <button type="button" onclick="closeKakModal()"
                        class="w-10 h-10 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>

            </div>


            <form id="kakForm" method="POST" action="{{ route('user.permintaan-kak.store') }}"
                enctype="multipart/form-data">

                @csrf

                <div class="p-6 space-y-5">

                    {{-- PROGRAM --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Program
                        </label>

                        <select id="kakProgram" class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Program --
                            </option>

                            @foreach ($programData as $program)
                                <option value="{{ $program['program_id'] }}">

                                    {{ $program['program_kode'] ? $program['program_kode'] . ' - ' : '' }}
                                    {{ $program['program_nama'] }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- KEGIATAN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kegiatan
                        </label>

                        <select id="kakKegiatan" disabled
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white disabled:bg-slate-100 disabled:text-slate-400">

                            <option value="">
                                -- Pilih Kegiatan --
                            </option>

                        </select>

                    </div>


                    {{-- SUB KEGIATAN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Sub Kegiatan
                        </label>

                        <select id="kakSubKegiatan" name="kak_sub_kegiatan_id" disabled required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white disabled:bg-slate-100 disabled:text-slate-400">

                            <option value="">
                                -- Pilih Sub Kegiatan --
                            </option>

                        </select>

                    </div>


                    {{-- UNIT --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Unit
                        </label>

                        <select name="kak_unit" required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Unit --
                            </option>

                            @foreach ($unitList as $unit)
                                <option value="{{ $unit }}" {{ old('kak_unit') == $unit ? 'selected' : '' }}>

                                    {{ $unit }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahun
                        </label>

                        <select name="kak_tahun" required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Tahun --
                            </option>

                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ old('kak_tahun') == $tahun ? 'selected' : '' }}>

                                    {{ $tahun }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- TAHAPAN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahapan
                        </label>

                        <select name="kak_tahapan" required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Tahapan --
                            </option>

                            <option value="Induk" {{ old('kak_tahapan') == 'Induk' ? 'selected' : '' }}>
                                Induk
                            </option>

                            <option value="Perubahan" {{ old('kak_tahapan') == 'Perubahan' ? 'selected' : '' }}>
                                Perubahan
                            </option>

                        </select>

                    </div>


                    {{-- FILE --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            File KAK
                        </label>

                        <div class="rounded-2xl border-2 border-dashed border-slate-300 p-5">

                            <input type="file" name="kak_file" accept="application/pdf,.pdf" required
                                class="w-full text-sm text-slate-600">

                            <p class="text-xs text-slate-500 mt-2">
                                Format PDF. Maksimal 200 MB.
                            </p>

                        </div>

                    </div>

                </div>


                <div
                    class="px-6 py-5 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                    <button type="button" onclick="closeKakModal()"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold">

                        Batal

                    </button>


                    <button type="submit"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">

                        Upload KAK

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
        MODAL EDIT
    ================================================================= --}}
    <div id="editKakModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-slate-900">
                            Edit KAK
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Perbarui data dan dokumen KAK
                        </p>

                    </div>


                    <button type="button" onclick="closeEditKakModal()"
                        class="w-10 h-10 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>

            </div>


            <form id="editKakForm" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="p-6 space-y-5">

                    {{-- PROGRAM --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Program
                        </label>

                        <select id="editKakProgram" class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Program --
                            </option>

                            @foreach ($programData as $program)
                                <option value="{{ $program['program_id'] }}">

                                    {{ $program['program_kode'] ? $program['program_kode'] . ' - ' : '' }}
                                    {{ $program['program_nama'] }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- KEGIATAN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kegiatan
                        </label>

                        <select id="editKakKegiatan" disabled
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white disabled:bg-slate-100 disabled:text-slate-400">

                            <option value="">
                                -- Pilih Kegiatan --
                            </option>

                        </select>

                    </div>


                    {{-- SUB KEGIATAN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Sub Kegiatan
                        </label>

                        <select id="editKakSubKegiatan" name="kak_sub_kegiatan_id" required disabled
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white disabled:bg-slate-100 disabled:text-slate-400">

                            <option value="">
                                -- Pilih Sub Kegiatan --
                            </option>

                        </select>

                    </div>


                    {{-- UNIT --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Unit
                        </label>

                        <select id="editKakUnit" name="kak_unit" required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Unit --
                            </option>

                            @foreach ($unitList as $unit)
                                <option value="{{ $unit }}">
                                    {{ $unit }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahun
                        </label>

                        <select id="editKakTahun" name="kak_tahun" required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Tahun --
                            </option>

                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}">
                                    {{ $tahun }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- TAHAPAN --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahapan
                        </label>

                        <select id="editKakTahapan" name="kak_tahapan" required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">

                            <option value="">
                                -- Pilih Tahapan --
                            </option>

                            <option value="Induk">
                                Induk
                            </option>

                            <option value="Perubahan">
                                Perubahan
                            </option>

                        </select>

                    </div>


                    {{-- FILE --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Ganti File KAK
                        </label>

                        <div class="rounded-2xl border-2 border-dashed border-slate-300 p-5">

                            <input type="file" name="kak_file" accept="application/pdf,.pdf"
                                class="w-full text-sm text-slate-600">

                            <p class="text-xs text-slate-500 mt-2">
                                Kosongkan jika tidak ingin mengganti file.
                                Format PDF, maksimal 200 MB.
                            </p>

                        </div>

                    </div>


                    {{-- LOADING --}}
                    <div id="editKakLoading" class="hidden rounded-2xl bg-blue-50 border border-blue-100 p-4">

                        <div class="flex items-center gap-3">

                            <div class="w-5 h-5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin">
                            </div>

                            <span class="text-sm text-blue-700">
                                Mengambil data KAK...
                            </span>

                        </div>

                    </div>

                </div>


                <div
                    class="px-6 py-5 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                    <button type="button" onclick="closeEditKakModal()"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold">

                        Batal

                    </button>


                    <button type="submit"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-amber-500 text-white font-semibold hover:bg-amber-600">

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
        MODAL CATATAN ADMIN
    ================================================================= --}}
    <div id="catatanModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-slate-900">
                            Catatan Admin
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Catatan terkait dokumen KAK
                        </p>

                    </div>


                    <button type="button" onclick="closeCatatanModal()"
                        class="w-10 h-10 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>

            </div>


            <div class="p-6">

                <div class="rounded-2xl bg-red-50 border border-red-100 p-5">

                    <div id="catatanModalContent" class="text-sm text-red-700 whitespace-pre-line leading-relaxed">
                    </div>

                </div>

            </div>


            <div class="px-6 py-5 bg-slate-50 border-t border-slate-200 flex justify-end">

                <button type="button" onclick="closeCatatanModal()"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">

                    Tutup

                </button>

            </div>

        </div>

    </div>


    {{-- ================================================================
        JAVASCRIPT
    ================================================================= --}}
    <script>
        /*
            |--------------------------------------------------------------------------
            | DATA PROGRAM
            |--------------------------------------------------------------------------
            */
        const kakPrograms = @json($programData);


        /*
        |--------------------------------------------------------------------------
        | OPEN UPLOAD MODAL
        |--------------------------------------------------------------------------
        */
        function openKakModal() {

            const modal = document.getElementById('kakModal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE UPLOAD MODAL
        |--------------------------------------------------------------------------
        */
        function closeKakModal() {

            const modal = document.getElementById('kakModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }


        /*
        |--------------------------------------------------------------------------
        | ISI KEGIATAN
        |--------------------------------------------------------------------------
        */
        function fillKegiatan(selectElement, programId, selectedId = '') {

            selectElement.innerHTML =
                '<option value="">-- Pilih Kegiatan --</option>';

            selectElement.disabled = true;

            if (!programId) {
                return;
            }

            const program = kakPrograms.find(function(item) {

                return String(item.program_id) === String(programId);

            });

            if (!program) {
                return;
            }

            const kegiatanList = program.kegiatan || [];

            kegiatanList.forEach(function(kegiatan) {

                const option = document.createElement('option');

                option.value = kegiatan.kegiatan_id;

                option.textContent =
                    (
                        kegiatan.kegiatan_kode ?
                        kegiatan.kegiatan_kode + ' - ' :
                        ''
                    ) +
                    kegiatan.kegiatan_nama;

                if (
                    selectedId &&
                    String(kegiatan.kegiatan_id) === String(selectedId)
                ) {
                    option.selected = true;
                }

                selectElement.appendChild(option);

            });

            selectElement.disabled = kegiatanList.length === 0;
        }


        /*
        |--------------------------------------------------------------------------
        | ISI SUB KEGIATAN
        |--------------------------------------------------------------------------
        */
        function fillSubKegiatan(
            selectElement,
            programId,
            kegiatanId,
            selectedId = ''
        ) {

            selectElement.innerHTML =
                '<option value="">-- Pilih Sub Kegiatan --</option>';

            selectElement.disabled = true;

            if (!programId || !kegiatanId) {
                return;
            }

            const program = kakPrograms.find(function(item) {

                return String(item.program_id) === String(programId);

            });

            if (!program) {
                return;
            }

            const kegiatan = (program.kegiatan || []).find(function(item) {

                return String(item.kegiatan_id) === String(kegiatanId);

            });

            if (!kegiatan) {
                return;
            }

            const subKegiatanList = kegiatan.sub_kegiatan || [];

            subKegiatanList.forEach(function(subKegiatan) {

                const option = document.createElement('option');

                option.value = subKegiatan.sub_kegiatan_id;

                option.textContent =
                    (
                        subKegiatan.sub_kegiatan_kode ?
                        subKegiatan.sub_kegiatan_kode + ' - ' :
                        ''
                    ) +
                    subKegiatan.sub_kegiatan_nama;

                if (
                    selectedId &&
                    String(subKegiatan.sub_kegiatan_id) ===
                    String(selectedId)
                ) {
                    option.selected = true;
                }

                selectElement.appendChild(option);

            });

            selectElement.disabled = subKegiatanList.length === 0;
        }


        /*
        |--------------------------------------------------------------------------
        | PROGRAM -> KEGIATAN UPLOAD
        |--------------------------------------------------------------------------
        */
        document.addEventListener('DOMContentLoaded', function() {

            const programSelect =
                document.getElementById('kakProgram');

            const kegiatanSelect =
                document.getElementById('kakKegiatan');

            const subKegiatanSelect =
                document.getElementById('kakSubKegiatan');


            if (
                programSelect &&
                kegiatanSelect &&
                subKegiatanSelect
            ) {

                programSelect.addEventListener('change', function() {

                    fillKegiatan(
                        kegiatanSelect,
                        this.value
                    );

                    subKegiatanSelect.innerHTML =
                        '<option value="">-- Pilih Sub Kegiatan --</option>';

                    subKegiatanSelect.disabled = true;

                });


                kegiatanSelect.addEventListener('change', function() {

                    fillSubKegiatan(
                        subKegiatanSelect,
                        programSelect.value,
                        this.value
                    );

                });

            }


            /*
            |--------------------------------------------------------------------------
            | PROGRAM -> KEGIATAN EDIT
            |--------------------------------------------------------------------------
            */
            const editProgram =
                document.getElementById('editKakProgram');

            const editKegiatan =
                document.getElementById('editKakKegiatan');

            const editSubKegiatan =
                document.getElementById('editKakSubKegiatan');


            if (
                editProgram &&
                editKegiatan &&
                editSubKegiatan
            ) {

                editProgram.addEventListener('change', function() {

                    fillKegiatan(
                        editKegiatan,
                        this.value
                    );

                    editSubKegiatan.innerHTML =
                        '<option value="">-- Pilih Sub Kegiatan --</option>';

                    editSubKegiatan.disabled = true;

                });


                editKegiatan.addEventListener('change', function() {

                    fillSubKegiatan(
                        editSubKegiatan,
                        editProgram.value,
                        this.value
                    );

                });

            }


            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */
            const searchInput =
                document.getElementById('kakSearch');

            const yearFilter =
                document.getElementById('kakYearFilter');


            function filterKak() {

                const keyword =
                    (searchInput.value || '')
                    .toLowerCase()
                    .trim();

                const selectedYear =
                    yearFilter.value;


                /*
                |--------------------------------------------------------------------------
                | DESKTOP
                |--------------------------------------------------------------------------
                */
                const rows =
                    document.querySelectorAll('.kak-row');

                let desktopVisible = 0;


                rows.forEach(function(row) {

                    const subKegiatan =
                        row.dataset.subKegiatan || '';

                    const tahapan =
                        row.dataset.tahapan || '';

                    const unit =
                        row.dataset.unit || '';

                    const tahun =
                        row.dataset.tahun || '';


                    const searchMatch = !keyword ||
                        subKegiatan.includes(keyword) ||
                        tahapan.includes(keyword) ||
                        unit.includes(keyword);


                    const yearMatch = !selectedYear ||
                        String(tahun) === String(selectedYear);


                    const visible =
                        searchMatch && yearMatch;


                    if (visible) {

                        row.classList.remove('hidden');

                        desktopVisible++;

                    } else {

                        row.classList.add('hidden');

                    }

                });


                const desktopEmpty =
                    document.getElementById('desktopFilterEmpty');


                if (desktopEmpty) {

                    if (
                        rows.length > 0 &&
                        desktopVisible === 0
                    ) {

                        desktopEmpty.classList.remove('hidden');

                    } else {

                        desktopEmpty.classList.add('hidden');

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | MOBILE
                |--------------------------------------------------------------------------
                */
                const cards =
                    document.querySelectorAll('.kak-mobile-card');

                let mobileVisible = 0;


                cards.forEach(function(card) {

                    const subKegiatan =
                        card.dataset.subKegiatan || '';

                    const tahapan =
                        card.dataset.tahapan || '';

                    const unit =
                        card.dataset.unit || '';

                    const tahun =
                        card.dataset.tahun || '';


                    const searchMatch = !keyword ||
                        subKegiatan.includes(keyword) ||
                        tahapan.includes(keyword) ||
                        unit.includes(keyword);


                    const yearMatch = !selectedYear ||
                        String(tahun) === String(selectedYear);


                    const visible =
                        searchMatch && yearMatch;


                    if (visible) {

                        card.classList.remove('hidden');

                        mobileVisible++;

                    } else {

                        card.classList.add('hidden');

                    }

                });


                const mobileEmpty =
                    document.getElementById('mobileFilterEmpty');


                if (mobileEmpty) {

                    if (
                        cards.length > 0 &&
                        mobileVisible === 0
                    ) {

                        mobileEmpty.classList.remove('hidden');

                    } else {

                        mobileEmpty.classList.add('hidden');

                    }

                }

            }


            if (searchInput) {

                searchInput.addEventListener(
                    'input',
                    filterKak
                );

            }


            if (yearFilter) {

                yearFilter.addEventListener(
                    'change',
                    filterKak
                );

            }

        });


        /*
        |--------------------------------------------------------------------------
        | EDIT KAK
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | Yang dikirim adalah kak_uid.
        |
        */
        function openEditKakModal(uid) {

            const modal =
                document.getElementById('editKakModal');

            const form =
                document.getElementById('editKakForm');

            const loading =
                document.getElementById('editKakLoading');


            if (!modal || !form || !uid) {

                alert('Data KAK tidak valid.');

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | RESET FORM
            |--------------------------------------------------------------------------
            */
            form.reset();


            /*
            |--------------------------------------------------------------------------
            | RESET DROPDOWN
            |--------------------------------------------------------------------------
            */
            document.getElementById('editKakProgram').value = '';

            document.getElementById('editKakKegiatan').innerHTML =
                '<option value="">-- Pilih Kegiatan --</option>';

            document.getElementById('editKakKegiatan').disabled = true;

            document.getElementById('editKakSubKegiatan').innerHTML =
                '<option value="">-- Pilih Sub Kegiatan --</option>';

            document.getElementById('editKakSubKegiatan').disabled = true;


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN LOADING
            |--------------------------------------------------------------------------
            */
            if (loading) {
                loading.classList.remove('hidden');
            }


            /*
            |--------------------------------------------------------------------------
            | BUKA MODAL
            |--------------------------------------------------------------------------
            */
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');


            /*
            |--------------------------------------------------------------------------
            | URL BERDASARKAN KAK UID
            |--------------------------------------------------------------------------
            */
            const baseUrl =
                "{{ url('/user/permintaan-kak') }}/" +
                encodeURIComponent(uid);


            /*
            |--------------------------------------------------------------------------
            | FORM ACTION
            |--------------------------------------------------------------------------
            */
            form.action = baseUrl;


            /*
            |--------------------------------------------------------------------------
            | REQUEST DATA EDIT
            |--------------------------------------------------------------------------
            */
            fetch(
                    baseUrl + "/edit", {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                )
                .then(function(response) {

                    if (!response.ok) {

                        return response.json()
                            .catch(function() {

                                throw new Error(
                                    'Gagal mengambil data KAK.'
                                );

                            })
                            .then(function(result) {

                                throw new Error(
                                    result.message ||
                                    'Gagal mengambil data KAK.'
                                );

                            });

                    }

                    return response.json();

                })
                .then(function(result) {

                    if (!result.success) {

                        throw new Error(
                            result.message ||
                            'Gagal mengambil data KAK.'
                        );

                    }


                    const data = result.data;


                    /*
                    |--------------------------------------------------------------------------
                    | DATA DASAR
                    |--------------------------------------------------------------------------
                    */
                    const subKegiatanId =
                        data.kak_sub_kegiatan_id;


                    /*
                    |--------------------------------------------------------------------------
                    | CARI PROGRAM DAN KEGIATAN BERDASARKAN SUB KEGIATAN
                    |--------------------------------------------------------------------------
                    */
                    let selectedProgram = null;

                    let selectedKegiatan = null;


                    kakPrograms.forEach(function(program) {

                        (program.kegiatan || []).forEach(function(kegiatan) {

                            const found =
                                (kegiatan.sub_kegiatan || [])
                                .some(function(sub) {

                                    return String(
                                        sub.sub_kegiatan_id
                                    ) === String(
                                        subKegiatanId
                                    );

                                });


                            if (found) {

                                selectedProgram = program;

                                selectedKegiatan = kegiatan;

                            }

                        });

                    });


                    /*
                    |--------------------------------------------------------------------------
                    | PROGRAM
                    |--------------------------------------------------------------------------
                    */
                    if (selectedProgram) {

                        const programSelect =
                            document.getElementById(
                                'editKakProgram'
                            );

                        programSelect.value =
                            selectedProgram.program_id;


                        /*
                        |--------------------------------------------------------------------------
                        | KEGIATAN
                        |--------------------------------------------------------------------------
                        */
                        fillKegiatan(
                            document.getElementById(
                                'editKakKegiatan'
                            ),
                            selectedProgram.program_id,
                            selectedKegiatan ?
                            selectedKegiatan.kegiatan_id :
                            ''
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | SUB KEGIATAN
                        |--------------------------------------------------------------------------
                        */
                        if (selectedKegiatan) {

                            fillSubKegiatan(
                                document.getElementById(
                                    'editKakSubKegiatan'
                                ),
                                selectedProgram.program_id,
                                selectedKegiatan.kegiatan_id,
                                subKegiatanId
                            );

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | UNIT
                    |--------------------------------------------------------------------------
                    */
                    document.getElementById(
                            'editKakUnit'
                        ).value =
                        data.kak_unit || '';


                    /*
                    |--------------------------------------------------------------------------
                    | TAHUN
                    |--------------------------------------------------------------------------
                    */
                    document.getElementById(
                            'editKakTahun'
                        ).value =
                        data.kak_tahun || '';


                    /*
                    |--------------------------------------------------------------------------
                    | TAHAPAN
                    |--------------------------------------------------------------------------
                    */
                    document.getElementById(
                            'editKakTahapan'
                        ).value =
                        data.kak_tahapan || '';


                })
                .catch(function(error) {

                    console.error(
                        'Edit KAK Error:',
                        error
                    );

                    alert(
                        error.message ||
                        'Gagal mengambil data KAK.'
                    );

                    closeEditKakModal();

                })
                .finally(function() {

                    if (loading) {
                        loading.classList.add('hidden');
                    }

                });

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE EDIT
        |--------------------------------------------------------------------------
        */
        function closeEditKakModal() {

            const modal =
                document.getElementById('editKakModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | CATATAN ADMIN
        |--------------------------------------------------------------------------
        */
        function openCatatanModal(catatan) {

            const modal =
                document.getElementById('catatanModal');

            const content =
                document.getElementById('catatanModalContent');


            if (!modal || !content) {
                return;
            }


            content.textContent =
                catatan || '-';


            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE CATATAN
        |--------------------------------------------------------------------------
        */
        function closeCatatanModal() {

            const modal =
                document.getElementById('catatanModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | BACKDROP
        |--------------------------------------------------------------------------
        */
        document.addEventListener('click', function(event) {

            const uploadModal =
                document.getElementById('kakModal');

            const editModal =
                document.getElementById('editKakModal');

            const catatanModal =
                document.getElementById('catatanModal');


            if (
                uploadModal &&
                event.target === uploadModal
            ) {

                closeKakModal();

            }


            if (
                editModal &&
                event.target === editModal
            ) {

                closeEditKakModal();

            }


            if (
                catatanModal &&
                event.target === catatanModal
            ) {

                closeCatatanModal();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */
        document.addEventListener('keydown', function(event) {

            if (event.key !== 'Escape') {
                return;
            }


            const uploadModal =
                document.getElementById('kakModal');

            const editModal =
                document.getElementById('editKakModal');

            const catatanModal =
                document.getElementById('catatanModal');


            if (
                uploadModal &&
                !uploadModal.classList.contains('hidden')
            ) {

                closeKakModal();

            }


            if (
                editModal &&
                !editModal.classList.contains('hidden')
            ) {

                closeEditKakModal();

            }


            if (
                catatanModal &&
                !catatanModal.classList.contains('hidden')
            ) {

                closeCatatanModal();

            }

        });
    </script>

@endsection
