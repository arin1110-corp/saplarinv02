@extends('administrator-v2.layouts.app')

@section('title', 'Pengelolaan Ruang Rapat')
@section('page_title', 'Pengelolaan Ruang Rapat')
@section('breadcrumb', 'Pengelolaan Ruang Rapat')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div
                class="rounded-2xl bg-green-50
                    border border-green-200
                    text-green-700 px-5 py-4">

                {{ session('success') }}

            </div>
        @endif


        @if (session('error'))
            <div
                class="rounded-2xl bg-red-50
                    border border-red-200
                    text-red-700 px-5 py-4">

                {{ session('error') }}

            </div>
        @endif


        <div class="bg-white rounded-3xl
                border border-slate-200
                shadow-sm p-6">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-2xl font-bold">
                        Pengelolaan Ruang Rapat
                    </h2>

                    <p class="text-slate-500 mt-1">
                        Kelola ruang yang tersedia untuk booking.
                    </p>

                </div>


                <button onclick="openRuangModal()"
                    class="px-5 py-3 rounded-2xl
                       bg-blue-600 text-white
                       font-semibold hover:bg-blue-700">

                    + Tambah Ruang

                </button>

            </div>

        </div>


        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">

            @forelse($ruangs as $ruang)
                <div
                    class="bg-white rounded-3xl
                        border border-slate-200
                        shadow-sm p-6">

                    <div class="flex items-start justify-between gap-3">

                        <div>

                            <h3 class="text-lg font-bold">
                                {{ $ruang->ruang_nama }}
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $ruang->ruang_lokasi }}
                            </p>

                        </div>


                        @if ($ruang->ruang_status)
                            <span
                                class="px-3 py-1.5 rounded-full
                                     bg-green-100 text-green-700
                                     text-xs font-semibold">
                                Aktif
                            </span>
                        @else
                            <span
                                class="px-3 py-1.5 rounded-full
                                     bg-red-100 text-red-700
                                     text-xs font-semibold">
                                Nonaktif
                            </span>
                        @endif

                    </div>


                    <div class="mt-5 space-y-3">

                        @if ($ruang->ruang_kapasitas)
                            <div class="flex gap-2 text-sm">

                                <i class="bi bi-people text-slate-400"></i>

                                <span>
                                    {{ $ruang->ruang_kapasitas }} orang
                                </span>

                            </div>
                        @endif


                        @if ($ruang->ruang_fasilitas)
                            <div class="text-sm text-slate-600">

                                {!! nl2br(e($ruang->ruang_fasilitas)) !!}

                            </div>
                        @endif


                        <div class="text-xs text-slate-400">

                            {{ $ruang->bookings_count }}
                            booking

                        </div>

                    </div>


                    <div class="flex gap-2 mt-6">

                        <button onclick='editRuang(@json($ruang))'
                            class="flex-1 px-4 py-2.5 rounded-xl
                               bg-blue-50 text-blue-700
                               font-semibold">

                            Edit

                        </button>


                        <form action="{{ route('admin.booking-ruang.ruang.status', $ruang->ruang_uid) }}" method="POST">

                            @csrf

                            <button
                                class="px-4 py-2.5 rounded-xl
                                   bg-slate-100 text-slate-700">

                                {{ $ruang->ruang_status ? 'Nonaktifkan' : 'Aktifkan' }}

                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div
                    class="col-span-full
                        bg-white rounded-3xl
                        border border-slate-200
                        p-10 text-center
                        text-slate-500">

                    Belum ada ruang rapat.

                </div>
            @endforelse

        </div>

    </div>


    {{-- MODAL --}}
    <div id="ruangModal"
        class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/50 p-4
           overflow-y-auto">

        <div
            class="w-full max-w-xl
                bg-white rounded-3xl
                shadow-2xl
                overflow-hidden">

            <div class="flex items-center justify-between
                    px-6 py-5 border-b">

                <h2 id="ruangModalTitle" class="text-xl font-bold">
                    Tambah Ruang
                </h2>

                <button onclick="closeRuangModal()" class="w-10 h-10 rounded-xl hover:bg-slate-100">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <form id="ruangForm" action="{{ route('admin.booking-ruang.ruang.store') }}" method="POST">

                @csrf

                <div id="methodField"></div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Nama Ruang
                        </label>

                        <input id="ruang_nama" type="text" name="ruang_nama" class="w-full rounded-xl border-slate-300"
                            required>

                    </div>


                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Lokasi
                        </label>

                        <input id="ruang_lokasi" type="text" name="ruang_lokasi"
                            class="w-full rounded-xl border-slate-300" required>

                    </div>


                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Kapasitas
                        </label>

                        <input id="ruang_kapasitas" type="number" name="ruang_kapasitas" min="1"
                            class="w-full rounded-xl border-slate-300">

                    </div>


                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Fasilitas
                        </label>

                        <textarea id="ruang_fasilitas" name="ruang_fasilitas" rows="4" class="w-full rounded-xl border-slate-300"></textarea>

                    </div>

                </div>


                <div class="border-t px-6 py-4
                        flex justify-end gap-3">

                    <button type="button" onclick="closeRuangModal()"
                        class="px-5 py-3 rounded-xl
                           bg-slate-100">

                        Batal

                    </button>

                    <button
                        class="px-5 py-3 rounded-xl
                           bg-blue-600 text-white
                           font-semibold">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        function openRuangModal() {
            document.getElementById('ruangModalTitle')
                .innerText = 'Tambah Ruang';

            document.getElementById('ruangForm')
                .action =
                "{{ route('admin.booking-ruang.ruang.store') }}";

            document.getElementById('methodField')
                .innerHTML = '';

            document.getElementById('ruangForm')
                .reset();

            showRuangModal();
        }


        function editRuang(ruang) {
            document.getElementById('ruangModalTitle')
                .innerText = 'Edit Ruang';

            document.getElementById('ruangForm')
                .action =
                "{{ url('admin/booking-ruang/ruang') }}/" +
                ruang.ruang_uid;

            document.getElementById('methodField')
                .innerHTML =
                '@method('PUT')';

            document.getElementById('ruang_nama')
                .value = ruang.ruang_nama ?? '';

            document.getElementById('ruang_lokasi')
                .value = ruang.ruang_lokasi ?? '';

            document.getElementById('ruang_kapasitas')
                .value = ruang.ruang_kapasitas ?? '';

            document.getElementById('ruang_fasilitas')
                .value = ruang.ruang_fasilitas ?? '';

            showRuangModal();
        }


        function showRuangModal() {
            document.getElementById('ruangModal')
                .classList.remove('hidden');

            document.getElementById('ruangModal')
                .classList.add('flex');
        }


        function closeRuangModal() {
            document.getElementById('ruangModal')
                .classList.remove('flex');

            document.getElementById('ruangModal')
                .classList.add('hidden');
        }
    </script>
@endpush
