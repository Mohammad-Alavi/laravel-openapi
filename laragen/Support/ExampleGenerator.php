<?php

namespace MohammadAlavi\Laragen\Support;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\IntegerRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\StringRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class ExampleGenerator
{
    public function for(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (!is_null($descriptor->getConstant())) {
            return $descriptor->examples($descriptor->getConstant()->value());
        }

        if (is_string($descriptor->getType())) {
            return $this->forType(
                $descriptor->getType(),
                $descriptor,
            );
        }

        if (is_array($descriptor->getType()) && 1 === count($descriptor->getType())) {
            return $this->forType(
                $descriptor->getType()[0],
                $descriptor,
            );
        }

        if (is_array($descriptor->getType()) && count($descriptor->getType()) > 1) {
            return $this->multiType($descriptor);
        }

        if ([] !== $descriptor->getEnum()) {
            return $this->forEnum($descriptor);
        }

        return $descriptor;
    }

    public function forType(mixed $type, LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        if (('object' !== $type) && ([] !== $descriptor->getExamples())) {
            return $descriptor;
        }

        return match ($type) {
            'string' => $this->forString($descriptor),
            'integer' => $this->forInteger($descriptor),
            'number' => $this->forNumber($descriptor),
            'boolean' => $this->forBoolean($descriptor),
            'object' => $this->forObject($descriptor),
            'array' => $this->forArray($descriptor),
            'null' => $this->forNull($descriptor),
            default => $descriptor,
        };
    }

    public function forString(StringRestrictor $descriptor): StringRestrictor
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

    public function forInteger(IntegerRestrictor $descriptor): IntegerRestrictor
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
                        $property->name() => fake()->randomElement($property->schema()->getExamples()),
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
                        return $this->for($itemDescriptor)->getExamples();
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

    public function multiType(LooseFluentDescriptor $descriptor)
    {
        return $descriptor->examples(
            ...collect($descriptor->getType())
            ->map(
                function (string $type) use ($descriptor) {
                    return $this->forType($type, $descriptor)->getExamples()[0];
                },
            )->toArray(),
        );
    }

    public function forEnum(LooseFluentDescriptor $descriptor): LooseFluentDescriptor
    {
        return $descriptor->examples(
            fake()->randomElement($descriptor->getEnum()),
        );
    }

    public function mergeExamples(LooseFluentDescriptor $first, LooseFluentDescriptor $second): LooseFluentDescriptor
    {
        $mergedExamples = array_merge(
            $first->getExamples(),
            $second->getExamples(),
        );

        return $first->examples(...$mergedExamples);
    }
}
