<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Link;
use MohammadAlavi\ObjectOrientedOAS\Objects\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Link::class)]
class LinkTest extends UnitTestCase
{
        public function test_create_with_all_parameters_works()
    {
        $link = Link::create('LinkName')
            ->operationId('goldspecdigital')
            ->description('The GoldSpec Digital website');

        $response = Response::create()
            ->links($link);

        $this->assertEquals([
            'links' => [
                'LinkName' => [
                    'operationId' => 'goldspecdigital',
                    'description' => 'The GoldSpec Digital website',
                ],
            ],
        ], $response->toArray());
    }
}
