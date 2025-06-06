<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Trash\JsonSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

// TODO: I think it doesnt make sense to be able to create a Not object without a schema
// So I think we should remove the null from the schema property and also remove the schema method
// And just add a constructor that accepts a schema object
// https://json-schema.org/understanding-json-schema/reference/combining
// Another Note:
// Not should also accept an anonymous schema object and the Schema doesn't need a key
class Not extends ExtensibleObject implements JsonSchema
{
    use SimpleCreatorTrait;

    protected Schema\Contracts\JSONSchema|null $schema = null;

    public function schema(Schema\Contracts\JSONSchema|null $schema): static
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'not' => $this->schema,
        ]);
    }
}
