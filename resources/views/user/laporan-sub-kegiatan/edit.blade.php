@extends('user.layouts.app')

@section('title', 'Edit Laporan Sub Kegiatan')
@section('page_title', 'Edit Laporan Sub Kegiatan')
@section('breadcrumb', 'Laporan Sub Kegiatan / Edit')

@section('content')

    <div class="max-w-6xl space-y-6">

        {{-- ========================================================= --}}
        {{-- ALERT --}}
        {{-- ========================================================= --}}

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

                <ul class="list-disc list-inside text-sm space-y-1">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">

            <h2 class="text-2xl font-bold">
                Edit Laporan Sub Kegiatan
            </h2>

            <p class="text-blue-100 text-sm mt-2">
                Perbarui bulan, realisasi indikator, permasalahan,
                solusi dan tindak lanjut laporan.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form method="POST" action="{{ route('user.laporan-sub-kegiatan.update', $laporan->laporan_uid) }}"
            class="space-y-6">

            @csrf
            @method('PUT')


            {{-- ===================================================== --}}
            {{-- INFORMASI LAPORAN --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="mb-5">

                    <h3 class="text-lg font-bold text-slate-900">
                        Informasi Laporan
                    </h3>

                    <p class="text-sm text-slate-500">
                        Unit, sub kegiatan dan tahun tidak dapat diubah.
                        Bulan dapat diperbarui.
                    </p>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">


                    {{-- ================================================= --}}
                    {{-- UNIT --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Unit
                        </label>

                        <input type="text" value="{{ $laporan->laporan_unit_nama ?? '-' }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-slate-100 text-slate-700"
                            readonly>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SUB KEGIATAN --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Sub Kegiatan
                        </label>

                        <input type="text" value="{{ $laporan->subKegiatan->sub_kegiatan_nama ?? '-' }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-slate-100 text-slate-700"
                            readonly>

                        @if ($laporan->subKegiatan)
                            <div class="text-xs text-slate-400 mt-2">
                                {{ $laporan->subKegiatan->sub_kegiatan_kode ?? '-' }}
                            </div>
                        @endif

                    </div>


                    {{-- ================================================= --}}
                    {{-- BULAN --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Bulan
                        </label>

                        @php
                            $bulanList = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp

                        <select name="laporan_bulan"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>

                            @foreach ($bulanList as $angka => $nama)
                                <option value="{{ $angka }}"
                                    {{ old('laporan_bulan', $laporan->laporan_bulan) == $angka ? 'selected' : '' }}>

                                    {{ $nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TAHUN --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahun
                        </label>

                        <input type="text" value="{{ $laporan->laporan_tahun }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-slate-100 text-slate-700"
                            readonly>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- REALISASI INDIKATOR --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="mb-5">

                    <h3 class="text-lg font-bold text-slate-900">
                        Realisasi Indikator
                    </h3>

                    <p class="text-sm text-slate-500">
                        Perbarui nilai realisasi indikator.
                    </p>

                </div>


                <div class="space-y-4">

                    @forelse ($laporan->detail as $detail)
                        @php

                            $target = (float) ($detail->detail_target ?? 0);

                            $realisasi = (float) ($detail->detail_realisasi ?? 0);

                            $persen = $target > 0 ? ($realisasi / $target) * 100 : 0;

                            if ($persen > 100) {
                                $persen = 100;
                            }

                        @endphp


                        <div class="border border-slate-200 rounded-2xl p-5">

                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">


                                {{-- INFORMASI INDIKATOR --}}

                                <div>

                                    <h4 class="font-bold text-slate-900">
                                        {{ $detail->detail_indikator_nama ?? '-' }}
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">

                                        Target :

                                        <span class="font-semibold text-blue-700">

                                            {{ number_format($target, 2, ',', '.') }}

                                            {{ $detail->detail_satuan ?? '' }}

                                        </span>

                                    </p>

                                </div>


                                {{-- INPUT REALISASI --}}

                                <div class="w-full md:w-64">

                                    <label class="block text-xs font-semibold text-slate-500 mb-2">
                                        Realisasi
                                    </label>

                                    <input type="number" step="0.01" min="0"
                                        name="realisasi[{{ $detail->detail_id }}]"
                                        value="{{ old('realisasi.' . $detail->detail_id, $realisasi) }}"
                                        oninput="hitungPersen(this, {{ $target }})"
                                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>

                                    <div class="text-xs text-slate-500 mt-2">

                                        Capaian :

                                        <span class="font-bold text-green-600 persen-capaian">

                                            {{ number_format($persen, 2, ',', '.') }}%

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 text-yellow-700">

                            Belum ada indikator pada laporan ini.

                        </div>
                    @endforelse

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- PERMASALAHAN --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="flex justify-between items-center mb-4">

                    <div>

                        <h3 class="text-lg font-bold text-slate-900">
                            Permasalahan
                        </h3>

                        <p class="text-sm text-slate-500">
                            Bisa menambahkan lebih dari satu permasalahan.
                        </p>

                    </div>


                    <button type="button" onclick="addPermasalahan()"
                        class="px-4 py-2 rounded-xl bg-red-50 text-red-600 font-semibold hover:bg-red-100">

                        + Tambah

                    </button>

                </div>


                <div id="permasalahanContainer" class="space-y-3">

                    @forelse ($laporan->permasalahan as $item)
                        <div class="flex gap-3 item-row">

                            <textarea name="permasalahan[]" rows="2"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-300"
                                placeholder="Tuliskan permasalahan...">{{ $item->permasalahan_uraian }}</textarea>


                            <button type="button" onclick="this.closest('.item-row').remove()"
                                class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">

                                ✕

                            </button>

                        </div>

                    @empty

                        <div class="flex gap-3 item-row">

                            <textarea name="permasalahan[]" rows="2"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-300"
                                placeholder="Tuliskan permasalahan..."></textarea>

                        </div>
                    @endforelse

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- SOLUSI --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="flex justify-between items-center mb-4">

                    <div>

                        <h3 class="text-lg font-bold text-slate-900">
                            Solusi
                        </h3>

                        <p class="text-sm text-slate-500">
                            Bisa menambahkan lebih dari satu solusi.
                        </p>

                    </div>


                    <button type="button" onclick="addSolusi()"
                        class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100">

                        + Tambah

                    </button>

                </div>


                <div id="solusiContainer" class="space-y-3">

                    @forelse ($laporan->solusi as $item)
                        <div class="flex gap-3 item-row">

                            <textarea name="solusi[]" rows="2"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                placeholder="Tuliskan solusi...">{{ $item->solusi_uraian }}</textarea>


                            <button type="button" onclick="this.closest('.item-row').remove()"
                                class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">

                                ✕

                            </button>

                        </div>

                    @empty

                        <div class="flex gap-3 item-row">

                            <textarea name="solusi[]" rows="2"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                placeholder="Tuliskan solusi..."></textarea>

                        </div>
                    @endforelse

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- TINDAK LANJUT --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="flex justify-between items-center mb-4">

                    <div>

                        <h3 class="text-lg font-bold text-slate-900">
                            Tindak Lanjut
                        </h3>

                        <p class="text-sm text-slate-500">
                            Bisa menambahkan lebih dari satu tindak lanjut.
                        </p>

                    </div>


                    <button type="button" onclick="addTindakLanjut()"
                        class="px-4 py-2 rounded-xl bg-green-50 text-green-600 font-semibold hover:bg-green-100">

                        + Tambah

                    </button>

                </div>


                <div id="tindakLanjutContainer" class="space-y-3">

                    @forelse ($laporan->tindakLanjut as $item)
                        <div class="flex gap-3 item-row">

                            <textarea name="tindak_lanjut[]" rows="2"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-300"
                                placeholder="Tuliskan tindak lanjut...">{{ $item->tindak_lanjut_uraian }}</textarea>


                            <button type="button" onclick="this.closest('.item-row').remove()"
                                class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">

                                ✕

                            </button>

                        </div>

                    @empty

                        <div class="flex gap-3 item-row">

                            <textarea name="tindak_lanjut[]" rows="2"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-300"
                                placeholder="Tuliskan tindak lanjut..."></textarea>

                        </div>
                    @endforelse

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- BUTTON --}}
            {{-- ===================================================== --}}

            <div class="flex justify-end gap-3">

                <a href="{{ route('user.laporan-sub-kegiatan.index') }}"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">

                    Batal

                </a>


                <button type="submit"
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">

                    <i class="bi bi-save mr-1"></i>

                    Update Laporan

                </button>

            </div>

        </form>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>
        /*
            |--------------------------------------------------------------------------
            | HITUNG PERSENTASE
            |--------------------------------------------------------------------------
            */

        function hitungPersen(input, target) {

            const wrapper = input.closest('.border');

            const output =
                wrapper.querySelector('.persen-capaian');

            const realisasi =
                Number(input.value || 0);

            let persen = 0;


            if (Number(target) > 0) {

                persen =
                    (realisasi / Number(target)) * 100;

            }


            if (persen > 100) {
                persen = 100;
            }


            output.innerText =
                persen.toFixed(2).replace('.', ',') + '%';

        }


        /*
        |--------------------------------------------------------------------------
        | TAMBAH PERMASALAHAN
        |--------------------------------------------------------------------------
        */

        function addPermasalahan() {

            addDynamicTextarea(
                'permasalahanContainer',
                'permasalahan[]',
                'Tuliskan permasalahan...'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TAMBAH SOLUSI
        |--------------------------------------------------------------------------
        */

        function addSolusi() {

            addDynamicTextarea(
                'solusiContainer',
                'solusi[]',
                'Tuliskan solusi...'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TAMBAH TINDAK LANJUT
        |--------------------------------------------------------------------------
        */

        function addTindakLanjut() {

            addDynamicTextarea(
                'tindakLanjutContainer',
                'tindak_lanjut[]',
                'Tuliskan tindak lanjut...'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DYNAMIC TEXTAREA
        |--------------------------------------------------------------------------
        */

        function addDynamicTextarea(
            containerId,
            inputName,
            placeholder
        ) {

            const container =
                document.getElementById(containerId);


            container.insertAdjacentHTML(
                'beforeend',
                `
                    <div class="flex gap-3 item-row">

                        <textarea
                            name="${inputName}"
                            rows="2"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-300"
                            placeholder="${placeholder}"></textarea>

                        <button
                            type="button"
                            onclick="this.closest('.item-row').remove()"
                            class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">

                            ✕

                        </button>

                    </div>
                `
            );

        }
    </script>

@endsection
