<?php

namespace Tests\src\Support\Doubles\Stubs\Tags;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

class TagWithExternalObjectDoc implements TagFactory
{
    public function build(): Tag
    {
        return Tag::create(
            'PostWithExternalObjectDoc',
        )->description('Post Tag')
            ->externalDocs(
                ExternalDocumentation::create(
                    'https://laragen.io/external-docs',
                )->description('External API documentation'),
            );
    }
}
