<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ResponsesFactory;
use Webmozart\Assert\Assert;

#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class Responses
{
    public function __construct(
        public string $factory,
    ) {
        Assert::classExists($factory);
        Assert::isAOf($factory, ResponsesFactory::class);

        // TODO What does this do? Ill remove it for now to see the consequences.
        // $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Responses\\' . $factory;
    }
}
