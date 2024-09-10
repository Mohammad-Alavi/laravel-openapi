<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOAS\Objects\OneOf;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(OneOf::class)]
class OneOfTest extends UnitTestCase
{
        public function test_two_schemas_work()
    {
        $schema1 = Schema::string();
        $schema2 = Schema::integer();

        $oneOf = OneOf::create()
            ->schemas($schema1, $schema2);

        $this->assertEquals([
            'oneOf' => [
                [
                    'type' => 'string',
                ],
                [
                    'type' => 'integer',
                ],
            ],
        ], $oneOf->toArray());
    }

        public function test_two_schemas_as_response_work()
    {
        $schema1 = Schema::string();
        $schema2 = Schema::integer();

        $oneOf = OneOf::create()
            ->schemas($schema1, $schema2);

        $response = MediaType::json()
            ->schema($oneOf);

        $this->assertEquals([
            'schema' => [
                'oneOf' => [
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
