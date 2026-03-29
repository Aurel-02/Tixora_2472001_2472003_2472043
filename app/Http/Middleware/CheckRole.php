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
        if (!$request->user()) {
            abort(403);
        }

        $userRole = strtolower(trim((string)$request->user()->role));
        
        $roleMap = [
            '1' => 'admin',
            '2' => 'organizer',
            '3' => 'buyer',
        ];

        $normalizedUserRole = $roleMap[$userRole] ?? $userRole;

        foreach ($roles as $role) {
            $requiredRole = strtolower(trim($role));
            
            $normalizedRequiredRole = $roleMap[$requiredRole] ?? $requiredRole;

            if ($normalizedUserRole === $normalizedRequiredRole) {
                return $next($request);
            }
        }

        abort(403);
    }
}
