<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\Description as ExtDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

describe('Tag', function (): void {
    it('can be created with all parameters', function (): void {
        $tag = Tag::create(
            Name::create('Users'),
            Description::create('All user endpoints'),
            ExternalDocumentation::create(
                URL::create('https://laragen.io/docs/users'),
                ExtDescription::create('User API documentation'),
            ),
        );

        expect($tag->asArray())->toBe([
            'name' => 'Users',
            'description' => 'All user endpoints',
            'externalDocs' => [
                'url' => 'https://laragen.io/docs/users',
                'description' => 'User API documentation',
            ],
        ]);
    });

    it('can be cast to string', function (): void {
        $tag = Tag::create(Name::create('Users'));

        expect((string) $tag)->toBe('Users');
    });
})->covers(Tag::class);
