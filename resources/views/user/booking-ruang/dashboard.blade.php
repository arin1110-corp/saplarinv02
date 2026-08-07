@extends('user.layouts.app')

@section('title', 'Booking Ruang Rapat')
@section('page_title', 'Booking Ruang Rapat')
@section('breadcrumb', 'Booking Ruang Rapat')

@section('content')

    <div class="space-y-6">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div>

                    <h2 class="text-2xl font-bold">

                        Kalender Booking Ruang

                    </h2>

                    <p class="text-slate-500 mt-1">

                        Pilih tanggal kosong untuk melakukan booking.

                    </p>

                </div>

                <div class="flex gap-3">

                    <a href="{{ route('user.booking-ruang.index') }}" class="px-5 py-3 rounded-2xl bg-blue-600 text-white">

                        Booking Saya

                    </a>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="space-y-8">

                @foreach ($ruangs as $ruang)
                    <div>

                        <div class="flex items-center justify-between mb-4">

                            <div>

                                <h3 class="text-xl font-bold">

                                    {{ $ruang->ruang_nama }}

                                </h3>

                                <p class="text-slate-500 text-sm">

                                    {{ $ruang->ruang_lokasi }}

                                </p>

                            </div>

                            <div>

                                @if ($ruang->ruang_status)
                                    <span class="px-4 py-2 rounded-xl bg-green-100 text-green-700">

                                        Aktif

                                    </span>
                                @else
                                    <span class="px-4 py-2 rounded-xl bg-red-100 text-red-700">

                                        Nonaktif

                                    </span>
                                @endif

                            </div>

                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4">

                            <div id="calendar-{{ $ruang->ruang_id }}">

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>
    <div id="modalBooking" class="fixed inset-0 z-[9999] hidden bg-black/60 backdrop-blur-sm">

        <div class="flex min-h-screen items-center justify-center p-8">

            <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl flex flex-col max-h-[92vh]">

                {{-- HEADER --}}
                <div class="flex items-center justify-between border-b px-8 py-5 shrink-0">

                    <div>

                        <h2 class="text-2xl font-bold">

                            Booking Ruang Rapat

                        </h2>

                        <p class="text-slate-500 mt-1">

                            Lengkapi data booking ruang.

                        </p>

                    </div>

                    <button type="button" id="btnClose" class="w-11 h-11 rounded-full hover:bg-slate-100">

                        <i class="bi bi-x-lg text-xl"></i>

                    </button>

                </div>

                {{-- BODY --}}
                <form action="{{ route('user.booking-ruang.store') }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col flex-1 overflow-hidden">

                    @csrf

                    <div class="flex-1 overflow-y-auto px-8 py-6">

                        <div class="grid lg:grid-cols-2 gap-6">

                            {{-- KIRI --}}
                            <div class="space-y-5">

                                <div>

                                    <label class="font-semibold text-slate-700">

                                        Ruang

                                    </label>

                                    <select id="booking_ruang_id" name="booking_ruang_id"
                                        class="mt-2 w-full rounded-2xl border-slate-300 form-control-modern">

                                        @foreach ($ruangs as $ruang)
                                            <option value="{{ $ruang->ruang_id }}">

                                                {{ $ruang->ruang_nama }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                                <div class="grid grid-cols-3 gap-4">

                                    <div>

                                        <label class="font-semibold">

                                            Tanggal

                                        </label>

                                        <input id="booking_tanggal" type="date" name="booking_tanggal"
                                            class="mt-2 w-full rounded-2xl border-slate-300 form-control-modern">

                                    </div>

                                    <div>

                                        <label class="font-semibold">

                                            Jam Mulai

                                        </label>

                                        <input id="booking_jam_mulai" type="time" name="booking_jam_mulai"
                                            class="mt-2 w-full rounded-2xl border-slate-300 form-control-modern">

                                    </div>

                                    <div>

                                        <label class="font-semibold">

                                            Jam Selesai

                                        </label>

                                        <input id="booking_jam_selesai" type="time" name="booking_jam_selesai"
                                            class="mt-2 w-full rounded-2xl border-slate-300 form-control-modern">

                                    </div>

                                </div>

                                <div>

                                    <label class="font-semibold">

                                        Peruntukan

                                    </label>

                                    <textarea rows="4" name="booking_peruntukan"
                                        class="mt-2 w-full rounded-2xl border-slate-300 resize-none form-control-modern"
                                        placeholder="Contoh : Rapat Evaluasi SPJ"></textarea>

                                </div>

                                <div>

                                    <label class="font-semibold">

                                        Catatan

                                    </label>

                                    <textarea rows="3" name="booking_catatan"
                                        class="mt-2 w-full rounded-2xl border-slate-300 resize-none form-control-modern"></textarea>

                                </div>

                            </div>

                            {{-- KANAN --}}
                            <div class="space-y-5">

                                <div class="rounded-2xl bg-slate-50 border p-5">

                                    <div class="font-semibold mb-3">

                                        Availability

                                    </div>

                                    <div id="availabilityResult">

                                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">

                                            <div class="text-slate-500">

                                                Pilih ruang, tanggal dan jam.

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div>

                                    <label class="font-semibold">

                                        Surat Undangan (Opsional)

                                    </label>

                                    <label
                                        class="mt-2 flex h-44 cursor-pointer items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 hover:border-blue-500 transition">

                                        <div class="text-center">

                                            <i class="bi bi-cloud-arrow-up text-5xl text-blue-600"></i>

                                            <div class="mt-3 font-semibold">

                                                Klik untuk upload

                                            </div>

                                            <div class="text-sm text-slate-500">

                                                PDF maksimal 5 MB

                                            </div>

                                        </div>

                                        <input type="file" name="booking_surat" accept=".pdf" class="hidden">

                                    </label>

                                </div>

                                <div class="rounded-2xl border bg-blue-50 p-5">

                                    <div class="font-semibold mb-3">

                                        Informasi

                                    </div>

                                    <ul class="space-y-2 text-sm text-slate-600">

                                        <li>

                                            ✅ Surat undangan bersifat opsional.

                                        </li>

                                        <li>

                                            ✅ Jadwal bentrok tidak dapat disimpan.

                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="border-t px-8 py-5 flex items-center justify-between shrink-0 bg-white">

                        <div class="text-sm text-slate-500">

                            Pastikan data yang diinput sudah benar.

                        </div>

                        <div class="flex gap-3">

                            <button type="button" id="btnClose2"
                                class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200">

                                Tutup

                            </button>

                            <button id="btnSimpanBooking" type="submit"
                                class="px-8 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold disabled:bg-slate-300 disabled:cursor-not-allowed"
                                disabled>

                                Simpan Booking

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
@push('styles')
    <style>
        .form-control-modern {

            width: 100%;

            height: 52px;

            border: 1px solid #CBD5E1;

            border-radius: 16px;

            padding: 0 16px;

            background: #fff;

            transition: .2s;

            outline: none;

            font-size: 15px;

        }

        .form-control-modern:focus {

            border-color: #2563eb;

            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);

        }

        textarea.form-control-modern {

            min-height: 120px;

            padding: 16px;

            resize: none;

        }

        select.form-control-modern {

            appearance: none;

            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7'/%3E%3C/svg%3E");

            background-repeat: no-repeat;

            background-position: right 16px center;

            background-size: 18px;

            padding-right: 48px;

        }

        .fc-daygrid-event {
            border-radius: 10px !important;
            padding: 4px 6px !important;
            margin-top: 3px !important;
            border: none !important;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
        }

        .fc-daygrid-event:hover {
            transform: scale(1.02);
            filter: brightness(.95);
        }

        .fc-event-main {
            color: #fff !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fc-daygrid-dot-event {
            align-items: center;
        }

        .fc-daygrid-dot-event .fc-event-dot {
            display: none !important;
        }

        .fc-daygrid-dot-event .fc-event-title {
            color: #fff !important;
            font-weight: 600;
        }
    </style>
@endpush
@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css">

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function checkAvailability() {

                let data = {

                    booking_ruang_id: $('#booking_ruang_id').val(),

                    booking_tanggal: $('#booking_tanggal').val(),

                    booking_jam_mulai: $('#booking_jam_mulai').val(),

                    booking_jam_selesai: $('#booking_jam_selesai').val(),

                    _token: "{{ csrf_token() }}"

                };

                if (

                    !data.booking_ruang_id ||

                    !data.booking_tanggal ||

                    !data.booking_jam_mulai ||

                    !data.booking_jam_selesai

                ) {

                    $('#availabilityResult').html(

                        `<div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">

                Pilih ruang, tanggal dan jam.

            </div>`

                    );

                    $('#btnSimpanBooking').prop('disabled', true);

                    return;

                }

                $.post(

                    "{{ route('user.booking-ruang.check') }}",

                    data,

                    function(res) {

                        if (res.status) {

                            $('#availabilityResult').html(

                                `<div class="rounded-2xl bg-green-50 border border-green-300 p-5">

                        <div class="font-bold text-green-700">

                            <i class="bi bi-check-circle-fill"></i>

                            Ruangan tersedia

                        </div>

                        <div class="mt-2 text-sm text-green-700">

                            Jadwal dapat digunakan.

                        </div>

                    </div>`

                            );

                            $('#btnSimpanBooking').prop('disabled', false);

                        } else {

                            $('#availabilityResult').html(

                                `<div class="rounded-2xl bg-red-50 border border-red-300 p-5">

                        <div class="font-bold text-red-700">

                            <i class="bi bi-x-circle-fill"></i>

                            Ruangan tidak tersedia

                        </div>

                        <div class="mt-3 text-sm">

                            <table class="w-full">

                                <tr>

                                    <td width="110">

                                        Peruntukan

                                    </td>

                                    <td>

                                        : ${res.booking.peruntukan}

                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        Operator

                                    </td>

                                    <td>

                                        : ${res.booking.operator}

                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        Jam

                                    </td>

                                    <td>

                                        : ${res.booking.mulai} - ${res.booking.selesai}

                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>`

                            );

                            $('#btnSimpanBooking').prop('disabled', true);

                        }

                    }

                );

            }

            function createCalendar(el, ruang) {

                let calendar = new FullCalendar.Calendar(el, {

                    locale: 'id',

                    initialView: 'dayGridMonth',

                    height: 'auto',

                    selectable: true,

                    nowIndicator: true,

                    headerToolbar: {

                        left: 'prev,next today',

                        center: 'title',

                        right: 'dayGridMonth,timeGridDay'

                    },
                    eventContent: function(arg) {

                        return {
                            html: `
            <div style="padding:2px 4px">
                ${arg.event.title}
            </div>
        `
                        };

                    },
                    eventDidMount: function(info) {

                        tippy(info.el, {

                            allowHTML: true,

                            theme: 'light',

                            content: `

            <div class="text-left">

                <div style="font-weight:600;margin-bottom:6px;">
                    ${info.event.extendedProps.peruntukan}
                </div>

                <div>🏢 ${info.event.title}</div>

                <div>🕒 ${info.event.extendedProps.jam}</div>

                <div>👤 ${info.event.extendedProps.operator}</div>

            </div>

        `

                        });

                    },

                    events: function(info, success) {

                        fetch(

                                "{{ route('user.booking-ruang.events') }}?ruang=" + ruang

                            )

                            .then(r => r.json())

                            .then(data => success(data));

                    },

                    dateClick: function(info) {

                        $('#modalBooking').removeClass('hidden');

                        $('#booking_ruang_id').val(ruang);

                        $('#booking_tanggal').val(info.dateStr);
                        $('#booking_jam_mulai').val('');

                        $('#booking_jam_selesai').val('');

                        $('#btnSimpanBooking').prop('disabled', true);

                        $('#availabilityResult').html(

                            `<div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">

        Pilih jam mulai dan jam selesai.

    </div>`

                        );

                    },

                    eventClick: function(info) {

                        loadBooking(info.event.id);

                    }

                });

                calendar.render();

                return calendar;

            }

            @foreach ($ruangs as $ruang)

                createCalendar(

                    document.getElementById(

                        'calendar-{{ $ruang->ruang_id }}'

                    ),

                    {{ $ruang->ruang_id }}

                );
            @endforeach
            $('#btnClose,#btnClose2').click(function() {

                $('#modalBooking').addClass('hidden');

            });

            $('#booking_ruang_id').change(checkAvailability);

            $('#booking_tanggal').change(checkAvailability);

            $('#booking_jam_mulai').change(checkAvailability);

            $('#booking_jam_selesai').change(checkAvailability);
        });
    </script>
@endpush
