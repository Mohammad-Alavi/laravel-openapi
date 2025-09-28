<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use Illuminate\Support\Arr;
use MohammadAlavi\LaravelOpenApi\Generator;
use Webmozart\Assert\Assert;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final readonly class Collection
{
    /** @var array<non-empty-string|class-string<\Stringable>> */
    public array $name;

    /**
     * @param non-empty-string|class-string<\Stringable>|array<non-empty-string|class-string<\Stringable>> $name
     */
    public function __construct(string|array $name = Generator::COLLECTION_DEFAULT)
    {
        $name = Arr::wrap($name);

        Assert::allStringNotEmpty($name);

        $this->name = $this->prepareCollection($name);
    }

    /**
     * @param array<non-empty-string|class-string<\Stringable>> $name
     *
     * @return array<non-empty-string|class-string<\Stringable>>
     */
    private function prepareCollection(array $name): array
    {
        return array_map(
            function (string $item): string {
                if ($this->isStringable($item)) {
                    return $this->stringableToString($item);
                }

                return $item;
            },
            $name,
        );
    }

    /**
     * @param non-empty-string|class-string<\Stringable> $name
     *
     * @phpstan-assert-if-true class-string<\Stringable> $name
     */
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
