<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ?string $role = null)
    {
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        if ($role) {
            $userRole = strtolower($request->user()->role ?? '');
            $requiredRole = strtolower($role);

            // Map 'Admin' alias to 'nsm' (National Sales Manager = top-level admin)
            $adminRoles = ['nsm'];
            if ($requiredRole === 'admin') {
                if (!in_array($userRole, $adminRoles)) {
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Forbidden'], 403);
                    }
                    abort(403, 'You do not have permission to access this resource.');
                }
            } elseif ($userRole !== $requiredRole) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Forbidden'], 403);
                }
                abort(403, 'You do not have permission to access this resource.');
            }
        }

        return $next($request);
    }
}
