<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields\URL;

describe(class_basename(ExternalDocumentation::class), function (): void {
    it('can be created with all parameters', function (): void {
        $externalDocs = ExternalDocumentation::create(
            URL::create('https://example.com'),
            Description::create('example Repo'),
        );

        expect($externalDocs->asArray())->toBe([
            'url' => 'https://example.com',
            'description' => 'example Repo',
        ]);
    });
})->covers(ExternalDocumentation::class);
