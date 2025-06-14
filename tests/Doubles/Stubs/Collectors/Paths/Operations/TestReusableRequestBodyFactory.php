<?php

namespace Tests\Doubles\Stubs\Collectors\Paths\Operations;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableRequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldReuse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

class TestReusableRequestBodyFactory extends ReusableRequestBodyFactory implements ShouldReuse
{
    public function build(): RequestBody
    {
        return RequestBody::create();
    }
}
