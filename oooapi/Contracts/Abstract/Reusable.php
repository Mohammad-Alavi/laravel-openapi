<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldReuse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

abstract class Reusable implements \JsonSerializable
{
    final public static function new(): JSONSchema
    {
        return Schema::ref(static::uri());
    }

    private static function uri(): string
    {
        return self::baseNamespace() . static::componentNamespace() . '/' . static::name();
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

    final public function jsonSerialize(): OASObject|JSONSchema
    {
        if (is_a($this, ShouldReuse::class)) {
            if ('/schemas' === static::componentNamespace()) {
                return Schema::ref(static::uri());
            }

            return self::reference();
        }

        return $this->build();
    }

    final public static function reference(): Reference
    {
        return Reference::create(
            Ref::create(static::uri()),
        );
    }

    final public static function create(): static
    {
        return new static();
    }

    abstract public function build(): OASObject|JSONSchema;
}
