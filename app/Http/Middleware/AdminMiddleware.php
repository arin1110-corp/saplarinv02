<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Cek Session Login
        |--------------------------------------------------------------------------
        */

        if (!session('logged_in') || !session('pegawai_id')) {
            session()->flush();

            return redirect('/')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        /*
        |--------------------------------------------------------------------------
        | Cek Role Admin
        |--------------------------------------------------------------------------
        */

        $role = session('active_role');

        if (!$role || !str_starts_with($role, 'Admin')) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}