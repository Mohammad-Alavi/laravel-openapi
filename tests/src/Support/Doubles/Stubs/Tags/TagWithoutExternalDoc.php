<?php

namespace Tests\src\Support\Doubles\Stubs\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class TagWithoutExternalDoc implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            'PostWithoutExternalDoc',
        )->description('Post Tag');
    }
}
