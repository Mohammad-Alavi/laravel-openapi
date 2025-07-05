<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Link;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\LinkFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;

class ImplicitCollectionLink extends LinkFactory implements ShouldBeReferenced
{
    public function component(): Link
    {
        return Link::create();
    }
}
