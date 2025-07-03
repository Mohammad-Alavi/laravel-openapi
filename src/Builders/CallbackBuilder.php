<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use Webmozart\Assert\Assert;

final readonly class CallbackBuilder
{
    /**
     * @param array<array-key, class-string<CallbackFactory>> $factory
     *
     * @return CallbackFactory[]
     */
    public function build(string ...$factory): array
    {
        Assert::allIsAOf($factory, CallbackFactory::class);

        /** @var CallbackFactory[] $servers */
        $servers = collect($factory)
            ->map(
            /**
             * @param class-string<CallbackFactory> $factory
             */
                static function (string $factory): CallbackFactory {
                    return $factory::create();
                },
            )->toArray();

        return $servers;
    }
}
