<?php

namespace MohammadAlavi\LaravelOpenApi\Support;

use Composer\ClassMapGenerator\ClassMapGenerator;
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
        $generator = new ClassMapGenerator();
        foreach ($this->directories as $directory) {
            $generator->scanPaths($directory);
        }

        $classes = collect(array_keys($generator->getClassMap()->getMap()))
            ->filter(function (string $class) use ($collection): bool {
                $reflectionClass = new \ReflectionClass($class);
                $attributes = $reflectionClass->getAttributes(CollectionAttribute::class);

                if (Generator::COLLECTION_DEFAULT === $collection && blank($attributes)) {
                    return true;
                }

                if (blank($attributes)) {
                    return false;
                }

                /** @var CollectionAttribute $collectionAttribute */
                $collectionAttribute = $attributes[0]->newInstance();

                return ['*'] === $collectionAttribute->name
                    || in_array(
                        $collection,
                        $collectionAttribute->name ?? [],
                        true,
                    );
            });

        if ($this->filterStrategy instanceof FilterStrategy) {
            $classes = $this->filterStrategy->apply($classes);
        }

        // TODO: refactor: maybe we can decouple this responsibility?
        // you know, instantiating the factories
        return $classes
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
