<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Title;

describe(class_basename(Title::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Title::create('User Schema');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Title::name())->toBe('title');
    });

    it('returns value correctly', function (): void {
        $keyword = Title::create('Product');

        expect($keyword->value())->toBe('Product');
    });

    it('serializes as string', function (): void {
        $keyword = Title::create('Order Item');

        expect($keyword->jsonSerialize())->toBe('Order Item');
    });
})->covers(Title::class);

describe(class_basename(Description::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Description::create('A user object');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Description::name())->toBe('description');
    });

    it('returns value correctly', function (): void {
        $keyword = Description::create('Represents a product in the catalog');

        expect($keyword->value())->toBe('Represents a product in the catalog');
    });

    it('serializes as string', function (): void {
        $keyword = Description::create('This field contains the user email address');

        expect($keyword->jsonSerialize())->toBe('This field contains the user email address');
    });

    it('handles multiline descriptions', function (): void {
        $description = "First line\nSecond line\nThird line";
        $keyword = Description::create($description);

        expect($keyword->value())->toBe($description);
    });
})->covers(Description::class);

describe(class_basename(DefaultValue::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = DefaultValue::create('default');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(DefaultValue::name())->toBe('default');
    });

    it('accepts string default', function (): void {
        $keyword = DefaultValue::create('hello');

        expect($keyword->value())->toBe('hello');
        expect($keyword->jsonSerialize())->toBe('hello');
    });

    it('accepts integer default', function (): void {
        $keyword = DefaultValue::create(42);

        expect($keyword->value())->toBe(42);
    });

    it('accepts float default', function (): void {
        $keyword = DefaultValue::create(3.14);

        expect($keyword->value())->toBe(3.14);
    });

    it('accepts boolean default', function (): void {
        $keyword = DefaultValue::create(false);

        expect($keyword->value())->toBeFalse();
    });

    it('accepts null default', function (): void {
        $keyword = DefaultValue::create(null);

        expect($keyword->value())->toBeNull();
    });

    it('accepts array default', function (): void {
        $keyword = DefaultValue::create(['a', 'b']);

        expect($keyword->value())->toBe(['a', 'b']);
    });

    it('accepts object default', function (): void {
        $default = ['name' => 'John', 'age' => 30];
        $keyword = DefaultValue::create($default);

        expect($keyword->value())->toBe($default);
    });
})->covers(DefaultValue::class);

describe(class_basename(Examples::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Examples::create('example1');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Examples::name())->toBe('examples');
    });

    it('returns values as array', function (): void {
        $keyword = Examples::create('red', 'green', 'blue');

        expect($keyword->value())->toBe(['red', 'green', 'blue']);
    });

    it('serializes as array', function (): void {
        $keyword = Examples::create(1, 2, 3);

        expect($keyword->jsonSerialize())->toBe([1, 2, 3]);
    });

    it('accepts mixed types', function (): void {
        $keyword = Examples::create('string', 123, true, null, ['array']);

        expect($keyword->value())->toBe(['string', 123, true, null, ['array']]);
    });

    it('handles single example', function (): void {
        $keyword = Examples::create('single');

        expect($keyword->value())->toBe(['single']);
    });
})->covers(Examples::class);

describe(class_basename(IsReadOnly::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = IsReadOnly::create();

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(IsReadOnly::name())->toBe('readOnly');
    });

    it('returns true as value', function (): void {
        $keyword = IsReadOnly::create();

        expect($keyword->value())->toBeTrue();
    });

    it('serializes as true', function (): void {
        $keyword = IsReadOnly::create();

        expect($keyword->jsonSerialize())->toBeTrue();
    });
})->covers(IsReadOnly::class);

describe(class_basename(IsWriteOnly::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = IsWriteOnly::create();

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(IsWriteOnly::name())->toBe('writeOnly');
    });

    it('returns true as value', function (): void {
        $keyword = IsWriteOnly::create();

        expect($keyword->value())->toBeTrue();
    });

    it('serializes as true', function (): void {
        $keyword = IsWriteOnly::create();

        expect($keyword->jsonSerialize())->toBeTrue();
    });
})->covers(IsWriteOnly::class);
