<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleKeyCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleKeyCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generator;

// TODO: I believe SchemaComposition are not Schemas and should not extend SchemaContract
// They combine schemas together tho.
// So they hold a list of schemas.
// https://json-schema.org/understanding-json-schema/reference/object
// https://json-schema.org/understanding-json-schema/reference/combining
// I also believe we shouldn't be allowed to be added to Schema "properties" and "items" as they are not schemas.
abstract class SchemaComposition implements JSONSchema, SimpleKeyCreator
{
    use Generator;
    use SimpleKeyCreatorTrait;

    /** @var JSONSchema|ReusableSchemaFactory[]|null */
    protected array|null $schemas = null;

    public function schemas(JSONSchema|ReusableSchemaFactory ...$schema): static
    {
        $clone = clone $this;

        // TODO: I think the $schema can never be null (Consult the documentation)
        // This can possible be correct for all Composition types
        // If that assumption is correct, we get the required data from the constructor
        // and we can remove the null check
        $clone->schemas = [] !== $schema ? $schema : null;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            $this->compositionType() => $this->schemas,
        ]);
    }

    abstract public function compositionType(): string;
}
