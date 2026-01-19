<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\PathItem;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\PathItemFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

#[Collection(['test', Collection::DEFAULT])]
class MultiCollectionPathItem extends PathItemFactory implements ShouldBeReferenced
{
    public function component(): PathItem
    {
        return PathItem::create();
    }
}
