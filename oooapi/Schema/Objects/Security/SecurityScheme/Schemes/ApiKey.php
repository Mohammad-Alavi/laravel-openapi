<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\ParameterLocation;

final readonly class ApiKey implements Scheme
{
    private function __construct(
        private string $name,
        private ParameterLocation $in,
    ) {
    }

    public static function query(string $name): self
    {
        return new self($name, ParameterLocation::QUERY);
    }

    public static function header(string $name): self
    {
        return new self($name, ParameterLocation::HEADER);
    }

    public static function cookie(string $name): self
    {
        return new self($name, ParameterLocation::COOKIE);
    }

    public function type(): string
    {
        return 'apiKey';
    }

    public function jsonSerialize(): array|null
    {
        return [
            'name' => $this->name,
            'in' => $this->in->value,
        ];
    }
}
