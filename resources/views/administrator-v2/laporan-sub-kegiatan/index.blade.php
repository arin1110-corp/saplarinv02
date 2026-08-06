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

    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-12 lg:col-span-3">

            @include('administrator-v2.laporan-sub-kegiatan.partials.tree')

        </div>

        <div class="col-span-12 lg:col-span-9">

            <div id="laporan-detail">

                @include('administrator-v2.laporan-sub-kegiatan.partials.empty')

            </div>

        </div>

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
        function loadLaporan(uid, el) {

            $('.laporan-item')

                .removeClass(

                    'bg-blue-100',

                    'dark:bg-blue-900/20',

                    'font-semibold'

                );

            $(el)

                .addClass(

                    'bg-blue-100',

                    'dark:bg-blue-900/20',

                    'font-semibold'

                );

            $('#laporan-detail').html(`

<div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 h-[700px] flex items-center justify-center">

<div class="text-center">

<div class="animate-spin rounded-full h-14 w-14 border-4 border-blue-600 border-t-transparent mx-auto"></div>

<div class="mt-5 text-slate-500">

Memuat laporan...

</div>

</div>

</div>

`);

            $.get(

                "{{ route('admin.laporan-sub-kegiatan.detail', 'UID') }}"

                .replace('UID', uid),

                function(html) {

                    $('#laporan-detail').html(html);

                }

            );

        }

        $('#laporan-detail').html(`

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 h-[700px] flex items-center justify-center">

            <div class="text-center">

                <div class="animate-spin rounded-full h-14 w-14 border-4 border-blue-600 border-t-transparent mx-auto"></div>

                <div class="mt-5 text-slate-500">

                    Memuat laporan...

                </div>

            </div>

        </div>

    `);

        $.get(
            "{{ route('admin.laporan-sub-kegiatan.detail', 'UID') }}"
            .replace('UID', uid),

            function(html) {

                $('#laporan-detail').html(html);

                window.scrollTo({

                    top: 0,

                    behavior: 'smooth'

                });

            }

        );

        
    </script>
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
