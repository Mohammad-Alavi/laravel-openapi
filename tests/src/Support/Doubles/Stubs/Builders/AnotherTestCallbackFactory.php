<?php

namespace Tests\src\Support\Doubles\Stubs\Builders;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class AnotherTestCallbackFactory extends CallbackFactory
{
    public function component(): Callback
    {
        return Callback::create('https://laragen.io/test', PathItem::create());
    }
}
