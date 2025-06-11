<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Reusable as ReusableContract;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\OpenAPISchema;
use Webmozart\Assert\Assert;

abstract class Reusable implements ReusableContract
{
    final public static function new(): Descriptor
    {
        return OpenAPISchema::withoutSchema()->ref(static::ref());
    }

    final public static function create(): static
    {
        return new static();
    }

    /**
     * The reference to the reusable component.
     */
    abstract public static function ref(): Reference|string;

    final protected static function path(): string
    {
        return static::basePath() .
            static::componentPath() . '/' .
            static::key();
    }

    private static function basePath(): string
    {
        return '#/components';
    }

    abstract protected static function componentPath(): string;

    /**
     * The key of the reusable component that is used to reference it in other places.
     */
    public static function key(): string
    {
        $key = class_basename(static::class);

        Assert::regex($key, '/^[a-zA-Z0-9.\-_]+$/');

        return $key;
    }
}
