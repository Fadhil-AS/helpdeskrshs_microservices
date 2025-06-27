<?php

namespace App\Services\Login\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $allowedRoles = ['direksi', 'unit_kerja'];
        if (!in_array(session('role'), $allowedRoles)) {
            abort(403, 'AKSES DITOLAK');
        }
        return $next($request);
    }
}
