<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class CreateVisibilityRuleData extends Data
{
    public function __construct(
        #[Required]
        public RuleType $rule_type,

        #[Required, Max(255)]
        public string $identifier,

        #[Required]
        public EndpointVisibility $visibility,
    ) {}

    public static function authorize(Request $request): bool
    {
        return $request->user()?->can('update', $request->route('project')) ?? false;
    }
}
