<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema;

interface Vocabulary
{
    public function id(): Id;

    public function schema(): Schema;

    /** @return array<array-key, Keyword> */
    public function keywords(): array;
}
