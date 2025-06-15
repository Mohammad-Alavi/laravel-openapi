<?php

namespace Tests\Doubles\Stubs\Collectors\Paths\Operations;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldReuse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

class TestRequestBodyFactory extends RequestBodyFactory implements ShouldReuse
{
    public function build(): RequestBody
    {
        return RequestBody::create();
    }
}
