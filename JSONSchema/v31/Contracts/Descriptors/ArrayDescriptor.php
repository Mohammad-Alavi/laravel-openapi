<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\UniqueItems;

interface ArrayDescriptor extends SharedDescriptor, MaxContains, MinContains, UniqueItems, MaxItems, MinItems, Items
{
}
