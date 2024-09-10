<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\AnyOf;
use MohammadAlavi\ObjectOrientedOAS\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(AnyOf::class)]
class AnyOfTest extends UnitTestCase
{
        public function test_two_schemas_work()
    {
        $schema1 = Schema::string();
        $schema2 = Schema::integer();

        $anyOf = AnyOf::create()
            ->schemas($schema1, $schema2);

        $this->assertEquals([
            'anyOf' => [
                [
                    'type' => 'string',
                ],
                [
                    'type' => 'integer',
                ],
            ],
        ], $anyOf->toArray());
    }

        public function test_two_schemas_as_response_work()
    {
        $schema1 = Schema::string();
        $schema2 = Schema::integer();

        $anyOf = AnyOf::create()
            ->schemas($schema1, $schema2);

        $response = MediaType::json()
            ->schema($anyOf);

        $this->assertEquals([
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
        ], $response->toArray());
    }
}
