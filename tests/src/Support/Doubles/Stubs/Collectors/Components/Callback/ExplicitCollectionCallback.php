<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors\Components\Callback;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

#[Collection('test')]
class ExplicitCollectionCallback extends CallbackFactory implements ShouldBeReferenced
{
    public function component(): Callback
    {
        return Callback::create('https://laragen.io/explicit-collection-callback', PathItem::create());
    }
}
