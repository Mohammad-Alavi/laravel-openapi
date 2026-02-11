<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class CreateAccessLinkData extends Data
{
    public function __construct(
        #[Required]
        public int $doc_role_id,

        #[Required, Max(255)]
        public string $name,

        public ?string $expires_at = null,
    ) {}
}
