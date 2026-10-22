<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Login admin PPDB menggunakan login utama
        if ($request->is('ppdb/admin') || $request->is('ppdb/admin/*')) {
            return route('login.index');
        }

        // Login orang tua PPDB berdasarkan jenjang
        if ($request->is('ppdb/*')) {
            $segments = $request->segments();

            // Contoh: /ppdb/sd/dashboard -> jenjang = sd
            $jenjang = $segments[1] ?? null;

            if (in_array($jenjang, ['sd', 'tk', 'kb'])) {
                return route('ppdb.login', [
                    'jenjang' => $jenjang,
                ]);
            }

            // URL PPDB tanpa jenjang
            return route('ppdb.landing');
        }

        return route('login.index');
    }
}
