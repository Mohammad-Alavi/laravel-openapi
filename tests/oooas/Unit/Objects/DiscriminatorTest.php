<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Discriminator;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Discriminator::class)]
class DiscriminatorTest extends UnitTestCase
{
        public function test_create_with_all_parameters_works()
    {
        $discriminator = Discriminator::create()
            ->propertyName('Discriminator Name')
            ->mapping(['key' => 'value']);

        $schema = Schema::object()
            ->discriminator($discriminator);

        $this->assertEquals([
            'type' => 'object',
            'discriminator' => [
                'propertyName' => 'Discriminator Name',
                'mapping' => [
                    'key' => 'value',
                ],
            ],
        ], $schema->toArray());
    }
}
