<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Reusable as ReusableContract;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use Webmozart\Assert\Assert;

abstract class Reusable implements ReusableContract
{
    use SimpleCreatorTrait;

    /**
     * The reference to the reusable component.
     */
    abstract public static function ref(): Reference|string;

    final protected static function path(): string
    {
        return static::basePath() .
            static::componentPath() . '/' .
            static::validate(static::key());
    }

    private static function basePath(): string
    {
        return '#/components';
    }

    abstract protected static function componentPath(): string;

    private static function validate(string $name): string
    {
        Assert::regex($name, '/^[a-zA-Z0-9\.\-_]+$/');

        return $name;
    }

    /**
     * The key of the reusable component that is used to reference it in other places.
     */
    public static function key(): string
    {
        return class_basename(static::class);
    }
}
