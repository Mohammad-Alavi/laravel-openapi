<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\FluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldReuse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use Webmozart\Assert\Assert;

abstract class Reusable implements \JsonSerializable
{
    final public static function new(): FluentDescriptor
    {
        return StrictFluentDescriptor::withoutSchema()->ref(self::reference()->ref());
    }

    final public static function reference(): Reference
    {
        $ref = self::baseNamespace() . static::componentNamespace() . '/' . static::name();

        return Reference::create(
            Ref::create($ref),
        );
    }

    private static function baseNamespace(): string
    {
        return '#/components';
    }

    abstract protected static function componentNamespace(): string;

    public static function name(): string
    {
        $key = class_basename(static::class);

        Assert::regex($key, '/^[a-zA-Z0-9.\-_]+$/');

        return $key;
    }

    final public static function create(): static
    {
        return new static();
    }

    abstract public function build(): OASObject|JSONSchema;

    final public function jsonSerialize(): OASObject|JSONSchema|Reference
    {
        if (is_a($this, ShouldReuse::class)) {
            if ('/schemas' === static::componentNamespace()) {
                return StrictFluentDescriptor::withoutSchema()->ref(self::reference()->ref());
            }

            return self::reference();
        }

        return $this->build();
    }
}
