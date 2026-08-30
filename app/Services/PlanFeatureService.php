<?php

namespace App\Services;

use App\Mail\FeatureDeniedMail;
use App\Models\AdminSetting;
use App\Models\Feature;
use App\Models\FeatureDenialLog;
use App\Models\Schools;

/**
 * Resolves whether a school can use a feature.
 *
 * Resolution order (most specific wins):
 *  1. Feature not in registry (key unknown)        -> allowed  (backward compatible)
 *  2. Global feature switch (features.is_active)    -> denied if off (all schools)
 *  3. Per-school override (school_feature pivot)    -> uses override value
 *  4. Plan pivot (plan_feature.is_enabled)          -> uses plan value
 *  5. No plan / no pivot row                        -> allowed  (backward compatible)
 */
class PlanFeatureService
{
    public const CODE_GLOBAL_FEATURE_DISABLED = 'GLOBAL_FEATURE_DISABLED';
    public const CODE_SCHOOL_FEATURE_DISABLED = 'SCHOOL_FEATURE_DISABLED';
    public const CODE_PLAN_FEATURE_DISABLED = 'PLAN_FEATURE_DISABLED';
    public const CODE_SCHOOL_NOT_FOUND = 'SCHOOL_NOT_FOUND';
    public const CODE_NOT_ALLOWED = 'FEATURE_DISABLED';

    /**
     * Check feature access for a school.
     *
     * @return array{allowed: bool, feature: ?Feature, plan: ?string, code: ?string, reason: ?string}
     */
    public function check(string $schId, string $featureKey): array
    {
        $school = Schools::with('plan.features')->where('sch_id', $schId)->first();

        if (!$school) {
            return [
                'allowed' => false,
                'feature' => null,
                'plan' => null,
                'code' => self::CODE_SCHOOL_NOT_FOUND,
                'reason' => 'School not found.',
            ];
        }

        $feature = Feature::where('key', $featureKey)->first();

        // Unknown feature keys are not enforced (backward compatibility).
        if (!$feature) {
            return $this->allowed();
        }

        // 1. Global switch — off for ALL schools.
        if (!$feature->is_active) {
            return [
                'allowed' => false,
                'feature' => $feature,
                'plan' => $school->plan?->name,
                'code' => self::CODE_GLOBAL_FEATURE_DISABLED,
                'reason' => "This feature has been disabled for all schools by the administrator.",
            ];
        }

        // 2. Per-school override.
        $override = $school->featureOverrides()
            ->where('feature_id', $feature->id)
            ->first();

        if ($override) {
            return $override->pivot->is_enabled
                ? $this->allowed($feature, $school)
                : [
                    'allowed' => false,
                    'feature' => $feature,
                    'plan' => $school->plan?->name,
                    'code' => self::CODE_SCHOOL_FEATURE_DISABLED,
                    'reason' => "This feature has been disabled for your school by the administrator.",
                ];
        }

        // 3. Plan pivot.
        if ($school->plan) {
            $pivot = $school->plan->features->firstWhere('id', $feature->id);

            if ($pivot) {
                return $pivot->pivot->is_enabled
                    ? $this->allowed($feature, $school)
                    : [
                        'allowed' => false,
                        'feature' => $feature,
                        'plan' => $school->plan->name,
                        'code' => self::CODE_PLAN_FEATURE_DISABLED,
                        'reason' => "This feature is not included in your current plan.",
                    ];
            }
        }

        // 4. No plan / no pivot row -> allowed.
        return $this->allowed($feature, $school);
    }

    /**
     * Convenience wrapper for middleware / controllers.
     */
    public function allows(string $schId, string $featureKey): bool
    {
        return $this->check($schId, $featureKey)['allowed'];
    }

    /**
     * Full feature list with the effective state for a school — used by the
     * frontend to show/hide UI and by the login payload.
     *
     * @return array<int, array{key: string, name: string, group: ?string, enabled: bool}>
     */
    public function schoolFeatures(string $schId): array
    {
        $school = Schools::with(['plan.features', 'featureOverrides'])->where('sch_id', $schId)->first();

        $features = Feature::orderBy('group')->orderBy('name')->get();

        return $features->map(function (Feature $feature) use ($school) {
            $enabled = $this->resolveForFeature($feature, $school);

            return [
                'key' => $feature->key,
                'name' => $feature->name,
                'group' => $feature->group,
                'enabled' => $enabled,
            ];
        })->values()->all();
    }

    /**
     * Effective (allowed?) state of a single feature for a school, without
     * generating a denial reason.
     */
    private function resolveForFeature(Feature $feature, ?Schools $school): bool
    {
        if (!$school) {
            return false;
        }

        if (!$feature->is_active) {
            return false;
        }

        $override = $school->featureOverrides->firstWhere('id', $feature->id);
        if ($override) {
            return (bool) $override->pivot->is_enabled;
        }

        if ($school->plan) {
            $pivot = $school->plan->features->firstWhere('id', $feature->id);
            if ($pivot) {
                return (bool) $pivot->pivot->is_enabled;
            }
        }

        return true;
    }

    /**
     * Email the school about a denied feature, honouring the admin setting
     * `notify_school_on_feature_denial` and throttling to one email per
     * school + feature per day.
     */
    public function notifyDenial(Schools $school, Feature $feature, string $reason, string $code): void
    {
        $setting = AdminSetting::value('notify_school_on_feature_denial', '1');
        if ((string) $setting !== '1') {
            return;
        }

        $alreadyNotifiedToday = FeatureDenialLog::where('school_id', $school->id)
            ->where('feature_key', $feature->key)
            ->whereDate('notified_at', now()->toDateString())
            ->exists();

        if ($alreadyNotifiedToday) {
            return;
        }

        try {
            defer_email($school->schemail, new FeatureDeniedMail($school, $feature, $reason, $code));

            FeatureDenialLog::create([
                'school_id' => $school->id,
                'feature_key' => $feature->key,
                'reason' => $reason,
                'email_sent' => true,
                'notified_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Logging must never break the API response.
            logger()->error('Failed to notify school about feature denial', [
                'school_id' => $school->id,
                'feature' => $feature->key,
                'error' => $e->getMessage(),
            ]);

            FeatureDenialLog::create([
                'school_id' => $school->id,
                'feature_key' => $feature->key,
                'reason' => $reason,
                'email_sent' => false,
                'notified_at' => now(),
            ]);
        }
    }

    private function allowed(?Feature $feature = null, ?Schools $school = null): array
    {
        return [
            'allowed' => true,
            'feature' => $feature,
            'plan' => $school?->plan?->name,
            'code' => null,
            'reason' => null,
        ];
    }
}
