<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors\Components\Callback;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

#[Collection('test')]
class ExplicitCollectionCallback extends CallbackFactory
{
    public function component(): Callback
    {
        return Callback::create('https://example.com/explicit-collection-callback', PathItem::create(), 'test');
    }
}
