<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\VocabularyConstraints;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Type;

interface ValidationConstraint extends ExclusiveMaximum, ExclusiveMinimum, Maximum, MaxLength, Minimum, MinLength, MultipleOf, Pattern, Type
{
}
