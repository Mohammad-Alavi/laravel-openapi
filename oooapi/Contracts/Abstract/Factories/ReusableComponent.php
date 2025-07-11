<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use Webmozart\Assert\Assert;

abstract class ReusableComponent implements \JsonSerializable, OASObject
{
    final public function __construct()
    {
    }

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
        return Reference::create(static::uri());
    }

    final public static function create(): static
    {
        return new static();
    }

    protected static function uri(): string
    {
        $name = static::name();
        self::validateName($name);

        return self::baseNamespace() . static::componentNamespace() . '/' . $name;
    }

    public static function name(): string
    {
        return class_basename(static::class);
    }

    final protected static function validateName(string $name): void
    {
        Assert::regex($name, '/^[a-zA-Z0-9.\-_]+$/');
    }

    private static function baseNamespace(): string
    {
        return '#/components';
    }

    abstract protected static function componentNamespace(): string;

    abstract public function component(): JSONSchema|OASObject;
}
