<?php

namespace Tests\Doubles\Stubs\Collectors\Paths\Operations;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class AnotherTestCallbackFactory extends CallbackFactory
{
    public function build(): Callback
    {
        return Callback::create('ReusableCallback', '/test', PathItem::create());
    }
}
