<?php

namespace App\Services\Login\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnitKerjaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('role') !== 'unit_kerja') {
            abort(403, 'AKSES DITOLAK');
            return redirect('/auth/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return $next($request);
    }
}
