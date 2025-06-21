<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchemaFactory;

final readonly class Property
{
    private function __construct(
        private string $name,
        private JSONSchema $descriptor,
    ) {
    }

    public static function create(string $name, JSONSchema|JSONSchemaFactory $schema): self
    {
        if ($schema instanceof JSONSchemaFactory) {
            $schema = $schema->build();
        }

        return new self($name, $schema);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function schema(): JSONSchema
    {
        return $this->descriptor;
    }
}
