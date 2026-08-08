@extends('administrator-v2.layouts.app')

@section('title', 'Booking Ruang Rapat')
@section('page_title', 'Booking Ruang Rapat')
@section('breadcrumb', 'Booking Ruang Rapat')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col lg:flex-row
                    lg:items-center lg:justify-between gap-5">

                <div>

                    <h2 class="text-2xl font-bold text-slate-900">
                        Booking Ruang Rapat
                    </h2>

                    <p class="text-slate-500 mt-1">
                        Monitoring jadwal penggunaan ruang rapat.
                    </p>

                </div>

                <div class="flex flex-wrap gap-3">

                    <select id="filterRuang" class="rounded-xl border-slate-300">

                        <option value="">
                            Semua Ruang
                        </option>

                        @foreach ($ruangs as $ruang)
                            <option value="{{ $ruang->ruang_id }}">
                                {{ $ruang->ruang_nama }}
                            </option>
                        @endforeach

                    </select>

                    <a href="{{ route('admin.booking-ruang.ruang') }}"
                        class="px-5 py-3 rounded-2xl
                           bg-blue-600 text-white
                           font-semibold hover:bg-blue-700">

                        <i class="bi bi-building me-1"></i>

                        Kelola Ruang

                    </a>

                </div>

            </div>

        </div>


        {{-- CALENDAR --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div id="calendar"></div>

        </div>

    </div>


    {{-- MODAL DETAIL --}}
    <div id="detailModal"
        class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/50 p-4">

        <div
            class="relative w-full max-w-3xl
                max-h-[90vh]
                bg-white rounded-3xl
                shadow-2xl
                flex flex-col
                overflow-hidden">

            <div class="flex items-center justify-between
                    px-6 py-5 border-b">

                <div>

                    <h2 class="text-xl font-bold">
                        Detail Booking
                    </h2>

                    <p class="text-sm text-slate-500">
                        Informasi booking ruang rapat.
                    </p>

                </div>

                <button onclick="closeDetailModal()" class="w-10 h-10 rounded-xl hover:bg-slate-100">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <div id="detailContent" class="overflow-y-auto p-6">

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
                                "?ruang=" +
                                ruang
                            )

                            .then(response => response.json())

                            .then(data => success(data));

                    },


                    eventClick: function(info) {

                        loadDetail(
                            info.event.id
                        );

                    },


                    eventContent: function(arg) {

                        const jam = arg.event.extendedProps.jam ?? '';
                        const bidang = arg.event.extendedProps.bidang ?? arg.event.title ?? '';

                        return {
                            html: `
            <div class="px-2 py-1.5 rounded-lg text-white overflow-hidden"
                 style="
                    background: ${arg.event.backgroundColor};
                    border-left: 4px solid rgba(255,255,255,.8);
                    min-height: 38px;
                 ">

                <div style="
                    font-size: 11px;
                    font-weight: 700;
                    line-height: 1.2;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                ">
                    ${jam}
                </div>

                <div style="
                    font-size: 11px;
                    font-weight: 600;
                    line-height: 1.2;
                    margin-top: 2px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                ">
                    ${bidang}
                </div>

            </div>
        `
                        };
                    },

                });


            calendar.render();


            document
                .getElementById('filterRuang')
                .addEventListener('change', function() {

                    calendar.refetchEvents();

                });


            window.loadDetail = function(uid) {

                fetch(
                        "{{ url('admin/booking-ruang/detail') }}/" +
                        uid
                    )

                    .then(response => response.text())

                    .then(html => {

                        document
                            .getElementById('detailContent')
                            .innerHTML = html;

                        document
                            .getElementById('detailModal')
                            .classList
                            .remove('hidden');

                        document
                            .getElementById('detailModal')
                            .classList
                            .add('flex');

                    });

            };


            window.closeDetailModal = function() {

                document
                    .getElementById('detailModal')
                    .classList
                    .remove('flex');

                document
                    .getElementById('detailModal')
                    .classList
                    .add('hidden');

            };

        });
    </script>
@endpush
