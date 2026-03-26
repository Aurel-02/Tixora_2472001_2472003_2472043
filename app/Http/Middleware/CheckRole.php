<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if ($request->user() && in_array($request->user()->role_id, $roles)) {
            return $next($request);
        }
        return new Response(view('unauthorized'), 403);
    }
}
