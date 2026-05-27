<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
{
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    }

    // ✅ ADMIN ALWAYS ALLOWED
    if ($user->role === 'admin') {
        return $next($request);
    }

    // ❗ ROLE CHECK FIRST (for non-admin)
    if (!in_array($user->role, $roles)) {
        abort(403, 'Unauthorized role');
    }

     // ❗ ONLY CHECK STATUS FOR PROTECTED APP ROUTES
    if ($user->status !== 'approved') {

        // 🚨 IMPORTANT: prevent loop
        if ($request->routeIs('pending.approval')) {
            return $next($request);
        }

        return redirect('/pending-approval');
    }


    return $next($request);
}
}