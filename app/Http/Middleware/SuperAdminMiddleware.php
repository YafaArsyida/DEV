<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user login
        if (!auth()->check()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah user memiliki salah satu peran yang diperbolehkan
        if (in_array(auth()->user()->peran, $roles)) {
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
    }
}
