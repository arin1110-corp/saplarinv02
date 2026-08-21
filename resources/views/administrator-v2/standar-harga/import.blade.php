@extends('administrator-v2.layouts.app')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>

        <a href="{{ route('admin.standar-harga.index') }}"
           class="inline-flex items-center gap-2
                  text-sm text-slate-500 hover:text-blue-600 mb-4">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">

            Import Standar Harga

        </h1>

        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">

            Import data SSH atau ASB dari Excel.

        </p>

    </div>


    {{-- FORM --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-800 p-6">

        <form method="POST"
              action="{{ route('admin.standar-harga.import.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf


            {{-- TAHUN --}}
            <div>

                <label class="block text-sm font-semibold
                              text-slate-700 dark:text-slate-300 mb-2">

                    Tahun

                </label>

                <select name="standar_harga_tahun"
                        required

                        class="w-full rounded-xl border border-slate-300
                               dark:border-slate-700
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               px-4 py-3">

                    @for($i = now()->year + 1; $i >= 2020; $i--)

                        <option value="{{ $i }}"
                            {{ $i == now()->year ? 'selected' : '' }}>

                            {{ $i }}

                        </option>

                    @endfor

                </select>

                @error('standar_harga_tahun')

                    <p class="text-sm text-red-600 mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- JENIS --}}
            <div>

                <label class="block text-sm font-semibold
                              text-slate-700 dark:text-slate-300 mb-2">

                    Jenis Standar

                </label>

                <div class="grid grid-cols-2 gap-3">

                    <label class="cursor-pointer">

                        <input type="radio"
                               name="standar_harga_jenis"
                               value="SSH"
                               class="peer sr-only"
                               checked>

                        <div class="rounded-xl border-2
                                    border-slate-200 dark:border-slate-700
                                    peer-checked:border-blue-600
                                    peer-checked:bg-blue-50
                                    dark:peer-checked:bg-blue-900/20
                                    p-4 transition">

                            <div class="font-bold text-slate-900
                                        dark:text-white">

                                SSH

                            </div>

                            <div class="text-xs text-slate-500 mt-1">

                                Standar Satuan Harga

                            </div>

                        </div>

                    </label>


                    <label class="cursor-pointer">

                        <input type="radio"
                               name="standar_harga_jenis"
                               value="ASB"
                               class="peer sr-only">

                        <div class="rounded-xl border-2
                                    border-slate-200 dark:border-slate-700
                                    peer-checked:border-purple-600
                                    peer-checked:bg-purple-50
                                    dark:peer-checked:bg-purple-900/20
                                    p-4 transition">

                            <div class="font-bold text-slate-900
                                        dark:text-white">

                                ASB

                            </div>

                            <div class="text-xs text-slate-500 mt-1">

                                Analisis Standar Belanja

                            </div>

                        </div>

                    </label>

                </div>

            </div>


            {{-- FILE --}}
            <div>

                <label class="block text-sm font-semibold
                              text-slate-700 dark:text-slate-300 mb-2">

                    File Excel

                </label>

                <input type="file"
                       name="file"
                       accept=".xlsx,.xls"
                       required

                       class="block w-full rounded-xl border
                              border-slate-300 dark:border-slate-700
                              bg-white dark:bg-slate-800
                              text-slate-700 dark:text-slate-300
                              file:mr-4 file:py-3 file:px-4
                              file:border-0
                              file:bg-slate-100
                              dark:file:bg-slate-700
                              file:text-slate-700
                              dark:file:text-slate-200">

                <p class="text-xs text-slate-500 mt-2">

                    Format: .xlsx atau .xls. Maksimal 20 MB.

                </p>

                @error('file')

                    <p class="text-sm text-red-600 mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- FORMAT --}}
            <div class="rounded-xl bg-slate-50
                        dark:bg-slate-800/50 p-4">

                <p class="font-semibold text-sm
                          text-slate-800 dark:text-slate-200 mb-2">

                    Header Excel

                </p>

                <p class="text-xs text-slate-500 dark:text-slate-400 leading-6">

                    NO,
                    KODE KELOMPOK BARANG,
                    URAIAN KELOMPOK BARANG,
                    ID STANDAR HARGA,
                    KODE BARANG,
                    URAIAN BARANG,
                    SPESIFIKASI,
                    SATUAN,
                    HARGA SATUAN,
                    KODE REKENING

                </p>

            </div>


            {{-- BUTTON --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('admin.standar-harga.index') }}"
                   class="px-5 py-3 rounded-xl
                          border border-slate-300
                          dark:border-slate-700
                          text-slate-700 dark:text-slate-300">

                    Batal

                </a>

                <button type="submit"
                        class="px-6 py-3 rounded-xl
                               bg-blue-600 hover:bg-blue-700
                               text-white font-semibold">

                    <i class="bi bi-upload mr-1"></i>

                    Import Data

                </button>

            </div>

        </form>

    </div>

</div>

@endsection