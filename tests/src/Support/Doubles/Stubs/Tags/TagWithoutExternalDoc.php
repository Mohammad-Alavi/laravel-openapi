<?php

namespace Tests\src\Support\Doubles\Stubs\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

class TagWithoutExternalDoc implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            Name::create('PostWithoutExternalDoc'),
            Description::create('Post Tag'),
        );
    }
}
