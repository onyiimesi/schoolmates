<?php

namespace App\Http\Middleware;

use App\Models\Schools;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $school = Schools::with('activeSubscription')->where('sch_id', $user->sch_id)->first();

        if (! $school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        if (! $school->activeSubscription) {
            return response()->json(['error' => 'Subscription not active'], 400);
        }

        return $next($request);
    }
}
