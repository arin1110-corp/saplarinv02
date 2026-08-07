@extends('user.layouts.app')

@section('title', 'Booking Saya')
@section('page_title', 'Booking Saya')
@section('breadcrumb', 'Booking Saya')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl bg-green-50 border border-green-200 p-5 text-green-700">

                {{ session('success') }}

            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-2xl font-bold">

                        Booking Saya

                    </h2>

                    <p class="text-slate-500 mt-1">

                        Riwayat booking ruang rapat.

                    </p>

                </div>

                <a href="{{ route('user.booking-ruang.dashboard') }}" class="px-5 py-3 rounded-2xl bg-blue-600 text-white">

                    + Booking Baru

                </a>

            </div>

        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="bg-slate-50">

                            <th class="px-6 py-4 text-left">No</th>

                            <th class="px-6 py-4 text-left">Tanggal</th>

                            <th class="px-6 py-4 text-left">Ruang</th>

                            <th class="px-6 py-4 text-left">Jam</th>

                            <th class="px-6 py-4 text-left">Peruntukan</th>

                            <th class="px-6 py-4 text-center">Status</th>

                            <th class="px-6 py-4 text-center">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($bookings as $booking)
                            <tr class="border-t hover:bg-slate-50">

                                <td class="px-6 py-4">

                                    {{ $loop->iteration }}

                                </td>

                                <td class="px-6 py-4">

                                    {{ \Carbon\Carbon::parse($booking->booking_tanggal)->translatedFormat('d F Y') }}

                                </td>

                                <td class="px-6 py-4">

                                    {{ $booking->ruang->ruang_nama }}

                                </td>

                                <td class="px-6 py-4">

                                    {{ substr($booking->booking_jam_mulai, 0, 5) }}

                                    -

                                    {{ substr($booking->booking_jam_selesai, 0, 5) }}

                                </td>

                                <td class="px-6 py-4">

                                    {{ Str::limit($booking->booking_peruntukan, 40) }}

                                </td>

                                <td class="px-6 py-4 text-center">

                                    @switch($booking->booking_status)
                                        @case('Disetujui')
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">

                                                Disetujui

                                            </span>
                                        @break

                                        @case('Menunggu')
                                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">

                                                Menunggu

                                            </span>
                                        @break

                                        @case('Ditolak')
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">

                                                Ditolak

                                            </span>
                                        @break

                                        @default
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700">

                                                {{ $booking->booking_status }}

                                            </span>
                                    @endswitch

                                </td>

                                <td class="px-6 py-4 text-center">

                                    <button onclick="detailBooking('{{ $booking->booking_uid }}')"
                                        class="px-4 py-2 rounded-xl bg-blue-600 text-white">

                                        Detail

                                    </button>

                                </td>

                            </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-12 text-slate-500">

                                        Belum ada booking.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div id="modalDetail" class="fixed inset-0 hidden z-[9999] bg-black/60">

            <div class="flex min-h-screen items-center justify-center p-6">

                <div class="bg-white rounded-3xl w-full max-w-4xl">

                    <div id="detailBookingContent"></div>

                </div>

            </div>

        </div>

    @endsection

    @push('scripts')
        <script>
            function detailBooking(uid) {

                $('#modalDetail').removeClass('hidden');

                $('#detailBookingContent').load(

                    "{{ url('/user/booking-ruang/detail') }}/" + uid

                );

            }

            $(document).on('click', '#btnCloseDetail', function() {

                $('#modalDetail').addClass('hidden');

            });
        </script>
    @endpush
