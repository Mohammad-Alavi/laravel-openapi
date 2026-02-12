<?php

namespace App\Application\Documentation\DTOs;

use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class CreateAccessLinkData extends Data
{
    public function __construct(
        #[Required]
        public string $doc_role_id,

        #[Required, Max(255)]
        public string $name,

        public ?string $expires_at = null,
    ) {}

    public static function authorize(Request $request): bool
    {
        return $request->user()?->can('update', $request->route('project')) ?? false;
    }
}
