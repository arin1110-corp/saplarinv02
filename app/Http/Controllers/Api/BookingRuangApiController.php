<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelBookingRuang;
use App\Models\ModelRuang;
use App\Services\ArinDriveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingRuangApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR RUANG
    |--------------------------------------------------------------------------
    */

    public function rooms(): JsonResponse
    {
        $ruangs = ModelRuang::where('ruang_status', 1)->orderBy('ruang_nama')->get();

        $data = $ruangs
            ->map(function ($ruang) {
                return [
                'id' => (int) $ruang->ruang_id,
                    'nama' => $ruang->ruang_nama,
                'lokasi' => $ruang->ruang_lokasi ?? '-',
                'kapasitas' => (int) ($ruang->ruang_kapasitas ?? 0),
                'keterangan' => $ruang->ruang_keterangan ?? null,
                'status' => (bool) $ruang->ruang_status,
            ];
        })
            ->values();

        return response()->json([
            'status' => true,
            'message' => 'Data ruang berhasil diambil.',
            'data' => $data,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING SAYA
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): JsonResponse
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
    | SEMUA JADWAL KALENDER
    |--------------------------------------------------------------------------
    |
    | Hanya booking Disetujui yang ditampilkan.
    | Ini berbeda dengan index(), karena index hanya milik user login.
    |
    */

    public function events(Request $request): JsonResponse
    {
        $query = ModelBookingRuang::with('ruang')->where('booking_status', 'Disetujui');

        /*
         * Filter berdasarkan ruang jika dikirim.
         */
        if ($request->filled('ruang_id')) {
            $query->where('booking_ruang_id', $request->ruang_id);
        }

        /*
         * Filter tanggal mulai jika dikirim.
         */
        if ($request->filled('start')) {
            $query->whereDate('booking_tanggal', '>=', $request->start);
        }

        /*
         * Filter tanggal akhir jika dikirim.
         */
        if ($request->filled('end')) {
            $query->whereDate('booking_tanggal', '<=', $request->end);
        }

        $bookings = $query->orderBy('booking_tanggal')->orderBy('booking_jam_mulai')->get();

        $data = $bookings
            ->map(function ($booking) {
                return [
                    'uid' => $booking->booking_uid,

                    'ruang_id' => (int) $booking->booking_ruang_id,

                    'ruang_nama' => $booking->ruang ? $booking->ruang->ruang_nama : '-',

                    'tanggal' => $booking->booking_tanggal ? $booking->booking_tanggal->format('Y-m-d') : null,

                    'jam_mulai' => substr($booking->booking_jam_mulai, 0, 5),

                    'jam_selesai' => substr($booking->booking_jam_selesai, 0, 5),

                    'peruntukan' => $booking->booking_peruntukan,

                    'created_by_nama' => $booking->booking_created_by_nama,

                    'created_by_nip' => $booking->booking_created_by_nip,

                    'created_by_unit' => $booking->booking_created_by_unit,

                    'status' => $booking->booking_status,
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'message' => 'Jadwal berhasil diambil.',
            'data' => $data,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BOOKING
    |--------------------------------------------------------------------------
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
            'message' => 'Detail booking berhasil diambil.',
            'data' => $this->formatBooking($booking),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CEK KETERSEDIAAN
    |--------------------------------------------------------------------------
    */

    public function checkAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'booking_ruang_id' => ['required', 'integer', 'exists:saplarin_ruang,ruang_id'],

            'booking_tanggal' => ['required', 'date'],

            'booking_jam_mulai' => ['required', 'date_format:H:i'],

            'booking_jam_selesai' => ['required', 'date_format:H:i', 'after:booking_jam_mulai'],
        ]);

        $bentrok = ModelBookingRuang::with('ruang')
            ->where('booking_ruang_id', $request->booking_ruang_id)
            ->whereDate('booking_tanggal', $request->booking_tanggal)
            ->whereIn('booking_status', ['Menunggu', 'Disetujui'])
            ->where('booking_jam_mulai', '<', $request->booking_jam_selesai)
            ->where('booking_jam_selesai', '>', $request->booking_jam_mulai)
            ->first();

        if ($bentrok) {
            return response()->json([
                'status' => false,
                'message' => 'Ruangan sudah dibooking pada waktu tersebut.',

                'booking' => [
                    'uid' => $bentrok->booking_uid,

                    'ruang_nama' => $bentrok->ruang?->ruang_nama ?? '-',

                    'tanggal' => $bentrok->booking_tanggal?->format('Y-m-d'),

                    'peruntukan' => $bentrok->booking_peruntukan,

                    'mulai' => substr($bentrok->booking_jam_mulai, 0, 5),

                    'selesai' => substr($bentrok->booking_jam_selesai, 0, 5),

                    'operator' => $bentrok->booking_created_by_nama,

                    'unit' => $bentrok->booking_created_by_unit,
                ],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Ruangan tersedia.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BUAT BOOKING
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, ArinDriveService $arinDrive): JsonResponse
    {
        $request->validate([
            'booking_ruang_id' => ['required', 'integer', 'exists:saplarin_ruang,ruang_id'],

            'booking_tanggal' => ['required', 'date'],

            'booking_jam_mulai' => ['required', 'date_format:H:i'],

            'booking_jam_selesai' => ['required', 'date_format:H:i', 'after:booking_jam_mulai'],

            'booking_peruntukan' => ['required', 'string', 'max:500'],

            'booking_catatan' => ['nullable', 'string', 'max:1000'],

            /*
             * SURAT OPTIONAL
             */
            'booking_surat' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User tidak terautentikasi.',
                ],
                401,
            );
        }

        /*
         * Identitas dari Sanctum user.
         */
        $nip = $user->user_nip ?? null;
        $nama = $user->user_nama ?? null;
        $unit = $user->user_bidang ?? null;

        if (empty($nip)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'NIP user tidak ditemukan.',
                ],
                422,
            );
        }

        /*
         * Pastikan ruang aktif.
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
         * CEK BENTROK FINAL
         *
         * Tetap dilakukan di server.
         */
        $bentrok = ModelBookingRuang::where('booking_ruang_id', $request->booking_ruang_id)
            ->whereDate('booking_tanggal', $request->booking_tanggal)
            ->whereIn('booking_status', ['Menunggu', 'Disetujui'])
            ->where('booking_jam_mulai', '<', $request->booking_jam_selesai)
            ->where('booking_jam_selesai', '>', $request->booking_jam_mulai)
            ->exists();

        if ($bentrok) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Ruangan sudah dibooking pada waktu tersebut.',
                ],
                422,
            );
        }

        /*
         * UID.
         */
        $bookingUid = (string) Str::uuid();

        /*
         * Surat optional.
         */
        $surat = null;

        if ($request->hasFile('booking_surat')) {
            $file = $request->file('booking_surat');

            $surat = $arinDrive->upload($file, 'booking_ruang_surat', $bookingUid . '_SURAT.' . $file->getClientOriginalExtension(), $bookingUid);
        }

        /*
         * SIMPAN.
         */
        $booking = ModelBookingRuang::create([
            'booking_uid' => $bookingUid,

            'booking_ruang_id' => $request->booking_ruang_id,

            'booking_tanggal' => $request->booking_tanggal,

            'booking_jam_mulai' => $request->booking_jam_mulai,

            'booking_jam_selesai' => $request->booking_jam_selesai,

            'booking_peruntukan' => $request->booking_peruntukan,

            'booking_surat' => $surat,

            'booking_catatan' => $request->booking_catatan,

            'booking_status' => 'Disetujui',

            'booking_created_by' => $nip,

            'booking_created_by_nama' => $nama,

            'booking_created_by_nip' => $nip,

            'booking_created_by_unit' => $unit,
        ]);

        $booking->load('ruang');

        return response()->json(
            [
                'status' => true,
                'message' => 'Booking berhasil dibuat.',
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

    public function cancel(Request $request, string $uid): JsonResponse
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

    /*
    |--------------------------------------------------------------------------
    | FORMAT BOOKING
    |--------------------------------------------------------------------------
    */

    private function formatBooking(ModelBookingRuang $booking): array
    {
        return [
            'uid' => $booking->booking_uid,

            'ruang_id' => (int) $booking->booking_ruang_id,

            'ruang' => $booking->ruang
                ? [
                'id' => (int) $booking->ruang->ruang_id,

                'nama' => $booking->ruang->ruang_nama,

                'lokasi' => $booking->ruang->ruang_lokasi ?? '-',

                'kapasitas' => (int) ($booking->ruang->ruang_kapasitas ?? 0),

                'keterangan' => $booking->ruang->ruang_keterangan ?? null,
                ]
                : null,

            'tanggal' => $booking->booking_tanggal ? $booking->booking_tanggal->format('Y-m-d') : null,

            'jam_mulai' => substr($booking->booking_jam_mulai, 0, 5),

            'jam_selesai' => substr($booking->booking_jam_selesai, 0, 5),

            'peruntukan' => $booking->booking_peruntukan,

            'surat' => $booking->booking_surat,

            'catatan' => $booking->booking_catatan,

            'status' => $booking->booking_status,

            'created_by' => $booking->booking_created_by,

            'created_by_nama' => $booking->booking_created_by_nama,

            'created_by_nip' => $booking->booking_created_by_nip,

            'created_by_unit' => $booking->booking_created_by_unit,

            'verifikator' => $booking->booking_verifikator,

            'verifikasi_at' => $booking->booking_verifikasi_at ? $booking->booking_verifikasi_at->toIso8601String() : null,

            'catatan_admin' => $booking->booking_catatan_admin,

            'created_at' => $booking->created_at ? $booking->created_at->toIso8601String() : null,
        ];
    }
}
