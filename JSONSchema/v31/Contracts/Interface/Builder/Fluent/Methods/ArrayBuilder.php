<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\UniqueItems;

interface ArrayBuilder extends SharedBuilder, MaxContains, MinContains, UniqueItems, MaxItems, MinItems, Items
{
}
