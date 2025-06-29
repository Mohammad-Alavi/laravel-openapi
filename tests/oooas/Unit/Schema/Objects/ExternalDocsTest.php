<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;

describe(class_basename(ExternalDocumentation::class), function (): void {
    it('can be created with all parameters', function (): void {
        $externalDocumentation = ExternalDocumentation::create('https://laragen.io')
            ->description('example Repo');

        expect($externalDocumentation->unserializeToArray())->toBe([
            'url' => 'https://laragen.io',
            'description' => 'example Repo',
        ]);
    });
})->covers(ExternalDocumentation::class);
