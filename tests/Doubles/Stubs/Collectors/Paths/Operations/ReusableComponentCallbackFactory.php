<?php

namespace Tests\Doubles\Stubs\Collectors\Paths\Operations;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableCallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class ReusableComponentCallbackFactory extends ReusableCallbackFactory
{
    public function build(): Callback
    {
        return Callback::create('ReusableCallback', '/test', PathItem::create());
    }
}
