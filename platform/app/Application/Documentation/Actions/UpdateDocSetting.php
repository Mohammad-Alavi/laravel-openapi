<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Contracts\DocSetting;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Domain\Documentation\Access\Events\DocSettingUpdated;
use App\Domain\Documentation\Access\Repositories\DocSettingRepository;

final class UpdateDocSetting
{
    public function __construct(
        private readonly DocSettingRepository $repository,
    ) {}

    public function execute(int $projectId, DocVisibility $visibility): DocSetting
    {
        $existing = $this->repository->findByProjectId($projectId);
        $oldVisibility = $existing?->getVisibility()->value ?? 'private';

        $setting = $this->repository->upsert($projectId, $visibility);

        DocSettingUpdated::dispatch(
            $projectId,
            $oldVisibility,
            $visibility->value,
        );

        return $setting;
    }
}
