<div class="grid lg:grid-cols-2 gap-8
            text-slate-800 dark:text-slate-200">

    {{-- KIRI --}}
    <div class="space-y-5">

        <div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                Ruang
            </div>

            <div class="font-semibold text-lg
                        text-slate-900 dark:text-white">

                {{ $booking->ruang->ruang_nama }}

            </div>

        </div>


        <div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                Pemesan
            </div>

            <div class="font-semibold
                        text-slate-900 dark:text-white">

                {{ $booking->booking_created_by_nama }}

            </div>

        </div>


        <div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                Bidang
            </div>

            <div class="font-semibold
                        text-slate-900 dark:text-white">

                {{ $booking->booking_created_by_unit }}

            </div>

        </div>


        <div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                Tanggal
            </div>

            <div class="font-semibold
                        text-slate-900 dark:text-white">

                {{ \Carbon\Carbon::parse($booking->booking_tanggal)->translatedFormat('d F Y') }}

            </div>

        </div>


        <div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                Jam
            </div>

            <div class="font-semibold
                        text-slate-900 dark:text-white">

                {{ substr($booking->booking_jam_mulai, 0, 5) }}

                -

                {{ substr($booking->booking_jam_selesai, 0, 5) }}

            </div>

        </div>


        {{-- STATUS --}}
        <div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                Status
            </div>

            <div class="mt-3">

                @if ($booking->booking_status == 'Disetujui')
                    <span
                        class="inline-flex items-center gap-2
                                 px-4 py-2 rounded-full
                                 bg-green-100 dark:bg-green-900/30
                                 text-green-700 dark:text-green-300
                                 font-semibold">

                        <span class="w-2 h-2 rounded-full
                                     bg-green-500"></span>

                        Disetujui

                    </span>
                @elseif ($booking->booking_status == 'Menunggu')
                    <span
                        class="inline-flex items-center gap-2
                                 px-4 py-2 rounded-full
                                 bg-yellow-100 dark:bg-yellow-900/30
                                 text-yellow-700 dark:text-yellow-300
                                 font-semibold">

                        <span class="w-2 h-2 rounded-full
                                     bg-yellow-500"></span>

                        Menunggu

                    </span>
                @elseif ($booking->booking_status == 'Ditolak')
                    <span
                        class="inline-flex items-center gap-2
                                 px-4 py-2 rounded-full
                                 bg-red-100 dark:bg-red-900/30
                                 text-red-700 dark:text-red-300
                                 font-semibold">

                        <span class="w-2 h-2 rounded-full
                                     bg-red-500"></span>

                        Ditolak

                    </span>
                @elseif ($booking->booking_status == 'Batal')
                    <span
                        class="inline-flex items-center gap-2
                                 px-4 py-2 rounded-full
                                 bg-slate-100 dark:bg-slate-800
                                 text-slate-600 dark:text-slate-300
                                 font-semibold">

                        <span class="w-2 h-2 rounded-full
                                     bg-slate-400"></span>

                        Batal

                    </span>
                @elseif ($booking->booking_status == 'Selesai')
                    <span
                        class="inline-flex items-center gap-2
                                 px-4 py-2 rounded-full
                                 bg-blue-100 dark:bg-blue-900/30
                                 text-blue-700 dark:text-blue-300
                                 font-semibold">

                        <span class="w-2 h-2 rounded-full
                                     bg-blue-500"></span>

                        Selesai

                    </span>
                @else
                    <span
                        class="inline-flex px-4 py-2
                                 rounded-full
                                 bg-slate-100 dark:bg-slate-800
                                 text-slate-600 dark:text-slate-300
                                 font-semibold">

                        {{ $booking->booking_status }}

                    </span>
                @endif

            </div>

        </div>

    </div>


    {{-- KANAN --}}
    <div class="space-y-5">

        {{-- PERUNTUKAN --}}
        <div>

            <div
                class="text-sm
                        text-slate-500 dark:text-slate-400
                        mb-2">

                Peruntukan

            </div>

            <div
                class="rounded-2xl
                        bg-slate-50 dark:bg-slate-800
                        border border-slate-200 dark:border-slate-700
                        p-5
                        text-slate-700 dark:text-slate-200">

                {!! nl2br(e($booking->booking_peruntukan)) !!}

            </div>

        </div>


        {{-- CATATAN --}}
        <div>

            <div
                class="text-sm
                        text-slate-500 dark:text-slate-400
                        mb-2">

                Catatan

            </div>

            <div
                class="rounded-2xl
                        bg-slate-50 dark:bg-slate-800
                        border border-slate-200 dark:border-slate-700
                        p-5
                        text-slate-700 dark:text-slate-200">

                {!! nl2br(e($booking->booking_catatan ?: '-')) !!}

            </div>

        </div>


        {{-- SURAT --}}
        <div>

            <div
                class="text-sm
                        text-slate-500 dark:text-slate-400
                        mb-2">

                Surat Undangan

            </div>

            @if ($booking->booking_surat)
                <a href="{{ asset($booking->booking_surat) }}" target="_blank"
                    class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-blue-600 hover:bg-blue-700
                           text-white font-semibold">

                    <i class="bi bi-file-earmark-pdf"></i>

                    Lihat Surat

                </a>
            @else
                <div
                    class="rounded-2xl
                            bg-slate-50 dark:bg-slate-800
                            border border-slate-200 dark:border-slate-700
                            p-5
                            text-slate-500 dark:text-slate-400">

                    Tidak ada surat.

                </div>
            @endif

        </div>


        {{-- BATAL --}}
        @if ($booking->booking_status != 'Batal')
            <div class="pt-3">

                <button onclick="batalBooking('{{ $booking->booking_uid }}')"
                    class="inline-flex items-center gap-2
                           px-5 py-3 rounded-2xl
                           bg-red-600 hover:bg-red-700
                           text-white font-semibold">

                    <i class="bi bi-x-circle"></i>

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

            text: 'Jadwal akan kembali tersedia untuk pegawai lain.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Ya, Batalkan',

            cancelButtonText: 'Tidak',

            confirmButtonColor: '#dc2626',

            background: document.documentElement.classList.contains('dark') ?
                '#0f172a' :
                '#ffffff',

            color: document.documentElement.classList.contains('dark') ?
                '#f8fafc' :
                '#0f172a'

        }).then(function(result) {

            if (!result.isConfirmed) {
                return;
            }

            $.post(
                    "{{ url('admin/booking-ruang/batal') }}/" + uid, {
                        _token: "{{ csrf_token() }}"
                    },

                    function(res) {

                        if (res.status) {

                            Swal.fire({

                                title: 'Berhasil',

                                text: 'Booking berhasil dibatalkan.',

                                icon: 'success',

                                confirmButtonColor: '#2563eb',

                                background: document.documentElement.classList.contains('dark') ?
                                    '#0f172a' :
                                    '#ffffff',

                                color: document.documentElement.classList.contains('dark') ?
                                    '#f8fafc' :
                                    '#0f172a'

                            }).then(function() {

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
                )
                .fail(function() {

                    Swal.fire(
                        'Gagal',
                        'Terjadi kesalahan saat membatalkan booking.',
                        'error'
                    );

                });

        });
    }
</script>
