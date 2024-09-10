<?php

namespace Examples\Petstore\OpenApi\Tags;

use MohammadAlavi\ObjectOrientedOAS\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOAS\Objects\Tag;
use MohammadAlavi\LaravelOpenApi\Factories\TagFactory;

class AnotherPetTag extends TagFactory
{
    public function build(): Tag
    {
        return Tag::create()
            ->name('AnotherPet')
            ->description('Everything about your other Pets!')
            ->externalDocs(
                ExternalDocs::create()
                    ->description('Find out more')
                    ->url('http://swagger.io')
            );
    }
}
