@extends('user.layouts.app')

@section('title', 'Edit Penerimaan PAD')
@section('page_title', 'Edit Penerimaan PAD')
@section('breadcrumb', 'Penerimaan PAD')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- ERROR --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div
                class="bg-red-50
                    border border-red-200
                    text-red-700
                    px-5 py-4
                    rounded-2xl">

                <div class="font-semibold mb-2">
                    Terdapat kesalahan:
                </div>

                <ul class="list-disc pl-5 space-y-1 text-sm">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div
            class="flex flex-col sm:flex-row
                sm:items-center
                sm:justify-between
                gap-4">

            <div>

                <h1 class="text-2xl font-bold text-slate-900">
                    Edit Penerimaan PAD
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Perbarui data realisasi penerimaan PAD.
                </p>

            </div>


            <a href="{{ route('user.pad.index', ['tahun' => $target->pad_target_tahun]) }}"
                class="inline-flex items-center
                   justify-center gap-2
                   px-5 py-3
                   rounded-2xl
                   bg-slate-100
                   hover:bg-slate-200
                   text-slate-700
                   font-semibold
                   transition">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMASI TARGET --}}
        {{-- ========================================================= --}}

        <div
            class="bg-white
                rounded-3xl
                border border-slate-200
                shadow-sm
                overflow-hidden">

            {{-- HEADER CARD --}}

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-xl font-bold text-slate-900">
                    Target PAD
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Informasi target penerimaan yang dipilih.
                </p>

            </div>


            {{-- CONTENT --}}

            <div class="p-6">

                <div
                    class="grid grid-cols-1
                        sm:grid-cols-2
                        lg:grid-cols-4
                        gap-6">


                    {{-- TAHUN --}}

                    <div>

                        <div class="text-sm text-slate-500">
                            Tahun
                        </div>

                        <div class="font-semibold text-slate-900 mt-1">

                            {{ $target->pad_target_tahun }}

                        </div>

                    </div>


                    {{-- JENIS --}}

                    <div>

                        <div class="text-sm text-slate-500">
                            Jenis PAD
                        </div>

                        <div class="font-semibold text-slate-900 mt-1">

                            {{ $target->jenis->pad_jenis_nama ?? '-' }}

                        </div>

                    </div>


                    {{-- KOMPONEN --}}

                    <div>

                        <div class="text-sm text-slate-500">
                            Komponen
                        </div>

                        <div class="font-semibold text-slate-900 mt-1">

                            {{ $target->komponen->pad_komponen_nama ?? '-' }}

                        </div>

                        @if ($target->komponen?->pad_komponen_kode)
                            <div class="text-xs text-slate-400 mt-1">

                                {{ $target->komponen->pad_komponen_kode }}

                            </div>
                        @endif

                    </div>


                    {{-- UNIT --}}

                    <div>

                        <div class="text-sm text-slate-500">
                            Unit
                        </div>

                        <div class="font-semibold text-slate-900 mt-1">

                            {{ $target->pad_target_unit_nama ?? '-' }}

                        </div>

                        @if ($target->pad_target_unit_kode)
                            <div class="text-xs text-slate-400 mt-1">

                                {{ $target->pad_target_unit_kode }}

                            </div>
                        @endif

                    </div>

                </div>


                {{-- TARGET NOMINAL --}}

                <div
                    class="mt-6
                        rounded-2xl
                        bg-slate-50
                        border border-slate-200
                        p-5">

                    <div
                        class="flex flex-col
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-3">

                        <div>

                            <div class="text-sm text-slate-500">
                                Target Penerimaan
                            </div>

                            <div class="text-xs text-slate-400 mt-1">
                                Target tahunan yang ditetapkan admin.
                            </div>

                        </div>


                        <div class="text-xl font-bold text-slate-900">

                            Rp
                            {{ number_format($target->pad_target_nominal, 0, ',', '.') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FORM EDIT REALISASI --}}
        {{-- ========================================================= --}}

        <div
            class="bg-white
                rounded-3xl
                border border-slate-200
                shadow-sm
                overflow-hidden">


            {{-- HEADER --}}

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-xl font-bold text-slate-900">
                    Edit Data Realisasi
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Perbarui data penerimaan PAD yang telah direalisasikan.
                </p>

            </div>


            {{-- FORM --}}

            <form method="POST" action="{{ route('user.pad.update', $realisasi->pad_realisasi_uid) }}"
                enctype="multipart/form-data">

                @csrf

                @method('PUT')


                <div class="p-6 space-y-6">


                    {{-- ================================================= --}}
                    {{-- SUBKOMPONEN --}}
                    {{-- ================================================= --}}

                    <div>

                        <label for="pad_realisasi_subkomponen"
                            class="block text-sm
                               font-semibold
                               text-slate-700
                               mb-2">

                            Subkomponen

                            <span class="text-red-500">
                                *
                            </span>

                        </label>


                        <select name="pad_realisasi_subkomponen" id="pad_realisasi_subkomponen" required
                            class="w-full
                               rounded-2xl
                               border border-slate-300
                               bg-white
                               text-slate-900
                               px-4 py-3

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500

                               outline-none">

                            <option value="">
                                Pilih Subkomponen
                            </option>


                            @foreach ($subkomponen as $item)
                                <option value="{{ $item->pad_subkomponen_id }}" @selected(old('pad_realisasi_subkomponen', $realisasi->pad_realisasi_subkomponen) == $item->pad_subkomponen_id)>

                                    {{ $item->pad_subkomponen_kode ? $item->pad_subkomponen_kode . ' - ' : '' }}

                                    {{ $item->pad_subkomponen_nama }}

                                </option>
                            @endforeach

                        </select>


                        <p class="text-xs text-slate-500 mt-2">

                            Pilih subkomponen sesuai dengan penerimaan
                            yang direalisasikan.

                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TANGGAL --}}
                    {{-- ================================================= --}}

                    <div>

                        <label for="pad_realisasi_tanggal"
                            class="block text-sm
                               font-semibold
                               text-slate-700
                               mb-2">

                            Tanggal Penerimaan

                            <span class="text-red-500">
                                *
                            </span>

                        </label>


                        <input type="date" name="pad_realisasi_tanggal" id="pad_realisasi_tanggal"
                            value="{{ old('pad_realisasi_tanggal', \Carbon\Carbon::parse($realisasi->pad_realisasi_tanggal)->format('Y-m-d')) }}"
                            min="{{ $target->pad_target_tahun }}-01-01" max="{{ $target->pad_target_tahun }}-12-31"
                            required
                            class="w-full
                               rounded-2xl
                               border border-slate-300
                               bg-white
                               text-slate-900
                               px-4 py-3

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500

                               outline-none">


                        <p class="text-xs text-slate-500 mt-2">

                            Triwulan akan dihitung otomatis berdasarkan
                            tanggal penerimaan.

                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- NOMINAL --}}
                    {{-- ================================================= --}}

                    <div>

                        <label for="pad_realisasi_nominal"
                            class="block text-sm
                               font-semibold
                               text-slate-700
                               mb-2">

                            Nominal Penerimaan

                            <span class="text-red-500">
                                *
                            </span>

                        </label>


                        <div class="relative">

                            <span
                                class="absolute
                                   left-4
                                   top-1/2
                                   -translate-y-1/2
                                   text-slate-500
                                   font-medium">

                                Rp

                            </span>


                            <input type="text" name="pad_realisasi_nominal" id="pad_realisasi_nominal"
                                value="{{ old('pad_realisasi_nominal', number_format($realisasi->pad_realisasi_nominal, 0, ',', '.')) }}"
                                placeholder="0" inputmode="numeric" autocomplete="off" required
                                class="w-full
                                   rounded-2xl
                                   border border-slate-300
                                   bg-white
                                   text-slate-900
                                   pl-12 pr-4 py-3

                                   focus:ring-2
                                   focus:ring-blue-500

                                   focus:border-blue-500

                                   outline-none">

                        </div>


                        <p class="text-xs text-slate-500 mt-2">

                            Contoh:
                            <span class="font-semibold">
                                175.319.200
                            </span>

                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- KETERANGAN --}}
                    {{-- ================================================= --}}

                    <div>

                        <label for="pad_realisasi_keterangan"
                            class="block text-sm
                               font-semibold
                               text-slate-700
                               mb-2">

                            Keterangan

                        </label>


                        <textarea name="pad_realisasi_keterangan" id="pad_realisasi_keterangan" rows="4"
                            placeholder="Keterangan penerimaan..."
                            class="w-full
                               rounded-2xl
                               border border-slate-300
                               bg-white
                               text-slate-900
                               px-4 py-3

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500

                               outline-none
                               resize-none">{{ old('pad_realisasi_keterangan', $realisasi->pad_realisasi_keterangan) }}</textarea>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DOKUMEN LAMA --}}
                    {{-- ================================================= --}}

                    @if ($realisasi->pad_realisasi_dokumen)
                        <div>

                            <label
                                class="block text-sm
                                   font-semibold
                                   text-slate-700
                                   mb-2">

                                Dokumen Saat Ini

                            </label>


                            <div
                                class="flex items-center
                                   justify-between
                                   gap-4

                                   rounded-2xl

                                   border border-red-100

                                   bg-red-50

                                   px-5 py-4">

                                <div
                                    class="flex items-center
                                       gap-4
                                       min-w-0">

                                    <div
                                        class="w-11 h-11
                                           rounded-xl
                                           bg-white
                                           flex
                                           items-center
                                           justify-center
                                           shrink-0">

                                        <i
                                            class="bi bi-file-earmark-pdf
                                               text-xl
                                               text-red-600">
                                        </i>

                                    </div>


                                    <div class="min-w-0">

                                        <div
                                            class="font-semibold
                                               text-slate-800">

                                            Dokumen PDF tersimpan

                                        </div>

                                        <div
                                            class="text-xs
                                               text-slate-500
                                               mt-1">

                                            Dokumen lama akan tetap digunakan
                                            jika tidak mengganti file.

                                        </div>

                                    </div>

                                </div>


                                <a href="{{ $realisasi->pad_realisasi_dokumen }}" target="_blank"
                                    class="inline-flex
                                       items-center
                                       gap-2

                                       px-4 py-2

                                       rounded-xl

                                       bg-white

                                       text-red-600

                                       font-semibold

                                       hover:bg-red-100

                                       transition

                                       shrink-0">

                                    <i class="bi bi-eye"></i>

                                    Lihat

                                </a>

                            </div>

                        </div>
                    @endif


                    {{-- ================================================= --}}
                    {{-- DOKUMEN BARU --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            class="block text-sm
                               font-semibold
                               text-slate-700
                               mb-2">

                            {{ $realisasi->pad_realisasi_dokumen ? 'Ganti Dokumen Pendukung' : 'Dokumen Pendukung' }}

                        </label>


                        <label for="pad_realisasi_dokumen"
                            class="flex items-center
                               gap-4

                               w-full

                               rounded-2xl

                               border border-slate-300

                               bg-slate-50

                               px-5 py-4

                               cursor-pointer

                               hover:bg-slate-100

                               transition">


                            {{-- ICON --}}

                            <div
                                class="w-11 h-11
                                   rounded-xl

                                   bg-red-50

                                   flex
                                   items-center
                                   justify-center

                                   shrink-0">

                                <i
                                    class="bi bi-file-earmark-pdf
                                       text-xl
                                       text-red-600">
                                </i>

                            </div>


                            {{-- INFO --}}

                            <div class="min-w-0 flex-1">

                                <div id="fileLabel"
                                    class="font-semibold
                                       text-slate-800
                                       truncate">

                                    Pilih Dokumen PDF

                                </div>


                                <div id="fileInfo"
                                    class="text-xs
                                       text-slate-500
                                       mt-1">

                                    PDF maksimal 5 MB.
                                    Kosongkan jika tidak ingin mengganti dokumen.

                                </div>

                            </div>


                            {{-- INPUT --}}

                            <input type="file" name="pad_realisasi_dokumen" id="pad_realisasi_dokumen"
                                accept=".pdf,application/pdf" class="hidden" onchange="updateFileName(this)">

                        </label>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}

                <div
                    class="px-6 py-5
                       border-t
                       border-slate-200

                       flex
                       flex-col-reverse
                       sm:flex-row

                       sm:justify-end

                       gap-3">


                    <a href="{{ route('user.pad.index', ['tahun' => $target->pad_target_tahun]) }}"
                        class="inline-flex
                           items-center
                           justify-center
                           gap-2

                           px-5 py-3

                           rounded-2xl

                           bg-slate-100
                           hover:bg-slate-200

                           text-slate-700

                           font-semibold

                           transition">

                        <i class="bi bi-x-lg"></i>

                        Batal

                    </a>


                    <button type="submit"
                        class="inline-flex
                           items-center
                           justify-center
                           gap-2

                           px-5 py-3

                           rounded-2xl

                           bg-amber-500
                           hover:bg-amber-600

                           text-white

                           font-semibold

                           transition">

                        <i class="bi bi-check-lg"></i>

                        Update Penerimaan

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>
        /*
            |--------------------------------------------------------------------------
            | FILE PDF
            |--------------------------------------------------------------------------
            */

        function updateFileName(input) {

            const label =
                document.getElementById('fileLabel');

            const info =
                document.getElementById('fileInfo');


            if (
                input.files &&
                input.files.length > 0
            ) {

                const file =
                    input.files[0];


                label.textContent =
                    file.name;


                info.textContent =
                    `${(
                        file.size / 1024 / 1024
                    ).toFixed(2)} MB`;


                label.classList.remove(
                    'text-slate-800'
                );


                label.classList.add(
                    'text-blue-600'
                );

            } else {

                label.textContent =
                    'Pilih Dokumen PDF';


                info.textContent =
                    'PDF maksimal 5 MB. Kosongkan jika tidak ingin mengganti dokumen.';


                label.classList.remove(
                    'text-blue-600'
                );


                label.classList.add(
                    'text-slate-800'
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | FORMAT NOMINAL RUPIAH
        |--------------------------------------------------------------------------
        */

        const nominalInput =
            document.getElementById(
                'pad_realisasi_nominal'
            );


        function formatRupiah(value) {

            value =
                value.replace(
                    /\D/g,
                    ''
                );


            if (!value) {

                return '';

            }


            return new Intl.NumberFormat(
                'id-ID'
            ).format(value);

        }


        if (nominalInput) {

            nominalInput.addEventListener(
                'input',
                function() {

                    this.value =
                        formatRupiah(
                            this.value
                        );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SEBELUM SUBMIT
        |--------------------------------------------------------------------------
        |
        | 175.319.200
        |        ↓
        | 175319200
        |
        */

        const form =
            document.querySelector(
                'form[action*="/pad/"]'
            );


        if (form && nominalInput) {

            form.addEventListener(
                'submit',
                function() {

                    nominalInput.value =
                        nominalInput.value.replace(
                            /\./g,
                            ''
                        );

                }
            );

        }
    </script>

@endsection
