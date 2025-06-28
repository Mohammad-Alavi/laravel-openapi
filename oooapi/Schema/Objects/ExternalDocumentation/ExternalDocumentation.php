<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class ExternalDocumentation extends ExtensibleObject
{
    private function __construct(
        private readonly URL $url,
        private readonly Description|null $description = null,
    ) {
    }

    public static function create(
        URL $url,
        Description|null $description = null,
    ): self {
        return new self($url, $description);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'url' => $this->url,
            'description' => $this->description,
        ]);
    }
}
