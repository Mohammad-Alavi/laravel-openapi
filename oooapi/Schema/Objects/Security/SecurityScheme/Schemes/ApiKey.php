<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;

final readonly class ApiKey implements Scheme
{
    private function __construct(
        private string $name,
        private string $in,
    ) {
    }

    public static function query(string $name): self
    {
        return new self($name, 'query');
    }

    public static function header(string $name): self
    {
        return new self($name, 'header');
    }

    public static function cookie(string $name): self
    {
        return new self($name, 'cookie');
    }

    public function type(): string
    {
        return 'apiKey';
    }

    public function jsonSerialize(): array|null
    {
        return [
            'name' => $this->name,
            'in' => $this->in,
        ];
    }
}
