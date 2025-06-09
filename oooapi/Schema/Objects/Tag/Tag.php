<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class Tag extends ExtensibleObject implements \Stringable
{
    private function __construct(
        private readonly Name $name,
        private readonly Description|null $description = null,
        private readonly ExternalDocumentation|null $externalDocumentation = null,
    ) {
    }

    public static function create(
        Name $name,
        Description|null $description = null,
        ExternalDocumentation|null $externalDocs = null,
    ): self {
        return new self($name, $description, $externalDocs);
    }

    public function __toString(): string
    {
        return $this->name->value();
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'description' => $this->description,
            'externalDocs' => $this->externalDocumentation,
        ]);
    }
}
