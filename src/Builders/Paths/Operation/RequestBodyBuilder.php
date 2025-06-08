<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation;

use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody as RequestBodyAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableRequestBodyFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

class RequestBodyBuilder
{
    public function build(RequestBodyAttribute $requestBodyAttribute): RequestBody|Reference
    {
        if (is_a($requestBodyAttribute->factory, ReusableRequestBodyFactory::class, true)) {
            return $requestBodyAttribute->factory::ref();
        }

        /** @var RequestBodyFactory $factory */
        $factory = app($requestBodyAttribute->factory);

        return $factory->build();
    }
}
