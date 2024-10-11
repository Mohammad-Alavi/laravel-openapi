<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Components\CallbackFactory as CallbackFactoryContract;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;

class CallbackFactory implements CallbackFactoryContract
{
    public function build(): Callback
    {
        return Callback::create('CallbackFactory', '/', PathItem::create());
    }
}
