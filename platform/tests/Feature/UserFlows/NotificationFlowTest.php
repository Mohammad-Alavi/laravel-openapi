<?php

declare(strict_types=1);

use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use App\Notifications\BuildCompletedNotification;

describe('Notification Flow', function (): void {
    it('completes the full notification lifecycle: receive → view → mark read', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create(['status' => 'completed']);

        // Step 1: Trigger notification
        $user->notify(new BuildCompletedNotification($build));

        // Step 2: Badge count shows on any page
        $this->actingAs($user)->get('/projects')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('unreadNotificationsCount', 1)
            );

        // Step 3: Visit notifications page
        $response = $this->actingAs($user)->get('/notifications');
        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Notifications/Index')
                ->has('notifications.data', 1)
                ->where('notifications.data.0.data.project_name', $project->name)
                ->where('notifications.data.0.data.status', 'completed')
                ->where('notifications.data.0.read_at', null)
            );

        $notificationId = $response->original->page['props']['notifications']['data'][0]['id'];

        // Step 4: Mark as read
        $this->actingAs($user)->patch("/notifications/{$notificationId}/read")
            ->assertRedirect();

        // Step 5: Verify unread count drops
        $this->actingAs($user)->get('/projects')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('unreadNotificationsCount', 0)
            );
    });

    it('marks all notifications as read in bulk', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build1 = Build::factory()->for($project)->create(['status' => 'completed']);
        $build2 = Build::factory()->for($project)->create(['status' => 'failed']);

        $user->notify(new BuildCompletedNotification($build1));
        $user->notify(new BuildCompletedNotification($build2));

        // Verify 2 unread
        $this->actingAs($user)->get('/notifications')
            ->assertInertia(fn ($page) => $page->has('notifications.data', 2));

        // Mark all as read
        $this->actingAs($user)->post('/notifications/mark-all-read')
            ->assertRedirect();

        // Verify 0 unread
        $this->actingAs($user)->get('/projects')
            ->assertInertia(fn ($page) => $page
                ->where('unreadNotificationsCount', 0)
            );
    });
});
