@extends('user.layouts.app')

@section('title', 'Permintaan KAK')
@section('page_title', 'Permintaan KAK')
@section('breadcrumb', 'Permintaan KAK')

@section('content')

    @php
        /*
        |--------------------------------------------------------------------------
        | DAFTAR TAHUN
        |--------------------------------------------------------------------------
        | Tahun tidak mengambil dari data KAK.
        | 2020 sampai 5 tahun ke depan.
        */
        $tahunList = range(2020, date('Y') + 5);
        $tahunList = array_reverse($tahunList);

        /*
        |--------------------------------------------------------------------------
        | DATA PROGRAM -> KEGIATAN -> SUB KEGIATAN
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
                                'kegiatan_status' => $kegiatan->kegiatan_status,

                                'sub_kegiatan' => $kegiatan->subKegiatan
                                    ->map(function ($subKegiatan) {
                                        return [
                                            'sub_kegiatan_id' => $subKegiatan->sub_kegiatan_id,
                                            'sub_kegiatan_kode' => $subKegiatan->sub_kegiatan_kode,
                                            'sub_kegiatan_nama' => $subKegiatan->sub_kegiatan_nama,
                                            'sub_kegiatan_status' => $subKegiatan->sub_kegiatan_status,
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
    @endphp


    <div class="space-y-6">

        {{-- ============================================================
            ALERT SUCCESS
        ============================================================= --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif


        {{-- ============================================================
            ALERT ERROR
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
                        <li>
                            {{ $error }}
                        </li>
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
                        Silakan upload dokumen Kerangka Acuan Kerja (KAK) sesuai
                        sub kegiatan dan tahapan yang dipilih. File yang diupload
                        harus dalam format PDF dengan ukuran maksimal 200 MB.
                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
            FILTER
        ============================================================= --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- SEARCH --}}
                <div class="lg:col-span-2">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Pencarian
                    </label>

                    <div class="relative">

                        <input type="text" id="kakSearch" placeholder="Cari sub kegiatan atau tahapan..."
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-10 focus:border-blue-500 focus:ring-blue-500">

                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                            </svg>

                        </div>

                    </div>

                </div>


                {{-- TAHUN --}}
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

                            <th class="px-5 py-4 text-left font-semibold text-slate-600 w-28">
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

                            <th class="px-5 py-4 text-center font-semibold text-slate-600 w-32">
                                Berkas
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($kaks as $index => $kak)
                            <tr class="kak-row border-b border-slate-100 hover:bg-slate-50 transition"
                                data-sub-kegiatan="{{ strtolower($kak->subKegiatan->sub_kegiatan_nama ?? '') }}"
                                data-tahapan="{{ strtolower($kak->kak_tahapan ?? '') }}" data-tahun="{{ $kak->kak_tahun }}">

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
                                        <a href="{{ asset('storage/' . $kak->kak_file) }}" target="_blank"
                                            rel="noopener noreferrer"
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

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-5 py-12 text-center">

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


                        {{-- FILTER EMPTY --}}
                        <tr id="desktopFilterEmpty" class="hidden">

                            <td colspan="7" class="px-5 py-12 text-center">

                                <div class="text-slate-400">

                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                                    </svg>

                                    <p class="font-semibold text-slate-500">
                                        Data tidak ditemukan
                                    </p>

                                    <p class="text-sm mt-1">
                                        Coba ubah kata pencarian atau tahun.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ============================================================
            MOBILE CARD
        ============================================================= --}}
        <div class="md:hidden space-y-4">

            @forelse ($kaks as $index => $kak)
                <div class="kak-mobile-card bg-white rounded-3xl border border-slate-200 shadow-sm p-5"
                    data-sub-kegiatan="{{ strtolower($kak->subKegiatan->sub_kegiatan_nama ?? '') }}"
                    data-tahapan="{{ strtolower($kak->kak_tahapan ?? '') }}" data-tahun="{{ $kak->kak_tahun }}">

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


                    <div class="mt-5 pt-4 border-t border-slate-100">

                        @if (!empty($kak->kak_file))
                            <a href="{{ asset('storage/' . $kak->kak_file) }}" target="_blank" rel="noopener noreferrer"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 transition font-semibold text-sm">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V7.5L14.5 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 3v5h5" />
                                </svg>

                                Buka Berkas PDF

                            </a>
                        @else
                            <div class="text-center text-sm text-slate-400">
                                Berkas belum tersedia
                            </div>
                        @endif

                    </div>

                </div>

            @empty

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center">

                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v12a2 2 0 01-2 2z" />
                    </svg>

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
        MODAL UPLOAD KAK
        ================================================================ --}}
    <div id="kakModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">

            {{-- ========================================================
                MODAL HEADER
            ========================================================= --}}
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
                        class="w-10 h-10 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center transition">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>

                    </button>

                </div>

            </div>


            {{-- ========================================================
                FORM
            ========================================================= --}}
            <form id="kakForm" method="POST" action="{{ route('user.permintaan-kak.store') }}"
                enctype="multipart/form-data">

                @csrf


                <div class="p-6 space-y-5">

                    {{-- =================================================
                        PROGRAM
                    ================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Program
                        </label>

                        <select id="kakProgram"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Program --
                            </option>

                            @foreach ($programData as $program)
                                <option value="{{ $program['program_id'] }}">
                                    {{ $program['program_kode'] ? $program['program_kode'] . ' - ' : '' }}{{ $program['program_nama'] }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                        KEGIATAN
                    ================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kegiatan
                        </label>

                        <select id="kakKegiatan" disabled
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white disabled:bg-slate-100 disabled:text-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Kegiatan --
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        SUB KEGIATAN
                    ================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Sub Kegiatan
                        </label>

                        <select id="kakSubKegiatan" name="kak_sub_kegiatan_id" disabled required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white disabled:bg-slate-100 disabled:text-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Sub Kegiatan --
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        TAHUN
                    ================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahun
                        </label>

                        <select name="kak_tahun" required
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white focus:border-blue-500 focus:ring-blue-500">

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


                    {{-- =================================================
                        TAHAPAN
                    ================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahapan
                        </label>

                        <input type="text" name="kak_tahapan" value="{{ old('kak_tahapan') }}" required
                            placeholder="Contoh: Penyusunan KAK"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

                    </div>


                    {{-- =================================================
                        FILE
                    ================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            File KAK
                        </label>

                        <div class="rounded-2xl border-2 border-dashed border-slate-300 p-5">

                            <input type="file" name="kak_file" accept="application/pdf,.pdf" required
                                class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100">

                            <p class="text-xs text-slate-500 mt-2">
                                Format file: PDF. Maksimal ukuran 200 MB.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                    MODAL FOOTER
                ===================================================== --}}
                <div
                    class="px-6 py-5 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                    <button type="button" onclick="closeKakModal()"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200 transition">
                        Batal
                    </button>


                    <button type="submit"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">

                        Upload KAK

                    </button>

                </div>

            </form>

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
        | OPEN MODAL
        |--------------------------------------------------------------------------
        */
        function openKakModal() {

            const modal = document.getElementById('kakModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE MODAL
        |--------------------------------------------------------------------------
        */
        function closeKakModal() {

            const modal = document.getElementById('kakModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | PROGRAM -> KEGIATAN
        |--------------------------------------------------------------------------
        */
        document.addEventListener('DOMContentLoaded', function() {

            const programSelect =
                document.getElementById('kakProgram');

            const kegiatanSelect =
                document.getElementById('kakKegiatan');

            const subKegiatanSelect =
                document.getElementById('kakSubKegiatan');


            /*
            |--------------------------------------------------------------------------
            | Saat Program berubah
            |--------------------------------------------------------------------------
            */
            programSelect.addEventListener('change', function() {

                const programId = this.value;


                /*
                | Reset Kegiatan
                */
                kegiatanSelect.innerHTML =
                    '<option value="">-- Pilih Kegiatan --</option>';

                /*
                | Reset Sub Kegiatan
                */
                subKegiatanSelect.innerHTML =
                    '<option value="">-- Pilih Sub Kegiatan --</option>';

                subKegiatanSelect.disabled = true;


                /*
                | Jika program belum dipilih
                */
                if (!programId) {

                    kegiatanSelect.disabled = true;

                    return;

                }


                /*
                | Cari program
                */
                const program = kakPrograms.find(function(item) {

                    return String(item.program_id) ===
                        String(programId);

                });


                if (!program) {

                    kegiatanSelect.disabled = true;

                    return;

                }


                /*
                | Isi Kegiatan
                */
                const kegiatanList =
                    program.kegiatan || [];


                kegiatanList.forEach(function(kegiatan) {

                    const option =
                        document.createElement('option');

                    option.value =
                        kegiatan.kegiatan_id;

                    option.textContent =
                        (
                            kegiatan.kegiatan_kode ?
                            kegiatan.kegiatan_kode + ' - ' :
                            ''
                        ) +
                        kegiatan.kegiatan_nama;

                    kegiatanSelect.appendChild(option);

                });


                kegiatanSelect.disabled =
                    kegiatanList.length === 0;

            });


            /*
            |--------------------------------------------------------------------------
            | Kegiatan -> Sub Kegiatan
            |--------------------------------------------------------------------------
            */
            kegiatanSelect.addEventListener('change', function() {

                const programId =
                    programSelect.value;

                const kegiatanId =
                    this.value;


                /*
                | Reset Sub Kegiatan
                */
                subKegiatanSelect.innerHTML =
                    '<option value="">-- Pilih Sub Kegiatan --</option>';

                subKegiatanSelect.disabled = true;


                if (!programId || !kegiatanId) {

                    return;

                }


                /*
                | Cari Program
                */
                const program =
                    kakPrograms.find(function(item) {

                        return String(item.program_id) ===
                            String(programId);

                    });


                if (!program) {

                    return;

                }


                /*
                | Cari Kegiatan
                */
                const kegiatan =
                    (program.kegiatan || []).find(function(item) {

                        return String(item.kegiatan_id) ===
                            String(kegiatanId);

                    });


                if (!kegiatan) {

                    return;

                }


                /*
                | Isi Sub Kegiatan
                */
                const subKegiatanList =
                    kegiatan.sub_kegiatan || [];


                subKegiatanList.forEach(function(subKegiatan) {

                    const option =
                        document.createElement('option');

                    option.value =
                        subKegiatan.sub_kegiatan_id;

                    option.textContent =
                        (
                            subKegiatan.sub_kegiatan_kode ?
                            subKegiatan.sub_kegiatan_kode + ' - ' :
                            ''
                        ) +
                        subKegiatan.sub_kegiatan_nama;

                    subKegiatanSelect.appendChild(option);

                });


                subKegiatanSelect.disabled =
                    subKegiatanList.length === 0;

            });


            /*
            |--------------------------------------------------------------------------
            | FILTER DATA
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
                | Desktop
                */
                const rows =
                    document.querySelectorAll('.kak-row');

                let desktopVisible =
                    0;


                rows.forEach(function(row) {

                    const subKegiatan =
                        row.dataset.subKegiatan || '';

                    const tahapan =
                        row.dataset.tahapan || '';

                    const tahun =
                        row.dataset.tahun || '';


                    const searchMatch = !keyword ||
                        subKegiatan.includes(keyword) ||
                        tahapan.includes(keyword);


                    const yearMatch = !selectedYear ||
                        String(tahun) ===
                        String(selectedYear);


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

                    if (rows.length > 0 && desktopVisible === 0) {

                        desktopEmpty.classList.remove('hidden');

                    } else {

                        desktopEmpty.classList.add('hidden');

                    }

                }


                /*
                | Mobile
                */
                const cards =
                    document.querySelectorAll('.kak-mobile-card');

                let mobileVisible =
                    0;


                cards.forEach(function(card) {

                    const subKegiatan =
                        card.dataset.subKegiatan || '';

                    const tahapan =
                        card.dataset.tahapan || '';

                    const tahun =
                        card.dataset.tahun || '';


                    const searchMatch = !keyword ||
                        subKegiatan.includes(keyword) ||
                        tahapan.includes(keyword);


                    const yearMatch = !selectedYear ||
                        String(tahun) ===
                        String(selectedYear);


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

                    if (cards.length > 0 && mobileVisible === 0) {

                        mobileEmpty.classList.remove('hidden');

                    } else {

                        mobileEmpty.classList.add('hidden');

                    }

                }

            }


            searchInput.addEventListener(
                'input',
                filterKak
            );


            yearFilter.addEventListener(
                'change',
                filterKak
            );

        });


        /*
        |--------------------------------------------------------------------------
        | CLOSE MODAL KETIKA KLIK BACKDROP
        |--------------------------------------------------------------------------
        */
        document.addEventListener('click', function(event) {

            const modal =
                document.getElementById('kakModal');

            if (!modal) {
                return;
            }


            /*
            | Hanya jika yang diklik adalah area backdrop,
            | bukan isi modal.
            */
            if (event.target === modal) {

                closeKakModal();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | ESC UNTUK CLOSE MODAL
        |--------------------------------------------------------------------------
        */
        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                const modal =
                    document.getElementById('kakModal');

                if (
                    modal &&
                    !modal.classList.contains('hidden')
                ) {

                    closeKakModal();

                }

            }

        });
    </script>

@endsection
