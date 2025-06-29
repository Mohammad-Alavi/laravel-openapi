<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\Prefix;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\XmlNamespace;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

final class Xml extends ExtensibleObject
{
    private Name|null $name = null;
    private XmlNamespace|null $namespace = null;
    private Prefix|null $prefix = null;
    private true|null $attribute = null;
    private true|null $wrapped = null;

    public static function create(): self
    {
        return new self();
    }

    public function name(Name|null $name): self
    {
        $clone = clone $this;

        $clone->name = $name;

        return $clone;
    }

    public function namespace(XmlNamespace|null $namespace): self
    {
        $clone = clone $this;

        $clone->namespace = $namespace;

        return $clone;
    }

    public function prefix(Prefix|null $prefix): self
    {
        $clone = clone $this;

        $clone->prefix = $prefix;

        return $clone;
    }

    public function attribute(): self
    {
        $clone = clone $this;

        $clone->attribute = true;

        return $clone;
    }

    public function wrapped(): self
    {
        $clone = clone $this;

        $clone->wrapped = true;

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'namespace' => $this->namespace,
            'prefix' => $this->prefix,
            'attribute' => $this->attribute,
            'wrapped' => $this->wrapped,
        ]);
    }
}
