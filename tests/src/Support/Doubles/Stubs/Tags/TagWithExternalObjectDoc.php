<?php

namespace Tests\src\Support\Doubles\Stubs\Tags;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\Description as ExtDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\URL;
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
            ExternalDocumentation::create(
                URL::create('https://example.com/external-docs'),
                ExtDescription::create('External API documentation'),
            ),
        );
    }
}
