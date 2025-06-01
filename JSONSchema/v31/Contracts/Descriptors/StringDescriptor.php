<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Pattern;

interface StringDescriptor extends SharedDescriptor, Format, MaxLength, MinLength, Pattern
{
}
