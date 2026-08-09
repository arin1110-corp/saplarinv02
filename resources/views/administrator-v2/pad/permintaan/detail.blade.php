@extends('administrator-v2.layouts.app')

@section('title', 'Detail Penerimaan PAD')
@section('page_title', 'Detail Penerimaan PAD')
@section('breadcrumb', 'Detail Penerimaan PAD')

@section('content')

    <div class="max-w-5xl space-y-6">


        {{-- BACK --}}

        <a href="{{ route('admin.pad.permintaan.index') }}"
            class="inline-flex
               items-center
               gap-2

               text-sm
               font-semibold

               text-slate-600
               dark:text-slate-400

               hover:text-blue-600">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>



        {{-- ========================================================= --}}
        {{-- DETAIL --}}
        {{-- ========================================================= --}}

        <div
            class="bg-white
                dark:bg-slate-900

                border
                border-slate-200
                dark:border-slate-800

                rounded-3xl
                overflow-hidden">


            <div
                class="px-6 py-5
                    border-b
                    border-slate-200
                    dark:border-slate-800">

                <div
                    class="flex
                        flex-col
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                        gap-3">

                    <div>

                        <h2
                            class="text-xl
                               font-bold
                               text-slate-900
                               dark:text-white">

                            Detail Penerimaan PAD

                        </h2>

                        <p
                            class="text-sm
                              text-slate-500
                              dark:text-slate-400
                              mt-1">

                            Data realisasi yang dimasukkan operator.

                        </p>

                    </div>


                    @if ($permintaan->pad_realisasi_status === 'Diterima')
                        <span
                            class="inline-flex
                                 items-center
                                 gap-2

                                 px-4 py-2

                                 rounded-full

                                 bg-green-100
                                 dark:bg-green-900/30

                                 text-green-700
                                 dark:text-green-400

                                 text-sm
                                 font-semibold">

                            <i class="bi bi-check-circle"></i>

                            Diterima

                        </span>
                    @endif

                </div>

            </div>



            <div class="p-6">

                <div
                    class="grid
                        grid-cols-1
                        md:grid-cols-2
                        gap-6">


                    {{-- TAHUN --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Tahun

                        </div>

                        <div
                            class="font-semibold
                                text-slate-900
                                dark:text-white
                                mt-1">

                            {{ $permintaan->target->pad_target_tahun }}

                        </div>

                    </div>


                    {{-- TANGGAL --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Tanggal Penerimaan

                        </div>

                        <div
                            class="font-semibold
                                text-slate-900
                                dark:text-white
                                mt-1">

                            {{ \Carbon\Carbon::parse($permintaan->pad_realisasi_tanggal)->translatedFormat('d F Y') }}

                        </div>

                    </div>


                    {{-- JENIS --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Jenis PAD

                        </div>

                        <div
                            class="font-semibold
                                text-slate-900
                                dark:text-white
                                mt-1">

                            {{ $permintaan->target->jenis->pad_jenis_nama ?? '-' }}

                        </div>

                    </div>


                    {{-- KOMPONEN --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Komponen

                        </div>

                        <div
                            class="font-semibold
                                text-slate-900
                                dark:text-white
                                mt-1">

                            {{ $permintaan->target->komponen->pad_komponen_nama ?? '-' }}

                        </div>

                    </div>


                    {{-- SUBKOMPONEN --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Subkomponen

                        </div>

                        <div
                            class="font-semibold
                                text-slate-900
                                dark:text-white
                                mt-1">

                            {{ $permintaan->subkomponen->pad_subkomponen_nama ?? '-' }}

                        </div>

                        @if ($permintaan->subkomponen && $permintaan->subkomponen->pad_subkomponen_kode)
                            <div
                                class="text-xs
                                    text-slate-400
                                    mt-1">

                                {{ $permintaan->subkomponen->pad_subkomponen_kode }}

                            </div>
                        @endif

                    </div>


                    {{-- UNIT --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Unit

                        </div>

                        <div
                            class="font-semibold
                                text-slate-900
                                dark:text-white
                                mt-1">

                            {{ $permintaan->target->pad_target_unit_nama ?? '-' }}

                        </div>

                    </div>


                    {{-- PENGINPUT --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Penginput

                        </div>

                        <div
                            class="font-semibold
                                text-slate-900
                                dark:text-white
                                mt-1">

                            {{ $permintaan->pad_realisasi_input_nama }}

                        </div>

                        <div
                            class="text-xs
                                text-slate-400
                                mt-1">

                            {{ $permintaan->pad_realisasi_input_nip }}

                        </div>

                    </div>


                    {{-- NOMINAL --}}

                    <div>

                        <div
                            class="text-sm
                                text-slate-500
                                dark:text-slate-400">

                            Nominal Penerimaan

                        </div>

                        <div
                            class="text-2xl
                                font-bold
                                text-green-600
                                mt-1">

                            Rp
                            {{ number_format($permintaan->pad_realisasi_nominal, 0, ',', '.') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- KETERANGAN --}}
        {{-- ========================================================= --}}

        <div
            class="bg-white
                dark:bg-slate-900

                border
                border-slate-200
                dark:border-slate-800

                rounded-3xl
                p-6">

            <div
                class="text-sm
                    text-slate-500
                    dark:text-slate-400
                    mb-2">

                Keterangan

            </div>


            <div
                class="rounded-2xl

                    bg-slate-50
                    dark:bg-slate-800

                    p-5

                    text-slate-800
                    dark:text-slate-200">

                {!! nl2br(e($permintaan->pad_realisasi_keterangan ?: '-')) !!}

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- DOKUMEN --}}
        {{-- ========================================================= --}}

        <div
            class="bg-white
                dark:bg-slate-900

                border
                border-slate-200
                dark:border-slate-800

                rounded-3xl
                p-6">

            <div
                class="text-sm
                    text-slate-500
                    dark:text-slate-400
                    mb-3">

                Dokumen Pendukung

            </div>


            @if ($permintaan->pad_realisasi_dokumen)
                <a href="{{ $permintaan->pad_realisasi_dokumen }}" target="_blank"
                    class="inline-flex
                       items-center
                       gap-3

                       px-5 py-3

                       rounded-2xl

                       bg-red-50
                       dark:bg-red-900/20

                       text-red-600
                       dark:text-red-400

                       font-semibold

                       hover:bg-red-100
                       dark:hover:bg-red-900/30">

                    <i class="bi bi-file-earmark-pdf text-xl"></i>

                    Lihat Dokumen PDF

                </a>
            @else
                <div class="text-slate-400
                        dark:text-slate-500">

                    Tidak ada dokumen pendukung.

                </div>
            @endif

        </div>

    </div>

@endsection
