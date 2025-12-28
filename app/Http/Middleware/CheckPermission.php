<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * Usage: Route::post(...)->middleware('permission:create_publication')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        if (!auth()->user()->hasPermission($permission)) {
            return response()->json(['message' => 'Permission refusée: ' . $permission], 403);
        }

        return $next($request);
    }
}
