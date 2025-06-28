<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Identifier;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(License::class)]
class LicenseTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $license = License::create(
            Name::create('MIT'),
            Identifier::create('MIT'),
        );

        $info = Info::create(
            Title::create('Example Api'),
            Version::create('v1'),
        )->license($license);

        $this->assertSame([
            'title' => 'Example Api',
            'license' => [
                'name' => 'MIT',
                'identifier' => 'MIT',
            ],
            'version' => 'v1',
        ], $info->asArray());
    }
}
