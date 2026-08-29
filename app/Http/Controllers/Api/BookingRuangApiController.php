<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelBookingRuang;
use App\Models\ModelRuang;
use App\Services\ArinDriveService;
use App\Services\BBMEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingRuangApiController extends Controller
{
    /**
     * Daftar ruang yang aktif.
     */
    public function rooms(): JsonResponse
    {
        $ruangs = ModelRuang::where('ruang_status', 1)->orderBy('ruang_nama')->get();

        return response()->json([
            'status' => true,
            'data' => $ruangs
                ->map(function ($ruang) {
                    return [
                        'id' => $ruang->ruang_id,
                        'nama' => $ruang->ruang_nama,
                        'lokasi' => $ruang->ruang_lokasi,
                        'kapasitas' => $ruang->ruang_kapasitas,
                        'keterangan' => $ruang->ruang_keterangan,
                        'status' => (bool) $ruang->ruang_status,
                    ];
                })
                ->values(),
        ]);
    }

    /**
     * Daftar booking milik user yang sedang login.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $bookings = ModelBookingRuang::with('ruang')->where('booking_created_by', $user->user_nip)->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $bookings
                ->map(function ($booking) {
                    return $this->formatBooking($booking);
                })
                ->values(),
        ]);
    }

    /**
     * Detail booking.
     */
    public function show(Request $request, string $uid): JsonResponse
    {
        $user = $request->user();

        $booking = ModelBookingRuang::with('ruang')->where('booking_uid', $uid)->where('booking_created_by', $user->user_nip)->first();

        if (!$booking) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Booking tidak ditemukan.',
                ],
                404,
            );
        }

        return response()->json([
            'status' => true,
            'data' => $this->formatBooking($booking),
        ]);
    }

    /**
     * Buat booking baru.
     */
    public function store(Request $request, BBMEmailService $emailService, ArinDriveService $arinDrive): JsonResponse
    {
        $request->validate([
            'booking_ruang_id' => 'required',
            'booking_tanggal' => 'required|date',
            'booking_jam_mulai' => 'required',
            'booking_jam_selesai' => 'required|after:booking_jam_mulai',
            'booking_peruntukan' => 'required|string',
            'booking_catatan' => 'nullable|string',
            'booking_surat' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Pastikan ruang aktif
        |--------------------------------------------------------------------------
        */

        $ruang = ModelRuang::where('ruang_id', $request->booking_ruang_id)->where('ruang_status', 1)->first();

        if (!$ruang) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Ruang rapat tidak tersedia.',
                ],
                422,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cek bentrok jadwal
        |--------------------------------------------------------------------------
        */

        $bentrok = ModelBookingRuang::where('booking_ruang_id', $request->booking_ruang_id)
            ->whereDate('booking_tanggal', $request->booking_tanggal)
            ->whereIn('booking_status', ['Menunggu', 'Disetujui'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('booking_jam_mulai', [$request->booking_jam_mulai, $request->booking_jam_selesai])

                    ->orWhereBetween('booking_jam_selesai', [$request->booking_jam_mulai, $request->booking_jam_selesai])

                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('booking_jam_mulai', '<=', $request->booking_jam_mulai)->where('booking_jam_selesai', '>=', $request->booking_jam_selesai);
                    });
            })
            ->first();

        if ($bentrok) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Jadwal sudah digunakan.',
                    'booking' => [
                        'peruntukan' => $bentrok->booking_peruntukan,
                        'mulai' => $bentrok->booking_jam_mulai,
                        'selesai' => $bentrok->booking_jam_selesai,
                        'operator' => $bentrok->booking_created_by_nama,
                    ],
                ],
                422,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload surat
        |--------------------------------------------------------------------------
        */

        $uid = (string) Str::uuid();

        $surat = null;

        if ($request->hasFile('booking_surat')) {
            $surat = $arinDrive->upload($request->file('booking_surat'), 'booking_ruang_surat', $uid . '_SURAT.' . $request->file('booking_surat')->getClientOriginalExtension(), $uid);
        }

        /*
        |--------------------------------------------------------------------------
        | Buat booking
        |--------------------------------------------------------------------------
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

            /*
             * Mengikuti Controller Web existing:
             * booking baru langsung Disetujui.
             */
            'booking_status' => 'Disetujui',

            'booking_created_by' => $user->user_nip,

            'booking_created_by_nama' => $user->user_nama,

            'booking_created_by_nip' => $user->user_nip,

            'booking_created_by_unit' => $user->user_bidang ?? ($user->user_bidang_nama ?? '-'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load relation untuk response + email
        |--------------------------------------------------------------------------
        */

        $booking->load('ruang');

        /*
        |--------------------------------------------------------------------------
        | Email admin/operator
        |--------------------------------------------------------------------------
        */

        $emailService->kirimKeAdminBBM(
            'Booking Ruang Rapat oleh - ' . $booking->booking_created_by_nama,

            "Yth. Operator Ruang Rapat,\n\n" . "Terdapat booking ruang rapat baru dengan data berikut:\n\n" . "Nama Pengaju       : {$booking->booking_created_by_nama}\n" . "NIP                : {$booking->booking_created_by_nip}\n" . "Bidang             : {$booking->booking_created_by_unit}\n" . "Peruntukan         : {$booking->booking_peruntukan}\n" . "Ruang Rapat        : {$booking->ruang->ruang_nama}\n" . "Tanggal Booking    : {$booking->booking_tanggal}\n" . "Jam Mulai          : {$booking->booking_jam_mulai}\n" . "Jam Selesai        : {$booking->booking_jam_selesai}\n" . "Surat Undangan     : {$booking->booking_surat}\n\n" . "Silakan login ke SAPLARIN untuk memantau status booking.\n\n" . 'SAPLARIN',
        );

        return response()->json(
            [
                'status' => true,
                'message' => 'Booking berhasil dikirim.',
                'data' => $this->formatBooking($booking),
            ],
            201,
        );
    }

    /**
     * Cek ketersediaan ruang.
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'booking_ruang_id' => 'required',
            'booking_tanggal' => 'required|date',
            'booking_jam_mulai' => 'required',
            'booking_jam_selesai' => 'required',
        ]);

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
                    'peruntukan' => $bentrok->booking_peruntukan,
                    'mulai' => $bentrok->booking_jam_mulai,
                    'selesai' => $bentrok->booking_jam_selesai,
                    'operator' => $bentrok->booking_created_by_nama,
                ],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Ruangan tersedia.',
        ]);
    }

    /**
     * Event kalender.
     */
    public function events(Request $request): JsonResponse
    {
        $events = ModelBookingRuang::with('ruang')

            ->when($request->filled('ruang'), fn($q) => $q->where('booking_ruang_id', $request->ruang)->where('booking_status', 'Disetujui'))

            ->whereIn('booking_status', ['Disetujui'])

            ->get()

            ->map(function ($item) {
                return [
                    'id' => $item->booking_uid,

                    'title' => $item->booking_created_by_unit,

                    'start' => $item->booking_tanggal->format('Y-m-d') . 'T' . $item->booking_jam_mulai,

                    'end' => $item->booking_tanggal->format('Y-m-d') . 'T' . $item->booking_jam_selesai,

                    'display' => 'block',

                    'textColor' => '#ffffff',

                    'extendedProps' => [
                        'peruntukan' => $item->booking_peruntukan,

                        'operator' => $item->booking_created_by_nama,

                        'jam' => substr($item->booking_jam_mulai, 0, 5) . ' - ' . substr($item->booking_jam_selesai, 0, 5),

                        'ruang' => $item->ruang?->ruang_nama,
                    ],

                    'color' => '#22c55e',
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'data' => $events,
        ]);
    }

    /**
     * Batalkan booking.
     */
    public function batal(Request $request, string $uid): JsonResponse
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

        if (!in_array($booking->booking_status, ['Menunggu', 'Disetujui'])) {
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

    /**
     * Format response booking untuk Flutter.
     */
    private function formatBooking(ModelBookingRuang $booking): array
    {
        return [
            'uid' => $booking->booking_uid,

            'ruang_id' => $booking->booking_ruang_id,

            'ruang' => $booking->ruang
                ? [
                    'id' => $booking->ruang->ruang_id,
                    'nama' => $booking->ruang->ruang_nama,
                    'lokasi' => $booking->ruang->ruang_lokasi,
                    'kapasitas' => $booking->ruang->ruang_kapasitas,
                    'keterangan' => $booking->ruang->ruang_keterangan,
                ]
                : null,

            'tanggal' => $booking->booking_tanggal?->format('Y-m-d'),

            'jam_mulai' => $booking->booking_jam_mulai,

            'jam_selesai' => $booking->booking_jam_selesai,

            'peruntukan' => $booking->booking_peruntukan,

            'surat' => $booking->booking_surat,

            'catatan' => $booking->booking_catatan,

            'status' => $booking->booking_status,

            'created_by' => $booking->booking_created_by,

            'created_by_nama' => $booking->booking_created_by_nama,

            'created_by_nip' => $booking->booking_created_by_nip,

            'created_by_unit' => $booking->booking_created_by_unit,

            'verifikator' => $booking->booking_verifikator,

            'verifikasi_at' => $booking->booking_verifikasi_at?->format('Y-m-d H:i:s'),

            'catatan_admin' => $booking->booking_catatan_admin,

            'created_at' => $booking->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}