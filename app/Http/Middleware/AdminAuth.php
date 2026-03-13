<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('siswa_logged_in') && !session('admin_logged_in')) {
            return redirect('/dashboard');
        }

        if (!session('admin_logged_in')) {
            return redirect('/admin/login')->withErrors([
                'auth' => 'Silakan login sebagai admin terlebih dahulu.',
            ]);
        }

        return $next($request);
    }
}
