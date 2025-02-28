<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            $allowedUrls = config('security.allowed_frontend_urls', []);
            $allowedApiKeys = config('security.allowed_api_keys', []);

            $origin = $request->headers->get('origin') ?? $request->headers->get('referer');
            $apiKey = $request->header('X-API-KEY');

            if ($origin && !in_array($origin, $allowedUrls)) {
                return response()->json(['error' => 'Unauthorized access.'], 401);
            }

            if (!$origin && $apiKey && !in_array($apiKey, $allowedApiKeys)) {
                return response()->json(['error' => 'Unauthorized access.'], 401);
            }

            if (!$origin && !$apiKey) {
                return response()->json(['error' => 'Unauthorized access.'], 401);
            }
        }

        return $next($request);
    }
}
