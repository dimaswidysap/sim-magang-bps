<?php

namespace App\Http\Middleware;

use App\Models\MahasiswaProfile;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Dipakai di routes sebagai: ->middleware('role:admin')
     * atau multi-role: ->middleware('role:admin,asn')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        Cache::remember('magang_status_sync_lock', now()->addMinutes(15), function () {
            MahasiswaProfile::syncStatusOtomatis();
            return true;
        });

        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
