<?php

namespace MohammadAlavi\LaravelOpenApi\Support;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection as CollectionAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\FilterStrategy;
use MohammadAlavi\LaravelOpenApi\Generator;

final class ComponentCollector
{
    public function __construct(
        private array|null $directories = null,
        private FilterStrategy|null $filterStrategy = null,
    ) {
    }

    public function collect(string $collection): Collection
    {
        $result = collect($this->directories)
            ->map(static function (string $directory): array {
                // TODO: can we make this dependency clear? And not use it as static? This hides the dependency.
                $map = ClassMapGenerator::createMap($directory);

                return array_keys($map);
            })
            ->flatten()
            ->filter(function (string $class) use ($collection): bool {
                $reflectionClass = new \ReflectionClass($class);
                $collectionAttributes = $reflectionClass->getAttributes(CollectionAttribute::class);

                if (Generator::COLLECTION_DEFAULT === $collection && blank($collectionAttributes)) {
                    return true;
                }

                if (blank($collectionAttributes)) {
                    return false;
                }

                /** @var CollectionAttribute $collectionAttribute */
                $collectionAttribute = $collectionAttributes[0]->newInstance();

                return ['*'] === $collectionAttribute->name
                    || in_array(
                        $collection,
                        $collectionAttribute->name ?? [],
                        true,
                    );
            });

        if ($this->filterStrategy instanceof FilterStrategy) {
            $result = $this->filterStrategy->apply($result);
        }

        // TODO: refactor: maybe we can decouple this responsibility?
        // you know, instantiating the factories
        return $result
            ->map(static function (string $factory) {
                return app($factory);
            })->values();
    }

    public function use(FilterStrategy $filterStrategy): self
    {
        $clone = clone $this;

        $clone->filterStrategy = $filterStrategy;

        return $clone;
    }

    public function in(array $directories): self
    {
        $clone = clone $this;

        $clone->directories = $directories;

        return $clone;
    }
}
