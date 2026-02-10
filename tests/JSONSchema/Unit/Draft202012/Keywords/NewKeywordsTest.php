<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Contains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ContentEncoding;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ContentMediaType;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ContentSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas\DependentSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas\DependentSchemas;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties\PatternProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties\PatternProperty;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PrefixItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PropertyNames;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe('PrefixItems keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = PrefixItems::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::string()),
            LooseFluentDescriptor::withoutSchema()->type(Type::number()),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(PrefixItems::name())->toBe('prefixItems');
    });

    it('serializes as array of schemas', function (): void {
        $keyword = PrefixItems::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::string()),
            LooseFluentDescriptor::withoutSchema()->type(Type::number()),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toBeArray();
        expect($serialized)->toHaveCount(2);
        expect($serialized[0]['type'])->toBe('string');
        expect($serialized[1]['type'])->toBe('number');
    });
})->covers(PrefixItems::class);

describe('Contains keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Contains::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::string()),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Contains::name())->toBe('contains');
    });

    it('serializes as schema', function (): void {
        $keyword = Contains::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::string()),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['type'])->toBe('string');
    });
})->covers(Contains::class);

describe('PatternProperties keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = PatternProperties::create(
            PatternProperty::create('^S_', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(PatternProperties::name())->toBe('patternProperties');
    });

    it('serializes as object with pattern keys', function (): void {
        $keyword = PatternProperties::create(
            PatternProperty::create('^S_', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
            PatternProperty::create('^I_', LooseFluentDescriptor::withoutSchema()->type(Type::integer())),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toHaveKey('^S_');
        expect($serialized)->toHaveKey('^I_');
        expect($serialized['^S_']['type'])->toBe('string');
        expect($serialized['^I_']['type'])->toBe('integer');
    });

    it('serializes as empty object when no patterns', function (): void {
        $keyword = PatternProperties::create();

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toBe([]);
    });
})->covers(PatternProperties::class, PatternProperty::class);

describe('DependentSchemas keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = DependentSchemas::create(
            DependentSchema::create('credit_card', LooseFluentDescriptor::withoutSchema()),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(DependentSchemas::name())->toBe('dependentSchemas');
    });

    it('serializes as object with property keys', function (): void {
        $keyword = DependentSchemas::create(
            DependentSchema::create('credit_card', LooseFluentDescriptor::withoutSchema()->required('billing_address')),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized)->toHaveKey('credit_card');
        expect($serialized['credit_card']['required'])->toBe(['billing_address']);
    });
})->covers(DependentSchemas::class, DependentSchema::class);

describe('PropertyNames keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = PropertyNames::create(
            LooseFluentDescriptor::withoutSchema()->pattern('^[A-Za-z_][A-Za-z0-9_]*$'),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(PropertyNames::name())->toBe('propertyNames');
    });

    it('serializes as schema', function (): void {
        $keyword = PropertyNames::create(
            LooseFluentDescriptor::withoutSchema()->pattern('^[A-Za-z_][A-Za-z0-9_]*$'),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['pattern'])->toBe('^[A-Za-z_][A-Za-z0-9_]*$');
    });
})->covers(PropertyNames::class);

describe('ContentEncoding keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = ContentEncoding::create('base64');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(ContentEncoding::name())->toBe('contentEncoding');
    });

    it('serializes as string', function (): void {
        $keyword = ContentEncoding::create('base64');

        expect($keyword->jsonSerialize())->toBe('base64');
    });

    it('returns value correctly', function (): void {
        $keyword = ContentEncoding::create('quoted-printable');

        expect($keyword->value())->toBe('quoted-printable');
    });
})->covers(ContentEncoding::class);

describe('ContentMediaType keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = ContentMediaType::create('application/json');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(ContentMediaType::name())->toBe('contentMediaType');
    });

    it('serializes as string', function (): void {
        $keyword = ContentMediaType::create('image/png');

        expect($keyword->jsonSerialize())->toBe('image/png');
    });

    it('returns value correctly', function (): void {
        $keyword = ContentMediaType::create('text/html');

        expect($keyword->value())->toBe('text/html');
    });
})->covers(ContentMediaType::class);

describe('ContentSchema keyword', function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = ContentSchema::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::object()),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(ContentSchema::name())->toBe('contentSchema');
    });

    it('serializes as schema', function (): void {
        $keyword = ContentSchema::create(
            LooseFluentDescriptor::withoutSchema()->type(Type::object()),
        );

        $serialized = json_decode(json_encode($keyword), true);

        expect($serialized['type'])->toBe('object');
    });
})->covers(ContentSchema::class);
