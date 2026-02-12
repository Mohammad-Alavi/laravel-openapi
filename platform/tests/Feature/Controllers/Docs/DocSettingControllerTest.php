<?php

use App\Domain\Documentation\Access\Entities\DocSetting;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Domain\Documentation\Access\Events\DocSettingUpdated;
use App\Http\Controllers\Docs\DocSettingController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(DocSettingController::class), function (): void {
    it('updates doc visibility', function (): void {
        Event::fake([DocSettingUpdated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->put("/projects/{$project->slug}/doc-settings", [
                'visibility' => 'public',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Documentation visibility updated.');

        $setting = DocSetting::where('project_id', $project->id)->first();
        expect($setting->getVisibility())->toBe(DocVisibility::Public);
    });

    it('requires authentication', function (): void {
        $project = Project::factory()->create();

        $this->put("/projects/{$project->slug}/doc-settings", [
            'visibility' => 'public',
        ])->assertRedirect('/login');
    });

    it('returns 403 for non-owner', function (): void {
        $user = User::factory()->create();
        $otherProject = Project::factory()->create();

        $this->actingAs($user)
            ->put("/projects/{$otherProject->slug}/doc-settings", [
                'visibility' => 'public',
            ])
            ->assertForbidden();
    });

    it('validates visibility field', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->put("/projects/{$project->slug}/doc-settings", [
                'visibility' => 'invalid',
            ])
            ->assertSessionHasErrors('visibility');
    });
})->covers(DocSettingController::class);
