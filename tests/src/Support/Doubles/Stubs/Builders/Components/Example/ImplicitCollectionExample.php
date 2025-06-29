<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Example;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ExampleFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields\ExternalValue;

class ImplicitCollectionExample extends ExampleFactory implements ShouldBeReferenced
{
    public function component(): Example
    {
        return Example::create()->externalValue(ExternalValue::create('Example External Value'));
    }
}
