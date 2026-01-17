<?php

namespace Tests\Unit\Collectors;

use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use MohammadAlavi\LaravelOpenApi\Builders\ExtensionBuilder;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use Tests\src\Support\Doubles\Stubs\FakeExtension;

describe(class_basename(ExtensionBuilder::class), function (): void {
    it('can be created using factory', function (): void {
        $example = Example::create();

        /** @var ExtensionBuilder $extensionBuilder */
        $extensionBuilder = app(ExtensionBuilder::class);
        $result = $extensionBuilder->build($example, collect([
            new Extension(factory: FakeExtension::class),
        ]));

        expect($result->compile())->toBe([
            'x-uuid' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);
    });

    it('can be created using key and value', function (): void {
        $example = Example::create();

        /** @var ExtensionBuilder $extensionBuilder */
        $extensionBuilder = app(ExtensionBuilder::class);
        $result = $extensionBuilder->build($example, collect([
            new Extension(key: 'x-foo', value: 'bar'),
            new Extension(key: 'x-key', value: '1'),
        ]));

        expect($result->compile())->toBe([
            'x-foo' => 'bar',
            'x-key' => '1',
        ]);
    });

    it('does not mutate the original object', function (): void {
        $original = Example::create();

        /** @var ExtensionBuilder $extensionBuilder */
        $extensionBuilder = app(ExtensionBuilder::class);
        $result = $extensionBuilder->build($original, collect([
            new Extension(key: 'x-foo', value: 'bar'),
        ]));

        expect($original->compile())->toBe([])
            ->and($result->compile())->toBe(['x-foo' => 'bar']);
    });
})->covers(ExtensionBuilder::class);
