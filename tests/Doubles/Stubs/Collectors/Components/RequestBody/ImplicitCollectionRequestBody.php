<?php

namespace Tests\Doubles\Stubs\Collectors\Components\RequestBody;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

class ImplicitCollectionRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create();
    }
}
