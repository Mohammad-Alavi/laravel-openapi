<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Contact::class)]
class ContactTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $contact = Contact::create()
            ->name('Example')
            ->url('https://example.com')
            ->email('hello@example.com');

        $info = Info::create()
            ->contact($contact);

        $this->assertSame([
            'contact' => [
                'name' => 'Example',
                'url' => 'https://example.com',
                'email' => 'hello@example.com',
            ],
        ], $info->asArray());
    }
}
