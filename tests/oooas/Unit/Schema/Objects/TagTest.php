<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Kind;
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

        expect($tag->compile())->toBe([
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

        expect($tag)->name()->toBe('Users');
    });

    it('can be created with summary (OAS 3.2)', function (): void {
        $tag = Tag::create('Users')
            ->summary('User management endpoints');

        expect($tag->compile())->toBe([
            'name' => 'Users',
            'summary' => 'User management endpoints',
        ]);
    });

    it('can be created with parent for hierarchical nesting (OAS 3.2)', function (): void {
        $tag = Tag::create('UserProfiles')
            ->parent('Users')
            ->description('Profile management for users');

        expect($tag->compile())->toBe([
            'name' => 'UserProfiles',
            'description' => 'Profile management for users',
            'parent' => 'Users',
        ]);
    });

    it('can be created with kind using enum (OAS 3.2)', function (): void {
        $tag = Tag::create('Internal')
            ->kind(Kind::AUDIENCE)
            ->description('Internal APIs');

        expect($tag->compile())->toBe([
            'name' => 'Internal',
            'description' => 'Internal APIs',
            'kind' => 'audience',
        ]);
    });

    it('can be created with kind using string (OAS 3.2)', function (): void {
        $tag = Tag::create('Navigation')
            ->kind('custom-kind')
            ->description('Custom navigation tag');

        expect($tag->compile())->toBe([
            'name' => 'Navigation',
            'description' => 'Custom navigation tag',
            'kind' => 'custom-kind',
        ]);
    });

    it('can be created with all OAS 3.2 fields', function (): void {
        $tag = Tag::create('UserSettings')
            ->summary('User settings management')
            ->description('Endpoints for managing user preferences and settings')
            ->parent('Users')
            ->kind(Kind::NAV)
            ->externalDocs(
                ExternalDocumentation::create('https://docs.example.com/settings'),
            );

        expect($tag->compile())->toBe([
            'name' => 'UserSettings',
            'summary' => 'User settings management',
            'description' => 'Endpoints for managing user preferences and settings',
            'externalDocs' => [
                'url' => 'https://docs.example.com/settings',
            ],
            'parent' => 'Users',
            'kind' => 'nav',
        ]);
    });
})->covers(Tag::class);

describe(class_basename(Kind::class), function (): void {
    it('has nav case for navigation grouping', function (): void {
        expect(Kind::NAV->value)->toBe('nav');
    });

    it('has badge case for visible labels', function (): void {
        expect(Kind::BADGE->value)->toBe('badge');
    });

    it('has audience case for API consumer groups', function (): void {
        expect(Kind::AUDIENCE->value)->toBe('audience');
    });
})->covers(Kind::class);
