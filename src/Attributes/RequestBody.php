<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;

#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class RequestBody
{
    public string $factory;

    public function __construct(string $factory)
    {
        $this->factory = class_exists($factory)
            ? $factory
            : app()->getNamespace() . 'OpenApi\\RequestBodies\\' . $factory;

        if (!is_a($this->factory, RequestBodyFactory::class, true)) {
            throw new \InvalidArgumentException('Factory class must be an instance of RequestBodyFactory');
        }
    }
}
