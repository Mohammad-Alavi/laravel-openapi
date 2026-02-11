<?php

declare(strict_types=1);

use App\Application\Documentation\Actions\UpdateDocSetting;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Domain\Documentation\Access\Events\DocSettingUpdated;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(UpdateDocSetting::class), function (): void {
    it('upserts doc setting and dispatches event', function (): void {
        Event::fake([DocSettingUpdated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $action = app(UpdateDocSetting::class);
        $setting = $action->execute($project->id, DocVisibility::Public);

        expect($setting->getVisibility())->toBe(DocVisibility::Public)
            ->and($setting->getProjectId())->toBe($project->id);

        Event::assertDispatched(DocSettingUpdated::class, function (DocSettingUpdated $event) use ($project): bool {
            return $event->projectId === $project->id
                && $event->newVisibility === 'public';
        });
    });

    it('updates existing setting', function (): void {
        Event::fake([DocSettingUpdated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $action = app(UpdateDocSetting::class);
        $action->execute($project->id, DocVisibility::Public);
        $setting = $action->execute($project->id, DocVisibility::Private);

        expect($setting->getVisibility())->toBe(DocVisibility::Private);

        Event::assertDispatched(DocSettingUpdated::class, function (DocSettingUpdated $event): bool {
            return $event->oldVisibility === 'public' && $event->newVisibility === 'private';
        });
    });
})->covers(UpdateDocSetting::class);
