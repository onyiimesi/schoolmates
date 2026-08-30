<?php

namespace App\Http\Controllers;

use App\Models\Schools;
use App\Services\PlanFeatureService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    use HttpResponses;

    public function __construct(private PlanFeatureService $features)
    {
    }

    public function plan(Request $request)
    {
        $schId = $request->input('sch_id');

        if (! $schId) {
            return $this->error(null, 'sch_id is required', 422);
        }

        $school = Schools::with(['plan', 'activeSubscription'])
            ->where('sch_id', $schId)
            ->first();

        if (! $school) {
            return $this->error(null, 'School not found', 404);
        }

        return $this->success([
            'plan' => [
                'id' => $school->plan?->id,
                'name' => $school->plan?->name,
                'slug' => $school->plan?->slug,
                'description' => $school->plan?->description,
            ],
            'features' => $this->features->schoolFeatures($school->sch_id),
            'subscription' => $school->activeSubscription ? [
                'starts_at' => $school->activeSubscription->starts_at?->toDateString(),
                'ends_at' => $school->activeSubscription->ends_at?->toDateString(),
                'status' => $school->activeSubscription->status,
            ] : null,
        ], 'Plan retrieved successfully');
    }

    public function features(Request $request)
    {
        $schId = $request->input('sch_id');

        if (! $schId) {
            return $this->error(null, 'sch_id is required', 422);
        }

        $school = Schools::where('sch_id', $schId)->exists();

        if (! $school) {
            return $this->error(null, 'School not found', 404);
        }

        return $this->success([
            'features' => $this->features->schoolFeatures($schId),
        ], 'Features retrieved successfully');
    }
}
