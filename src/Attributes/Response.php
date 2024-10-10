<?php

namespace MohammadAlavi\LaravelOpenApi\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Components\ResponseFactory;
use Webmozart\Assert\Assert;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final readonly class Response
{
    public function __construct(
        public string $factory,
        public int|null $statusCode = null,
        public string|null $description = null,
    ) {
        Assert::classExists($factory);
        Assert::isAnyOf($factory, [ResponseFactory::class, ReusableResponseFactory::class]);

        // TODO What does this do? Ill remove it for now to see the consequences.
        // $this->factory = class_exists($factory) ? $factory : app()->getNamespace() . 'OpenApi\\Responses\\' . $factory;
    }
}
