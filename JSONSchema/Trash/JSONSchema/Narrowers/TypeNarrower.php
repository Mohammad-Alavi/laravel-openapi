<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers;

use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeConstraints\IntegerConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeConstraints\NumberConstraint;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\TypeConstraints\StringConstraint;

interface TypeNarrower
{
    public function string(): StringConstraint;

    public function integer(): IntegerConstraint;

    public function number(): NumberConstraint;
}
