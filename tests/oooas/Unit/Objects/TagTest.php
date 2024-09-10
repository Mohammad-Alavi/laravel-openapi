<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOAS\Objects\Tag;
use MohammadAlavi\ObjectOrientedOAS\OpenApi;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Tag::class)]
class TagTest extends UnitTestCase
{
        public function test_create_with_all_parameters_works()
    {
        $tag = Tag::create()
            ->name('Users')
            ->description('All user endpoints')
            ->externalDocs(ExternalDocs::create());

        $openApi = OpenApi::create()
            ->tags($tag);

        $this->assertEquals([
            'tags' => [
                [
                    'name' => 'Users',
                    'description' => 'All user endpoints',
                    'externalDocs' => [],
                ],
            ],
        ], $openApi->toArray());
    }
}
