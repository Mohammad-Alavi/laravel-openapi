<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ComponentFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;

interface LinkFactory extends ComponentFactory
{
    public function build(): Link;
}
