<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

final readonly class Email extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::email($this->value, 'The value "%s" is not a valid email address.');
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
