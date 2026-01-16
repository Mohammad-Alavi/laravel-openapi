<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "dependentSchemas" keyword applies schemas conditionally based on property presence.
 *
 * When a property exists, the associated schema must validate the entire instance.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.2.2.4
 */
final readonly class DependentSchemas implements Keyword
{
    /** @param DependentSchema[] $dependentSchemas */
    private function __construct(
        private array $dependentSchemas,
    ) {
    }

    public static function create(DependentSchema ...$dependentSchema): self
    {
        return new self($dependentSchema);
    }

    public static function name(): string
    {
        return 'dependentSchemas';
    }

    public function jsonSerialize(): array|\stdClass
    {
        $result = [];
        foreach ($this->value() as $dependentSchema) {
            $result[$dependentSchema->property()] = $dependentSchema->schema();
        }

        return blank($result) ? new \stdClass() : $result;
    }

    /**
     * @return DependentSchema[]
     */
    public function value(): array
    {
        return $this->dependentSchemas;
    }
}
