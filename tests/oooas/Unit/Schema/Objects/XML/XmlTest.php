<?php

namespace Tests\oooas\Unit\Schema\Objects\XML;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\NodeType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Xml;

describe(class_basename(Xml::class), function (): void {
    it('can be created with name', function (): void {
        $xml = Xml::create()
            ->name('item');

        expect($xml->compile())->toBe([
            'name' => 'item',
        ]);
    });

    it('can be created with namespace and prefix', function (): void {
        $xml = Xml::create()
            ->name('animal')
            ->namespace('http://example.com/schema/sample')
            ->prefix('sample');

        expect($xml->compile())->toBe([
            'name' => 'animal',
            'namespace' => 'http://example.com/schema/sample',
            'prefix' => 'sample',
        ]);
    });

    it('can be created with attribute flag', function (): void {
        $xml = Xml::create()
            ->name('id')
            ->attribute();

        expect($xml->compile())->toBe([
            'name' => 'id',
            'attribute' => true,
        ]);
    });

    it('can be created with wrapped flag', function (): void {
        $xml = Xml::create()
            ->name('items')
            ->wrapped();

        expect($xml->compile())->toBe([
            'name' => 'items',
            'wrapped' => true,
        ]);
    });

    it('can be created with nodeType element (OAS 3.2)', function (): void {
        $xml = Xml::create()
            ->name('item')
            ->nodeType(NodeType::ELEMENT);

        expect($xml->compile())->toBe([
            'name' => 'item',
            'nodeType' => 'element',
        ]);
    });

    it('can be created with nodeType attribute (OAS 3.2)', function (): void {
        $xml = Xml::create()
            ->name('id')
            ->nodeType(NodeType::ATTRIBUTE);

        expect($xml->compile())->toBe([
            'name' => 'id',
            'nodeType' => 'attribute',
        ]);
    });

    it('can be created with nodeType text (OAS 3.2)', function (): void {
        $xml = Xml::create()
            ->nodeType(NodeType::TEXT);

        expect($xml->compile())->toBe([
            'nodeType' => 'text',
        ]);
    });

    it('can be created with nodeType cdata (OAS 3.2)', function (): void {
        $xml = Xml::create()
            ->nodeType(NodeType::CDATA);

        expect($xml->compile())->toBe([
            'nodeType' => 'cdata',
        ]);
    });

    it('can be created with nodeType none (OAS 3.2)', function (): void {
        $xml = Xml::create()
            ->nodeType(NodeType::NONE);

        expect($xml->compile())->toBe([
            'nodeType' => 'none',
        ]);
    });

    it('can be created with all parameters including nodeType (OAS 3.2)', function (): void {
        $xml = Xml::create()
            ->name('person')
            ->namespace('http://example.com/schema')
            ->prefix('ex')
            ->nodeType(NodeType::ELEMENT);

        expect($xml->compile())->toBe([
            'name' => 'person',
            'namespace' => 'http://example.com/schema',
            'prefix' => 'ex',
            'nodeType' => 'element',
        ]);
    });
})->covers(Xml::class);

describe(class_basename(NodeType::class), function (): void {
    it('has element case', function (): void {
        expect(NodeType::ELEMENT->value)->toBe('element');
    });

    it('has attribute case', function (): void {
        expect(NodeType::ATTRIBUTE->value)->toBe('attribute');
    });

    it('has text case', function (): void {
        expect(NodeType::TEXT->value)->toBe('text');
    });

    it('has cdata case', function (): void {
        expect(NodeType::CDATA->value)->toBe('cdata');
    });

    it('has none case', function (): void {
        expect(NodeType::NONE->value)->toBe('none');
    });
})->covers(NodeType::class);
