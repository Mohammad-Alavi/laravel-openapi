<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Builder;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Methods\HasConstraint;

interface Narrowable
{
    public function all(): Builder;

    public function groupedBy(): HasConstraint;
}
