<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if ($role === 'hr' && !$user->isHr()) {
            abort(403, 'Akses terbatas untuk akun HR.');
        }

        if ($role === 'kandidat' && !$user->isKandidat()) {
            abort(403, 'Akses khusus untuk kandidat.');
        }

        return $next($request);
    }
}
