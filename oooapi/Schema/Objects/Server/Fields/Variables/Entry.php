<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;

final readonly class Entry
{
    private function __construct(
        private string $name,
        private ServerVariable $serverVariable,
    ) {
    }

    public static function create(string $name, ServerVariable $serverVariable): self
    {
        return new self($name, $serverVariable);
    }

    public function value(): array
    {
        return [$this->name => $this->serverVariable];
    }
}
