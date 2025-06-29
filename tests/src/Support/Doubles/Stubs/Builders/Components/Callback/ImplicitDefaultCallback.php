<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Callback;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class ImplicitDefaultCallback extends CallbackFactory implements ShouldBeReferenced
{
    public function component(): Callback
    {
        return Callback::create('https://laragen.io/implicit-default-callback', PathItem::create());
    }
}
