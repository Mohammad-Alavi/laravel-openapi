<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use MohammadAlavi\ObjectOrientedOAS\Objects\Xml;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Xml::class)]
class XmlTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $xml = Xml::create()
            ->name('Xml name')
            ->namespace('xsi:example')
            ->prefix('gsd')
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
        ], $schema->toArray());
    }
}
