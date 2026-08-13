<?php

namespace App\Http\Controllers;

use App\Models\ModelBookingRuang;
use App\Models\ModelRuang;
use Illuminate\Http\Request;
use App\Services\ArinDriveService;
use Illuminate\Support\Str;
use App\Services\BBMEmailService;

class UserBookingRuangController extends Controller
{
    public function dashboard()
    {
        $ruangs = ModelRuang::where('ruang_status', 1)->orderBy('ruang_nama')->get();

        return view('user.booking-ruang.dashboard', compact('ruangs'));
    }

    public function index()
    {
        $bookings = ModelBookingRuang::with('ruang')

            ->where('booking_created_by', session('pegawai_nip'))

            ->latest()

            ->get();

        return view('user.booking-ruang.index', compact('bookings'));
    }

    public function create()
    {
        $ruangs = ModelRuang::where('ruang_status', 1)

            ->orderBy('ruang_nama')

            ->get();

        return view('user.booking-ruang.create', compact('ruangs'));
    }

    public function store(Request $request, BBMEmailService $emailService, ArinDriveService $arinDrive)
    {
        $request->validate([
            'booking_ruang_id' => 'required',

            'booking_tanggal' => 'required|date',

            'booking_jam_mulai' => 'required',

            'booking_jam_selesai' => 'required|after:booking_jam_mulai',

            'booking_peruntukan' => 'required',

            'booking_surat' => 'nullable|mimes:pdf|max:5120',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cek Bentrok Jadwal
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

            ->exists();

        if ($bentrok) {
            return back()
                ->withInput()

                ->with('error', 'Jadwal sudah digunakan.');
        }
        $uid = (string) Str::uuid();
        $surat = null;

        if ($request->hasFile('booking_surat')) {
            $surat = $arinDrive->upload(
                $request->file('booking_surat'),

                'booking_ruang_surat',

                $uid . '_SURAT.' . $request->file('booking_surat')->getClientOriginalExtension(),

                $uid,
            );
        }

        $booking = ModelBookingRuang::create([
            'booking_ruang_id' => $request->booking_ruang_id,

            'booking_tanggal' => $request->booking_tanggal,

            'booking_jam_mulai' => $request->booking_jam_mulai,

            'booking_jam_selesai' => $request->booking_jam_selesai,

            'booking_peruntukan' => $request->booking_peruntukan,

            'booking_surat' => $surat,

            'booking_catatan' => $request->booking_catatan,

            'booking_status' => 'Disetujui',

            'booking_created_by' => session('pegawai_nip'),

            'booking_created_by_nama' => session('pegawai_nama'),

            'booking_created_by_nip' => session('pegawai_nip'),

            'booking_created_by_unit' => session('pegawai_bidang'),
        ]);

        $emailService->kirimKeAdminBBM(
            'Booking Ruang Rapat oleh - ' . $booking->booking_created_by_nama,
            "Yth. Operator Ruang Rapat,\n\n" .
                "Terdapat pengajuan booking ruang rapat baru dengan data berikut:\n\n" .
                "Nama Pengaju       : {$booking->booking_created_by_nama}\n" .
                "NIP                : {$booking->booking_created_by_nip}\n" .
                "Bidang             : {$booking->booking_created_by_unit}\n" .
                "Peruntukan         : {$booking->booking_peruntukan}\n" .
                "Ruang Rapat        : {$booking->ruang->ruang_nama}\n" .
                "Tanggal Booking    : {$booking->booking_tanggal}\n" . "Jam Mulai    : {$booking->booking_jam_mulai}\n" . "Jam Selesai  : {$booking->booking_jam_selesai}\n" .
                "Surat Undangan     : {$booking->booking_surat}\n\n" .
                "Silakan login ke SAPLARIN untuk memantau status pengajuan.\n\n" .
                "SAPLARIN"
        );

        return redirect()
            ->route('user.booking-ruang.index')

            ->with('success', 'Booking berhasil dikirim.');
    }

    public function show($uid)
    {
        $booking = ModelBookingRuang::with('ruang')

            ->where('booking_uid', $uid)

            ->firstOrFail();

        return view('user.booking-ruang.show', compact('booking'));
    }

    public function events(Request $request)
    {
        $events = ModelBookingRuang::with('ruang')

            ->when($request->ruang, fn($q) => $q->where('booking_ruang_id', $request->ruang)->whereIn('booking_status', ['Disetujui']))

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
                    ],

                    'color' => match ($item->booking_status) {
                        'Disetujui' => '#22c55e',
                        'Menunggu' => '#f59e0b',
                        'Ditolak' => '#ef4444',
                        'Selesai' => '#3b82f6',
                        default => '#f60b0b',
                    },
                ];
            });

        return response()->json($events);
    }
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'booking_ruang_id' => 'required',

            'booking_tanggal' => 'required',

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
                        $qq->where(
                            'booking_jam_mulai',

                            '<',

                            $request->booking_jam_mulai,
                        )->where(
                            'booking_jam_selesai',

                            '>',

                            $request->booking_jam_selesai,
                        );
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
    public function detail($uid)
    {
        $booking = ModelBookingRuang::with('ruang')

            ->where('booking_uid', $uid)

            ->where('booking_created_by', session('pegawai_nip'))

            ->firstOrFail();

        return view('user.booking-ruang.detail', compact('booking'));
    }
    public function batal($uid)
    {
        $booking = ModelBookingRuang::where('booking_uid', $uid)->where('booking_created_by', session('pegawai_nip'))->firstOrFail();

        if (!in_array($booking->booking_status, ['Menunggu', 'Disetujui'])) {
            return response()->json([
                'status' => false,

                'message' => 'Booking tidak dapat dibatalkan.',
            ]);
        }

        $booking->update([
            'booking_status' => 'Batal',
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
}