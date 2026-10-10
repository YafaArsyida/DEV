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
        // if ($request->expectsJson()) {
        //     return null;
        // }

        // if ($request->is('ppdb') || $request->is('ppdb/*')) {
        //     return route('ppdb.login');
        // }

        // return route('login.index');
        if (! $request->expectsJson()) {
            // return route('login');
            return route('login.index');
        }
    }
}
