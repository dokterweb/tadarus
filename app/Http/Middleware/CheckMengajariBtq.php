<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMengajariBtq
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Admin bebas akses
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Kalau bukan ustadz → tolak
        if (! $user->hasRole('ustadz')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data ustadz dari relasi user -> ustadz
        $ustadz = $user->ustadz;

        if (! $ustadz) {
            abort(403, 'Data ustadz tidak ditemukan.');
        }

        // Cek apakah ustadz mengajari BTQ
        if ($ustadz->mengajari !== 'btq') {
            abort(403, 'Akses khusus ustadz BTQ.');
        }

        return $next($request);
    }
}
