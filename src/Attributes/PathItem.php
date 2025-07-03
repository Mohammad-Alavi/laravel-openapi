<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ServerFactory;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class PathItem
{
    /**
     * @param class-string<ServerFactory>|array<array-key, class-string<ServerFactory>>|null $servers
     * @param class-string<ParametersFactory>|null $parameters
     */
    public function __construct(
        public string|null $summary = null,
        public string|null $description = null,
        public string|array|null $servers = null,
        public string|null $parameters = null,
    ) {
    }
}
