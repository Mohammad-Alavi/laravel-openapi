<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;

interface ComponentMiddleware
{
    public function after(Components $components): void;
}
