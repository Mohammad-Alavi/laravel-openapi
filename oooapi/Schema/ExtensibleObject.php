<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema;

use MohammadAlavi\ObjectOrientedOpenAPI\Exceptions\PropertyDoesNotExistException;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extensions;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Generatable;

// TODO: refactor!
//  I mean, I don't even know the the extension supposed to work!
//  I have to read the docs! Also, check if the Extension can be created per object as the docs seems to suggest.
//   Or it is just generated for all objects! Like a global thing!
//  - Also, can we make this and all it's driven classes immutable/readonly?
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

    // TODO: remove this and use methods instead
    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new PropertyDoesNotExistException(sprintf('[%s] is not a valid property.', $name));
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
