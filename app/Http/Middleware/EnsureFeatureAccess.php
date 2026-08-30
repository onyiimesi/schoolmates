<?php

namespace App\Http\Middleware;

use App\Models\Schools;
use App\Services\PlanFeatureService;
use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureAccess
{
    use HttpResponses;

    /**
     * Protect a route with a feature key.
     *
     * Usage: ->middleware('feature:result_management')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        $user = $request->user();

        if (! $user) {
            return $this->error(null, 'Unauthenticated', 401);
        }

        $service = app(PlanFeatureService::class);
        $result = $service->check($user->sch_id, $featureKey);

        if ($result['allowed']) {
            return $next($request);
        }

        // Notify the school (email is throttled + toggleable in admin).
        if ($result['feature']) {
            $school = Schools::where('sch_id', $user->sch_id)->first();
            if ($school) {
                $service->notifyDenial($school, $result['feature'], $result['reason'], $result['code']);
            }
        }

        return $this->error([
            'code' => $result['code'] ?? PlanFeatureService::CODE_NOT_ALLOWED,
            'feature_key' => $featureKey,
            'feature_name' => $result['feature']?->name,
            'plan' => $result['plan'],
            'reason' => $result['reason'],
            'contact' => 'Please contact your school administrator to enable this feature or upgrade your plan.',
        ], 'Feature not available: ' . ($result['feature']?->name ?? $featureKey), 403);
    }
}
