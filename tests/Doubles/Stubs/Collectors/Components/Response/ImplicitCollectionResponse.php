<?php

namespace Tests\Doubles\Stubs\Collectors\Components\Response;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class ImplicitCollectionResponse extends ReusableResponseFactory
{
    public function build(): Response
    {
        return Response::ok();
    }
}
