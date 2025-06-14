<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableCallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class CallbackFactory extends ReusableCallbackFactory
{
    public function build(): Callback
    {
        return Callback::create('CallbackFactory', '/', PathItem::create());
    }
}
