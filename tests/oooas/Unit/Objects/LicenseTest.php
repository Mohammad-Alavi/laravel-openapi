<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Info;
use MohammadAlavi\ObjectOrientedOAS\Objects\License;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(License::class)]
class LicenseTest extends UnitTestCase
{
        public function test_create_with_all_parameters_works()
    {
        $license = License::create()
            ->name('MIT')
            ->url('https://goldspecdigital.com');

        $info = Info::create()
            ->license($license);

        $this->assertEquals([
            'license' => [
                'name' => 'MIT',
                'url' => 'https://goldspecdigital.com',
            ],
        ], $info->toArray());
    }
}
