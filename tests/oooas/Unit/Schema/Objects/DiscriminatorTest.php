<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Discriminator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\Entry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\Mapping;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\PropertyName;

describe('Discriminator', function (): void {
    it('can be created with all parameters', function (): void {
        $discriminator = Discriminator::create(
            PropertyName::create('Discriminator Name'),
            Mapping::create(
                Entry::create('key', 'value'),
            ),
        );

        expect($discriminator->asArray())->toBe([
            'propertyName' => 'Discriminator Name',
            'mapping' => [
                'key' => 'value',
            ],
        ]);
    });

    it('will have no mapping if no mapping is passed', function (): void {
        $discriminator = Discriminator::create(PropertyName::create('something'));

        expect($discriminator->asArray())->toBe([
            'propertyName' => 'something',
        ]);
    });
})->covers(Discriminator::class);
