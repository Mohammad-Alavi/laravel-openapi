<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\BuildStatus;
use App\Models\Build;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class BuildCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Build $build,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $project = $this->build->project;
        $succeeded = $this->build->status === BuildStatus::Completed;
        $statusText = $succeeded ? 'succeeded' : 'failed';

        $message = (new MailMessage())
            ->subject("{$project->name} build {$statusText}")
            ->greeting("Build {$statusText}!")
            ->line("The build for **{$project->name}** has {$statusText}.")
            ->line("Commit: `{$this->build->commit_sha}`");

        if (! $succeeded && $this->build->error_log !== null) {
            $excerpt = mb_substr($this->build->error_log, 0, 500);
            $message->line("Error log excerpt:")->line("```\n{$excerpt}\n```");
        }

        return $message->action('View Project', url("/projects/{$project->id}"));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->build->project_id,
            'project_name' => $this->build->project->name,
            'build_id' => $this->build->id,
            'status' => $this->build->status->value,
            'commit_sha' => $this->build->commit_sha,
        ];
    }
}
