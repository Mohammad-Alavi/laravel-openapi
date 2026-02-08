<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Style;

/**
 * Schema-based serialization for parameters.
 *
 * Provides the schema and style-based serialization fields for parameters.
 * The `example`/`examples` fields are common fields on the parent object
 * (Parameter/Header), not on the serialization rule.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
 */
abstract class SchemaSerialized implements SerializationRule
{
    private true|null $allowReserved = null;

    final protected function __construct(
        private readonly JSONSchema|SchemaFactory $jsonSchema,
        private readonly Style|null $style,
    ) {
    }

    /**
     * When true, allows reserved characters to pass through without percent-encoding.
     *
     * Reserved characters as defined by RFC3986: :/?#[]@!$&'()*+,;=
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
     */
    final public function allowReserved(): static
    {
        $clone = clone $this;

        $clone->allowReserved = true;

        return $clone;
    }

    final public function jsonSerialize(): array
    {
        return [
            ...($this->style?->jsonSerialize() ?? []),
            'allowReserved' => $this->allowReserved,
            'schema' => $this->jsonSchema,
        ];
    }
}
