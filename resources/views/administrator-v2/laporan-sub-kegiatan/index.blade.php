@extends('administrator-v2.layouts.app')

@section('title', 'Laporan Sub Kegiatan')

@section('content')

    @if (session('success'))
        <div
            class="mb-6 rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-5 py-4">

            <div class="flex items-center gap-3">

                <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>

                <span class="text-green-700 dark:text-green-300">

                    {{ session('success') }}

                </span>

            </div>

        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">

                Laporan Sub Kegiatan

            </h1>

            <p class="text-slate-500 dark:text-slate-400 mt-1">

                Rekap laporan realisasi indikator, permasalahan, solusi dan tindak lanjut operator.

            </p>

        </div>

        <a href="{{ route('admin.laporan-sub-kegiatan.export.excel') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3">

            <i class="bi bi-file-earmark-excel"></i>

            Export Excel

        </a>

    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6 mb-6">

        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Unit

                    </label>

                    <select name="unit"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Unit

                        </option>

                        @foreach ($units as $unit)
                            <option value="{{ $unit->indikator_unit_kode }}" @selected(request('unit') == $unit->indikator_unit_kode)>

                                {{ $unit->indikator_unit_nama }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Program

                    </label>

                    <select id="program" name="program"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Program

                        </option>

                        @foreach ($programs as $program)
                            <option value="{{ $program->program_id }}" @selected(request('program') == $program->program_id)>

                                {{ $program->program_nama }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Kegiatan

                    </label>

                    <select id="kegiatan" name="kegiatan"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Kegiatan

                        </option>

                        @isset($kegiatans)

                            @foreach ($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->kegiatan_id }}" @selected(request('kegiatan') == $kegiatan->kegiatan_id)>

                                    {{ $kegiatan->kegiatan_nama }}

                                </option>
                            @endforeach

                        @endisset

                    </select>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Sub Kegiatan

                    </label>

                    <select id="sub_kegiatan" name="sub_kegiatan"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Sub Kegiatan

                        </option>

                        @isset($subKegiatans)

                            @foreach ($subKegiatans as $sub)
                                <option value="{{ $sub->sub_kegiatan_id }}" @selected(request('sub_kegiatan') == $sub->sub_kegiatan_id)>

                                    {{ $sub->sub_kegiatan_nama }}

                                </option>
                            @endforeach

                        @endisset

                    </select>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Status

                    </label>

                    <select name="status"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="">

                            Semua Status

                        </option>

                        <option value="Aktif" @selected(request('status') == 'Aktif')>

                            Aktif

                        </option>

                        <option value="Nonaktif" @selected(request('status') == 'Nonaktif')>

                            Nonaktif

                        </option>

                    </select>

                </div>

            </div>

            <div class="flex gap-3 mt-6">

                <button class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3">

                    <i class="bi bi-search me-2"></i>

                    Filter

                </button>

                <a href="{{ route('admin.laporan-sub-kegiatan.index') }}"
                    class="rounded-xl bg-slate-600 hover:bg-slate-700 text-white font-semibold px-6 py-3">

                    Reset

                </a>

            </div>

        </form>

    </div>

    <div class="space-y-6">
        @forelse ($laporan as $item)

            @php

                $bulanNama = [
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

                $totalPersen = 0;

                $jumlahIndikator = $item->detail->count();

                foreach ($item->detail as $detail) {
                    $persen =
                        $detail->detail_target > 0 ? ($detail->detail_realisasi / $detail->detail_target) * 100 : 0;

                    if ($persen > 100) {
                        $persen = 100;
                    }

                    $totalPersen += $persen;
                }

                $rataCapaian = $jumlahIndikator > 0 ? $totalPersen / $jumlahIndikator : 0;

            @endphp

            <div
                class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">

                <div class="px-7 py-6 border-b border-slate-200 dark:border-slate-800">

                    <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">

                        <div>

                            <div class="text-sm text-slate-500 dark:text-slate-400">

                                {{ $bulanNama[$item->laporan_bulan] ?? '-' }}

                                {{ $item->laporan_tahun }}

                            </div>

                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mt-2">

                                {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}

                            </h2>

                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-2">

                                Kode :

                                <span class="font-medium">

                                    {{ $item->subKegiatan->sub_kegiatan_kode ?? '-' }}

                                </span>

                            </div>

                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                                Operator :

                                <span class="font-semibold text-slate-700 dark:text-white">

                                    {{ $item->laporan_created_by_nama ?? '-' }}

                                </span>

                                /

                                {{ $item->laporan_created_by_nip ?? '-' }}

                            </div>

                        </div>

                        <div class="w-full xl:w-64">

                            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5">

                                <div class="text-sm text-slate-500 dark:text-slate-400">

                                    Rata-rata Capaian

                                </div>

                                <div
                                    class="mt-2 text-3xl font-black
                        {{ $rataCapaian >= 100 ? 'text-green-600 dark:text-green-400' : ($rataCapaian >= 60 ? 'text-amber-500' : 'text-red-500') }}">

                                    {{ number_format($rataCapaian, 2, ',', '.') }}%

                                </div>

                                <div class="w-full h-3 rounded-full bg-slate-200 dark:bg-slate-700 mt-4 overflow-hidden">

                                    <div class="h-3 rounded-full
                            {{ $rataCapaian >= 100 ? 'bg-green-500' : ($rataCapaian >= 60 ? 'bg-amber-500' : 'bg-red-500') }}"
                                        style="width: {{ min($rataCapaian, 100) }}%">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="p-7">

                    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">

                        <table class="min-w-full text-sm">

                            <thead class="bg-slate-100 dark:bg-slate-800">

                                <tr>

                                    <th class="px-5 py-4 text-left">

                                        No

                                    </th>

                                    <th class="px-5 py-4 text-left">

                                        Indikator

                                    </th>

                                    <th class="px-5 py-4 text-right">

                                        Target

                                    </th>

                                    <th class="px-5 py-4 text-right">

                                        Realisasi

                                    </th>

                                    <th class="px-5 py-4 text-right">

                                        Capaian

                                    </th>

                                </tr>

                            </thead>

                            <tbody>
                            <tbody>

                                @forelse($item->detail as $detail)
                                    @php

                                        $persen =
                                            $detail->detail_target > 0
                                                ? ($detail->detail_realisasi / $detail->detail_target) * 100
                                                : 0;

                                        if ($persen > 100) {
                                            $persen = 100;
                                        }

                                    @endphp

                                    <tr class="border-b border-slate-200 dark:border-slate-800">

                                        <td class="px-5 py-4">

                                            {{ $loop->iteration }}

                                        </td>

                                        <td class="px-5 py-4">

                                            <div class="font-semibold text-slate-800 dark:text-white">

                                                {{ $detail->detail_indikator_nama }}

                                            </div>

                                        </td>

                                        <td class="px-5 py-4 text-right">

                                            {{ number_format($detail->detail_target, 2, ',', '.') }}

                                            {{ $detail->detail_satuan }}

                                        </td>

                                        <td class="px-5 py-4 text-right text-green-600 dark:text-green-400">

                                            {{ number_format($detail->detail_realisasi, 2, ',', '.') }}

                                            {{ $detail->detail_satuan }}

                                        </td>

                                        <td class="px-5 py-4 text-right">

                                            @if ($persen >= 100)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                                                    {{ number_format($persen, 2, ',', '.') }}%

                                                </span>
                                            @elseif($persen >= 60)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 px-3 py-1 text-xs font-semibold">

                                                    {{ number_format($persen, 2, ',', '.') }}%

                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 py-1 text-xs font-semibold">

                                                    {{ number_format($persen, 2, ',', '.') }}%

                                                </span>
                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" class="py-12 text-center text-slate-500 dark:text-slate-400">

                                            Belum ada indikator.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-7 space-y-5">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div
                                class="rounded-2xl border border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/20 p-5">

                                <h3 class="font-bold text-red-700 dark:text-red-300 mb-4">

                                    Permasalahan

                                </h3>

                                <ol class="list-decimal list-inside space-y-2 text-sm text-slate-700 dark:text-slate-300">

                                    @forelse($item->permasalahan as $masalah)
                                        <li>{{ $masalah->permasalahan_uraian }}</li>

                                    @empty

                                        <li class="list-none text-slate-500">

                                            Tidak ada permasalahan.

                                        </li>
                                    @endforelse

                                </ol>

                            </div>

                            <div
                                class="rounded-2xl border border-blue-200 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/20 p-5">

                                <h3 class="font-bold text-blue-700 dark:text-blue-300 mb-4">

                                    Solusi

                                </h3>

                                <ol class="list-decimal list-inside space-y-2 text-sm text-slate-700 dark:text-slate-300">

                                    @forelse($item->solusi as $solusi)
                                        <li>{{ $solusi->solusi_uraian }}</li>

                                    @empty

                                        <li class="list-none text-slate-500">

                                            Tidak ada solusi.

                                        </li>
                                    @endforelse

                                </ol>

                            </div>

                            <div
                                class="rounded-2xl border border-green-200 dark:border-green-900 bg-green-50 dark:bg-green-900/20 p-5">

                                <h3 class="font-bold text-green-700 dark:text-green-300 mb-4">

                                    Tindak Lanjut

                                </h3>

                                <ol class="list-decimal list-inside space-y-2 text-sm text-slate-700 dark:text-slate-300">

                                    @forelse($item->tindakLanjut as $tl)
                                        <li>{{ $tl->tindak_lanjut_uraian }}</li>

                                    @empty

                                        <li class="list-none text-slate-500">

                                            Tidak ada tindak lanjut.

                                        </li>
                                    @endforelse

                                </ol>

                            </div>

                        </div>

                        <div class="flex flex-wrap justify-end gap-3">

                            <a href="{{ route('admin.laporan-sub-kegiatan.pdf', $item->laporan_uid) }}" target="_blank"
                                class="inline-flex items-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-3">

                                <i class="bi bi-file-earmark-pdf"></i>

                                PDF

                            </a>

                            @if ($item->laporan_status == 'Aktif')
                                <form method="POST"
                                    action="{{ route('admin.laporan-sub-kegiatan.nonaktif', $item->laporan_uid) }}">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-3">

                                        Nonaktifkan

                                    </button>

                                </form>
                            @else
                                <form method="POST"
                                    action="{{ route('admin.laporan-sub-kegiatan.aktif', $item->laporan_uid) }}">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3">

                                        Aktifkan

                                    </button>

                                </form>
                            @endif

                            <button onclick="openCatatan('{{ $item->laporan_uid }}')"
                                class="inline-flex items-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-3">

                                <i class="bi bi-chat-left-text"></i>

                                Catatan

                            </button>

                        </div>

                    </div>

                </div>

            @empty

                <div
                    class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 p-12 text-center">

                    <div class="flex flex-col items-center">

                        <div
                            class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-5">

                            <i class="bi bi-inboxes text-4xl text-slate-400"></i>

                        </div>

                        <h3 class="text-xl font-bold text-slate-700 dark:text-slate-200">

                            Belum Ada Laporan

                        </h3>

                        <p class="text-slate-500 dark:text-slate-400 mt-2">

                            Belum ada laporan sub kegiatan yang tersedia.

                        </p>

                    </div>

                </div>

        @endforelse

    </div>
    <div id="modalCatatan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl flex flex-col overflow-hidden">

            <div class="flex items-center justify-between px-7 py-5 border-b border-slate-200 dark:border-slate-700">

                <div>

                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                        Catatan Admin

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Berikan catatan kepada operator terkait laporan sub kegiatan.

                    </p>

                </div>

                <button type="button" onclick="closeCatatan()"
                    class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg text-lg"></i>

                </button>

            </div>

            <form method="POST" id="formCatatan">

                @csrf

                <div class="px-7 py-6">

                    <label class="block text-sm font-semibold mb-2">

                        Catatan

                    </label>

                    <textarea name="catatan" rows="6"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                        placeholder="Masukkan catatan admin..."></textarea>

                </div>

                <div class="flex justify-end gap-3 px-7 py-5 border-t border-slate-200 dark:border-slate-700">

                    <button type="button" onclick="closeCatatan()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                        Batal

                    </button>

                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5">

                        <i class="bi bi-save me-2"></i>

                        Simpan Catatan

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        function openCatatan(uid) {

            let url = "{{ route('admin.laporan-sub-kegiatan.catatan', ':uid') }}";

            url = url.replace(':uid', uid);

            $('#formCatatan').attr('action', url);

            $('#modalCatatan')
                .removeClass('hidden')
                .addClass('flex');

            $('body').addClass('overflow-hidden');

        }

        function closeCatatan() {

            $('#modalCatatan')
                .removeClass('flex')
                .addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }

        $('#modalCatatan').on('click', function(e) {

            if (e.target === this) {

                closeCatatan();

            }

        });

        $(document).keydown(function(e) {

            if (e.key === 'Escape') {

                closeCatatan();

            }

        });

        $('#program').change(function() {

            let programId = $(this).val();

            $('#kegiatan').html('<option value="">Loading...</option>');

            $('#sub_kegiatan').html('<option value="">Semua Sub Kegiatan</option>');

            $.get('/admin/master/kegiatan', {

                program_id: programId

            }, function(data) {

                let html = '<option value="">Semua Kegiatan</option>';

                data.forEach(function(item) {

                    html += `<option value="${item.kegiatan_id}">
                    ${item.kegiatan_nama}
                </option>`;

                });

                $('#kegiatan').html(html);

            });

        });

        $('#kegiatan').change(function() {

            let kegiatanId = $(this).val();

            $('#sub_kegiatan').html('<option value="">Loading...</option>');

            $.get('/admin/master/sub-kegiatan', {

                kegiatan_id: kegiatanId

            }, function(data) {

                let html = '<option value="">Semua Sub Kegiatan</option>';

                data.forEach(function(item) {

                    html += `<option value="${item.sub_kegiatan_id}">
                    ${item.sub_kegiatan_nama}
                </option>`;

                });

                $('#sub_kegiatan').html(html);

            });

        });
    </script>
@endpush
