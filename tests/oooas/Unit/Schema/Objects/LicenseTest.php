<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(License::class)]
class LicenseTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $license = License::create('MIT')->identifier('MIT');

        $info = Info::create('Example Api', 'v1')->license($license);

        $this->assertSame([
            'title' => 'Example Api',
            'license' => [
                'name' => 'MIT',
                'identifier' => 'MIT',
            ],
            'version' => 'v1',
        ], $info->unserializeToArray());
    }
}
