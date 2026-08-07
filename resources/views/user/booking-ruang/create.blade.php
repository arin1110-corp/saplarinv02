@extends('user.layouts.app')

@section('title', 'Booking Ruang Rapat')
@section('page_title', 'Booking Ruang Rapat')
@section('breadcrumb', 'Booking Ruang Rapat')

@section('content')

    <div class="space-y-6">

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4">

                {{ session('error') }}

            </div>
        @endif

        @if ($errors->any())

            <div class="bg-red-50 border border-red-200 rounded-2xl p-4">

                <ul class="list-disc ml-5 text-red-600">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif
        <form action="{{ route('user.booking-ruang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">

            @csrf

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <h2 class="text-xl font-bold">

                    Form Booking Ruang

                </h2>

                <p class="text-slate-500 mt-1">

                    Lengkapi data booking ruang rapat.

                </p>

            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="grid lg:grid-cols-2 gap-6">

                    <div>

                        <label class="font-semibold">

                            Ruang Rapat

                        </label>

                        <select name="booking_ruang_id" class="mt-2 w-full rounded-xl border-slate-300" required>

                            <option value="">

                                Pilih Ruang

                            </option>

                            @foreach ($ruangs as $ruang)
                                <option value="{{ $ruang->ruang_id }}" @selected(request('ruang') == $ruang->ruang_id)>

                                    {{ $ruang->ruang_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="font-semibold">

                            Tanggal

                        </label>

                        <input type="date" name="booking_tanggal" value="{{ request('tanggal') }}"
                            class="mt-2 w-full rounded-xl border-slate-300" required>

                    </div>

                    <div>

                        <label class="font-semibold">

                            Jam Mulai

                        </label>

                        <input type="time" name="booking_jam_mulai" class="mt-2 w-full rounded-xl border-slate-300"
                            required>

                    </div>

                    <div>

                        <label class="font-semibold">

                            Jam Selesai

                        </label>

                        <input type="time" name="booking_jam_selesai" class="mt-2 w-full rounded-xl border-slate-300"
                            required>

                    </div>

                </div>


            </div>

            <div id="availabilityResult"></div>
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

                <div class="space-y-6">

                    <div>

                        <label class="font-semibold">

                            Peruntukan

                        </label>

                        <textarea name="booking_peruntukan" rows="4" class="mt-2 w-full rounded-xl border-slate-300"
                            placeholder="Masukkan tujuan penggunaan ruang..." required>{{ old('booking_peruntukan') }}</textarea>

                    </div>

                    <div>

                        <label class="font-semibold">

                            Surat Undangan (Opsional)

                        </label>

                        <input type="file" name="booking_surat" accept=".pdf"
                            class="mt-2 w-full rounded-xl border-slate-300">

                        <p class="text-sm text-slate-500 mt-1">

                            Maksimal 5 MB (PDF)

                        </p>

                    </div>

                    <div>

                        <label class="font-semibold">

                            Catatan

                        </label>

                        <textarea name="booking_catatan" rows="3" class="mt-2 w-full rounded-xl border-slate-300"
                            placeholder="Catatan tambahan (opsional)...">{{ old('booking_catatan') }}</textarea>

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3">

                <a href="{{ route('user.booking-ruang.dashboard') }}"
                    class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200">

                    Kembali

                </a>

                <button class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">

                    Simpan Booking

                </button>

            </div>

        </form>

    </div>

@endsection
@push('scripts')
    <script>
        function checkAvailability() {

            let data = {

                booking_ruang_id: $('[name=booking_ruang_id]').val(),

                booking_tanggal: $('[name=booking_tanggal]').val(),

                booking_jam_mulai: $('[name=booking_jam_mulai]').val(),

                booking_jam_selesai: $('[name=booking_jam_selesai]').val(),

                _token: "{{ csrf_token() }}"

            };

            if (

                !data.booking_ruang_id ||

                !data.booking_tanggal ||

                !data.booking_jam_mulai ||

                !data.booking_jam_selesai

            ) {

                return;

            }

            $.post(

                "{{ route('user.booking-ruang.check') }}",

                data,

                function(res) {

                    if (res.status) {

                        $('#availabilityResult').html(

                            `<div class="rounded-2xl bg-green-50 border border-green-200 p-4">

                        <div class="font-semibold text-green-700">

                            ✅ Ruangan tersedia

                        </div>

                    </div>`

                        );

                    } else {

                        $('#availabilityResult').html(

                            `<div class="rounded-2xl bg-red-50 border border-red-200 p-4">

                        <div class="font-bold text-red-700">

                            ❌ Ruangan sudah dipakai

                        </div>

                        <div class="mt-2 text-sm">

                            <b>Peruntukan :</b>

                            ${res.booking.peruntukan}<br>

                            <b>Operator :</b>

                            ${res.booking.operator}<br>

                            <b>Jam :</b>

                            ${res.booking.mulai}

                            -

                            ${res.booking.selesai}

                        </div>

                    </div>`

                        );

                    }

                }

            );

        }

        $('[name=booking_ruang_id]').change(checkAvailability);

        $('[name=booking_tanggal]').change(checkAvailability);

        $('[name=booking_jam_mulai]').change(checkAvailability);

        $('[name=booking_jam_selesai]').change(checkAvailability);
    </script>
@endpush
