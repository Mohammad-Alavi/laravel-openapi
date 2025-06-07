<?php

namespace Tests\Doubles\Stubs\Petstore\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class PetTag extends TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            Name::create('Pet'),
            Description::create('Everything about your other Pets!'),
            ExternalDocs::create()
                ->description('Find out more')
                ->url('https://swagger.io'),
        );
    }
}
