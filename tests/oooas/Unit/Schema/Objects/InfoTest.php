<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\TermsOfService;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License;

describe(class_basename(Info::class), function (): void {
    it('should set all parameters', function (): void {
        $info = Info::create(
            Title::create('Pretend API'),
            Version::create('v1'),
        )->summary(Summary::create('Some Arrays!'))
            ->description(Description::create('A pretend API'))
            ->termsOfService(TermsOfService::create('https://example.com'))
            ->contact(Contact::create())
            ->license(License::create());

        expect($info->asArray())->toBe([
            'title' => 'Pretend API',
            'summary' => 'Some Arrays!',
            'description' => 'A pretend API',
            'termsOfService' => 'https://example.com',
            'contact' => [],
            'license' => [],
            'version' => 'v1',
        ]);
    });
})->covers(Info::class);
