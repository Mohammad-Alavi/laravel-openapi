<?php

namespace Tests\Doubles\Stubs\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class TagWithoutExternalDoc extends TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            Name::create('PostWithoutExternalDoc'),
            Description::create('Post Tag')
        );
    }
}
