<?php

namespace Tests\Doubles\Stubs\Collectors\Components\Callback;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableCallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;

#[Collection('test')]
class ExplicitCollectionCallback extends ReusableCallbackFactory
{
    public function build(): Callback
    {
        return Callback::create('test', '/explicit-collection-callback', PathItem::create());
    }
}
