<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

describe('Tag', function (): void {
    it('can be created with all parameters', function (): void {
        $tag = Tag::create('Users')
        ->description('All user endpoints')
        ->externalDocs(
            ExternalDocumentation::create(
                'https://laragen.io/docs/users',
            )->description('User API documentation'),
        );

        expect($tag->unserializeToArray())->toBe([
            'name' => 'Users',
            'description' => 'All user endpoints',
            'externalDocs' => [
                'url' => 'https://laragen.io/docs/users',
                'description' => 'User API documentation',
            ],
        ]);
    });

    it('can be cast to string', function (): void {
        $tag = Tag::create('Users');

        expect((string) $tag)->toBe('Users');
    });
})->covers(Tag::class);
