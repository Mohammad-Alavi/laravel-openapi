<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictor;

interface StringRestrictor extends Restrictor, SharedRestrictor, Format, MaxLength, MinLength, Pattern
{
}
