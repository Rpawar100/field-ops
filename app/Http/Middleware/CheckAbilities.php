<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class CheckAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$abilities
     * @return mixed
     *
     * @throws \Laravel\Sanctum\Exceptions\MissingAbilityException
     */
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        // Check if user has wildcard ability (admin)
        if ($request->user()->tokenCan('*')) {
            return $next($request);
        }

        foreach ($abilities as $ability) {
            if (!$request->user()->tokenCan($ability)) {
                throw new MissingAbilityException($ability);
            }
        }

        return $next($request);
    }
}
