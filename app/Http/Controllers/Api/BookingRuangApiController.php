<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelBookingRuang;
use App\Models\ModelRuang;
use App\Services\ArinDriveService;
use App\Services\BBMEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingRuangApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ROOMS
    |--------------------------------------------------------------------------
    */

    public function rooms()
    {
        $ruangs = ModelRuang::query()->where('ruang_status', 1)->orderBy('ruang_nama')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data ruang rapat berhasil diambil.',
            'data' => $ruangs
                ->map(function ($ruang) {
                    return [
                        'ruang_id' => $ruang->ruang_id,
                        'ruang_uid' => $ruang->ruang_uid,
                        'ruang_nama' => $ruang->ruang_nama,
                        'ruang_lokasi' => $ruang->ruang_lokasi,
                        'ruang_kapasitas' => $ruang->ruang_kapasitas,
                        'ruang_keterangan' => $ruang->ruang_keterangan,
                        'ruang_status' => $ruang->ruang_status,
                    ];
                })
                ->values(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING SAYA
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = ModelBookingRuang::with('ruang')->where('booking_created_by', $user->user_nip)->latest('booking_id')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data booking berhasil diambil.',
            'data' => $bookings->map(fn($booking) => $this->formatBooking($booking))->values(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BOOKING
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, string $uid)
    {
        $user = $request->user();

        $booking = ModelBookingRuang::with('ruang')->where('booking_uid', $uid)->where('booking_created_by', $user->user_nip)->first();

        if (!$booking) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Booking tidak ditemukan.',
                    'data' => null,
                ],
                404,
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail booking berhasil diambil.',
            'data' => $this->formatBooking($booking),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTS / KALENDER
    |--------------------------------------------------------------------------
    */

    public function events(Request $request)
    {
        $query = ModelBookingRuang::with('ruang')->whereIn('booking_status', ['Disetujui']);

        /*
        |--------------------------------------------------------------------------
        | FILTER RUANG
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ruang')) {
            $query->where('booking_ruang_id', $request->ruang);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start')) {
            $query->whereDate('booking_tanggal', '>=', $request->start);
        }

        if ($request->filled('end')) {
            $query->whereDate('booking_tanggal', '<=', $request->end);
        }

        $bookings = $query->orderBy('booking_tanggal')->orderBy('booking_jam_mulai')->get();

        $events = $bookings
            ->map(function ($item) {
                return [
                    'id' => $item->booking_uid,

                    'title' => $item->booking_created_by_unit,

                    'start' => $item->booking_tanggal->format('Y-m-d') . 'T' . $item->booking_jam_mulai,

                    'end' => $item->booking_tanggal->format('Y-m-d') . 'T' . $item->booking_jam_selesai,

                    'display' => 'block',

                    'textColor' => '#ffffff',

                    'color' => '#22c55e',

                    'extendedProps' => [
                        'uid' => $item->booking_uid,

                        'ruang_id' => $item->booking_ruang_id,

                        'ruang_nama' => $item->ruang?->ruang_nama,

                        'peruntukan' => $item->booking_peruntukan,

                        'operator' => $item->booking_created_by_nama,

                        'nip' => $item->booking_created_by_nip,

                        'unit' => $item->booking_created_by_unit,

                        'jam' => substr($item->booking_jam_mulai, 0, 5) . ' - ' . substr($item->booking_jam_selesai, 0, 5),
                    ],
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'message' => 'Data kalender berhasil diambil.',
            'data' => $events,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK AVAILABILITY
    |--------------------------------------------------------------------------
    */

    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_ruang_id' => 'required|integer',

            'booking_tanggal' => 'required|date',

            'booking_jam_mulai' => 'required|date_format:H:i',

            'booking_jam_selesai' => 'required|date_format:H:i|after:booking_jam_mulai',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan ruang masih aktif
        |--------------------------------------------------------------------------
        */

        $ruang = ModelRuang::where('ruang_id', $request->booking_ruang_id)->where('ruang_status', 1)->first();

        if (!$ruang) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Ruang rapat tidak ditemukan atau tidak aktif.',
                    'booking' => null,
                ],
                404,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK BENTROK
        |--------------------------------------------------------------------------
        |
        | Sama dengan logika Laravel web:
        |
        | - Menunggu
        | - Disetujui
        |
        */

        $bentrok = ModelBookingRuang::with('ruang')
            ->where('booking_ruang_id', $request->booking_ruang_id)
            ->whereDate('booking_tanggal', $request->booking_tanggal)
            ->whereIn('booking_status', ['Menunggu', 'Disetujui'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('booking_jam_mulai', [$request->booking_jam_mulai, $request->booking_jam_selesai])

                    ->orWhereBetween('booking_jam_selesai', [$request->booking_jam_mulai, $request->booking_jam_selesai])

                    ->orWhere(function ($qq) use ($request) {
                        $qq->where('booking_jam_mulai', '<', $request->booking_jam_mulai)->where('booking_jam_selesai', '>', $request->booking_jam_selesai);
                    });
            })
            ->first();

        if ($bentrok) {
            return response()->json([
                'status' => false,

                'message' => 'Ruangan sudah dibooking.',

                'booking' => [
                    'uid' => $bentrok->booking_uid,

                    'ruang_nama' => $bentrok->ruang?->ruang_nama,

                    'peruntukan' => $bentrok->booking_peruntukan,

                    'mulai' => $bentrok->booking_jam_mulai,

                    'selesai' => $bentrok->booking_jam_selesai,

                    'operator' => $bentrok->booking_created_by_nama,

                    'nip' => $bentrok->booking_created_by_nip,

                    'unit' => $bentrok->booking_created_by_unit,

                    'status' => $bentrok->booking_status,
                ],
            ]);
        }

        return response()->json([
            'status' => true,

            'message' => 'Ruangan tersedia.',

            'booking' => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE BOOKING
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, BBMEmailService $emailService, ArinDriveService $arinDrive)
    {
        $validator = Validator::make($request->all(), [
            'booking_ruang_id' => 'required|integer',

            'booking_tanggal' => 'required|date',

            'booking_jam_mulai' => 'required|date_format:H:i',

            'booking_jam_selesai' => 'required|date_format:H:i|after:booking_jam_mulai',

            'booking_peruntukan' => 'required|string',

            'booking_catatan' => 'nullable|string',

            'booking_surat' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | CEK RUANG
        |--------------------------------------------------------------------------
        */

        $ruang = ModelRuang::where('ruang_id', $request->booking_ruang_id)->where('ruang_status', 1)->first();

        if (!$ruang) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Ruang rapat tidak ditemukan atau tidak aktif.',
                ],
                404,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK BENTROK LAGI
        |--------------------------------------------------------------------------
        |
        | WAJIB dicek lagi ketika benar-benar menyimpan.
        |
        | Jangan hanya mengandalkan checkAvailability
        | karena setelah user melakukan pengecekan,
        | user lain bisa saja melakukan booking terlebih dahulu.
        |
        */

        $bentrok = ModelBookingRuang::where('booking_ruang_id', $request->booking_ruang_id)
            ->whereDate('booking_tanggal', $request->booking_tanggal)
            ->whereIn('booking_status', ['Menunggu', 'Disetujui'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('booking_jam_mulai', [$request->booking_jam_mulai, $request->booking_jam_selesai])

                    ->orWhereBetween('booking_jam_selesai', [$request->booking_jam_mulai, $request->booking_jam_selesai])

                    ->orWhere(function ($qq) use ($request) {
                        $qq->where('booking_jam_mulai', '<', $request->booking_jam_mulai)->where('booking_jam_selesai', '>', $request->booking_jam_selesai);
                    });
            })
            ->first();

        if ($bentrok) {
            return response()->json(
                [
                    'status' => false,

                    'message' => 'Ruangan sudah dibooking pada waktu tersebut.',

                    'booking' => [
                        'uid' => $bentrok->booking_uid,

                        'peruntukan' => $bentrok->booking_peruntukan,

                        'mulai' => $bentrok->booking_jam_mulai,

                        'selesai' => $bentrok->booking_jam_selesai,

                        'operator' => $bentrok->booking_created_by_nama,

                        'nip' => $bentrok->booking_created_by_nip,

                        'unit' => $bentrok->booking_created_by_unit,

                        'status' => $bentrok->booking_status,
                    ],
                ],
                409,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UID
        |--------------------------------------------------------------------------
        */

        $uid = (string) Str::uuid();

        $surat = null;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD SURAT KE ARINDRIVE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('booking_surat')) {
            $file = $request->file('booking_surat');

            $surat = $arinDrive->upload($file, 'booking_ruang_surat', $uid . '_SURAT.' . $file->getClientOriginalExtension(), $uid);
        }

        /*
        |--------------------------------------------------------------------------
        | BUAT BOOKING
        |--------------------------------------------------------------------------
        |
        | Mengikuti Laravel web:
        |
        | booking_status = Disetujui
        |
        */

        $booking = ModelBookingRuang::create([
            'booking_uid' => $uid,

            'booking_ruang_id' => $request->booking_ruang_id,

            'booking_tanggal' => $request->booking_tanggal,

            'booking_jam_mulai' => $request->booking_jam_mulai,

            'booking_jam_selesai' => $request->booking_jam_selesai,

            'booking_peruntukan' => $request->booking_peruntukan,

            'booking_surat' => $surat,

            'booking_catatan' => $request->booking_catatan,

            'booking_status' => 'Disetujui',

            'booking_created_by' => $user->user_nip,

            'booking_created_by_nama' => $user->user_nama,

            'booking_created_by_nip' => $user->user_nip,

            'booking_created_by_unit' => $user->user_bidang,
        ]);

        /*
        |--------------------------------------------------------------------------
        | EMAIL ADMIN
        |--------------------------------------------------------------------------
        */

        try {
            $emailService->kirimKeAdminBBM(
                'Booking Ruang Rapat oleh - ' . $booking->booking_created_by_nama,

                "Yth. Operator Ruang Rapat,\n\n" . "Terdapat pengajuan booking ruang rapat baru dengan data berikut:\n\n" . "Nama Pengaju       : {$booking->booking_created_by_nama}\n" . "NIP                : {$booking->booking_created_by_nip}\n" . "Bidang             : {$booking->booking_created_by_unit}\n" . "Peruntukan         : {$booking->booking_peruntukan}\n" . "Ruang Rapat        : {$ruang->ruang_nama}\n" . "Tanggal Booking    : {$booking->booking_tanggal}\n" . "Jam Mulai          : {$booking->booking_jam_mulai}\n" . "Jam Selesai        : {$booking->booking_jam_selesai}\n" . "Surat Undangan     : {$booking->booking_surat}\n\n" . "Silakan login ke SAPLARIN untuk memantau status pengajuan.\n\n" . 'SAPLARIN',
            );
        } catch (\Throwable $e) {
            /*
            |--------------------------------------------------------------------------
            | Email gagal tidak membatalkan booking.
            |--------------------------------------------------------------------------
            */

            report($e);
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        $booking->load('ruang');

        return response()->json(
            [
                'status' => true,

                'message' => 'Booking berhasil dikirim.',

                'data' => $this->formatBooking($booking),
            ],
            201,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BATAL BOOKING
    |--------------------------------------------------------------------------
    */

    public function cancel(Request $request, string $uid)
    {
        $user = $request->user();

        $booking = ModelBookingRuang::where('booking_uid', $uid)->where('booking_created_by', $user->user_nip)->first();

        if (!$booking) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Booking tidak ditemukan.',
                ],
                404,
            );
        }

        if (!in_array($booking->booking_status, ['Menunggu', 'Disetujui'], true)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Booking tidak dapat dibatalkan.',
                ],
                422,
            );
        }

        $booking->update([
            'booking_status' => 'Batal',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil dibatalkan.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT BOOKING
    |--------------------------------------------------------------------------
    */

    private function formatBooking(ModelBookingRuang $booking): array
    {
        return [
            'booking_id' => $booking->booking_id,

            'booking_uid' => $booking->booking_uid,

            'booking_ruang_id' => $booking->booking_ruang_id,

            'booking_tanggal' => $booking->booking_tanggal?->format('Y-m-d'),

            'booking_jam_mulai' => $booking->booking_jam_mulai,

            'booking_jam_selesai' => $booking->booking_jam_selesai,

            'booking_peruntukan' => $booking->booking_peruntukan,

            'booking_surat' => $booking->booking_surat,

            'booking_catatan' => $booking->booking_catatan,

            'booking_status' => $booking->booking_status,

            'booking_created_by' => $booking->booking_created_by,

            'booking_created_by_nama' => $booking->booking_created_by_nama,

            'booking_created_by_nip' => $booking->booking_created_by_nip,

            'booking_created_by_unit' => $booking->booking_created_by_unit,

            'booking_verifikator' => $booking->booking_verifikator,

            'booking_verifikasi_at' => $booking->booking_verifikasi_at?->toDateTimeString(),

            'booking_catatan_admin' => $booking->booking_catatan_admin,

            'ruang' => $booking->ruang
                ? [
                    'ruang_id' => $booking->ruang->ruang_id,

                    'ruang_uid' => $booking->ruang->ruang_uid,

                    'ruang_nama' => $booking->ruang->ruang_nama,

                    'ruang_lokasi' => $booking->ruang->ruang_lokasi,

                    'ruang_kapasitas' => $booking->ruang->ruang_kapasitas,

                    'ruang_keterangan' => $booking->ruang->ruang_keterangan,

                    'ruang_status' => $booking->ruang->ruang_status,
                ]
                : null,
        ];
    }
}
