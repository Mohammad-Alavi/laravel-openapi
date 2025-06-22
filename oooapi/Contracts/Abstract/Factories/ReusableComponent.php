<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use Webmozart\Assert\Assert;

abstract class ReusableComponent implements \JsonSerializable
{
    final public function jsonSerialize(): JSONSchema|OASObject
    {
        return $this->build();
    }

    public function build(): JSONSchema|OASObject
    {
        if (is_a($this, ShouldBeReferenced::class)) {
            return self::reference();
        }

        return $this->component();
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

    protected static function uri(): string
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

    abstract public function component(): JSONSchema|OASObject;
}
