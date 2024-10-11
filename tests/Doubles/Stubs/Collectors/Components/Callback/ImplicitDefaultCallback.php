<?php

namespace Tests\Doubles\Stubs\Collectors\Components\Callback;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableCallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;

class ImplicitDefaultCallback extends ReusableCallbackFactory
{
    public function build(): Callback
    {
        return Callback::create('test', '/', PathItem::create());
    }
}
