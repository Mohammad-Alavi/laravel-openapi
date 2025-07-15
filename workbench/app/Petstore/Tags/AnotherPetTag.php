<?php

namespace Workbench\App\Petstore\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class AnotherPetTag implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create('AnotherPet')
            ->description('Everything about your other Pets!')
            ->externalDocs(
                ExternalDocumentation::create('https://swagger.io')->description('Find out more'),
            );
    }
}
