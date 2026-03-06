<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiswaAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('siswa_logged_in')) {
            return redirect('/login')->withErrors([
                'auth' => 'Silakan login terlebih dahulu.',
            ]);
        }

        return $next($request);
    }
}
