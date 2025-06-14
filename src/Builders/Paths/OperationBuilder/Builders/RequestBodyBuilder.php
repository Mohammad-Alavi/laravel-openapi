<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders;

use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody as RequestBodyAttribute;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableRequestBodyFactory;

class RequestBodyBuilder
{
    public function build(RequestBodyAttribute $requestBodyAttribute): ReusableRequestBodyFactory
    {
        /** @var ReusableRequestBodyFactory $factory */
        $factory = app($requestBodyAttribute->factory);

        return $factory::create();
    }
}
