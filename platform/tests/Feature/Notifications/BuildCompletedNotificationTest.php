<?php

use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use App\Notifications\BuildCompletedNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;

describe('BuildCompletedNotification', function (): void {
    it('is sent to project owner on successful build', function (): void {
        Notification::fake();

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
        ]);

        $user->notify(new BuildCompletedNotification($build));

        Notification::assertSentTo($user, BuildCompletedNotification::class);
    });

    it('is sent to project owner on failed build', function (): void {
        Notification::fake();

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create([
            'status' => BuildStatus::Failed,
            'error_log' => 'Build failed due to syntax error',
        ]);

        $user->notify(new BuildCompletedNotification($build));

        Notification::assertSentTo($user, BuildCompletedNotification::class);
    });

    it('uses both mail and database channels', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
        ]);

        $notification = new BuildCompletedNotification($build);

        expect($notification->via($user))->toBe(['mail', 'database']);
    });

    it('mail subject contains project name and succeeded for completed build', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'My API']);
        $build = Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
        ]);

        $notification = new BuildCompletedNotification($build);
        $mail = $notification->toMail($user);

        expect($mail)->toBeInstanceOf(MailMessage::class)
            ->and($mail->subject)->toBe('My API build succeeded');
    });

    it('mail subject contains project name and failed for failed build', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'My API']);
        $build = Build::factory()->for($project)->create([
            'status' => BuildStatus::Failed,
        ]);

        $notification = new BuildCompletedNotification($build);
        $mail = $notification->toMail($user);

        expect($mail)->toBeInstanceOf(MailMessage::class)
            ->and($mail->subject)->toBe('My API build failed');
    });

    it('stores correct data in database', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'Test Project']);
        $build = Build::factory()->for($project)->create([
            'commit_sha' => 'abc123def456',
            'status' => BuildStatus::Completed,
        ]);

        $notification = new BuildCompletedNotification($build);
        $data = $notification->toArray($user);

        expect($data)->toBe([
            'project_slug' => $project->slug,
            'project_name' => 'Test Project',
            'build_ulid' => $build->ulid,
            'status' => 'completed',
            'commit_sha' => 'abc123def456',
        ]);
    });

    it('includes error log excerpt in failed build email', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create([
            'status' => BuildStatus::Failed,
            'error_log' => 'Fatal error: Class not found',
        ]);

        $notification = new BuildCompletedNotification($build);
        $mail = $notification->toMail($user);

        $introLines = collect($mail->introLines);

        expect($introLines->contains(fn ($line) => str_contains($line, 'Fatal error: Class not found')))->toBeTrue();
    });

    it('creates a database notification record', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'DB Test']);
        $build = Build::factory()->for($project)->create([
            'commit_sha' => 'deadbeef1234',
            'status' => BuildStatus::Completed,
        ]);

        $user->notify(new BuildCompletedNotification($build));

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'notifiable_type' => User::class,
        ]);

        $dbNotification = $user->notifications()->first();

        expect($dbNotification->data)->toBe([
            'project_slug' => $project->slug,
            'project_name' => 'DB Test',
            'build_ulid' => $build->ulid,
            'status' => 'completed',
            'commit_sha' => 'deadbeef1234',
        ]);
    });
});
