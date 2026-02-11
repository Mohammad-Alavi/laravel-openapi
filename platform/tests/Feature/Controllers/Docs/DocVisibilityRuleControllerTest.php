<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Entities\DocVisibilityRule;
use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;
use App\Domain\Documentation\Access\Events\VisibilityRuleCreated;
use App\Domain\Documentation\Access\Events\VisibilityRuleDeleted;
use App\Domain\Documentation\Access\Events\VisibilityRuleUpdated;
use App\Http\Controllers\Docs\DocVisibilityRuleController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(DocVisibilityRuleController::class), function (): void {
    it('creates a visibility rule', function (): void {
        Event::fake([VisibilityRuleCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->id}/doc-rules", [
                'rule_type' => 'tag',
                'identifier' => 'payments',
                'visibility' => 'restricted',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Visibility rule created.');

        $rule = DocVisibilityRule::where('project_id', $project->id)->first();
        expect($rule->getRuleType())->toBe(RuleType::Tag)
            ->and($rule->getIdentifier())->toBe('payments')
            ->and($rule->getVisibility())->toBe(EndpointVisibility::Restricted);
    });

    it('updates a visibility rule', function (): void {
        Event::fake([VisibilityRuleUpdated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $rule = DocVisibilityRule::create([
            'project_id' => $project->id,
            'rule_type' => RuleType::Tag,
            'identifier' => 'payments',
            'visibility' => EndpointVisibility::Restricted,
        ]);

        $this->actingAs($user)
            ->put("/projects/{$project->id}/doc-rules/{$rule->id}", [
                'visibility' => 'hidden',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Visibility rule updated.');

        $rule->refresh();
        expect($rule->getVisibility())->toBe(EndpointVisibility::Hidden);
    });

    it('deletes a visibility rule', function (): void {
        Event::fake([VisibilityRuleDeleted::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $rule = DocVisibilityRule::create([
            'project_id' => $project->id,
            'rule_type' => RuleType::Tag,
            'identifier' => 'payments',
            'visibility' => EndpointVisibility::Restricted,
        ]);

        $this->actingAs($user)
            ->delete("/projects/{$project->id}/doc-rules/{$rule->id}")
            ->assertRedirect()
            ->assertSessionHas('success', 'Visibility rule deleted.');

        expect(DocVisibilityRule::find($rule->id))->toBeNull();
    });

    it('requires authentication for store', function (): void {
        $project = Project::factory()->create();

        $this->post("/projects/{$project->id}/doc-rules", [
            'rule_type' => 'tag',
            'identifier' => 'payments',
            'visibility' => 'restricted',
        ])->assertRedirect('/login');
    });

    it('returns 403 for non-owner', function (): void {
        $user = User::factory()->create();
        $otherProject = Project::factory()->create();

        $this->actingAs($user)
            ->post("/projects/{$otherProject->id}/doc-rules", [
                'rule_type' => 'tag',
                'identifier' => 'payments',
                'visibility' => 'restricted',
            ])
            ->assertForbidden();
    });

    it('validates rule_type is required', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->id}/doc-rules", [
                'identifier' => 'payments',
                'visibility' => 'restricted',
            ])
            ->assertSessionHasErrors('rule_type');
    });

    it('validates identifier is required', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->id}/doc-rules", [
                'rule_type' => 'tag',
                'visibility' => 'restricted',
            ])
            ->assertSessionHasErrors('identifier');
    });

    it('validates visibility is required', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->id}/doc-rules", [
                'rule_type' => 'tag',
                'identifier' => 'payments',
            ])
            ->assertSessionHasErrors('visibility');
    });
})->covers(DocVisibilityRuleController::class);
