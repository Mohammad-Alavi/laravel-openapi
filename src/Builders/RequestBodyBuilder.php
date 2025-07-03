<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;

final readonly class RequestBodyBuilder
{
    public function build(RequestBody $requestBody): RequestBodyFactory
    {
        /** @var RequestBodyFactory $factory */
        $factory = $requestBody->factory;

        return $factory::create();
    }
}
