<?php

namespace Workbench\App\Documentation\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

final readonly class UserTag implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create('User')
            ->description('Operations related to user management.');
    }
}
