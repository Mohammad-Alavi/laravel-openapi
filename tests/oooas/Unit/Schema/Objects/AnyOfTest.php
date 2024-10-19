<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\AnyOf;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(AnyOf::class)]
class AnyOfTest extends UnitTestCase
{
    public function testTwoSchemasWork(): void
    {
        $schema1 = Schema::string('string_test');
        $schema2 = Schema::integer('integer_test');

        $anyOf = AnyOf::create('test')
            ->schemas($schema1, $schema2);

        $this->assertSame([
            'anyOf' => [
                [
                    'type' => 'string',
                ],
                [
                    'type' => 'integer',
                ],
            ],
        ], $anyOf->asArray());
    }

    public function testTwoSchemasAsResponseWork(): void
    {
        $schema1 = Schema::string('string_test');
        $schema2 = Schema::integer('integer_test');

        $anyOf = AnyOf::create('test')
            ->schemas($schema1, $schema2);

        $mediaType = MediaType::json()
            ->schema($anyOf);

        $this->assertSame([
            'schema' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'integer',
                    ],
                ],
            ],
        ], $mediaType->asArray());
    }
}
