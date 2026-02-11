<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Contracts\DocSetting;
use Spatie\LaravelData\Data;

final class DocSettingData extends Data
{
    public function __construct(
        public int $project_id,
        public string $visibility,
    ) {}

    public static function fromContract(DocSetting $setting): self
    {
        return new self(
            project_id: $setting->getProjectId(),
            visibility: $setting->getVisibility()->value,
        );
    }
}
