<?php

namespace Tests\Feature;

use App\Models\AdminSetting;
use App\Models\Designation;
use App\Models\Feature;
use App\Models\Schools;
use App\Models\Staff;
use App\Services\PlanFeatureService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * NOTE: The schools DB is shared with schoolmates_admin and already
 * migrated + seeded. RefreshDatabase is intentionally NOT used here;
 * DatabaseTransactions wraps each test in a rollback instead.
 */
class PlanFeatureAccessTest extends TestCase
{
    use DatabaseTransactions;

    private function makeSchoolWithStaff(): array
    {
        // Use an existing seeded school that has a plan assigned.
        $school = Schools::with('plan')
            ->whereNotNull('plan_id')
            ->first();

        if (! $school) {
            $this->markTestSkipped('No school with a plan found in the shared DB (run PlanFeatureSeeder first).');
        }

        $designation = Designation::query()->first();
        $staff = Staff::factory()->create([
            'sch_id' => $school->sch_id,
            'username' => 'plan_test_' . uniqid(),
            'designation_id' => $designation?->id,
            'status' => 'active',
        ]);

        $token = $staff->createToken('plan-test')->plainTextToken;

        return [$school, $staff, $token];
    }

    public function test_school_plan_endpoint_returns_plan_and_features()
    {
        [$school, $staff, $token] = $this->makeSchoolWithStaff();

        $this->withToken($token)
            ->getJson('/api/school/plan')
            ->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'plan' => ['id', 'name', 'slug', 'description'],
                    'features',
                    'subscription',
                ],
            ]);
    }

    public function test_school_features_endpoint_returns_feature_states()
    {
        [$school, $staff, $token] = $this->makeSchoolWithStaff();

        $this->withToken($token)
            ->getJson('/api/school/features')
            ->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['features'],
            ]);
    }

    public function test_feature_middleware_allows_when_feature_enabled()
    {
        [$school, $staff, $token] = $this->makeSchoolWithStaff();

        $this->withToken($token)
            ->getJson('/api/designation')
            ->assertStatus(200);
    }

    public function test_feature_middleware_returns_403_with_payload_when_disabled()
    {
        [$school, $staff, $token] = $this->makeSchoolWithStaff();

        $feature = Feature::where('key', 'skills_management')->first();

        if (! $feature) {
            $this->markTestSkipped('skills_management feature missing (run PlanFeatureSeeder first).');
        }

        // Disable via per-school override.
        $school->featureOverrides()->syncWithoutDetaching([
            $feature->id => ['is_enabled' => false],
        ]);

        // Disable the email notification to keep tests quiet & fast.
        AdminSetting::updateOrCreate(
            ['key' => 'notify_school_on_feature_denial'],
            ['value' => '0'],
        );

        $this->withToken($token)
            ->getJson('/api/skills')
            ->assertStatus(403)
            ->assertJsonPath('status', false)
            ->assertJsonPath('data.code', PlanFeatureService::CODE_SCHOOL_FEATURE_DISABLED)
            ->assertJsonPath('data.feature_key', 'skills_management')
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'code',
                    'feature_key',
                    'feature_name',
                    'plan',
                    'reason',
                    'contact',
                ],
            ]);
    }

    public function test_feature_middleware_allows_after_re_enabled()
    {
        [$school, $staff, $token] = $this->makeSchoolWithStaff();

        $feature = Feature::where('key', 'skills_management')->first();

        if (! $feature) {
            $this->markTestSkipped('skills_management feature missing (run PlanFeatureSeeder first).');
        }

        AdminSetting::updateOrCreate(
            ['key' => 'notify_school_on_feature_denial'],
            ['value' => '0'],
        );

        $school->featureOverrides()->syncWithoutDetaching([
            $feature->id => ['is_enabled' => false],
        ]);

        $this->withToken($token)->getJson('/api/skills')->assertStatus(403);

        // Re-enable (remove override -> falls back to plan).
        $school->featureOverrides()->detach($feature->id);

        $this->withToken($token)->getJson('/api/skills')->assertStatus(200);
    }

    public function test_global_feature_disable_blocks_all_schools()
    {
        [$school, $staff, $token] = $this->makeSchoolWithStaff();

        $feature = Feature::where('key', 'skills_management')->first();

        if (! $feature) {
            $this->markTestSkipped('skills_management feature missing (run PlanFeatureSeeder first).');
        }

        AdminSetting::updateOrCreate(
            ['key' => 'notify_school_on_feature_denial'],
            ['value' => '0'],
        );

        $feature->update(['is_active' => false]);

        $this->withToken($token)
            ->getJson('/api/skills')
            ->assertStatus(403)
            ->assertJsonPath('data.code', PlanFeatureService::CODE_GLOBAL_FEATURE_DISABLED);

        $feature->update(['is_active' => true]);
    }
}
