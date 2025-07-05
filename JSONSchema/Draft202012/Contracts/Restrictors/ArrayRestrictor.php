<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictor;

interface ArrayRestrictor extends Restrictor, SharedRestrictor, MaxContains, MinContains, UniqueItems, MaxItems, MinItems, Items
{
}
