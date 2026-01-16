<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Keyword;

/**
 * OpenAPI externalDocs keyword.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#external-documentation-object
 */
final readonly class ExternalDocs implements Keyword
{
    private function __construct(
        private string $url,
        private string|null $description = null,
    ) {
    }

    public static function create(string $url, string|null $description = null): self
    {
        return new self($url, $description);
    }

    public static function name(): string
    {
        return 'externalDocs';
    }

    public function value(): array
    {
        return [
            'url' => $this->url,
            'description' => $this->description,
        ];
    }

    public function jsonSerialize(): array
    {
        $result = ['url' => $this->url];

        if (null !== $this->description) {
            $result['description'] = $this->description;
        }

        return $result;
    }
}
