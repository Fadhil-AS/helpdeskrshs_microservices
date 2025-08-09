<?php

namespace App\Services\Login\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = ['humas', 'direksi', 'unit_kerja', 'spi'];
        if (!in_array(session('role'), $allowedRoles)) {
            abort(403, 'AKSES DITOLAK');
            return redirect('/auth/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return $next($request);
    }
}
