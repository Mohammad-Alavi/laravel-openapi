<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\StringField;

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
