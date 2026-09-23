<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPegawaiSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('logged_in') || !session('pegawai_id')) {
            session()->flush();

            return redirect('/')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        return $next($request);
    }
}