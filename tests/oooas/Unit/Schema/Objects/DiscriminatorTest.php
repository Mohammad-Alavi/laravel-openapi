<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Discriminator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\Entry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\URL;

describe('Discriminator', function (): void {
    it('can be created with all parameters', function (): void {
        $discriminator = Discriminator::create(
            'Discriminator Name',
            Entry::create('cat', Name::create('value')),
            Entry::create(
                'dog',
                URL::create('https://laragen.io/dog'),
            ),
        );

        expect($discriminator->compile())->toBe([
            'propertyName' => 'Discriminator Name',
            'mapping' => [
                'cat' => 'value',
                'dog' => 'https://laragen.io/dog',
            ],
        ]);
    });

    it('will have no mapping if no mapping is passed', function (): void {
        $discriminator = Discriminator::create('something');

        expect($discriminator->compile())->toBe([
            'propertyName' => 'something',
        ]);
    });

    it('can set defaultMapping', function (): void {
        $discriminator = Discriminator::create('petType')
            ->defaultMapping('GenericPet');

        expect($discriminator->compile())->toBe([
            'propertyName' => 'petType',
            'defaultMapping' => 'GenericPet',
        ]);
    });

    it('can set defaultMapping with mapping', function (): void {
        $discriminator = Discriminator::create(
            'petType',
            Entry::create('cat', Name::create('Cat')),
        )->defaultMapping('GenericPet');

        expect($discriminator->compile())->toBe([
            'propertyName' => 'petType',
            'mapping' => [
                'cat' => 'Cat',
            ],
            'defaultMapping' => 'GenericPet',
        ]);
    });

    it('can set defaultMapping as URL', function (): void {
        $discriminator = Discriminator::create('petType')
            ->defaultMapping('https://example.com/schemas/GenericPet');

        expect($discriminator->compile())->toBe([
            'propertyName' => 'petType',
            'defaultMapping' => 'https://example.com/schemas/GenericPet',
        ]);
    });
})->covers(Discriminator::class);
