<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

describe('Tag', function (): void {
    it('can be created with all parameters', function (): void {
        $tag = Tag::create(
            Name::create('Users'),
            Description::create('All user endpoints'),
            ExternalDocs::create(),
        );

        expect($tag->asArray())->toBe([
            'name' => 'Users',
            'description' => 'All user endpoints',
            'externalDocs' => [],
        ]);
    });

    it('can be cast to string', function (): void {
        $tag = Tag::create(Name::create('Users'));

        expect((string) $tag)->toBe('Users');
    });
})->covers(Tag::class);
