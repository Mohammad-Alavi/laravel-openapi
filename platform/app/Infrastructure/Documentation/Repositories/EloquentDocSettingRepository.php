<?php

declare(strict_types=1);

namespace App\Infrastructure\Documentation\Repositories;

use App\Domain\Documentation\Access\Contracts\DocSetting;
use App\Domain\Documentation\Access\Entities\DocSetting as DocSettingEntity;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Domain\Documentation\Access\Repositories\DocSettingRepository;

final class EloquentDocSettingRepository implements DocSettingRepository
{
    public function findByProjectId(int $projectId): ?DocSetting
    {
        return DocSettingEntity::where('project_id', $projectId)->first();
    }

    public function upsert(int $projectId, DocVisibility $visibility): DocSetting
    {
        return DocSettingEntity::updateOrCreate(
            ['project_id' => $projectId],
            ['visibility' => $visibility],
        );
    }
}
