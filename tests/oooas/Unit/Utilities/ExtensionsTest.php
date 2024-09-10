<?php

namespace Tests\oooas\Unit\Utilities;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\PropertyDoesNotExistException;
use MohammadAlavi\ObjectOrientedOAS\Objects\Components;
use MohammadAlavi\ObjectOrientedOAS\Objects\Operation;
use MohammadAlavi\ObjectOrientedOAS\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOAS\Objects\Response;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Extensions;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Extensions::class)]
class ExtensionsTest extends UnitTestCase
{
    /**
     * @test
     * @dataProvider schemasDataProvider
     * @param string|\MohammadAlavi\ObjectOrientedOAS\Objects\Schema $schema
     */
    public function create_with_extensions($schema)
    {
        $object = $schema::create()
            ->x('key', 'value')
            ->x('x-foo', 'bar')
            ->x('x-baz', null)
            ->x('x-array', Schema::array()->items(Schema::string()));

        $this->assertEquals([
            'x-key' => 'value',
            'x-foo' => 'bar',
            'x-baz' => null,
            'x-array' => Schema::array()->items(Schema::string()),
        ], $object->toArray());

        $this->assertEquals(
            '{"x-key":"value","x-foo":"bar","x-baz":null,"x-array":{"type":"array","items":{"type":"string"}}}',
            $object->toJson()
        );
    }

        public function test_can_unset_extensions()
    {
        $object = Schema::create()
            ->x('key', 'value')
            ->x('x-foo', 'bar')
            ->x('x-baz', null);

        $object = $object->x('key');

        $this->assertEquals([
            'x-foo' => 'bar',
            'x-baz' => null,
        ], $object->toArray());

        $this->assertEquals('{"x-foo":"bar","x-baz":null}', $object->toJson());
    }

    /**
     * @test
     * @dataProvider schemasDataProvider
     * @param string|\MohammadAlavi\ObjectOrientedOAS\Objects\Schema $schema
     */
    public function get_single_extension($schema)
    {
        $object = $schema::create()->x('foo', 'bar');

        $this->assertEquals('bar', $object->{'x-foo'});
    }

    /**
     * @test
     * @dataProvider schemasDataProvider
     * @param string|\MohammadAlavi\ObjectOrientedOAS\Objects\Schema $schema
     */
    public function get_single_extension_does_not_exist($schema)
    {
        $object = $schema::create()->x('foo', 'bar');

        $this->expectException(PropertyDoesNotExistException::class);
        $this->expectExceptionMessage('[x-key] is not a valid property');
        $this->assertEquals('bar', $object->{'x-key'});
    }

    /**
     * @test
     * @dataProvider schemasDataProvider
     * @param string|\MohammadAlavi\ObjectOrientedOAS\Objects\Schema $schema
     */
    public function get_all_extensions($schema)
    {
        $object = $schema::create();

        $this->assertEquals([], $object->x);

        $object = $object
            ->x('key', 'value')
            ->x('foo', 'bar');

        $this->assertEquals(['x-key' => 'value', 'x-foo' => 'bar'], $object->x);
    }

    /**
     * @return array
     */
    public function schemasDataProvider(): array
    {
        return [
            [Components::class],
            [Operation::class],
            [PathItem::class],
            [Response::class],
            [Schema::class],
        ];
    }
}
