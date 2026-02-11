<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Enums\DocVisibility;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class UpdateDocSettingData extends Data
{
    public function __construct(
        #[Required]
        public DocVisibility $visibility,
    ) {}

    public static function authorize(Request $request): bool
    {
        return $request->user()?->can('update', $request->route('project')) ?? false;
    }
}
