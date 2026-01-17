<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extensions;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use Webmozart\Assert\Assert;

/**
 * Base class for OpenAPI objects that support specification extensions.
 *
 * OpenAPI allows extending the specification with custom properties prefixed
 * with "x-". This abstract class provides the foundation for all objects
 * that may contain such extensions.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#specification-extensions
 */
abstract class ExtensibleObject extends Generatable
{
    private Extensions|null $extensions = null;

    public function addExtension(Extension ...$extension): static
    {
        $clone = clone $this;
        $existingExtensions = $this->extensions?->all() ?? [];
        $clone->extensions = Extensions::create(...$existingExtensions, ...$extension);

        return $clone;
    }

    public function removeExtension(string $name): static
    {
        Assert::notNull($this->extensions, 'Extension not found: ' . $name);
        Assert::true($this->extensions->has($name), 'Extension not found: ' . $name);

        $clone = clone $this;
        $remainingExtensions = array_filter(
            $this->extensions->all(),
            static fn (Extension $ext): bool => $ext->name() !== $name,
        );
        $clone->extensions = blank($remainingExtensions) ? null : Extensions::create(...$remainingExtensions);

        return $clone;
    }

    public function getExtension(string $name): Extension
    {
        Assert::notNull($this->extensions, 'Extension not found: ' . $name);

        return $this->extensions->get($name);
    }

    /** @return Extension[] */
    public function extensions(): array
    {
        return $this->extensions?->all() ?? [];
    }

    public function jsonSerialize(): array
    {
        if (null === $this->extensions || $this->extensions->isEmpty()) {
            return parent::jsonSerialize();
        }

        return Arr::filter([
            ...$this->toArray(),
            ...$this->extensions->jsonSerialize(),
        ]);
    }
}
