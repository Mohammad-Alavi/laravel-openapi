<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Contact;
use MohammadAlavi\ObjectOrientedOAS\Objects\Info;
use MohammadAlavi\ObjectOrientedOAS\Objects\License;
use MohammadAlavi\ObjectOrientedOAS\OpenApi;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Info::class)]
class InfoTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $info = Info::create()
            ->title('Pretend API')
            ->description('A pretend API')
            ->termsOfService('https://goldspecdigital.com')
            ->contact(Contact::create())
            ->license(License::create())
            ->version('v1');

        $openApi = OpenApi::create()
            ->info($info);

        $this->assertSame([
            'info' => [
                'title' => 'Pretend API',
                'description' => 'A pretend API',
                'termsOfService' => 'https://goldspecdigital.com',
                'contact' => [],
                'license' => [],
                'version' => 'v1',
            ],
        ], $openApi->toArray());
    }
}
