<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors\Components;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Collectors\CollectionLocator;
use MohammadAlavi\LaravelOpenApi\Concerns\Referencable;
use MohammadAlavi\LaravelOpenApi\Contracts\Reusable;
use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;
use MohammadAlavi\LaravelOpenApi\Generator;

final readonly class SecuritySchemeCollector implements Reusable
{
    use Referencable;

    public function __construct(
        private CollectionLocator $collectionLocator,
    ) {
    }

    public function collect(string $collection = Generator::COLLECTION_DEFAULT): Collection
    {
        return $this->collectionLocator->find($collection)
            ->filter(static fn (string $class): bool => is_a($class, SecuritySchemeFactory::class, true))
            ->map(static function (string $class) {
                /** @var SecuritySchemeFactory $clone */
                $clone = app($class);

                return $clone->build();
            })
            ->values();
    }
}
