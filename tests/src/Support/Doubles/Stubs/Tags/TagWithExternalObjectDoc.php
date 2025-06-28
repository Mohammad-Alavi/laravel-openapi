<?php

namespace Tests\src\Support\Doubles\Stubs\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;

class TagWithExternalObjectDoc implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            Name::create('PostWithExternalObjectDoc'),
            Description::create('Post Tag'),
            ExternalDocumentation::create(
                URL::create('https://laragen.io/external-docs'),
                Description::create('External API documentation'),
            ),
        );
    }
}
