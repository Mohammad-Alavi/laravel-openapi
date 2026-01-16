<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "patternProperties" keyword validates properties matching regex patterns.
 *
 * Maps regex patterns to schemas; matching properties are validated against the schema.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.3.2.2
 */
final readonly class PatternProperties implements Keyword
{
    /** @param PatternProperty[] $patternProperties */
    private function __construct(
        private array $patternProperties,
    ) {
    }

    public static function create(PatternProperty ...$patternProperty): self
    {
        return new self($patternProperty);
    }

    public static function name(): string
    {
        return 'patternProperties';
    }

    public function jsonSerialize(): array|\stdClass
    {
        $result = [];
        foreach ($this->value() as $patternProperty) {
            $result[$patternProperty->pattern()] = $patternProperty->schema();
        }

        return blank($result) ? new \stdClass() : $result;
    }

    /**
     * @return PatternProperty[]
     */
    public function value(): array
    {
        return $this->patternProperties;
    }
}
