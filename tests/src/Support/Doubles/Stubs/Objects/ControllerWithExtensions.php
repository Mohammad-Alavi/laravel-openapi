<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem(summary: 'Test path item')]
#[Collection(['test', 'example'])]
class ControllerWithExtensions
{
    #[Operation(summary: 'Test operation')]
    #[Extension(key: 'x-custom', value: 'custom-value')]
    #[Extension(key: 'x-another', value: 'another-value')]
    #[Collection('action-collection')]
    public function withExtensions(): void
    {
    }

    #[Operation(summary: 'No extensions')]
    public function withoutExtensions(): void
    {
    }
}
