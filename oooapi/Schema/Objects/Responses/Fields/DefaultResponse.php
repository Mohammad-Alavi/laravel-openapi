<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

final readonly class DefaultResponse extends StringField
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function value(): string
    {
        return 'default';
    }
}
