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
