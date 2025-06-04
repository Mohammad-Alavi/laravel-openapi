<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields;

use Webmozart\Assert\Assert;

final readonly class Enum implements \JsonSerializable
{
    private function __construct(
        private array $values,
    ) {
        Assert::notEmpty($values);
    }

    public static function create(string ...$value): self
    {
        return new self($value);
    }

    /**
     * @return string[] $values
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * @return string[] $values
     */
    public function jsonSerialize(): array
    {
        return $this->values;
    }
}
