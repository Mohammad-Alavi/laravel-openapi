<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Collections\ParameterCollectionFactory;

#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class Parameters
{
    public string $factory;

    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Parameters\\' . $factory;

        if (!is_a($this->factory, ParameterCollectionFactory::class, true)) {
            throw new \InvalidArgumentException('Factory class must be an instance of ParametersFactory');
        }
    }
}
