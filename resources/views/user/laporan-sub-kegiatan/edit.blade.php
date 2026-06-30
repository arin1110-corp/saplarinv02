@extends('user.layouts.app')

@section('title', 'Edit Laporan Sub Kegiatan')
@section('page_title', 'Edit Laporan Sub Kegiatan')
@section('breadcrumb', 'Laporan Sub Kegiatan / Edit')

@section('content')

<div class="max-w-6xl space-y-6">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
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
        <h2 class="text-2xl font-bold">
            Edit Laporan Sub Kegiatan
        </h2>

        <p class="text-blue-100 text-sm mt-2">
            Perbarui realisasi indikator, permasalahan, solusi dan tindak lanjut.
        </p>
    </div>

    <form method="POST"
        action="{{ route('user.laporan-sub-kegiatan.update', $laporan->laporan_uid) }}"
        class="space-y-6">

        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Unit
                    </label>

                    <input type="text"
                        value="{{ $laporan->laporan_unit_nama }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-slate-100"
                        readonly>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Sub Kegiatan
                    </label>

                    <input type="text"
                        value="{{ $laporan->subKegiatan->sub_kegiatan_nama ?? '-' }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-slate-100"
                        readonly>
                </div>

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

                    <input type="text"
                        value="{{ $bulanList[$laporan->laporan_bulan] ?? '-' }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-slate-100"
                        readonly>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tahun
                    </label>

                    <input type="text"
                        value="{{ $laporan->laporan_tahun }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-slate-100"
                        readonly>
                </div>

            </div>

        </div>

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

                @foreach($laporan->detail as $detail)

                    @php
                        $persen = 0;

                        if ($detail->detail_target > 0) {
                            $persen =
                                ($detail->detail_realisasi /
                                $detail->detail_target) * 100;
                        }
                    @endphp

                    <div class="border border-slate-200 rounded-2xl p-5">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div>

                                <h4 class="font-bold text-slate-900">
                                    {{ $detail->detail_indikator_nama }}
                                </h4>

                                <p class="text-sm text-slate-500 mt-1">
                                    Target :
                                    <span class="font-semibold text-blue-700">
                                        {{ number_format($detail->detail_target,0,',','.') }}
                                        {{ $detail->detail_satuan }}
                                    </span>
                                </p>

                            </div>

                            <div class="w-full md:w-64">

                                <label class="block text-xs font-semibold text-slate-500 mb-2">
                                    Realisasi
                                </label>

                                <input type="number"
                                    step="0.01"
                                    name="realisasi[{{ $detail->detail_id }}]"
                                    value="{{ $detail->detail_realisasi }}"
                                    oninput="hitungPersen(this, {{ $detail->detail_target }})"
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                                    required>

                                <div class="text-xs text-slate-500 mt-2">
                                    Capaian :
                                    <span class="font-bold text-green-600 persen-capaian">
                                        {{ number_format($persen,2,',','.') }}%
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>
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

                <button type="button"
                    onclick="addPermasalahan()"
                    class="px-4 py-2 rounded-xl bg-red-50 text-red-600 font-semibold hover:bg-red-100">

                    + Tambah

                </button>

            </div>

            <div id="permasalahanContainer" class="space-y-3">

                @forelse($laporan->permasalahan as $item)

                    <div class="flex gap-3 item-row">

                        <textarea
                            name="permasalahan[]"
                            rows="2"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $item->permasalahan_uraian }}</textarea>

                        <button type="button"
                            onclick="this.closest('.item-row').remove()"
                            class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">

                            ✕

                        </button>

                    </div>

                @empty

                    <div class="flex gap-3 item-row">

                        <textarea
                            name="permasalahan[]"
                            rows="2"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                            placeholder="Tuliskan permasalahan..."></textarea>

                    </div>

                @endforelse

            </div>

        </div>

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

                <button type="button"
                    onclick="addSolusi()"
                    class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100">

                    + Tambah

                </button>

            </div>

            <div id="solusiContainer" class="space-y-3">

                @forelse($laporan->solusi as $item)

                    <div class="flex gap-3 item-row">

                        <textarea
                            name="solusi[]"
                            rows="2"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $item->solusi_uraian }}</textarea>

                        <button type="button"
                            onclick="this.closest('.item-row').remove()"
                            class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">

                            ✕

                        </button>

                    </div>

                @empty

                    <div class="flex gap-3 item-row">

                        <textarea
                            name="solusi[]"
                            rows="2"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                            placeholder="Tuliskan solusi..."></textarea>

                    </div>

                @endforelse

            </div>

        </div>

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

                <button type="button"
                    onclick="addTindakLanjut()"
                    class="px-4 py-2 rounded-xl bg-green-50 text-green-600 font-semibold hover:bg-green-100">

                    + Tambah

                </button>

            </div>

            <div id="tindakLanjutContainer" class="space-y-3">

                @forelse($laporan->tindakLanjut as $item)

                    <div class="flex gap-3 item-row">

                        <textarea
                            name="tindak_lanjut[]"
                            rows="2"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ $item->tindak_lanjut_uraian }}</textarea>

                        <button type="button"
                            onclick="this.closest('.item-row').remove()"
                            class="px-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100">

                            ✕

                        </button>

                    </div>

                @empty

                    <div class="flex gap-3 item-row">

                        <textarea
                            name="tindak_lanjut[]"
                            rows="2"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                            placeholder="Tuliskan tindak lanjut..."></textarea>

                    </div>

                @endforelse

            </div>

        </div>

        <div class="flex justify-end gap-3">

            <a href="{{ route('user.laporan-sub-kegiatan.index') }}"
                class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">

                Batal

            </a>

            <button type="submit"
                class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">

                Update Laporan

            </button>

        </div>

    </form>

</div>

<script>
    function hitungPersen(input, target) {
        const wrapper = input.closest('.border');
        const output = wrapper.querySelector('.persen-capaian');

        const realisasi = Number(input.value || 0);

        let persen = 0;

        if (Number(target) > 0) {
            persen = (realisasi / Number(target)) * 100;
        }

        if (persen > 100) {
            persen = 100;
        }

        output.innerText =
            persen.toFixed(2).replace('.', ',') + '%';
    }

    function addPermasalahan() {
        addDynamicTextarea(
            'permasalahanContainer',
            'permasalahan[]',
            'Tuliskan permasalahan...'
        );
    }

    function addSolusi() {
        addDynamicTextarea(
            'solusiContainer',
            'solusi[]',
            'Tuliskan solusi...'
        );
    }

    function addTindakLanjut() {
        addDynamicTextarea(
            'tindakLanjutContainer',
            'tindak_lanjut[]',
            'Tuliskan tindak lanjut...'
        );
    }

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
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
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