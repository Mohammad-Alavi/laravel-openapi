<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Contact;
use MohammadAlavi\ObjectOrientedOAS\Objects\Info;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(Contact::class)]
class ContactTest extends UnitTestCase
{
        public function test_create_with_all_parameters_works()
    {
        $contact = Contact::create()
            ->name('GoldSpec Digital')
            ->url('https://goldspecdigital.com')
            ->email('hello@goldspecdigital.com');

        $info = Info::create()
            ->contact($contact);

        $this->assertEquals([
            'contact' => [
                'name' => 'GoldSpec Digital',
                'url' => 'https://goldspecdigital.com',
                'email' => 'hello@goldspecdigital.com',
            ],
        ], $info->toArray());
    }
}
