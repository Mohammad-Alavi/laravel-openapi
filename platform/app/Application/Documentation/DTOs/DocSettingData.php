<?php

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Contracts\DocSetting;
use Spatie\LaravelData\Data;

final class DocSettingData extends Data
{
    public function __construct(
        public string $visibility,
    ) {}

    public static function fromContract(DocSetting $setting): self
    {
        return new self(
            visibility: $setting->getVisibility()->value,
        );
    }
}
