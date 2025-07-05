<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Extensions;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ReadonlyGeneratable;
use Webmozart\Assert\Assert;

final readonly class Extension extends ReadonlyGeneratable
{
    private const EXTENSION_PREFIX = 'x-';

    private function __construct(
        private string $name,
        private mixed $value,
    ) {
        Assert::startsWith(
            $name,
            self::EXTENSION_PREFIX,
            'Extension name must start with ' . self::EXTENSION_PREFIX,
        );
        Assert::notEq($name, 'x-oai-', 'Extension name cannot be x-oai-');
        Assert::notEq($name, 'x-oas-', 'Extension name cannot be x-oas-');
    }

    public static function create(string $name, mixed $value): self
    {
        return new self($name, $value);
    }

    public static function isExtension(string $value): bool
    {
        return 0 === mb_strpos($value, self::EXTENSION_PREFIX);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function equals(self $extension): bool
    {
        return $this->name === $extension->name && $this->value === $extension->value;
    }

    public function toArray(): array
    {
        return [$this->name => $this->value];
    }
}
