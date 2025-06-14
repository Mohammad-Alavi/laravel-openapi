<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableCallbackFactory;

#[\Attribute]
final readonly class Callback
{
    public string $factory;

    // TODO: these should be made readonly and public
    //  We should also add class-string param docs for them
    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Callbacks\\' . $factory;

        if (!is_a($this->factory, ReusableCallbackFactory::class, true)) {
            throw new \InvalidArgumentException('Factory class must be an instance of ' . ReusableCallbackFactory::class);
        }
    }
}
