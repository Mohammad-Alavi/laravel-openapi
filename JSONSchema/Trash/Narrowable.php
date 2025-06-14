<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\FluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\HasConstraint;

interface Narrowable
{
    public function all(): FluentDescriptor;

    public function groupedBy(): HasConstraint;
}
