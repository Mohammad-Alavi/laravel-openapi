<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;

interface ComponentMiddleware
{
    public function after(Components $components): void;
}
