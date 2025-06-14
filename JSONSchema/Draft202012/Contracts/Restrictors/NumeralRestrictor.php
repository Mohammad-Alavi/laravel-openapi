<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictor;

interface NumeralRestrictor extends Restrictor, SharedRestrictor, ExclusiveMaximum, ExclusiveMinimum, Maximum, Minimum, MultipleOf, Format
{
}
