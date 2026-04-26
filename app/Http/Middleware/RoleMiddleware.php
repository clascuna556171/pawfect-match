<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $guard = $request->is('admin*') ? 'admin' : 'web';
        $authGuard = Auth::guard($guard);
        $expectsJson = $request->expectsJson() || $request->wantsJson() || $request->ajax();

        if (!$authGuard->check()) {
            if ($expectsJson) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return $guard === 'admin'
                ? redirect()->route('admin.login')->with('error', 'Please log in to access this page.')
                : redirect('/login')->with('error', 'Please log in to access this page.');
        }

        if (!$authGuard->user()->hasRole($roles)) {
            if ($expectsJson) {
                return response()->json([
                    'message' => 'Forbidden.',
                ], 403);
            }

            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
