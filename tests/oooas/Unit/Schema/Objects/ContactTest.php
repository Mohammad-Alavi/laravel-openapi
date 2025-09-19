<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Contact::class)]
class ContactTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $contact = Contact::create()
            ->name('Example')
            ->url('https://laragen.io')
            ->email('hello@laragen.io');

        $info = Info::create('API Specification', 'v1')
            ->contact($contact);

        $this->assertSame([
            'title' => 'API Specification',
            'contact' => [
                'name' => 'Example',
                'url' => 'https://laragen.io',
                'email' => 'hello@laragen.io',
            ],
            'version' => 'v1',
        ], $info->compile());
    }
}
