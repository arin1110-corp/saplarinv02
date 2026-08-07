<div class="flex items-center justify-between border-b px-8 py-5">

    <div>

        <h2 class="text-2xl font-bold">

            Detail Booking

        </h2>

        <p class="text-slate-500 mt-1">

            Informasi booking ruang rapat.

        </p>

    </div>

    <button id="btnCloseDetail" class="w-11 h-11 rounded-full hover:bg-slate-100">

        <i class="bi bi-x-lg text-xl"></i>

    </button>

</div>

<div class="p-8">

    <div class="grid lg:grid-cols-2 gap-8">

        <div class="space-y-5">

            <div>

                <div class="text-sm text-slate-500">

                    Ruang

                </div>

                <div class="font-semibold text-lg">

                    {{ $booking->ruang->ruang_nama }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    Tanggal

                </div>

                <div class="font-semibold">

                    {{ \Carbon\Carbon::parse($booking->booking_tanggal)->translatedFormat('d F Y') }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    Jam

                </div>

                <div class="font-semibold">

                    {{ substr($booking->booking_jam_mulai, 0, 5) }}

                    -

                    {{ substr($booking->booking_jam_selesai, 0, 5) }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    Status

                </div>

                <div class="mt-2">

                    @if ($booking->booking_status == 'Disetujui')
                        <span class="px-4 py-2 rounded-full bg-green-100 text-green-700">

                            Disetujui

                        </span>
                    @elseif($booking->booking_status == 'Menunggu')
                        <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700">

                            Menunggu

                        </span>
                    @elseif($booking->booking_status == 'Ditolak')
                        <span class="px-4 py-2 rounded-full bg-red-100 text-red-700">

                            Ditolak

                        </span>
                    @else
                        <span class="px-4 py-2 rounded-full bg-slate-100">

                            {{ $booking->booking_status }}

                        </span>
                    @endif

                </div>

            </div>

        </div>

        <div class="space-y-5">

            <div>

                <div class="text-sm text-slate-500 mb-2">

                    Peruntukan

                </div>

                <div class="rounded-2xl bg-slate-50 p-5">

                    {!! nl2br(e($booking->booking_peruntukan)) !!}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500 mb-2">

                    Catatan

                </div>

                <div class="rounded-2xl bg-slate-50 p-5">

                    {!! nl2br(e($booking->booking_catatan ?: '-')) !!}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500 mb-2">

                    Surat Undangan

                </div>

                @if ($booking->booking_surat)
                    <a href="{{ asset($booking->booking_surat) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 text-white">

                        <i class="bi bi-file-earmark-pdf"></i>

                        Lihat Surat

                    </a>
                @else
                    <div class="rounded-2xl bg-slate-50 p-5">

                        Tidak ada surat.

                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

<div class="border-t px-8 py-5 flex justify-between">

    <div>

        @if (in_array($booking->booking_status, ['Menunggu', 'Disetujui']))
            <button onclick="batalkanBooking('{{ $booking->booking_uid }}')"
                class="px-5 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white">

                Batalkan Booking

            </button>
        @endif

    </div>

    <button id="btnCloseDetail" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200">

        Tutup

    </button>

</div>
<script>
    function batalkanBooking(uid) {
        $('#modalDetail').addClass('hidden');
        Swal.fire({

            title: 'Batalkan Booking?',

            text: 'Jadwal akan kembali tersedia untuk pegawai lain.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Ya, Batalkan',

            cancelButtonText: 'Tidak'

        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            $.post(
                "{{ url('/user/booking-ruang/batal') }}/" + uid, {
                    _token: "{{ csrf_token() }}"
                },
                function(res) {

                    if (res.status) {

                        Swal.fire(
                            'Berhasil',
                            'Booking berhasil dibatalkan.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });

                    } else {

                        Swal.fire(
                            'Gagal',
                            res.message,
                            'error'
                        );

                    }

                }
            );

        });

    }
</script>
