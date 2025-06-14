<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Style;

abstract readonly class SchemaSerialized implements SerializationRule
{
    final protected function __construct(
        private JSONSchema   $jsonSchema,
        private Style|null   $style,
        private Example|null $example,
        /** @var Example[] */
        private array        $examples,
    ) {
    }

    final public function toArray(): array
    {
        $examples = [];
        foreach ($this->examples as $example) {
            $examples[$example->key()] = $example;
        }
        $examples = [] !== $examples ? $examples : null;

        return [
            'schema' => $this->jsonSchema,
            ...($this->style?->toArray() ?? []),
            'example' => $this->example,
            'examples' => $examples,
        ];
    }
}
