<?php

declare(strict_types=1);

use App\Domain\Documentation\Rendering\DTOs\SpecPathData;
use App\Domain\Documentation\Rendering\DTOs\SpecTagData;
use App\Domain\Documentation\Rendering\Services\SpecParser;

describe(class_basename(SpecParser::class), function (): void {
    it('extracts declared tags', function (): void {
        $parser = new SpecParser();
        $spec = [
            'tags' => [
                ['name' => 'payments', 'description' => 'Payment endpoints'],
                ['name' => 'users', 'description' => 'User endpoints'],
            ],
            'paths' => [],
        ];

        $tags = $parser->extractTags($spec);

        expect($tags)->toHaveCount(2)
            ->and($tags[0])->toBeInstanceOf(SpecTagData::class)
            ->and($tags[0]->name)->toBe('payments')
            ->and($tags[0]->description)->toBe('Payment endpoints')
            ->and($tags[1]->name)->toBe('users');
    });

    it('discovers tags from operations not in tags array', function (): void {
        $parser = new SpecParser();
        $spec = [
            'tags' => [
                ['name' => 'payments'],
            ],
            'paths' => [
                '/api/users' => [
                    'get' => ['tags' => ['users'], 'summary' => 'List users'],
                ],
                '/api/payments' => [
                    'get' => ['tags' => ['payments'], 'summary' => 'List'],
                ],
            ],
        ];

        $tags = $parser->extractTags($spec);

        expect($tags)->toHaveCount(2)
            ->and(array_column(array_map(fn ($t) => (array) $t, $tags), 'name'))->toContain('users');
    });

    it('deduplicates tags', function (): void {
        $parser = new SpecParser();
        $spec = [
            'tags' => [['name' => 'payments']],
            'paths' => [
                '/api/a' => ['get' => ['tags' => ['payments']]],
                '/api/b' => ['get' => ['tags' => ['payments']]],
            ],
        ];

        $tags = $parser->extractTags($spec);

        expect($tags)->toHaveCount(1);
    });

    it('returns empty array for spec with no tags', function (): void {
        $parser = new SpecParser();

        expect($parser->extractTags([]))->toBe([])
            ->and($parser->extractTags(['paths' => []]))->toBe([]);
    });

    it('extracts paths with methods', function (): void {
        $parser = new SpecParser();
        $spec = [
            'paths' => [
                '/api/users' => [
                    'get' => ['summary' => 'List users'],
                    'post' => ['summary' => 'Create user'],
                ],
                '/api/users/{id}' => [
                    'get' => ['summary' => 'Get user'],
                    'put' => ['summary' => 'Update user'],
                    'delete' => ['summary' => 'Delete user'],
                ],
            ],
        ];

        $paths = $parser->extractPaths($spec);

        expect($paths)->toHaveCount(2)
            ->and($paths[0])->toBeInstanceOf(SpecPathData::class)
            ->and($paths[0]->path)->toBe('/api/users')
            ->and($paths[0]->methods)->toBe(['GET', 'POST'])
            ->and($paths[1]->path)->toBe('/api/users/{id}')
            ->and($paths[1]->methods)->toBe(['GET', 'PUT', 'DELETE']);
    });

    it('returns empty array for spec with no paths', function (): void {
        $parser = new SpecParser();

        expect($parser->extractPaths([]))->toBe([])
            ->and($parser->extractPaths(['paths' => []]))->toBe([]);
    });
})->covers(SpecParser::class);
