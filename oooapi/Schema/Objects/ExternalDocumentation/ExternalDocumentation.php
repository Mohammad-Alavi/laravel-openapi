<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;

/**
 * External Documentation Object.
 *
 * Allows referencing an external resource for extended documentation.
 * The url field is required and must be a valid URL.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#external-documentation-object
 */
final class ExternalDocumentation extends ExtensibleObject
{
    private Description|null $description = null;

    private function __construct(
        private readonly URL $url,
    ) {
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public static function create(string $url): self
    {
        return new self(URL::create($url));
    }

    public function toArray(): array
    {
        return Arr::filter([
            'url' => $this->url,
            'description' => $this->description,
        ]);
    }
}
