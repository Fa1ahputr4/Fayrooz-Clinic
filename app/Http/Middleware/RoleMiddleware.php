<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        // Cek apakah user memiliki salah satu role yang dibutuhkan
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);
        }

        // Redirect ke halaman lain jika role tidak sesuai
        return redirect('/');
    }
}
