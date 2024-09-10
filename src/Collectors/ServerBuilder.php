<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors;

use MohammadAlavi\LaravelOpenApi\Factories\ServerFactory;
use MohammadAlavi\LaravelOpenApi\Helpers\BuilderHelper;
use MohammadAlavi\ObjectOrientedOAS\Objects\Server;

class ServerBuilder
{
    /**
     * @param array<array-key, class-string<Server>> $serverFactories
     *
     * @return Server[]
     */
    public function build(array $serverFactories): array
    {
        return collect($serverFactories)
            ->filter(static fn ($server) => app($server) instanceof ServerFactory)
            ->map(static function (string $server): Server {
                /** @var Server $server */
                $server = app($server)->build();

                throw_if(BuilderHelper::hasInvalidField($server->toArray(), 'url'), new \InvalidArgumentException('Server url is required.'));

                return $server;
            })
            ->toArray();
    }
}
