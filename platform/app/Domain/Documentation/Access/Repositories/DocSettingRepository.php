<?php

namespace App\Domain\Documentation\Access\Repositories;

use App\Domain\Documentation\Access\Contracts\DocSetting;
use App\Domain\Documentation\Access\Enums\DocVisibility;

interface DocSettingRepository
{
    public function findByProjectId(int $projectId): ?DocSetting;

    public function upsert(int $projectId, DocVisibility $visibility): DocSetting;
}
