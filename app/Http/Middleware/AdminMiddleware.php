<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // Check if user has admin role via role_id relationship
        $user = auth()->user();
        if ($user->role_id) {
            $role = \App\Models\Role::find($user->role_id);
            if ($role && strtolower($role->nom) === 'admin') {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Accès non autorisé'], 403);
    }
}
