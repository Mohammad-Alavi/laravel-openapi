<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors\Components\Response;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

class ImplicitCollectionResponse extends ResponseFactory
{
    public function component(): Response
    {
        return Response::create(Description::create('OK'));
    }
}
