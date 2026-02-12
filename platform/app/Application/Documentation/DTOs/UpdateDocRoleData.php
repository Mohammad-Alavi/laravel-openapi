<?php

namespace App\Application\Documentation\DTOs;

use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class UpdateDocRoleData extends Data
{
    public function __construct(
        #[Max(255)]
        public string|Optional $name,

        /** @var list<string>|Optional */
        public array|Optional $scopes,

        public bool|Optional $is_default,
    ) {}

    public static function authorize(Request $request): bool
    {
        return $request->user()?->can('update', $request->route('project')) ?? false;
    }
}
