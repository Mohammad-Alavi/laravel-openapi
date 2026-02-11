<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Enums\DocVisibility;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class UpdateDocSettingData extends Data
{
    public function __construct(
        #[Required]
        public DocVisibility $visibility,
    ) {}
}
