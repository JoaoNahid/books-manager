<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $token = Str::of($request->header('Authorization'))->after('Bearer ')->value();
        if (!$token || $token !== config('auth.api_token')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
