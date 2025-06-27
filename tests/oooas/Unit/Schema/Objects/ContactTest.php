<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Contact::class)]
class ContactTest extends UnitTestCase
{
    public function testCreateWithAllParametersWorks(): void
    {
        $contact = Contact::create()
            ->name(Name::create('Example'))
            ->url(URL::create('https://laragen.io'))
            ->email(Email::create('hello@laragen.io'));

        $info = Info::create(
            Title::create('API Specification'),
            Version::create('v1'),
        )->contact($contact);

        $this->assertSame([
            'title' => 'API Specification',
            'contact' => [
                'name' => 'Example',
                'url' => 'https://laragen.io',
                'email' => 'hello@laragen.io',
            ],
            'version' => 'v1',
        ], $info->asArray());
    }
}
