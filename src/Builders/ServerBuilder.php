<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ServerFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use Webmozart\Assert\Assert;

final readonly class ServerBuilder
{
    /**
     * @param array<array-key, class-string<ServerFactory>> $factory
     *
     * @return Server[]
     */
    public function build(string ...$factory): array
    {
        Assert::allIsAOf($factory, ServerFactory::class);

        /** @var Server[] $servers */
        $servers = collect($factory)
            ->map(
                /**
                 * @param class-string<ServerFactory> $factory
                 */
                static function (string $factory): Server {
                    return (new $factory())->build();
                },
            )->toArray();

        return $servers;
    }
}
