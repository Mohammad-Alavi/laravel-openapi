<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Keyword;

/**
 * OpenAPI discriminator keyword.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#discriminator-object
 */
final readonly class Discriminator implements Keyword
{
    /**
     * @param array<string, string> $mapping
     */
    private function __construct(
        private string $propertyName,
        private array $mapping = [],
    ) {
    }

    /**
     * @param array<string, string> $mapping
     */
    public static function create(string $propertyName, array $mapping = []): self
    {
        return new self($propertyName, $mapping);
    }

    public static function name(): string
    {
        return 'discriminator';
    }

    public function value(): array
    {
        return [
            'propertyName' => $this->propertyName,
            'mapping' => $this->mapping,
        ];
    }

    public function jsonSerialize(): array
    {
        $result = ['propertyName' => $this->propertyName];

        if (!empty($this->mapping)) {
            $result['mapping'] = $this->mapping;
        }

        return $result;
    }
}
