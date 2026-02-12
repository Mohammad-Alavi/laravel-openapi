<?php

namespace MohammadAlavi\LaravelOpenApi\Support;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection as CollectionAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\FilterStrategy;

final class ComponentCollector
{
    public function __construct(
        private array|null $paths = null,
        private FilterStrategy|null $filterStrategy = null,
    ) {
    }

    public function collect(string $collection): Collection
    {
        $generator = new ClassMapGenerator();
        foreach ($this->paths as $path) {
            $generator->scanPaths($path);
        }

        $classes = collect(array_keys($generator->getClassMap()->getMap()))
            ->sort()
            ->filter(function (string $class) use ($collection): bool {
                $reflectionClass = new \ReflectionClass($class);
                $attributes = $reflectionClass->getAttributes(CollectionAttribute::class);

                if (CollectionAttribute::DEFAULT === $collection && blank($attributes)) {
                    return true;
                }

                if (blank($attributes)) {
                    return false;
                }

                /** @var CollectionAttribute $collectionAttribute */
                $collectionAttribute = $attributes[0]->newInstance();
                $collections = Arr::wrap($collectionAttribute->name);

                return ['*'] === $collections
                    || in_array(
                        $collection,
                        when(filled($collections), $collections, []),
                        true,
                    );
            });

        if ($this->filterStrategy instanceof FilterStrategy) {
            $classes = $this->filterStrategy->apply($classes);
        }

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

    public function in(array $paths): self
    {
        $clone = clone $this;

        $clone->paths = $paths;

        return $clone;
    }
}
