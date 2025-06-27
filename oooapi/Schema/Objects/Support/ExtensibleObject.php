<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extensions;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Generatable;

// TODO: Can we make this and all it's driven classes immutable/readonly?
abstract class ExtensibleObject extends Generatable
{
    private Extensions|null $extensions = null;

    public function addExtension(Extension ...$extension): static
    {
        $this->extensionsInstance()->add(...$extension);

        return $this;
    }

    private function extensionsInstance(): Extensions
    {
        return $this->extensions ??= Extensions::create();
    }

    public function removeExtension(string $name): static
    {
        $this->extensionsInstance()->remove($name);

        return $this;
    }

    public function getExtension(string $name): Extension
    {
        return $this->extensionsInstance()->get($name);
    }

    /** @return Extension[] */
    public function extensions(): array
    {
        return $this->extensionsInstance()->all();
    }

    public function jsonSerialize(): array
    {
        if ($this->extensionsInstance()->isEmpty()) {
            return parent::jsonSerialize();
        }

        return Arr::filter([
            ...$this->toArray(),
            ...$this->extensionsInstance()->jsonSerialize(),
        ]);
    }
}
