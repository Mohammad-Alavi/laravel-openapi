<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface;

use Illuminate\Support\Collection;

interface FilterStrategy
{
    public function apply(Collection $data): Collection;
}
