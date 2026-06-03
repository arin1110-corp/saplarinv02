<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (!session('logged_in')) {
            return redirect('/')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $role = session('active_role');

        if (!$role || !str_starts_with($role, 'Admin')) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}