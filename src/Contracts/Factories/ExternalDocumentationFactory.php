<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;

interface ExternalDocumentationFactory
{
    public function build(): ExternalDocumentation;
}
