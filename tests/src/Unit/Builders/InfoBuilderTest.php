<?php

use MohammadAlavi\LaravelOpenApi\Builders\InfoBuilder;

describe(class_basename(InfoBuilder::class), function (): void {
    dataset('contact_dataset', function (): Iterator {
        $common = [
            'title' => 'sample_title',
            'description' => 'sample_description',
            'version' => 'sample_version',
        ];
        yield 'If all the elements are present, the correct json can be output.' => [
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If Contact.name does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
            [...$common, 'contact' => [
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If Contact.email does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If Contact.url does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If Contact does not exist, the correct json can be output.' => [
            [...$common, 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
            [...$common, 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If Contact.* does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [], 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
            [...$common, 'license' => [
                'name' => 'sample_license_name',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If License.name does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => [
                'url' => 'https://example.com',
            ]],
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If License.url does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
            ]],
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => [
                'name' => 'sample_license_name',
            ]],
        ];
        yield 'If License does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ]],
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If License.* does not exist, the correct json can be output.' => [
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ], 'license' => []],
            [...$common, 'contact' => [
                'name' => 'sample_contact_name',
                'email' => 'email@example.com',
                'url' => 'https://example.com',
            ]],
        ];
        yield 'If License and Contacts do not exist, the correct json can be output.' => [
            $common,
            $common,
        ];
    });

    it('can build contact', function (array $config, array $expected): void {
        $infoBuilder = new InfoBuilder();
        $info = $infoBuilder->build($config);

        expect($info->asArray())->toEqualCanonicalizing($expected);
    })->with('contact_dataset');
})->covers(InfoBuilder::class);
