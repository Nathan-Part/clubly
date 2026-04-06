<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsValidated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->is_validated && !$request->user()->is_admin) {
            if ($request->routeIs('pending-validation')) {
                return $next($request);
            }
            return redirect()->route('pending-validation');
        }
        return $next($request);
    }
}
