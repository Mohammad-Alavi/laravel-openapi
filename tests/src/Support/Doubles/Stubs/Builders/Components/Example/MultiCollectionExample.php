<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Example;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ExampleFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionExample extends ExampleFactory implements ShouldBeReferenced
{
    public function component(): Example
    {
        return Example::create()->value('Example Value');
    }
}
