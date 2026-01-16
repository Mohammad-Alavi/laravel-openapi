<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\Prefix;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\XmlNamespace;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

/**
 * XML Object.
 *
 * A metadata object that allows for more fine-tuned XML model definitions.
 * When using arrays, XML element names are not inferred and the name
 * property should be used to add that information.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#xml-object
 */
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

    public function name(string $name): self
    {
        $clone = clone $this;

        $clone->name = Name::create($name);

        return $clone;
    }

    public function namespace(string $namespace): self
    {
        $clone = clone $this;

        $clone->namespace = XmlNamespace::create($namespace);

        return $clone;
    }

    public function prefix(string $prefix): self
    {
        $clone = clone $this;

        $clone->prefix = Prefix::create($prefix);

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
