<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors\Paths\Operation;

use MohammadAlavi\ObjectOrientedOAS\Objects\Server;

class ServerBuilder
{
    public function __construct(
        private readonly \MohammadAlavi\LaravelOpenApi\Collectors\ServerBuilder $serverBuilder,
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
