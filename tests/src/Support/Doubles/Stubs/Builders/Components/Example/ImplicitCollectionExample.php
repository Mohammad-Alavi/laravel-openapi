<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Example;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ExampleFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;

class ImplicitCollectionExample extends ExampleFactory implements ShouldBeReferenced
{
    public function component(): Example
    {
        return Example::create()->externalValue('Example External Value');
    }
}
