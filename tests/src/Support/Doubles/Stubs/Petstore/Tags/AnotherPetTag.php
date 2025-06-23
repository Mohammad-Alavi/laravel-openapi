<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\Description as ExtDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class AnotherPetTag implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            Name::create('AnotherPet'),
            Description::create('Everything about your other Pets!'),
            ExternalDocumentation::create(
                URL::create(
                    'https://swagger.io',
                ),
                ExtDescription::create('Find out more'),
            ),
        );
    }
}
