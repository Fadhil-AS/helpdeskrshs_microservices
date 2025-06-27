<?php

namespace App\Services\Login\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UnitKerjaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('role') !== 'unit_kerja') {
            abort(403, 'AKSES DITOLAK');
        }
        return $next($request);
    }
}
