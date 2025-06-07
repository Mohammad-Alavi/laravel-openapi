<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

final readonly class Entry
{
    private function __construct(
        private string $payloadValue,
        private string $schemaName,
    ) {
    }

    public static function create(string $payloadValue, string $schemaName): self
    {
        return new self($payloadValue, $schemaName);
    }

    public function value(): array
    {
        return [$this->payloadValue => $this->schemaName];
    }
}
