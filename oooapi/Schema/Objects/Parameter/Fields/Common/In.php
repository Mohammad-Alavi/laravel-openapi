<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class In extends StringField
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function path(): self
    {
        return new self('path');
    }

    public static function query(): self
    {
        return new self('query');
    }

    public static function header(): self
    {
        return new self('header');
    }

    public static function cookie(): self
    {
        return new self('cookie');
    }

    public function value(): string
    {
        return $this->value;
    }
}
