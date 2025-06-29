<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\Prefix;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields\XmlNamespace;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Xml;

describe(class_basename(Xml::class), function (): void {
    it('it can be creates with basic property', function (): void {
        $xml = Xml::create()
            ->name('Xml name')
            ->namespace(XmlNamespace::create('xsi:example'))
            ->prefix(Prefix::create('gsd'))
            ->attribute()
            ->wrapped();

        $schema = Schema::object()
            ->xml($xml);

        $this->assertEquals([
            'type' => 'object',
            'xml' => [
                'name' => 'Xml name',
                'namespace' => 'xsi:example',
                'prefix' => 'gsd',
                'attribute' => true,
                'wrapped' => true,
            ],
        ], $schema->asArray());
    })->todo();
})->covers(Xml::class);
