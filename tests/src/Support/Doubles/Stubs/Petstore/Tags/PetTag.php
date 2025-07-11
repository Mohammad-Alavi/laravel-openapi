<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class PetTag implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create('Pet')
            ->description('Everything about your other Pets!')
            ->externalDocs(
                ExternalDocumentation::create('https://swagger.io')
                ->description('Find out more'),
            );
    }
}
