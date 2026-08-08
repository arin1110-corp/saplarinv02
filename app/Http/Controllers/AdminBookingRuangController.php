<?php

namespace App\Http\Controllers;

use App\Models\ModelBookingRuang;
use App\Models\ModelRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBookingRuangController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $ruangs = ModelRuang::orderBy('ruang_nama')
            ->get();

        return view(
            'administrator-v2.booking-ruang.index',
            compact('ruangs')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EVENTS CALENDAR
    |--------------------------------------------------------------------------
    */

    public function events(Request $request)
    {
        $events = ModelBookingRuang::with('ruang')
            ->when(
                $request->ruang,
                fn($q) =>
                $q->where('booking_ruang_id', $request->ruang)
            )
            ->where('booking_status', '!=', 'Batal')
            ->get()
            ->map(function ($item) {

                return [

                    'id' => $item->booking_uid,

                    'title' => $item->booking_created_by_unit,

                    'start' =>
                    $item->booking_tanggal->format('Y-m-d')
                        . 'T'
                        . $item->booking_jam_mulai,

                    'end' =>
                    $item->booking_tanggal->format('Y-m-d')
                        . 'T'
                        . $item->booking_jam_selesai,

                    'extendedProps' => [

                        'peruntukan' =>
                        $item->booking_peruntukan,

                        'operator' =>
                        $item->booking_created_by_nama,

                        'bidang' =>
                        $item->booking_created_by_unit,

                        'jam' =>
                        substr($item->booking_jam_mulai, 0, 5)
                            . ' - ' .
                            substr($item->booking_jam_selesai, 0, 5),

                    ],

                    'color' => match ($item->booking_status) {

                        'Disetujui' => '#22c55e',

                        'Menunggu' => '#f59e0b',

                        'Ditolak' => '#ef4444',

                        'Selesai' => '#3b82f6',

                        default => '#94a3b8',
                    },

                ];
            });

        return response()->json($events);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL BOOKING
    |--------------------------------------------------------------------------
    */

    public function detail($uid)
    {
        $booking = ModelBookingRuang::with('ruang')
            ->where('booking_uid', $uid)
            ->firstOrFail();

        return view(
            'administrator-v2.booking-ruang.detail',
            compact('booking')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BATAL BOOKING
    |--------------------------------------------------------------------------
    */

    public function batal($uid)
    {
        $booking = ModelBookingRuang::where(
            'booking_uid',
            $uid
        )->firstOrFail();

        if ($booking->booking_status === 'Batal') {

            return response()->json([
                'status' => false,
                'message' => 'Booking sudah dibatalkan.'
            ]);
        }

        $booking->update([
            'booking_status' => 'Batal',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil dibatalkan.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PENGELOLAAN RUANG
    |--------------------------------------------------------------------------
    */

    public function ruang()
    {
        $ruangs = ModelRuang::withCount('booking')
            ->orderBy('ruang_nama')
            ->get();

        return view(
            'administrator-v2.booking-ruang.ruang',
            compact('ruangs')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH RUANG
    |--------------------------------------------------------------------------
    */

    public function ruangStore(Request $request)
    {
        $request->validate([

            'ruang_nama' =>
            'required|string|max:150',

            'ruang_lokasi' =>
            'required|string|max:255',

            'ruang_kapasitas' =>
            'nullable|integer|min:1',

            'ruang_fasilitas' =>
            'nullable|string',

        ]);


        ModelRuang::create([

            'ruang_uid' =>
            (string) Str::uuid(),

            'ruang_nama' =>
            $request->ruang_nama,

            'ruang_lokasi' =>
            $request->ruang_lokasi,

            'ruang_kapasitas' =>
            $request->ruang_kapasitas,

            'ruang_fasilitas' =>
            $request->ruang_fasilitas,

            'ruang_status' =>
            true,

        ]);


        return back()->with(
            'success',
            'Ruang rapat berhasil ditambahkan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE RUANG
    |--------------------------------------------------------------------------
    */

    public function ruangUpdate(
        Request $request,
        $uid
    ) {

        $request->validate([

            'ruang_nama' =>
            'required|string|max:150',

            'ruang_lokasi' =>
            'required|string|max:255',

            'ruang_kapasitas' =>
            'nullable|integer|min:1',

            'ruang_fasilitas' =>
            'nullable|string',

        ]);


        $ruang = ModelRuang::where(
            'ruang_uid',
            $uid
        )->firstOrFail();


        $ruang->update([

            'ruang_nama' =>
            $request->ruang_nama,

            'ruang_lokasi' =>
            $request->ruang_lokasi,

            'ruang_kapasitas' =>
            $request->ruang_kapasitas,

            'ruang_fasilitas' =>
            $request->ruang_fasilitas,

        ]);


        return back()->with(
            'success',
            'Ruang rapat berhasil diperbarui.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS RUANG
    |--------------------------------------------------------------------------
    */

    public function ruangStatus($uid)
    {
        $ruang = ModelRuang::where(
            'ruang_uid',
            $uid
        )->firstOrFail();


        $ruang->update([

            'ruang_status' =>
            !$ruang->ruang_status,

        ]);


        return back()->with(
            'success',
            'Status ruang berhasil diperbarui.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE RUANG
    |--------------------------------------------------------------------------
    */

    public function ruangDelete($uid)
    {
        $ruang = ModelRuang::where(
            'ruang_uid',
            $uid
        )->firstOrFail();


        $jumlahBooking = ModelBookingRuang::where(
            'booking_ruang_id',
            $ruang->ruang_id
        )->count();


        if ($jumlahBooking > 0) {

            return back()->with(
                'error',
                'Ruang tidak dapat dihapus karena sudah memiliki data booking.'
            );
        }


        $ruang->delete();


        return back()->with(
            'success',
            'Ruang berhasil dihapus.'
        );
    }
}