<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

readonly class ServerBuilder
{
    public function __construct(
        private \MohammadAlavi\LaravelOpenApi\Builders\ServerBuilder $serverBuilder,
    ) {
    }

    /**
     * @param array<array-key, class-string<Server>> $serverFactories
     *
     * @return Server[]
     */
    public function build(array $serverFactories): array
    {
        return $this->serverBuilder->build($serverFactories);
    }
}
