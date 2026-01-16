<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\Discriminator;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\ExternalDocs;

// Example custom keyword for testing
final readonly class XCustomTest implements Keyword
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'x-custom-test';
    }

    public function value(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}

describe('Custom Keywords', function (): void {
    it('allows setting custom keywords via set()', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->set(XCustomTest::create('test-value'));

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('type')
            ->and($compiled)->toHaveKey('x-custom-test')
            ->and($compiled['x-custom-test'])->toBe('test-value');
    });

    it('allows getting custom keywords via getKeyword()', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->set(XCustomTest::create('test-value'));

        $keyword = $schema->getKeyword('x-custom-test');

        expect($keyword)->toBeInstanceOf(Keyword::class)
            ->and($keyword->value())->toBe('test-value');
    });

    it('returns null for non-existent custom keyword', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema();

        expect($schema->getKeyword('non-existent'))->toBeNull();
    });

    it('can check if custom keyword exists', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->set(XCustomTest::create('test-value'));

        expect($schema->hasKeyword('x-custom-test'))->toBeTrue()
            ->and($schema->hasKeyword('non-existent'))->toBeFalse();
    });

    it('can remove custom keyword', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->set(XCustomTest::create('test-value'));

        expect($schema->hasKeyword('x-custom-test'))->toBeTrue();

        $schemaWithout = $schema->withoutKeyword('x-custom-test');

        expect($schemaWithout->hasKeyword('x-custom-test'))->toBeFalse()
            // Original should be unchanged (immutability)
            ->and($schema->hasKeyword('x-custom-test'))->toBeTrue();
    });

    it('custom keywords merge with standard keywords', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->maxProperties(10)
            ->set(XCustomTest::create('test-value'));

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('type')
            ->and($compiled)->toHaveKey('maxProperties')
            ->and($compiled)->toHaveKey('x-custom-test');
    });

    it('works with StrictFluentDescriptor', function (): void {
        $schema = StrictFluentDescriptor::object()
            ->set(XCustomTest::create('test-value'));

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('type')
            ->and($compiled)->toHaveKey('x-custom-test');
    });

    it('can set OpenAPI discriminator keyword', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->set(Discriminator::create('petType', [
                'cat' => '#/components/schemas/Cat',
                'dog' => '#/components/schemas/Dog',
            ]));

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('discriminator')
            ->and($compiled['discriminator']['propertyName'])->toBe('petType')
            ->and($compiled['discriminator']['mapping'])->toBe([
                'cat' => '#/components/schemas/Cat',
                'dog' => '#/components/schemas/Dog',
            ]);
    });

    it('can set OpenAPI externalDocs keyword', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->set(ExternalDocs::create('https://example.com/docs', 'API Documentation'));

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('externalDocs')
            ->and($compiled['externalDocs']['url'])->toBe('https://example.com/docs')
            ->and($compiled['externalDocs']['description'])->toBe('API Documentation');
    });

    it('can override custom keyword by setting again', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->set(XCustomTest::create('first-value'))
            ->set(XCustomTest::create('second-value'));

        $compiled = $schema->compile();

        expect($compiled['x-custom-test'])->toBe('second-value');
    });
})->covers(LooseFluentDescriptor::class, StrictFluentDescriptor::class);
