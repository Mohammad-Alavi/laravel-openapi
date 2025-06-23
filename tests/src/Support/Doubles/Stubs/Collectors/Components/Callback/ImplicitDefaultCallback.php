<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors\Components\Callback;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class ImplicitDefaultCallback extends CallbackFactory
{
    public function component(): Callback
    {
        return Callback::create('test', '/implicit-default-callback', PathItem::create());
    }
}
