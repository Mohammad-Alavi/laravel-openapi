<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\PathItem;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\PathItemFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

class ImplicitCollectionPathItem extends PathItemFactory implements ShouldBeReferenced
{
    public function component(): PathItem
    {
        return PathItem::create();
    }
}
