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
            Applicator::ALL_OF => $descriptor->allOf(...collect($descriptor->getAllOf())
                ->map(
                    function (LooseFluentDescriptor $item) {
                        return $this->for($item);
                    },
                )->toArray()),
            Applicator::ANY_OF => $descriptor->anyOf(...collect($descriptor->getAnyOf())
                ->map(
                    function (LooseFluentDescriptor $item) {
                        return $this->for($item);
                    },
                )->toArray()),
            Applicator::ONE_OF => $descriptor->oneOf(...collect($descriptor->getOneOf())
                ->map(
                    function (LooseFluentDescriptor $item) {
                        return $this->for($item);
                    },
                )->toArray()),
            default => $descriptor,
        };
    }

    public function forType(string $type, LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (!is_array($descriptor->getType())) {
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
        if (!is_null($descriptor->getFormat())) {
            try {
                return $descriptor->examples(
                    fake()->{$descriptor->getFormat()}(),
                );
            } catch (\Throwable) {
            }
        }

        return $descriptor->examples(
            $this->fastRandomStringBetween(
                is_null($descriptor->getMinLength()) ? 5 : $descriptor->getMinLength(),
                is_null($descriptor->getMaxLength()) ? 10 : $descriptor->getMaxLength(),
            ),
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
        return $descriptor->examples(
            fake()->numberBetween(
                is_null($descriptor->getMinimum()) ? 1 : $descriptor->getMinimum(),
                is_null($descriptor->getMaximum()) ? 100 : $descriptor->getMaximum(),
            ),
        );
    }

    public function forNumber(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        return $descriptor->examples(
            fake()->randomFloat(
                2,
                is_null($descriptor->getMinimum()) ? 0 : $descriptor->getMinimum(),
                is_null($descriptor->getMaximum()) ? 100 : $descriptor->getMaximum(),
            ),
        );
    }

    public function forBoolean(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        return $descriptor->examples(fake()->boolean());
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
                    return [
                        $property->name() => fake()->randomElement($property->schema()->getExamples() ?? []),
                    ];
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
                    if ($itemDescriptor) {
                        return $this->for($itemDescriptor)->getExamples() ?? [];
                    }

                    return [];
                },
            )->toArray(),
        );
    }

    public function forNull(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        return $descriptor->examples(null);
    }

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
                function (string $type) use ($descriptor) {
                    $examples = $this->forType($type, $descriptor)->getExamples();

                    return fake()->randomElement(
                        when(filled($examples), $examples, []),
                    );
                },
            )->toArray(),
        );
    }

    public function forEnum(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        $types = collect($descriptor->getEnum())
            ->map(
                function ($value) {
                    return gettype($value);
                },
            )->unique();

        $values = collect($descriptor->getEnum())
            ->groupBy(
                function ($value) {
                    return gettype($value);
                },
            )->toArray();

        $shemaType = $descriptor->getType();
        if ($this->isMultiType($shemaType)) {
            foreach ($shemaType as $type) {
                if ('null' === $type && $types->doesntContain('null')) {
                    $types->push('null');
                    $values['null'] = [null];
                    $descriptor = $descriptor->enum(null, ...$descriptor->getEnum());
                }
            }
        }

        return $descriptor->examples(
            ...collect($types)
            ->map(
                function (string $type) use ($values) {
                    return fake()->randomElement($values[$type] ?? []);
                },
            )->toArray(),
        );
    }
}
