<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(License::class)]
class LicenseTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $license = License::create()
            ->name('MIT')
            ->url('https://example.com');

        $info = Info::create(
            Title::create('Example Api'),
            Version::create('v1'),
        )->license($license);

        $this->assertSame([
            'title' => 'Example Api',
            'license' => [
                'name' => 'MIT',
                'url' => 'https://example.com',
            ],
            'version' => 'v1',
        ], $info->asArray());
    }
}
