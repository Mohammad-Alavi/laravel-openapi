<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class CreateDocRoleData extends Data
{
    public function __construct(
        #[Required, Max(255)]
        public string $name,

        #[Required]
        /** @var list<string> */
        public array $scopes,

        public bool $is_default = false,
    ) {}

    public static function authorize(Request $request): bool
    {
        return $request->user()?->can('update', $request->route('project')) ?? false;
    }
}
