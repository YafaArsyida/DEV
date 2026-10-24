<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MiddlewarePeran
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user()) {
            abort(401);
        }

        $peran = strtoupper(trim($request->user()->peran ?? ''));

        $roles = array_map(
            fn ($role) => strtoupper(trim($role)),
            $roles
        );

        // dd([
        //     'user_id' => auth()->id(),
        //     'peran_user' => auth()->user()?->peran,
        //     'roles_diizinkan' => $roles,
        //     'path' => request()->path(),
        //     'route_name' => request()->route()?->getName(),
        //     'route_action' => request()->route()?->getActionName(),
        // ]);

        abort_unless(in_array($peran, $roles, true), 403);

        return $next($request);
    }
}
