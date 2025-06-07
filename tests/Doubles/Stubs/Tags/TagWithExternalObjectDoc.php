<?php

namespace Tests\Doubles\Stubs\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class TagWithExternalObjectDoc extends TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            Name::create('PostWithExternalObjectDoc'),
            Description::create('Post Tag'),
            ExternalDocs::create()
                ->description('External API documentation')
                ->url('https://example.com/external-docs'),
        );
    }
}
