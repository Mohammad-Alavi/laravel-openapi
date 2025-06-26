<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors\Components\Callback;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionCallback extends CallbackFactory
{
    public function component(): Callback
    {
        return Callback::create('test', '/multi-collection-callback', PathItem::create());
    }
}
