<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

final readonly class URL extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::regex(
            $this->value,
            '~^(?:(?:https?|ftp|file)://(?:[\w-]+|\{\w+})(?:\.(?:[\w-]+|\{\w+}))*(?::(?:\d+|\{\w+}))?(?:/[^\s?#]*)?(?:\?[^\s#]*)?(?:#\S*)?|/[^\s?#]*)$~',
        );
    }

    public static function create(string $url): self
    {
        return new self($url);
    }

    public function value(): string
    {
        return $this->value;
    }
}
