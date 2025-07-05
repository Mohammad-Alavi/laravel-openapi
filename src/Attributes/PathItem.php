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
        private string|array|null $servers = null,
        public string|null $parameters = null,
    ) {
    }

    /**
     * @return array<array-key, class-string<ServerFactory>>
     */
    public function getServers(): array
    {
        if (is_string($this->servers)) {
            return [$this->servers];
        }

        return when(blank($this->servers), [], $this->servers);
    }
}
