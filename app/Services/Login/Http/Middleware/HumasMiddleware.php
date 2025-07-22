<?php

namespace App\Services\Login\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HumasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('role') !== 'humas') {
            abort(403, 'AKSES DITOLAK');
        }
        return $next($request);
    }
}
