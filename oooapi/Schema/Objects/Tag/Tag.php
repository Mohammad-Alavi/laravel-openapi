<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

final class Tag extends ExtensibleObject implements \Stringable
{
    private Description|null $description = null;
    private ExternalDocumentation|null $externalDocumentation = null;

    private function __construct(
        private readonly Name $name,
    ) {
    }

    public static function create(
        string $name,
    ): self {
        return new self(Name::create($name));
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function externalDocs(ExternalDocumentation $externalDocumentation): self
    {
        $clone = clone $this;

        $clone->externalDocumentation = $externalDocumentation;

        return $clone;
    }

    public function __toString(): string
    {
        return $this->name->value();
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'description' => $this->description,
            'externalDocs' => $this->externalDocumentation,
        ]);
    }
}
