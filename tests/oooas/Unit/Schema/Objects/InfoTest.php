<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;

describe(class_basename(Info::class), function (): void {
    it('should set all parameters', function (): void {
        $info = Info::create(
            'Pretend API',
            'v1',
        )->summary('Some Arrays!')
            ->description('A pretend API')
            ->termsOfService('https://laragen.io')
            ->contact(Contact::create())
            ->license(License::create('MIT'));

        expect($info->unserializeToArray())->toBe([
            'title' => 'Pretend API',
            'summary' => 'Some Arrays!',
            'description' => 'A pretend API',
            'termsOfService' => 'https://laragen.io',
            'contact' => [],
            'license' => [
                'name' => 'MIT',
            ],
            'version' => 'v1',
        ]);
    });
})->covers(Info::class);
