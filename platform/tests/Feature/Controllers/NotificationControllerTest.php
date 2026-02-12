<?php

use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use App\Notifications\BuildCompletedNotification;

describe('NotificationController', function (): void {
    it('lists notifications for authenticated user', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create(['status' => BuildStatus::Completed]);

        $user->notify(new BuildCompletedNotification($build));

        $this->actingAs($user)
            ->get('/notifications')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Notifications/Index')
                ->has('notifications.data', 1)
            );
    });

    it('marks a single notification as read', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create(['status' => BuildStatus::Completed]);

        $user->notify(new BuildCompletedNotification($build));
        $notification = $user->notifications()->first();

        expect($notification->read_at)->toBeNull();

        $this->actingAs($user)
            ->patch("/notifications/{$notification->id}/read")
            ->assertRedirect();

        expect($notification->fresh()->read_at)->not->toBeNull();
    });

    it('marks all notifications as read', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build1 = Build::factory()->for($project)->create(['status' => BuildStatus::Completed]);
        $build2 = Build::factory()->for($project)->create(['status' => BuildStatus::Failed]);

        $user->notify(new BuildCompletedNotification($build1));
        $user->notify(new BuildCompletedNotification($build2));

        expect($user->unreadNotifications()->count())->toBe(2);

        $this->actingAs($user)
            ->post('/notifications/mark-all-read')
            ->assertRedirect()
            ->assertSessionHas('success');

        expect($user->unreadNotifications()->count())->toBe(0);
    });

    it('requires authentication', function (): void {
        $this->get('/notifications')->assertRedirect('/login');
    });

    it('cannot mark another user\'s notification as read', function (): void {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $build = Build::factory()->for($project)->create(['status' => BuildStatus::Completed]);

        $owner->notify(new BuildCompletedNotification($build));
        $notification = $owner->notifications()->first();

        $this->actingAs($other)
            ->patch("/notifications/{$notification->id}/read")
            ->assertNotFound();
    });
});
