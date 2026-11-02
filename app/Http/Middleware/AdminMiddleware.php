<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (! $request->user()) {
            abort(401);
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}