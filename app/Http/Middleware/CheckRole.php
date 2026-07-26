<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Dipakai di routes sebagai: ->middleware('role:admin')
     * atau multi-role: ->middleware('role:admin,asn')
     *
     * Beda dari versi Sanctum: pakai abort(403) bukan JSON response,
     * supaya Laravel merender halaman error yang benar untuk request web/Inertia.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
