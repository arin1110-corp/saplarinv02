@extends('administrator-v2.layouts.app')

@section('title', 'Kelola Ruang Rapat')
@section('page_title', 'Kelola Ruang Rapat')
@section('breadcrumb', 'Kelola Ruang Rapat')


@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div
            class="bg-white dark:bg-slate-900
                rounded-3xl
                border border-slate-200 dark:border-slate-700
                shadow-sm p-6">

            <div class="flex flex-col lg:flex-row
                    lg:items-center lg:justify-between gap-5">

                <div>

                    <h2 class="text-2xl font-bold
                           text-slate-900 dark:text-white">

                        Kelola Ruang Rapat

                    </h2>

                    <p class="text-slate-500 dark:text-slate-400 mt-1">

                        Kelola data ruang yang tersedia untuk booking.

                    </p>

                </div>


                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('admin.booking-ruang.dashboard') }}"
                        class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-slate-800 hover:bg-slate-900
                           dark:bg-slate-700 dark:hover:bg-slate-600
                           text-white font-semibold">

                        <i class="bi bi-calendar3"></i>

                        Kalender Booking

                    </a>


                    <a href="{{ route('admin.booking-ruang.pengajuan') }}"
                        class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-slate-100 hover:bg-slate-200
                           dark:bg-slate-800 dark:hover:bg-slate-700
                           text-slate-700 dark:text-slate-200
                           font-semibold">

                        <i class="bi bi-list-check"></i>

                        Detail Pengajuan

                    </a>


                    <button onclick="openRuangModal()"
                        class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-blue-600 hover:bg-blue-700
                           text-white font-semibold">

                        <i class="bi bi-plus-lg"></i>

                        Tambah Ruang

                    </button>

                </div>

            </div>

        </div>


        {{-- FLASH --}}
        @if (session('success'))
            <div
                class="rounded-2xl
                    bg-green-50 dark:bg-green-900/20
                    border border-green-200 dark:border-green-800
                    text-green-700 dark:text-green-300
                    px-5 py-4">

                <div class="flex items-center gap-3">

                    <i class="bi bi-check-circle-fill"></i>

                    {{ session('success') }}

                </div>

            </div>
        @endif


        @if (session('error'))
            <div
                class="rounded-2xl
                    bg-red-50 dark:bg-red-900/20
                    border border-red-200 dark:border-red-800
                    text-red-700 dark:text-red-300
                    px-5 py-4">

                <div class="flex items-center gap-3">

                    <i class="bi bi-exclamation-circle-fill"></i>

                    {{ session('error') }}

                </div>

            </div>
        @endif


        {{-- RUANG --}}
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">

            @forelse ($ruangs as $ruang)
                <div
                    class="bg-white dark:bg-slate-900
                        rounded-3xl
                        border border-slate-200 dark:border-slate-700
                        shadow-sm
                        p-6
                        flex flex-col">

                    {{-- TOP --}}
                    <div class="flex items-start
                            justify-between gap-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-12 h-12 rounded-2xl
                                    bg-blue-50 dark:bg-blue-900/30
                                    text-blue-600 dark:text-blue-400
                                    flex items-center justify-center">

                                <i class="bi bi-building text-xl"></i>

                            </div>


                            <div>

                                <h3 class="font-bold
                                       text-slate-900 dark:text-white">

                                    {{ $ruang->ruang_nama }}

                                </h3>

                                <p class="text-sm
                                      text-slate-500 dark:text-slate-400">

                                    {{ $ruang->ruang_lokasi ?? '-' }}

                                </p>

                            </div>

                        </div>


                        @if ($ruang->ruang_status)
                            <span
                                class="shrink-0
                                     inline-flex items-center gap-1.5
                                     px-3 py-1.5 rounded-full
                                     bg-green-100 dark:bg-green-900/30
                                     text-green-700 dark:text-green-300
                                     text-xs font-semibold">

                                <span
                                    class="w-1.5 h-1.5 rounded-full
                                         bg-green-500"></span>

                                Aktif

                            </span>
                        @else
                            <span
                                class="shrink-0
                                     inline-flex items-center gap-1.5
                                     px-3 py-1.5 rounded-full
                                     bg-red-100 dark:bg-red-900/30
                                     text-red-700 dark:text-red-300
                                     text-xs font-semibold">

                                <span
                                    class="w-1.5 h-1.5 rounded-full
                                         bg-red-500"></span>

                                Nonaktif

                            </span>
                        @endif

                    </div>


                    {{-- INFO --}}
                    <div class="mt-6 space-y-4">

                        <div>

                            <div
                                class="text-xs font-semibold
                                    uppercase tracking-wide
                                    text-slate-400 dark:text-slate-500">

                                Kapasitas

                            </div>

                            <div
                                class="mt-1 font-semibold
                                    text-slate-800 dark:text-slate-200">

                                <i class="bi bi-people me-1"></i>

                                {{ $ruang->ruang_kapasitas ?? '-' }}

                                orang

                            </div>

                        </div>


                        <div>

                            <div
                                class="text-xs font-semibold
                                    uppercase tracking-wide
                                    text-slate-400 dark:text-slate-500">

                                Fasilitas

                            </div>

                            <div
                                class="mt-1
                                    text-sm
                                    text-slate-600 dark:text-slate-300">

                                {{ $ruang->ruang_fasilitas ?: '-' }}

                            </div>

                        </div>

                    </div>


                    {{-- ACTION --}}
                    <div
                        class="mt-6 pt-5
                            border-t border-slate-200
                            dark:border-slate-700
                            flex gap-2">

                        <button
                            onclick="editRuang(
                            '{{ $ruang->ruang_id }}',
                            @js($ruang->ruang_nama),
                            @js($ruang->ruang_lokasi),
                            @js($ruang->ruang_kapasitas),
                            @js($ruang->ruang_fasilitas),
                            {{ $ruang->ruang_status ? 1 : 0 }}
                        )"
                            class="flex-1
                               inline-flex items-center
                               justify-center gap-2
                               px-4 py-2.5 rounded-xl
                               bg-blue-50 dark:bg-blue-900/30
                               text-blue-700 dark:text-blue-300
                               font-semibold
                               hover:bg-blue-100
                               dark:hover:bg-blue-900/50">

                            <i class="bi bi-pencil"></i>

                            Edit

                        </button>


                        <form action="{{ route('admin.booking-ruang.ruang.status', $ruang->ruang_id) }}" method="POST">

                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="px-4 py-2.5 rounded-xl
                                   bg-slate-100 dark:bg-slate-800
                                   text-slate-700 dark:text-slate-300
                                   font-semibold
                                   hover:bg-slate-200
                                   dark:hover:bg-slate-700">

                                @if ($ruang->ruang_status)
                                    <i class="bi bi-pause-circle"></i>
                                @else
                                    <i class="bi bi-play-circle"></i>
                                @endif

                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="md:col-span-2 xl:col-span-3">

                    <div
                        class="bg-white dark:bg-slate-900
                            rounded-3xl
                            border border-slate-200 dark:border-slate-700
                            p-12 text-center">

                        <div
                            class="w-16 h-16 mx-auto
                                rounded-2xl
                                bg-slate-100 dark:bg-slate-800
                                flex items-center justify-center
                                text-slate-400">

                            <i class="bi bi-building text-2xl"></i>

                        </div>

                        <h3 class="mt-5 font-bold
                               text-slate-800 dark:text-slate-200">

                            Belum ada ruang

                        </h3>

                        <p class="text-sm
                              text-slate-500 dark:text-slate-400 mt-1">

                            Tambahkan ruang rapat terlebih dahulu.

                        </p>

                    </div>

                </div>
            @endforelse

        </div>

    </div>


    {{-- MODAL --}}
    <div id="ruangModal"
        class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/60 backdrop-blur-sm
           p-4 overflow-y-auto">

        <div
            class="relative w-full max-w-xl
                max-h-[90vh]
                bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-700
                rounded-3xl shadow-2xl
                flex flex-col overflow-hidden">

            {{-- HEADER --}}
            <div
                class="flex items-center justify-between
                    px-6 py-5 shrink-0
                    border-b border-slate-200
                    dark:border-slate-700">

                <div>

                    <h2 id="ruangModalTitle"
                        class="text-xl font-bold
                           text-slate-900 dark:text-white">

                        Tambah Ruang

                    </h2>

                    <p class="text-sm
                          text-slate-500 dark:text-slate-400">

                        Isi informasi ruang rapat.

                    </p>

                </div>


                <button type="button" onclick="closeRuangModal()"
                    class="w-10 h-10 rounded-xl
                       hover:bg-slate-100 dark:hover:bg-slate-800
                       text-slate-600 dark:text-slate-300">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            {{-- FORM --}}
            <form id="ruangForm" method="POST" class="flex flex-col min-h-0">

                @csrf

                <div class="overflow-y-auto p-6 space-y-5">

                    <input type="hidden" name="_method" id="ruangMethod" value="POST">


                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">

                            Nama Ruang

                        </label>

                        <input type="text" name="ruang_nama" id="ruang_nama" required
                            class="w-full rounded-xl
                               border-slate-300 dark:border-slate-600
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               placeholder:text-slate-400
                               focus:border-blue-500
                               focus:ring-blue-500">

                    </div>


                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">

                            Lokasi

                        </label>

                        <input type="text" name="ruang_lokasi" id="ruang_lokasi"
                            class="w-full rounded-xl
                               border-slate-300 dark:border-slate-600
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               placeholder:text-slate-400
                               focus:border-blue-500
                               focus:ring-blue-500">

                    </div>


                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">

                            Kapasitas

                        </label>

                        <input type="number" name="ruang_kapasitas" id="ruang_kapasitas" min="1"
                            class="w-full rounded-xl
                               border-slate-300 dark:border-slate-600
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               placeholder:text-slate-400
                               focus:border-blue-500
                               focus:ring-blue-500">

                    </div>


                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">

                            Fasilitas

                        </label>

                        <textarea name="ruang_fasilitas" id="ruang_fasilitas" rows="4"
                            placeholder="Contoh: AC, Proyektor, TV, Whiteboard..."
                            class="w-full rounded-xl
                               border-slate-300 dark:border-slate-600
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               placeholder:text-slate-400
                               focus:border-blue-500
                               focus:ring-blue-500"></textarea>

                    </div>


                    <div>

                        <label
                            class="block text-sm font-semibold
                                  text-slate-700 dark:text-slate-300 mb-2">

                            Status

                        </label>

                        <select name="ruang_status" id="ruang_status"
                            class="w-full rounded-xl
                               border-slate-300 dark:border-slate-600
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               focus:border-blue-500
                               focus:ring-blue-500">

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div
                    class="shrink-0
                        border-t border-slate-200
                        dark:border-slate-700
                        px-6 py-5
                        flex justify-end gap-3">

                    <button type="button" onclick="closeRuangModal()"
                        class="px-5 py-3 rounded-xl
                           bg-slate-100 dark:bg-slate-800
                           text-slate-700 dark:text-slate-300
                           font-semibold
                           hover:bg-slate-200
                           dark:hover:bg-slate-700">

                        Batal

                    </button>


                    <button type="submit"
                        class="px-5 py-3 rounded-xl
                           bg-blue-600 hover:bg-blue-700
                           text-white font-semibold">

                        <i class="bi bi-check-lg me-1"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        const ruangModal =
            document.getElementById('ruangModal');

        const ruangForm =
            document.getElementById('ruangForm');


        function openRuangModal() {
            ruangForm.reset();

            document.getElementById('ruangModalTitle')
                .innerText = 'Tambah Ruang';

            document.getElementById('ruangMethod')
                .value = 'POST';

            ruangForm.action =
                "{{ route('admin.booking-ruang.ruang.store') }}";

            ruangModal.classList.remove('hidden');

            ruangModal.classList.add('flex');
        }


        function editRuang(
            id,
            nama,
            lokasi,
            kapasitas,
            fasilitas,
            status
        ) {
            document.getElementById('ruangModalTitle')
                .innerText = 'Edit Ruang';

            document.getElementById('ruang_nama')
                .value = nama ?? '';

            document.getElementById('ruang_lokasi')
                .value = lokasi ?? '';

            document.getElementById('ruang_kapasitas')
                .value = kapasitas ?? '';

            document.getElementById('ruang_fasilitas')
                .value = fasilitas ?? '';

            document.getElementById('ruang_status')
                .value = status ? '1' : '0';

            document.getElementById('ruangMethod')
                .value = 'PUT';

            ruangForm.action =
                "{{ url('admin/booking-ruang/ruang') }}/" + id;

            ruangModal.classList.remove('hidden');

            ruangModal.classList.add('flex');
        }


        function closeRuangModal() {
            ruangModal.classList.remove('flex');

            ruangModal.classList.add('hidden');
        }


        ruangModal.addEventListener('click', function(e) {

            if (e.target === ruangModal) {

                closeRuangModal();

            }

        });


        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                closeRuangModal();

            }

        });
    </script>
@endpush
