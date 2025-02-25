<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAllowedUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production')) {
            $allowedUrls = [
                'https://portal.schoolmateglobal.com',
            ];

            $origin = $request->headers->get('origin') ?? $request->headers->get('referer');

            if (!$origin || !in_array($origin, $allowedUrls)) {
                return response()->json(['error' => 'Unauthorized access.'], 401);
            }
        }

        return $next($request);
    }
}
