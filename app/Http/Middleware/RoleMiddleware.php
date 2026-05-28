<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{public function handle(Request $request, Closure $next, ...$roles): Response
{
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    }

    // allow admin everywhere
    if ($user->role === 'admin') {
        return $next($request);
    }

    // block role mismatch
    if (!in_array($user->role, $roles)) {
        abort(403, 'Unauthorized role');
    }

    // allow pending page
    if ($user->status !== 'approved') {
        if ($request->routeIs('pending.approval')) {
            return $next($request);
        }

        return redirect('/pending-approval');
    }

    return $next($request);
}
}