<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts;

use MohammadAlavi\ObjectOrientedOAS\Objects\PathItem;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInformation;

interface PathMiddleware
{
    public function before(RouteInformation $routeInformation): void;

    public function after(PathItem $pathItem): PathItem;
}
