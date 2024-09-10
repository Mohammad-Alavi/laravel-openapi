<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts;

use MohammadAlavi\ObjectOrientedOAS\Objects\Components;

interface ComponentMiddleware
{
    public function after(Components $components): void;
}
