<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Callback;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionCallback extends CallbackFactory implements ShouldBeReferenced
{
    public function component(): Callback
    {
        return Callback::create('https://laragen.io/multi-collection-callback', PathItem::create());
    }
}
