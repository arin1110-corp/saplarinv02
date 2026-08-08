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
                Pemesan
            </div>

            <div class="font-semibold">
                {{ $booking->booking_created_by_nama }}
            </div>
        </div>


        <div>
            <div class="text-sm text-slate-500">
                Bidang
            </div>

            <div class="font-semibold">
                {{ $booking->booking_created_by_unit }}
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
                    <span class="px-4 py-2 rounded-full
                                 bg-green-100 text-green-700">
                        Disetujui
                    </span>
                @elseif ($booking->booking_status == 'Menunggu')
                    <span class="px-4 py-2 rounded-full
                                 bg-yellow-100 text-yellow-700">
                        Menunggu
                    </span>
                @elseif ($booking->booking_status == 'Ditolak')
                    <span class="px-4 py-2 rounded-full
                                 bg-red-100 text-red-700">
                        Ditolak
                    </span>
                @elseif ($booking->booking_status == 'Batal')
                    <span class="px-4 py-2 rounded-full
                                 bg-slate-100 text-slate-600">
                        Batal
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full
                                 bg-slate-100">
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
                    class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-blue-600 text-white">

                    <i class="bi bi-file-earmark-pdf"></i>

                    Lihat Surat

                </a>
            @else
                <div class="rounded-2xl bg-slate-50 p-5">
                    Tidak ada surat.
                </div>
            @endif

        </div>


        @if ($booking->booking_status != 'Batal')
            <div class="pt-3">

                <button onclick="batalBooking('{{ $booking->booking_uid }}')"
                    class="px-5 py-3 rounded-2xl
                           bg-red-600 hover:bg-red-700
                           text-white font-semibold">

                    <i class="bi bi-x-circle me-1"></i>

                    Batalkan Booking

                </button>

            </div>
        @endif

    </div>

</div>


<script>
    function batalBooking(uid) {
        Swal.fire({

            title: 'Batalkan Booking?',

            text: 'Jadwal akan kembali tersedia.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Ya, Batalkan',

            cancelButtonText: 'Tidak',

            confirmButtonColor: '#dc2626'

        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }


            $.post(
                "{{ url('admin/booking-ruang/batal') }}/" + uid,

                {
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

            ).fail(function() {

                Swal.fire(
                    'Gagal',
                    'Terjadi kesalahan saat membatalkan booking.',
                    'error'
                );

            });

        });
    }
</script>
