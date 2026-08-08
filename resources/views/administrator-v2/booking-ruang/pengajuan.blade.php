@extends('administrator-v2.layouts.app')

@section('title', 'Detail Pengajuan Booking')
@section('page_title', 'Detail Pengajuan Booking')
@section('breadcrumb', 'Detail Pengajuan Booking')

@push('styles')
    <style>
        .dark select option {
            background-color: #0f172a;
            color: #f8fafc;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>
@endpush


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

                        Detail Pengajuan Booking

                    </h2>

                    <p class="text-slate-500 dark:text-slate-400 mt-1">

                        Seluruh pengajuan booking ruang rapat.

                    </p>

                </div>


                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('admin.booking-ruang.dashboard') }}"
                        class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-blue-600 hover:bg-blue-700
                           text-white font-semibold">

                        <i class="bi bi-calendar3"></i>

                        Kalender Booking

                    </a>


                    <a href="{{ route('admin.booking-ruang.ruang') }}"
                        class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-slate-800 hover:bg-slate-900
                           dark:bg-slate-700 dark:hover:bg-slate-600
                           text-white font-semibold">

                        <i class="bi bi-building"></i>

                        Kelola Ruang

                    </a>

                </div>

            </div>

        </div>


        {{-- FILTER --}}
        <div
            class="bg-white dark:bg-slate-900
                rounded-3xl
                border border-slate-200 dark:border-slate-700
                shadow-sm p-5">

            <div class="grid md:grid-cols-3 gap-4">

                <div>

                    <label
                        class="block text-sm font-semibold
                              text-slate-700 dark:text-slate-300 mb-2">

                        Cari

                    </label>

                    <div class="relative">

                        <i
                            class="bi bi-search absolute left-4 top-1/2
                              -translate-y-1/2
                              text-slate-400"></i>

                        <input type="text" id="searchBooking" placeholder="Nama, bidang, peruntukan..."
                            class="w-full rounded-xl
                               border-slate-300 dark:border-slate-600
                               bg-white dark:bg-slate-800
                               text-slate-900 dark:text-white
                               placeholder:text-slate-400
                               pl-11
                               focus:border-blue-500
                               focus:ring-blue-500">

                    </div>

                </div>


                <div>

                    <label
                        class="block text-sm font-semibold
                              text-slate-700 dark:text-slate-300 mb-2">

                        Ruang

                    </label>

                    <select id="filterRuang"
                        class="w-full rounded-xl
                           border-slate-300 dark:border-slate-600
                           bg-white dark:bg-slate-800
                           text-slate-900 dark:text-white
                           focus:border-blue-500
                           focus:ring-blue-500">

                        <option value="">
                            Semua Ruang
                        </option>

                        @foreach ($bookings->pluck('ruang')->filter()->unique('ruang_id') as $ruang)
                            <option value="{{ $ruang->ruang_id }}">
                                {{ $ruang->ruang_nama }}
                            </option>
                        @endforeach

                    </select>

                </div>


                <div>

                    <label
                        class="block text-sm font-semibold
                              text-slate-700 dark:text-slate-300 mb-2">

                        Status

                    </label>

                    <select id="filterStatus"
                        class="w-full rounded-xl
                           border-slate-300 dark:border-slate-600
                           bg-white dark:bg-slate-800
                           text-slate-900 dark:text-white
                           focus:border-blue-500
                           focus:ring-blue-500">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="Disetujui">
                            Disetujui
                        </option>

                        <option value="Menunggu">
                            Menunggu
                        </option>

                        <option value="Ditolak">
                            Ditolak
                        </option>

                        <option value="Batal">
                            Batal
                        </option>

                        <option value="Selesai">
                            Selesai
                        </option>

                    </select>

                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div
            class="bg-white dark:bg-slate-900
                rounded-3xl
                border border-slate-200 dark:border-slate-700
                shadow-sm overflow-hidden">

            <div class="overflow-x-auto scrollbar-thin">

                <table class="w-full text-sm" id="bookingTable">

                    <thead>

                        <tr
                            class="bg-slate-50 dark:bg-slate-800
                               border-b border-slate-200
                               dark:border-slate-700
                               text-slate-600 dark:text-slate-300">

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                No
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Ruang
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Pemesan
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Bidang
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Jam
                            </th>

                            <th class="px-5 py-4 text-left min-w-[250px]">
                                Peruntukan
                            </th>

                            <th class="px-5 py-4 text-left whitespace-nowrap">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right whitespace-nowrap">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($bookings as $booking)
                            <tr data-ruang="{{ $booking->booking_ruang_id }}" data-status="{{ $booking->booking_status }}"
                                data-search="{{ strtolower(
                                    $booking->booking_created_by_nama . ' ' . $booking->booking_created_by_unit . ' ' . $booking->booking_peruntukan,
                                ) }}"
                                class="booking-row
                                   border-b border-slate-100
                                   dark:border-slate-800
                                   hover:bg-slate-50
                                   dark:hover:bg-slate-800/60
                                   transition">

                                <td
                                    class="px-5 py-4
                                       text-slate-500 dark:text-slate-400">

                                    {{ $loop->iteration }}

                                </td>


                                <td class="px-5 py-4">

                                    <div
                                        class="font-semibold
                                            text-slate-900 dark:text-white">

                                        {{ $booking->ruang->ruang_nama ?? '-' }}

                                    </div>

                                    <div
                                        class="text-xs
                                            text-slate-400 dark:text-slate-500 mt-1">

                                        {{ $booking->ruang->ruang_lokasi ?? '-' }}

                                    </div>

                                </td>


                                <td class="px-5 py-4">

                                    <div
                                        class="font-semibold
                                            text-slate-800 dark:text-slate-200">

                                        {{ $booking->booking_created_by_nama }}

                                    </div>

                                </td>


                                <td
                                    class="px-5 py-4
                                       text-slate-600 dark:text-slate-300">

                                    {{ $booking->booking_created_by_unit ?? '-' }}

                                </td>


                                <td
                                    class="px-5 py-4 whitespace-nowrap
                                       text-slate-700 dark:text-slate-300">

                                    {{ \Carbon\Carbon::parse($booking->booking_tanggal)->translatedFormat('d F Y') }}

                                </td>


                                <td
                                    class="px-5 py-4 whitespace-nowrap
                                       text-slate-700 dark:text-slate-300">

                                    {{ substr($booking->booking_jam_mulai, 0, 5) }}

                                    <span class="text-slate-400 mx-1">
                                        -
                                    </span>

                                    {{ substr($booking->booking_jam_selesai, 0, 5) }}

                                </td>


                                <td
                                    class="px-5 py-4
                                       text-slate-600 dark:text-slate-300">

                                    <div class="max-w-[300px] line-clamp-2">

                                        {{ $booking->booking_peruntukan }}

                                    </div>

                                </td>


                                <td class="px-5 py-4">

                                    @if ($booking->booking_status == 'Disetujui')
                                        <span
                                            class="inline-flex items-center gap-2
                                                 px-3 py-1.5 rounded-full
                                                 bg-green-100 dark:bg-green-900/30
                                                 text-green-700 dark:text-green-300
                                                 text-xs font-semibold">

                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>

                                            Disetujui

                                        </span>
                                    @elseif ($booking->booking_status == 'Menunggu')
                                        <span
                                            class="inline-flex items-center gap-2
                                                 px-3 py-1.5 rounded-full
                                                 bg-yellow-100 dark:bg-yellow-900/30
                                                 text-yellow-700 dark:text-yellow-300
                                                 text-xs font-semibold">

                                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>

                                            Menunggu

                                        </span>
                                    @elseif ($booking->booking_status == 'Ditolak')
                                        <span
                                            class="inline-flex items-center gap-2
                                                 px-3 py-1.5 rounded-full
                                                 bg-red-100 dark:bg-red-900/30
                                                 text-red-700 dark:text-red-300
                                                 text-xs font-semibold">

                                            <span class="w-2 h-2 rounded-full bg-red-500"></span>

                                            Ditolak

                                        </span>
                                    @elseif ($booking->booking_status == 'Batal')
                                        <span
                                            class="inline-flex items-center gap-2
                                                 px-3 py-1.5 rounded-full
                                                 bg-slate-100 dark:bg-slate-800
                                                 text-slate-600 dark:text-slate-300
                                                 text-xs font-semibold">

                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>

                                            Batal

                                        </span>
                                    @elseif ($booking->booking_status == 'Selesai')
                                        <span
                                            class="inline-flex items-center gap-2
                                                 px-3 py-1.5 rounded-full
                                                 bg-blue-100 dark:bg-blue-900/30
                                                 text-blue-700 dark:text-blue-300
                                                 text-xs font-semibold">

                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>

                                            Selesai

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-3 py-1.5 rounded-full
                                                 bg-slate-100 dark:bg-slate-800
                                                 text-slate-600 dark:text-slate-300
                                                 text-xs font-semibold">

                                            {{ $booking->booking_status }}

                                        </span>
                                    @endif

                                </td>


                                <td class="px-5 py-4 text-right">

                                    <button onclick="loadDetail('{{ $booking->booking_uid }}')"
                                        class="inline-flex items-center gap-2
                                           px-4 py-2 rounded-xl
                                           bg-blue-50 dark:bg-blue-900/30
                                           text-blue-700 dark:text-blue-300
                                           font-semibold
                                           hover:bg-blue-100
                                           dark:hover:bg-blue-900/50">

                                        <i class="bi bi-eye"></i>

                                        Detail

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="px-5 py-16 text-center">

                                    <div class="flex flex-col
                                            items-center">

                                        <div
                                            class="w-14 h-14 rounded-2xl
                                                bg-slate-100 dark:bg-slate-800
                                                flex items-center justify-center
                                                text-slate-400">

                                            <i class="bi bi-inbox text-2xl"></i>

                                        </div>

                                        <p
                                            class="mt-4 font-semibold
                                              text-slate-700 dark:text-slate-300">

                                            Belum ada pengajuan

                                        </p>

                                        <p class="text-sm text-slate-400 mt-1">

                                            Data pengajuan booking akan tampil di sini.

                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- DETAIL MODAL --}}
    <div id="detailModal"
        class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/60 backdrop-blur-sm
           p-4 overflow-y-auto">

        <div
            class="relative w-full max-w-4xl
                max-h-[90vh]
                bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-700
                rounded-3xl shadow-2xl
                flex flex-col overflow-hidden">

            <div
                class="flex items-center justify-between
                    px-6 py-5 shrink-0
                    border-b border-slate-200 dark:border-slate-700">

                <div>

                    <h2 class="text-xl font-bold
                           text-slate-900 dark:text-white">

                        Detail Booking

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Informasi lengkap pengajuan.

                    </p>

                </div>


                <button onclick="closeDetailModal()"
                    class="w-10 h-10 rounded-xl
                       hover:bg-slate-100 dark:hover:bg-slate-800
                       text-slate-600 dark:text-slate-300">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <div id="detailContent"
                class="flex-1 overflow-y-auto p-6
                   text-slate-800 dark:text-slate-200">

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        function loadDetail(uid) {
            fetch(
                    "{{ url('admin/booking-ruang/detail') }}/" + uid
                )
                .then(response => response.text())
                .then(html => {

                    document.getElementById('detailContent').innerHTML = html;

                    document.getElementById('detailModal')
                        .classList.remove('hidden');

                    document.getElementById('detailModal')
                        .classList.add('flex');

                });
        }


        function closeDetailModal() {
            document.getElementById('detailModal')
                .classList.remove('flex');

            document.getElementById('detailModal')
                .classList.add('hidden');
        }


        document.addEventListener('DOMContentLoaded', function() {

            const search = document.getElementById('searchBooking');
            const ruang = document.getElementById('filterRuang');
            const status = document.getElementById('filterStatus');

            function filterTable() {
                const keyword = search.value.toLowerCase();
                const ruangValue = ruang.value;
                const statusValue = status.value;

                document.querySelectorAll('.booking-row')
                    .forEach(row => {

                        const rowSearch =
                            row.dataset.search || '';

                        const rowRuang =
                            row.dataset.ruang || '';

                        const rowStatus =
                            row.dataset.status || '';

                        const matchSearch =
                            rowSearch.includes(keyword);

                        const matchRuang = !ruangValue ||
                            rowRuang === ruangValue;

                        const matchStatus = !statusValue ||
                            rowStatus === statusValue;

                        row.style.display =
                            matchSearch &&
                            matchRuang &&
                            matchStatus ?
                            '' :
                            'none';

                    });
            }


            search.addEventListener('input', filterTable);
            ruang.addEventListener('change', filterTable);
            status.addEventListener('change', filterTable);

        });
    </script>
@endpush
