<?php

namespace Tests\src\Support\Doubles\Stubs\Builders;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\ExternalDocumentationFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;

class ExternalDocsFactory implements ExternalDocumentationFactory
{
    public function build(): ExternalDocumentation
    {
        return ExternalDocumentation::create('https://laragen.io/test')
            ->description('description');
    }
}
