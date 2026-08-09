@extends('user.layouts.app')

@section('title', 'Penerimaan PAD')
@section('page_title', 'Penerimaan PAD')
@section('breadcrumb', 'Penerimaan PAD')

@section('content')

    <div class="space-y-6">

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif


        {{-- ERROR --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif


        {{-- HEADER / FILTER --}}

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div
                class="flex flex-col lg:flex-row
                    lg:justify-between
                    lg:items-center gap-4">

                <div>

                    <h2 class="text-xl font-bold text-slate-900">
                        Daftar Penerimaan PAD
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Target dan realisasi penerimaan PAD
                        {{ $tahun }} untuk unit Anda.
                    </p>

                </div>


                {{-- TAHUN --}}

                <form method="GET" action="{{ route('user.pad.index') }}">

                    <select name="tahun" onchange="this.form.submit()"
                        class="px-4 py-3 rounded-2xl
                           border border-slate-300
                           bg-white
                           text-slate-700
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500">

                        @for ($i = now()->year - 2; $i <= now()->year + 2; $i++)
                            <option value="{{ $i }}" @selected($tahun == $i)>

                                Tahun {{ $i }}

                            </option>
                        @endfor

                    </select>

                </form>

            </div>

        </div>


        {{-- INFO UNIT --}}

        <div
            class="bg-blue-50
                border border-blue-100
                rounded-3xl
                px-6 py-5">

            <div
                class="text-xs
                    uppercase
                    tracking-wide
                    text-blue-500">

                Unit / Bidang

            </div>

            <div class="mt-1
                    font-bold
                    text-lg
                    text-slate-900">

                {{ $unit }}

            </div>

        </div>


        {{-- TABLE --}}

        <div
            class="bg-white
                rounded-3xl
                border border-slate-200
                shadow-sm
                p-6">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr
                            class="border-b
                               text-left
                               text-slate-500">

                            <th class="py-3 px-3">
                                No
                            </th>

                            <th class="py-3 px-3">
                                Unit
                            </th>

                            <th class="py-3 px-3">
                                Jenis PAD
                            </th>

                            <th class="py-3 px-3">
                                Komponen
                            </th>

                            <th class="py-3 px-3">
                                Target
                            </th>

                            <th class="py-3 px-3">
                                Realisasi
                            </th>

                            <th class="py-3 px-3">
                                Sisa
                            </th>

                            <th class="py-3 px-3">
                                Capaian
                            </th>

                            <th class="py-3 px-3 text-right">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($targets as $item)
                            @php

                                $target = (float) $item->pad_target_nominal;

                                $realisasi = (float) $item->total_realisasi;

                                $sisa = max(0, $target - $realisasi);

                                $persentase = $target > 0 ? ($realisasi / $target) * 100 : 0;
                            @endphp


                            <tr
                                class="border-b
                                   hover:bg-slate-50
                                   transition">


                                {{-- NO --}}

                                <td class="py-4 px-3">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- UNIT --}}

                                <td class="py-4 px-3">

                                    <div class="font-semibold
                                            text-slate-800">

                                        {{ $item->pad_target_unit_nama ?? '-' }}

                                    </div>

                                </td>


                                {{-- JENIS --}}

                                <td class="py-4 px-3">

                                    <div class="font-medium
                                            text-slate-800">

                                        {{ $item->jenis->pad_jenis_nama ?? '-' }}

                                    </div>

                                </td>


                                {{-- KOMPONEN --}}

                                <td class="py-4 px-3">

                                    <div class="font-semibold
                                            text-slate-800">

                                        {{ $item->komponen->pad_komponen_nama ?? '-' }}

                                    </div>

                                    @if (!empty($item->komponen->pad_komponen_kode))
                                        <div
                                            class="text-xs
                                                text-slate-400
                                                mt-1">

                                            {{ $item->komponen->pad_komponen_kode }}

                                        </div>
                                    @endif

                                </td>


                                {{-- TARGET --}}

                                <td class="py-4 px-3">

                                    <span class="font-semibold
                                             text-slate-800">

                                        Rp
                                        {{ number_format($target, 0, ',', '.') }}

                                    </span>

                                </td>


                                {{-- REALISASI --}}

                                <td class="py-4 px-3">

                                    <span class="font-semibold
                                             text-green-600">

                                        Rp
                                        {{ number_format($realisasi, 0, ',', '.') }}

                                    </span>

                                </td>


                                {{-- SISA --}}

                                <td class="py-4 px-3">

                                    <span class="font-medium
                                             text-slate-700">

                                        Rp
                                        {{ number_format($sisa, 0, ',', '.') }}

                                    </span>

                                </td>


                                {{-- PERSENTASE --}}

                                <td class="py-4 px-3">

                                    <div
                                        class="flex items-center
                                            gap-3
                                            min-w-[130px]">

                                        <div
                                            class="flex-1
                                                h-2
                                                rounded-full
                                                bg-slate-100
                                                overflow-hidden">

                                            <div class="h-full
                                                   rounded-full
                                                   bg-blue-600"
                                                style="
                                                width:
                                                {{ min($persentase, 100) }}%;
                                            ">
                                            </div>

                                        </div>

                                        <span
                                            class="text-xs
                                               font-bold
                                               text-slate-700">

                                            {{ number_format($persentase, 1) }}%

                                        </span>

                                    </div>

                                </td>


                                {{-- AKSI --}}

                                <td class="py-4 px-3 text-right">

                                    <a href="{{ route('user.pad.input', $item->pad_target_uid) }}"
                                        class="inline-flex
                                           items-center
                                           gap-2
                                           px-4 py-2
                                           rounded-xl
                                           bg-blue-600
                                           text-white
                                           font-semibold
                                           hover:bg-blue-700
                                           transition">

                                        <i class="bi bi-plus-lg"></i>

                                        Input

                                    </a>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="8"
                                    class="py-12
                                       text-center
                                       text-slate-500">

                                    <div class="text-4xl mb-3">
                                        💰
                                    </div>

                                    <div class="font-semibold
                                            text-slate-700">

                                        Belum Ada Target PAD

                                    </div>

                                    <div class="text-sm mt-1">

                                        Belum ada target PAD
                                        tahun {{ $tahun }}
                                        untuk unit Anda.

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- RIWAYAT PENERIMAAN --}}

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            {{-- HEADER --}}

            <div
                class="flex flex-col lg:flex-row
                lg:items-center
                lg:justify-between
                gap-4 mb-6">

                <div>

                    <h2 class="text-xl font-bold text-slate-900">
                        Riwayat Penerimaan
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Riwayat realisasi penerimaan PAD tahun {{ $tahun }}.
                    </p>

                </div>


                {{-- SEARCH --}}

                <form method="GET" action="{{ route('user.pad.index') }}" class="w-full lg:w-[350px]">

                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <div class="relative">

                        <i
                            class="bi bi-search
                           absolute
                           left-4
                           top-1/2
                           -translate-y-1/2
                           text-slate-400">
                        </i>


                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari unit, komponen, jenis..."
                            class="w-full
                           pl-11 pr-12 py-3
                           rounded-2xl
                           border border-slate-300
                           bg-white
                           text-slate-900
                           placeholder-slate-400
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500">


                        @if ($search)
                            <a href="{{ route('user.pad.index', ['tahun' => $tahun]) }}"
                                class="absolute
                               right-4
                               top-1/2
                               -translate-y-1/2
                               text-slate-400
                               hover:text-red-500">

                                <i class="bi bi-x-circle"></i>

                            </a>
                        @endif

                    </div>

                </form>

            </div>


            {{-- INFO HASIL --}}

            @if ($search)
                <div
                    class="mb-5
                    px-4 py-3
                    rounded-2xl
                    bg-blue-50
                    border border-blue-100
                    text-sm
                    text-blue-700">

                    Menampilkan hasil pencarian:

                    <span class="font-bold">
                        "{{ $search }}"
                    </span>

                    — {{ $riwayat->total() }} data ditemukan.

                </div>
            @endif


            {{-- TABLE --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="border-b text-left text-slate-500">

                            <th class="py-3 px-3">
                                No
                            </th>

                            <th class="py-3 px-3">
                                Tanggal
                            </th>

                            <th class="py-3 px-3">
                                Unit
                            </th>

                            <th class="py-3 px-3">
                                Jenis PAD
                            </th>

                            <th class="py-3 px-3">
                                Komponen
                            </th>

                            <th class="py-3 px-3">
                                Triwulan
                            </th>

                            <th class="py-3 px-3">
                                Nominal
                            </th>

                            <th class="py-3 px-3">
                                Dokumen
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($riwayat as $item)
                            @php

                                $tanggal = \Carbon\Carbon::parse($item->pad_realisasi_tanggal);

                                $triwulan = match (true) {
                                    $tanggal->month <= 3 => 'TW I',

                                    $tanggal->month <= 6 => 'TW II',

                                    $tanggal->month <= 9 => 'TW III',

                                    default => 'TW IV',
                                };

                            @endphp


                            <tr class="border-b hover:bg-slate-50 transition">


                                {{-- NO --}}

                                <td class="py-4 px-3">

                                    {{ $riwayat->firstItem() + $loop->index }}

                                </td>


                                {{-- TANGGAL --}}

                                <td class="py-4 px-3 whitespace-nowrap">

                                    <span class="font-semibold text-slate-800">

                                        {{ $tanggal->format('d/m/Y') }}

                                    </span>

                                </td>


                                {{-- UNIT --}}

                                <td class="py-4 px-3">

                                    <span class="font-semibold text-slate-800">

                                        {{ $item->target->pad_target_unit_nama ?? '-' }}

                                    </span>

                                </td>


                                {{-- JENIS PAD --}}

                                <td class="py-4 px-3">

                                    {{ $item->target->jenis->pad_jenis_nama ?? '-' }}

                                </td>


                                {{-- KOMPONEN --}}

                                <td class="py-4 px-3">

                                    <div class="font-semibold text-slate-800">

                                        {{ $item->target->komponen->pad_komponen_nama ?? '-' }}

                                    </div>


                                    @if ($item->target->komponen->pad_komponen_kode ?? false)
                                        <div class="text-xs text-slate-400 mt-1">

                                            {{ $item->target->komponen->pad_komponen_kode }}

                                        </div>
                                    @endif

                                </td>


                                {{-- TRIWULAN --}}

                                <td class="py-4 px-3">

                                    <span
                                        class="inline-flex
                                       px-3 py-1
                                       rounded-full
                                       bg-blue-50
                                       text-blue-700
                                       font-semibold
                                       text-xs">

                                        {{ $triwulan }}

                                    </span>

                                </td>


                                {{-- NOMINAL --}}

                                <td class="py-4 px-3 whitespace-nowrap">

                                    <span class="font-bold text-green-600">

                                        Rp
                                        {{ number_format($item->pad_realisasi_nominal, 0, ',', '.') }}

                                    </span>

                                </td>


                                {{-- DOKUMEN --}}

                                <td class="py-4 px-3">

                                    @if ($item->pad_realisasi_dokumen)
                                        <a href="{{ $item->pad_realisasi_dokumen }}" target="_blank"
                                            class="inline-flex
                                           items-center
                                           gap-2
                                           px-3 py-2
                                           rounded-xl
                                           bg-red-50
                                           text-red-600
                                           font-semibold
                                           hover:bg-red-100">

                                            <i class="bi bi-file-earmark-pdf"></i>

                                            PDF

                                        </a>
                                    @else
                                        <span class="text-slate-400">
                                            -
                                        </span>
                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="8" class="py-10 text-center text-slate-500">

                                    <div class="text-3xl mb-2">
                                        📋
                                    </div>

                                    @if ($search)
                                        <div class="font-semibold text-slate-700">

                                            Data Tidak Ditemukan

                                        </div>

                                        <div class="text-sm mt-1">

                                            Tidak ada penerimaan yang cocok
                                            dengan pencarian "{{ $search }}".

                                        </div>
                                    @else
                                        <div class="font-semibold text-slate-700">

                                            Belum Ada Riwayat

                                        </div>

                                        <div class="text-sm mt-1">

                                            Belum ada realisasi penerimaan PAD
                                            pada tahun {{ $tahun }}.

                                        </div>
                                    @endif

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if ($riwayat->hasPages())
                <div
                    class="mt-6
                    pt-5
                    border-t border-slate-200
                    flex flex-col
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                    gap-4">

                    <div class="text-sm text-slate-500">

                        Menampilkan

                        <span class="font-semibold text-slate-700">
                            {{ $riwayat->firstItem() }}
                        </span>

                        sampai

                        <span class="font-semibold text-slate-700">
                            {{ $riwayat->lastItem() }}
                        </span>

                        dari

                        <span class="font-semibold text-slate-700">
                            {{ $riwayat->total() }}
                        </span>

                        data

                    </div>


                    <div>

                        {{ $riwayat->links() }}

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection
