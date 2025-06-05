<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Methods\MultipleOf;

interface NumeralDescriptor extends SharedDescriptor, ExclusiveMaximum, ExclusiveMinimum, Maximum, Minimum, MultipleOf, Format
{
}
