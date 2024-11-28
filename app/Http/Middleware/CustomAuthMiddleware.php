<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Authenticate using Passport's token mechanism
        if (!$request->user('api')) {
            return response()->json([
                'message' => 'Unauthorized. Please provide a valid API token.',
                'error_code' => 401,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
