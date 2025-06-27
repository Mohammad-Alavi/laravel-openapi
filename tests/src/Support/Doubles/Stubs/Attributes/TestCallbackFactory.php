<?php

namespace Tests\src\Support\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class TestCallbackFactory extends CallbackFactory
{
    public function component(): Callback
    {
        return Callback::create('https://example.com/', PathItem::create(), 'CallbackFactory');
    }
}
