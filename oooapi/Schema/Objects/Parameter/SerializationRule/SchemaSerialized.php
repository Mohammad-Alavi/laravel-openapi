<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Style;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;

abstract readonly class SchemaSerialized implements SerializationRule
{
    private Examples|null $examples;

    final protected function __construct(
        private JSONSchema|SchemaFactory $jsonSchema,
        private Style|null $style,
        ExampleEntry ...$exampleEntry,
    ) {
        $this->examples = when(
            blank($exampleEntry),
            null,
            Examples::create(...$exampleEntry),
        );
    }

    final public function jsonSerialize(): array
    {
        return [
            'schema' => $this->jsonSchema,
            ...($this->style?->jsonSerialize() ?? []),
            'examples' => $this->examples,
        ];
    }
}
