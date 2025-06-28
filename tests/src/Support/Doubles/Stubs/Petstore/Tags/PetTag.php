<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;

class PetTag implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            Name::create('Pet'),
            Description::create('Everything about your other Pets!'),
            ExternalDocumentation::create(
                URL::create(
                    'https://swagger.io',
                ),
                Description::create('Find out more'),
            ),
        );
    }
}
