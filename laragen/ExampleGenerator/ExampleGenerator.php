<?php

namespace MohammadAlavi\Laragen\ExampleGenerator;

use MohammadAlavi\Laragen\Support\Applicator;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class ExampleGenerator
{
    public function for(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        /** @var string|string[] $type */
        $type = $descriptor->getType();
        if (!is_null($descriptor->getConstant())) {
            $descriptor = $descriptor->examples($descriptor->getConstant()->value());
        }

        if (filled($descriptor->getAllOf())) {
            $descriptor = $this->forApplicator(Applicator::ALL_OF, $descriptor);
        }

        if (filled($descriptor->getAnyOf())) {
            $descriptor = $this->forApplicator(Applicator::ANY_OF, $descriptor);
        }

        if (filled($descriptor->getOneOf())) {
            $descriptor = $this->forApplicator(Applicator::ONE_OF, $descriptor);
        }

        if (is_string($type) && filled($type)) {
            $descriptor = $this->forType($type, $descriptor);
        }

        if ($this->isMultiType($type) && blank($descriptor->getEnum())) {
            if (1 === count($type)) {
                $descriptor = $this->forType($type[0], $descriptor);
            }

            $descriptor = $this->multiType($type, $descriptor);
        }

        if (filled($descriptor->getEnum())) {
            $descriptor = $this->forEnum($descriptor);
        }

        if (filled($descriptor->getProperties())) {
            $descriptor = $this->forObject($descriptor);
        }

        if (!is_null($descriptor->getItems())) {
            $descriptor = $this->forArray($descriptor);
        }

        return $descriptor;
    }

    public function forApplicator(Applicator $applicator, LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        return match ($applicator) {
            Applicator::ALL_OF => $descriptor->allOf(...collect($descriptor->getAllOf() ?? [])
                ->map(
                    function (LooseFluentDescriptor $item) {
                        return $this->for($item);
                    },
                )->toArray()),
            Applicator::ANY_OF => $descriptor->anyOf(...collect($descriptor->getAnyOf() ?? [])
                ->map(
                    function (LooseFluentDescriptor $item) {
                        return $this->for($item);
                    },
                )->toArray()),
            Applicator::ONE_OF => $descriptor->oneOf(...collect($descriptor->getOneOf() ?? [])
                ->map(
                    function (LooseFluentDescriptor $item) {
                        return $this->for($item);
                    },
                )->toArray()),
        };
    }

    public function forType(string $type, LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (!$this->isMultiType($descriptor->getType())) {
            if (filled($descriptor->getExamples())) {
                return $descriptor;
            }
        } elseif (count($descriptor->getExamples() ?? []) === count($descriptor->getType() ?? [])) {
            return $descriptor;
        }

        return match ($type) {
            'string' => $this->forString($descriptor),
            'integer' => $this->forInteger($descriptor),
            'number' => $this->forNumber($descriptor),
            'boolean' => $this->forBoolean($descriptor),
            'null' => $this->forNull($descriptor),
            default => $descriptor,
        };
    }

    public function forString(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (filled($descriptor->getExamples())) {
            return $descriptor;
        }

        if (!is_null($descriptor->getFormat())) {
            try {
                $examples = when(
                    filled($descriptor->getExamples()),
                    $descriptor->getExamples(),
                    fake()->{$descriptor->getFormat()}(),
                );

                return $descriptor->examples(...$examples);
            } catch (\Throwable) {
            }
        }

        $minLength = $descriptor->getMinLength();
        $maxLength = $descriptor->getMaxLength();

        return $descriptor->examples(
            $this->fastRandomStringBetween($minLength ?? 10, $maxLength ?? 50),
        );
    }

    private function fastRandomStringBetween(int $min, int $max): string
    {
        $length = random_int($min, $max);
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
    }

    public function forInteger(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (filled($descriptor->getExamples())) {
            return $descriptor;
        }

        $minimum = $descriptor->getMinimum();
        $maximum = $descriptor->getMaximum();

        return $descriptor->examples(
            fake()->numberBetween($minimum ?? 1, $maximum ?? 100),
        );
    }

    public function forNumber(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (filled($descriptor->getExamples())) {
            return $descriptor;
        }

        $minimum = $descriptor->getMinimum();
        $maximum = $descriptor->getMaximum();

        return $descriptor->examples(
            fake()->randomFloat(2, $minimum ?? 1, $maximum ?? 100),
        );
    }

    public function forBoolean(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (filled($descriptor->getExamples())) {
            return $descriptor;
        }

        return $descriptor->examples(fake()->boolean());
    }

    public function forNull(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        return $descriptor->examples(null);
    }

    /**
     * @param string[]|string|null $type
     *
     * @phpstan-assert-if-true non-empty-array $type
     */
    private function isMultiType(array|string|null $type): bool
    {
        return is_array($type) && filled($type);
    }

    /**
     * Generates examples for multiple types.
     *
     * @param non-empty-array<int, string> $type
     */
    public function multiType(array $type, LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        return $descriptor->examples(
            ...collect($type)
            ->map(
                function (string $type) use ($descriptor): mixed {
                    $examples = $this->forType($type, $descriptor)->getExamples();

                    return when(filled($examples), $examples, []);
                },
            )->reduce(
                static function (array $carry, array $item): array {
                    return array_merge($carry, $item);
                },
                [],
            ),
        );
    }

    public function forEnum(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        $values = $descriptor->getEnum() ?? [];
        $types = collect($values)
            ->map(static fn ($value) => gettype($value))
            ->unique();

        $valuesByType = collect($values)
            ->groupBy(static fn ($value) => gettype($value))
            ->toArray();

        $shemaType = $descriptor->getType();
        if ($this->isMultiType($shemaType)) {
            foreach ($shemaType as $type) {
                if ('null' === $type && $types->doesntContain('null')) {
                    $types->push('null');
                    $valuesByType['null'] = [null];
                    $descriptor = $descriptor->enum(null, ...$values);
                }
            }
        }

        return $descriptor->examples(
            ...collect($types)
            ->map(
                function (string $type) use ($valuesByType) {
                    return fake()->randomElement($valuesByType[$type] ?? []);
                },
            )->toArray(),
        );
    }

    public function forObject(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        $properties = collect($descriptor->getProperties())
            ->map(
                function (Property $property) {
                    return Property::create(
                        $property->name(),
                        $this->for($property->schema()),
                    );
                },
            )->toArray();

        $objectExamples = collect($properties)
            ->map(
                function (Property $property) {
                    if (!is_null($property->schema()->getExamples())) {
                        return [
                            $property->name() => fake()->randomElement($property->schema()->getExamples()),
                        ];
                    }

                    return [];
                },
            )->reduce(
                static function (array $carry, array $item): array {
                    return array_merge($carry, $item);
                },
                [],
            );

        $objectExamples = array_filter(
            $objectExamples,
            static function ($value) {
                return [] !== $value;
            },
        );

        if ([] !== $properties) {
            $descriptor = $descriptor->properties(...$properties);
        }

        if ([] !== $objectExamples) {
            $descriptor = $descriptor->examples($objectExamples);
        }

        return $descriptor;
    }

    public function forArray(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        $itemDescriptor = $descriptor->getItems();
        if (!is_null($itemDescriptor)) {
            $itemDescriptor = $this->for($itemDescriptor);
        }

        return $descriptor->examples(
            ...collect(range(1, 3))
            ->flatMap(
                function () use ($itemDescriptor): array {
                    if (!is_null($itemDescriptor)) {
                        return $this->for($itemDescriptor)->getExamples() ?? [];
                    }

                    return [];
                },
            )->toArray(),
        );
    }
}
