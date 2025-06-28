<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;

describe(class_basename(ExternalDocumentation::class), function (): void {
    it('can be created with all parameters', function (): void {
        $externalDocumentation = ExternalDocumentation::create(
            URL::create('https://laragen.io'),
            Description::create('example Repo'),
        );

        expect($externalDocumentation->unserializeToArray())->toBe([
            'url' => 'https://laragen.io',
            'description' => 'example Repo',
        ]);
    });
})->covers(ExternalDocumentation::class);
