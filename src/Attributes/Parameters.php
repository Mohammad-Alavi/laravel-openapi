<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;

#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class Parameters
{
    public string $factory;

    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Parameters\\' . $factory;

        if (!is_a($this->factory, ParametersFactory::class, true)) {
            throw new \InvalidArgumentException('Factory class must be an instance of ' . ParametersFactory::class);
        }
    }
}
