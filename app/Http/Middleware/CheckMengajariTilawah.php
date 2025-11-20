<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMengajariTilawah
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();  // Mendapatkan pengguna yang sedang login

        // Pastikan user memiliki role 'ustadz'
        if ($user->hasRole('ustadz')) {

            // Cek apakah ustadz mengajar tilawah
            $ustadz = $user->ustadz; // Relasi antara user dan ustadz

            // Pastikan relasi ada dan cek apakah ustadz mengajar tilawah
            if ($ustadz && $ustadz->mengajari === 'tilawah') {
                return $next($request);  // Akses diperbolehkan jika mengajar tilawah
            }

            abort(403, 'Akses hanya untuk ustadz yang mengajar tilawah.');
        }

        abort(403, 'Anda tidak memiliki akses.');
    }
}
