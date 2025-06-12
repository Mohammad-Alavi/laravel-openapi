<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Reusable as ReusableContract;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\OpenAPISchema;
use Webmozart\Assert\Assert;

abstract class Reusable implements ReusableContract
{
    final public static function new(): Descriptor
    {
        return OpenAPISchema::withoutSchema()->ref(self::reference()->ref());
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
}
