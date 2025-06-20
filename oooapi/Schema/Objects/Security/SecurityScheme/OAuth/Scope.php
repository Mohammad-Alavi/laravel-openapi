<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth;

final readonly class Scope
{
    private function __construct(
        private string $name,
        private string $description,
    ) {
    }

    public static function create(string $name, string $description): self
    {
        return new self($name, $description);
    }

    public function equals(self $scope): bool
    {
        return $this->name === $scope->name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }
}
