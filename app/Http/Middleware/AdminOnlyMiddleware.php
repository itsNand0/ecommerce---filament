<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOnlyMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            abort(403, 'No tienes permiso para acceder al panel de administración.');
        }
        return $next($request);
    }
}
