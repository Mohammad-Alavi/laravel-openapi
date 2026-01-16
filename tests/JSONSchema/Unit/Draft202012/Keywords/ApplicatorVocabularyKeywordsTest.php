<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ElseKeyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IfKeyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Not;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Then;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(AdditionalProperties::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = AdditionalProperties::create(false);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(AdditionalProperties::name())->toBe('additionalProperties');
    });

    it('can be set to false', function (): void {
        $keyword = AdditionalProperties::create(false);

        expect($keyword->value())->toBeFalse();
        expect($keyword->jsonSerialize())->toBeFalse();
    });

    it('can be set to true', function (): void {
        $keyword = AdditionalProperties::create(true);

        expect($keyword->value())->toBeTrue();
        expect($keyword->jsonSerialize())->toBeTrue();
    });

    it('can be set to a schema', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('string');
        $keyword = AdditionalProperties::create($schema);

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['type'])->toBe('string');
    });
})->covers(AdditionalProperties::class);

describe(class_basename(Items::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Items::create(
            LooseFluentDescriptor::withoutSchema()->type('string'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Items::name())->toBe('items');
    });

    it('returns value correctly', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('integer');
        $keyword = Items::create($schema);

        expect($keyword->value())->toBe($schema);
    });

    it('serializes as schema', function (): void {
        $keyword = Items::create(
            LooseFluentDescriptor::withoutSchema()->type('number')->minimum(0),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['type'])->toBe('number');
        expect($serialized['minimum'])->toBe(0);
    });
})->covers(Items::class);

describe(class_basename(AllOf::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = AllOf::create(
            LooseFluentDescriptor::withoutSchema()->type('object'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(AllOf::name())->toBe('allOf');
    });

    it('serializes as array of schemas', function (): void {
        $keyword = AllOf::create(
            LooseFluentDescriptor::withoutSchema()->type('object'),
            LooseFluentDescriptor::withoutSchema()->required('name'),
            LooseFluentDescriptor::withoutSchema()->required('email'),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toHaveCount(3);
        expect($serialized[0]['type'])->toBe('object');
        expect($serialized[1]['required'])->toBe(['name']);
        expect($serialized[2]['required'])->toBe(['email']);
    });
})->covers(AllOf::class);

describe(class_basename(AnyOf::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = AnyOf::create(
            LooseFluentDescriptor::withoutSchema()->type('string'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(AnyOf::name())->toBe('anyOf');
    });

    it('serializes as array of schemas', function (): void {
        $keyword = AnyOf::create(
            LooseFluentDescriptor::withoutSchema()->type('string'),
            LooseFluentDescriptor::withoutSchema()->type('number'),
            LooseFluentDescriptor::withoutSchema()->type('null'),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toHaveCount(3);
        expect($serialized[0]['type'])->toBe('string');
        expect($serialized[1]['type'])->toBe('number');
        expect($serialized[2]['type'])->toBe('null');
    });
})->covers(AnyOf::class);

describe(class_basename(OneOf::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = OneOf::create(
            LooseFluentDescriptor::withoutSchema()->type('string'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(OneOf::name())->toBe('oneOf');
    });

    it('serializes as array of schemas', function (): void {
        $keyword = OneOf::create(
            LooseFluentDescriptor::withoutSchema()->type('integer')->multipleOf(5),
            LooseFluentDescriptor::withoutSchema()->type('integer')->multipleOf(3),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toHaveCount(2);
        expect($serialized[0]['type'])->toBe('integer');
        expect($serialized[0]['multipleOf'])->toBe(5);
        expect($serialized[1]['multipleOf'])->toBe(3);
    });
})->covers(OneOf::class);

describe(class_basename(Not::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Not::create(
            LooseFluentDescriptor::withoutSchema()->type('string'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Not::name())->toBe('not');
    });

    it('serializes as schema', function (): void {
        $keyword = Not::create(
            LooseFluentDescriptor::withoutSchema()->type('null'),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['type'])->toBe('null');
    });
})->covers(Not::class);

describe(class_basename(IfKeyword::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = IfKeyword::create(
            LooseFluentDescriptor::withoutSchema()->type('object'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(IfKeyword::name())->toBe('if');
    });

    it('serializes as schema', function (): void {
        $keyword = IfKeyword::create(
            LooseFluentDescriptor::withoutSchema()
                ->properties(
                    MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property::create(
                        'country',
                        LooseFluentDescriptor::withoutSchema()->const('USA'),
                    ),
                ),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['properties']['country']['const'])->toBe('USA');
    });
})->covers(IfKeyword::class);

describe(class_basename(Then::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Then::create(
            LooseFluentDescriptor::withoutSchema()->required('postal_code'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Then::name())->toBe('then');
    });

    it('serializes as schema', function (): void {
        $keyword = Then::create(
            LooseFluentDescriptor::withoutSchema()->required('zip_code'),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['required'])->toBe(['zip_code']);
    });
})->covers(Then::class);

describe(class_basename(ElseKeyword::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = ElseKeyword::create(
            LooseFluentDescriptor::withoutSchema()->required('region'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(ElseKeyword::name())->toBe('else');
    });

    it('serializes as schema', function (): void {
        $keyword = ElseKeyword::create(
            LooseFluentDescriptor::withoutSchema()->required('province'),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['required'])->toBe(['province']);
    });
})->covers(ElseKeyword::class);
