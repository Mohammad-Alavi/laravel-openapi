<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

class ServerBuilder
{
    /**
     * @param array<array-key, class-string<ServerFactory>> $serverFactories
     *
     * @return Server[]
     */
    public function build(array $serverFactories): array
    {
        return collect($serverFactories)
            ->filter(static fn (string $serverFactory): bool => app($serverFactory) instanceof ServerFactory)
            ->map(static function (string $serverFactory): Server {
                /** @var Server $server */
                $server = app($serverFactory)->build();

                return $server;
            })
            ->toArray();
    }
}
