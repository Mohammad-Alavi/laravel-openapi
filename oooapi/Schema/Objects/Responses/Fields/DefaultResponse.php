<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class DefaultResponse extends StringField
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function create(): self
    {
        return new self('default');
    }

    public function value(): string
    {
        return $this->value;
    }
}
