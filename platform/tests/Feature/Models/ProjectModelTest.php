<?php

use App\Models\Project;

describe(class_basename(Project::class), function (): void {
    it('hides webhook fields from serialization', function (): void {
        $project = Project::factory()->create([
            'github_webhook_id' => 42,
            'github_webhook_secret' => 'super-secret',
        ]);

        $serialized = $project->toArray();

        expect($serialized)->not->toHaveKey('github_webhook_id')
            ->and($serialized)->not->toHaveKey('github_webhook_secret');
    });

    it('excludes webhook fields from JSON output', function (): void {
        $project = Project::factory()->create([
            'github_webhook_id' => 42,
            'github_webhook_secret' => 'super-secret',
        ]);

        $json = $project->toJson();

        expect($json)->not->toContain('github_webhook_id')
            ->and($json)->not->toContain('github_webhook_secret')
            ->and($json)->not->toContain('super-secret');
    });
})->covers(Project::class);
