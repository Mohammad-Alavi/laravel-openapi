<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\NodeType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Xml;

describe(class_basename(Xml::class), function (): void {
    it('can be created with all fields', function (): void {
        $xml = Xml::create()
            ->name('animal')
            ->namespace('https://example.com/schema/sample')
            ->prefix('sample')
            ->nodeType(NodeType::ELEMENT)
            ->attribute()
            ->wrapped();

        expect($xml->toArray())->toBe([
            'name' => 'animal',
            'namespace' => 'https://example.com/schema/sample',
            'prefix' => 'sample',
            'nodeType' => 'element',
            'attribute' => true,
            'wrapped' => true,
        ]);
    });

    it('can be created with only name', function (): void {
        $xml = Xml::create()->name('animal');

        expect($xml->toArray())->toBe([
            'name' => 'animal',
        ]);
    });

    it('can be created with namespace and prefix', function (): void {
        $xml = Xml::create()
            ->namespace('https://example.com/schema/sample')
            ->prefix('sample');

        expect($xml->toArray())->toBe([
            'namespace' => 'https://example.com/schema/sample',
            'prefix' => 'sample',
        ]);
    });

    describe('nodeType', function (): void {
        it('can set element node type', function (): void {
            $xml = Xml::create()->nodeType(NodeType::ELEMENT);

            expect($xml->toArray())->toBe([
                'nodeType' => 'element',
            ]);
        });

        it('can set attribute node type', function (): void {
            $xml = Xml::create()->nodeType(NodeType::ATTRIBUTE);

            expect($xml->toArray())->toBe([
                'nodeType' => 'attribute',
            ]);
        });

        it('can set text node type', function (): void {
            $xml = Xml::create()->nodeType(NodeType::TEXT);

            expect($xml->toArray())->toBe([
                'nodeType' => 'text',
            ]);
        });

        it('can set cdata node type', function (): void {
            $xml = Xml::create()->nodeType(NodeType::CDATA);

            expect($xml->toArray())->toBe([
                'nodeType' => 'cdata',
            ]);
        });

        it('can set none node type', function (): void {
            $xml = Xml::create()->nodeType(NodeType::NONE);

            expect($xml->toArray())->toBe([
                'nodeType' => 'none',
            ]);
        });
    });

    it('can set attribute flag', function (): void {
        $xml = Xml::create()->attribute();

        expect($xml->toArray())->toBe([
            'attribute' => true,
        ]);
    });

    it('can set wrapped flag', function (): void {
        $xml = Xml::create()->wrapped();

        expect($xml->toArray())->toBe([
            'wrapped' => true,
        ]);
    });
})->covers(Xml::class);
