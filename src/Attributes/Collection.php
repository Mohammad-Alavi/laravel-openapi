<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\LaravelOpenApi\Generator;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final readonly class Collection
{
    /** @var string|class-string<\Stringable>|(string|class-string<\Stringable>)[] */
    public string|array $name;

    public function __construct(string|array $name = Generator::COLLECTION_DEFAULT)
    {
        $this->name = $this->prepareCollection($name);
    }

    /**
     * @param string|class-string<\Stringable>|(string|class-string<\Stringable>)[] $name
     *
     * @return (string|class-string<\Stringable>)[]
     */
    private function prepareCollection(string|array $name): array
    {
        if (is_string($name)) {
            return [$this->getString($name)];
        }

        return array_map(fn (string $item): string => $this->getString($item), $name);
    }

    /** @param string|class-string<\Stringable> $name */
    private function getString(string $name): string
    {
        if ($this->isStringable($name)) {
            return $this->stringableToString($name);
        }

        return $name;
    }

    /** @param string|class-string<\Stringable> $name */
    private function isStringable(string $name): bool
    {
        return class_exists($name) && is_subclass_of($name, \Stringable::class);
    }

    /** @param class-string<\Stringable> $stringable */
    private function stringableToString(string $stringable): string
    {
        return (string) new $stringable();
    }
}
