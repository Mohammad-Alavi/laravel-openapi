<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\HasConstraint;

interface Narrowable
{
    public function all(): Descriptor;

    public function groupedBy(): HasConstraint;
}
