@extends('administrator-v2.layouts.app')

@section('title', 'Booking Ruang Rapat')
@section('page_title', 'Booking Ruang Rapat')
@section('breadcrumb', 'Booking Ruang Rapat')

@push('styles')
    <style>
        .fc {
            --fc-border-color: #e2e8f0;
            --fc-page-bg-color: transparent;
        }

        .dark .fc {
            --fc-border-color: #334155;
            --fc-page-bg-color: transparent;
            color: #e2e8f0;
        }

        .dark .fc-theme-standard td,
        .dark .fc-theme-standard th {
            border-color: #334155;
        }

        .dark .fc-scrollgrid {
            border-color: #334155 !important;
        }

        .dark .fc-col-header-cell {
            background: #0f172a;
        }

        .dark .fc-col-header-cell-cushion {
            color: #cbd5e1;
        }

        .dark .fc-daygrid-day {
            background: #0f172a;
        }

        .dark .fc-daygrid-day-number {
            color: #cbd5e1;
        }

        .dark .fc-day-today {
            background: rgba(37, 99, 235, .12) !important;
        }

        .dark .fc-toolbar-title {
            color: #f8fafc;
        }

        .dark .fc-button {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        .dark .fc-button:hover {
            background: #334155 !important;
        }

        .dark .fc-button-active {
            background: #2563eb !important;
            border-color: #2563eb !important;
        }

        .fc-daygrid-event {
            border: none !important;
            padding: 0 !important;
            margin: 2px 3px !important;
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

                        Booking Ruang Rapat

                    </h2>

                    <p class="text-slate-500 dark:text-slate-400 mt-1">

                        Monitoring jadwal penggunaan ruang rapat.

                    </p>

                </div>


                <div class="flex flex-wrap gap-3">

                    <select id="filterRuang"
                        class="rounded-xl
                           border-slate-300 dark:border-slate-600
                           bg-white dark:bg-slate-800
                           text-slate-800 dark:text-white">

                        <option value="">
                            Semua Ruang
                        </option>

                        @foreach ($ruangs as $ruang)
                            <option value="{{ $ruang->ruang_id }}">
                                {{ $ruang->ruang_nama }}
                            </option>
                        @endforeach

                    </select>


                    <a href="{{ route('admin.booking-ruang.pengajuan') }}"
                        class="px-5 py-3 rounded-2xl
                           bg-slate-800 hover:bg-slate-900
                           dark:bg-slate-700 dark:hover:bg-slate-600
                           text-white font-semibold">

                        <i class="bi bi-list-check me-1"></i>

                        Detail Pengajuan

                    </a>


                    <a href="{{ route('admin.booking-ruang.ruang') }}"
                        class="px-5 py-3 rounded-2xl
                           bg-blue-600 hover:bg-blue-700
                           text-white font-semibold">

                        <i class="bi bi-building me-1"></i>

                        Kelola Ruang

                    </a>

                </div>

            </div>

        </div>


        {{-- CALENDAR --}}
        <div
            class="bg-white dark:bg-slate-900
                rounded-3xl
                border border-slate-200 dark:border-slate-700
                shadow-sm p-6">

            <div id="calendar"></div>

        </div>

    </div>


    {{-- DETAIL MODAL --}}
    <div id="detailModal"
        class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/60 backdrop-blur-sm
           p-4 overflow-y-auto">

        <div
            class="relative w-full max-w-3xl
                max-h-[90vh]
                bg-white dark:bg-slate-900
                rounded-3xl
                shadow-2xl
                border border-slate-200 dark:border-slate-700
                flex flex-col
                overflow-hidden">

            <div
                class="flex items-center justify-between
                    px-6 py-5
                    border-b border-slate-200 dark:border-slate-700
                    shrink-0">

                <div>

                    <h2 class="text-xl font-bold
                           text-slate-900 dark:text-white">

                        Detail Booking

                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">

                        Informasi booking ruang rapat.

                    </p>

                </div>


                <button onclick="closeDetailModal()"
                    class="w-10 h-10 rounded-xl
                       hover:bg-slate-100 dark:hover:bg-slate-800
                       text-slate-600 dark:text-slate-300">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <div id="detailContent" class="flex-1 overflow-y-auto p-6">

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const calendarEl =
                document.getElementById('calendar');


            const calendar =
                new FullCalendar.Calendar(calendarEl, {

                    locale: 'id',

                    initialView: 'dayGridMonth',

                    height: 'auto',

                    nowIndicator: true,

                    headerToolbar: {

                        left: 'prev,next today',

                        center: 'title',

                        right: 'dayGridMonth,timeGridDay'

                    },


                    events: function(info, success) {

                        const ruang =
                            document.getElementById('filterRuang').value;

                        fetch(
                                "{{ route('admin.booking-ruang.events') }}" +
                                "?ruang=" + ruang
                            )

                            .then(response => response.json())

                            .then(data => success(data));

                    },


                    eventClick: function(info) {

                        loadDetail(info.event.id);

                    },


                    eventContent: function(arg) {

                        const jam =
                            arg.event.extendedProps.jam ?? '';

                        const bidang =
                            arg.event.extendedProps.bidang ??
                            arg.event.title ??
                            '';

                        return {

                            html: `
                        <div class="px-2 py-1.5 rounded-lg
                                    text-white overflow-hidden"
                             style="
                                background:${arg.event.backgroundColor};
                                min-height:38px;
                             ">

                            <div style="
                                font-size:11px;
                                font-weight:700;
                                line-height:1.2;
                            ">
                                ${jam}
                            </div>

                            <div style="
                                font-size:11px;
                                font-weight:600;
                                line-height:1.2;
                                margin-top:2px;
                                white-space:nowrap;
                                overflow:hidden;
                                text-overflow:ellipsis;
                            ">
                                ${bidang}
                            </div>

                        </div>
                    `

                        };

                    }

                });


            calendar.render();


            document
                .getElementById('filterRuang')
                .addEventListener('change', function() {

                    calendar.refetchEvents();

                });


            window.loadDetail = function(uid) {

                fetch(
                        "{{ url('admin/booking-ruang/detail') }}/" + uid
                    )

                    .then(response => response.text())

                    .then(html => {

                        document.getElementById('detailContent')
                            .innerHTML = html;

                        document.getElementById('detailModal')
                            .classList.remove('hidden');

                        document.getElementById('detailModal')
                            .classList.add('flex');

                    });

            };


            window.closeDetailModal = function() {

                document.getElementById('detailModal')
                    .classList.remove('flex');

                document.getElementById('detailModal')
                    .classList.add('hidden');

            };

        });
    </script>
@endpush
