<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Build;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

final class BuildStatusChanged implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(
        public readonly Build $build,
    ) {}

    /** @return list<Channel> */
    public function broadcastOn(): array
    {
        return [new PrivateChannel("projects.{$this->build->project->slug}")];
    }

    /** @return array<string, mixed> */
    public function broadcastWith(): array
    {
        return [
            'build_id' => $this->build->ulid,
            'status' => $this->build->status?->value ?? 'pending',
            'commit_sha' => $this->build->commit_sha,
            'error_log' => $this->build->error_log,
            'started_at' => $this->build->started_at?->toJSON(),
            'completed_at' => $this->build->completed_at?->toJSON(),
            'project_status' => $this->build->project->status?->value ?? 'building',
        ];
    }
}
