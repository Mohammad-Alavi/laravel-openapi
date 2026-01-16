<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UniqueItems;

describe(class_basename(Constant::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Constant::create('test');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Constant::name())->toBe('const');
    });

    it('accepts string value', function (): void {
        $keyword = Constant::create('hello');

        expect($keyword->value())->toBe('hello')
            ->and($keyword->jsonSerialize())->toBe('hello');
    });

    it('accepts integer value', function (): void {
        $keyword = Constant::create(42);

        expect($keyword->value())->toBe(42)
            ->and($keyword->jsonSerialize())->toBe(42);
    });

    it('accepts boolean value', function (): void {
        $keyword = Constant::create(true);

        expect($keyword->value())->toBeTrue();
    });

    it('accepts null value', function (): void {
        $keyword = Constant::create(null);

        expect($keyword->value())->toBeNull();
    });

    it('accepts array value', function (): void {
        $keyword = Constant::create(['a', 'b', 'c']);

        expect($keyword->value())->toBe(['a', 'b', 'c']);
    });
})->covers(Constant::class);

describe(class_basename(Enum::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Enum::create('a', 'b', 'c');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Enum::name())->toBe('enum');
    });

    it('returns values correctly', function (): void {
        $keyword = Enum::create('red', 'green', 'blue');

        expect($keyword->value())->toBe(['red', 'green', 'blue']);
    });

    it('serializes as array', function (): void {
        $keyword = Enum::create(1, 2, 3, null);

        expect($keyword->jsonSerialize())->toBe([1, 2, 3, null]);
    });

    it('accepts mixed types', function (): void {
        $keyword = Enum::create('string', 123, true, null);

        expect($keyword->value())->toBe(['string', 123, true, null]);
    });
})->covers(Enum::class);

describe(class_basename(Maximum::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Maximum::create(100);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Maximum::name())->toBe('maximum');
    });

    it('returns value correctly', function (): void {
        $keyword = Maximum::create(50.5);

        expect($keyword->value())->toBe(50.5);
    });

    it('serializes as number', function (): void {
        $keyword = Maximum::create(99);

        expect($keyword->jsonSerialize())->toEqual(99);
    });
})->covers(Maximum::class);

describe(class_basename(Minimum::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Minimum::create(0);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Minimum::name())->toBe('minimum');
    });

    it('returns value correctly', function (): void {
        $keyword = Minimum::create(-10.5);

        expect($keyword->value())->toBe(-10.5);
    });

    it('serializes as number', function (): void {
        $keyword = Minimum::create(1);

        expect($keyword->jsonSerialize())->toEqual(1);
    });
})->covers(Minimum::class);

describe(class_basename(ExclusiveMaximum::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = ExclusiveMaximum::create(100);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(ExclusiveMaximum::name())->toBe('exclusiveMaximum');
    });

    it('returns value correctly', function (): void {
        $keyword = ExclusiveMaximum::create(50);

        expect($keyword->value())->toEqual(50);
    });

    it('serializes as number', function (): void {
        $keyword = ExclusiveMaximum::create(99.9);

        expect($keyword->jsonSerialize())->toBe(99.9);
    });
})->covers(ExclusiveMaximum::class);

describe(class_basename(ExclusiveMinimum::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = ExclusiveMinimum::create(0);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(ExclusiveMinimum::name())->toBe('exclusiveMinimum');
    });

    it('returns value correctly', function (): void {
        $keyword = ExclusiveMinimum::create(-5);

        expect($keyword->value())->toEqual(-5);
    });

    it('serializes as number', function (): void {
        $keyword = ExclusiveMinimum::create(0.1);

        expect($keyword->jsonSerialize())->toBe(0.1);
    });
})->covers(ExclusiveMinimum::class);

describe(class_basename(MultipleOf::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MultipleOf::create(5);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MultipleOf::name())->toBe('multipleOf');
    });

    it('returns value correctly', function (): void {
        $keyword = MultipleOf::create(0.01);

        expect($keyword->value())->toBe(0.01);
    });

    it('serializes as number', function (): void {
        $keyword = MultipleOf::create(10);

        expect($keyword->jsonSerialize())->toEqual(10);
    });
})->covers(MultipleOf::class);

describe(class_basename(MaxLength::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MaxLength::create(100);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MaxLength::name())->toBe('maxLength');
    });

    it('returns value correctly', function (): void {
        $keyword = MaxLength::create(255);

        expect($keyword->value())->toBe(255);
    });

    it('serializes as integer', function (): void {
        $keyword = MaxLength::create(50);

        expect($keyword->jsonSerialize())->toBe(50);
    });
})->covers(MaxLength::class);

describe(class_basename(MinLength::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MinLength::create(1);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MinLength::name())->toBe('minLength');
    });

    it('returns value correctly', function (): void {
        $keyword = MinLength::create(10);

        expect($keyword->value())->toBe(10);
    });

    it('serializes as integer', function (): void {
        $keyword = MinLength::create(0);

        expect($keyword->jsonSerialize())->toBe(0);
    });
})->covers(MinLength::class);

describe(class_basename(Pattern::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Pattern::create('^[a-z]+$');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Pattern::name())->toBe('pattern');
    });

    it('returns value correctly', function (): void {
        $keyword = Pattern::create('^\\d{3}-\\d{4}$');

        expect($keyword->value())->toBe('^\\d{3}-\\d{4}$');
    });

    it('serializes as string', function (): void {
        $keyword = Pattern::create('[A-Z][a-z]*');

        expect($keyword->jsonSerialize())->toBe('[A-Z][a-z]*');
    });
})->covers(Pattern::class);

describe(class_basename(MaxItems::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MaxItems::create(10);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MaxItems::name())->toBe('maxItems');
    });

    it('returns value correctly', function (): void {
        $keyword = MaxItems::create(100);

        expect($keyword->value())->toBe(100);
    });

    it('serializes as integer', function (): void {
        $keyword = MaxItems::create(5);

        expect($keyword->jsonSerialize())->toBe(5);
    });
})->covers(MaxItems::class);

describe(class_basename(MinItems::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MinItems::create(1);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MinItems::name())->toBe('minItems');
    });

    it('returns value correctly', function (): void {
        $keyword = MinItems::create(0);

        expect($keyword->value())->toBe(0);
    });

    it('serializes as integer', function (): void {
        $keyword = MinItems::create(3);

        expect($keyword->jsonSerialize())->toBe(3);
    });
})->covers(MinItems::class);

describe(class_basename(UniqueItems::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = UniqueItems::create(true);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(UniqueItems::name())->toBe('uniqueItems');
    });

    it('returns value correctly', function (): void {
        $keyword = UniqueItems::create(true);

        expect($keyword->value())->toBeTrue();
    });

    it('serializes as boolean', function (): void {
        $keyword = UniqueItems::create(false);

        expect($keyword->jsonSerialize())->toBeFalse();
    });
})->covers(UniqueItems::class);

describe(class_basename(MaxContains::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MaxContains::create(5);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MaxContains::name())->toBe('maxContains');
    });

    it('returns value correctly', function (): void {
        $keyword = MaxContains::create(10);

        expect($keyword->value())->toBe(10);
    });

    it('serializes as integer', function (): void {
        $keyword = MaxContains::create(3);

        expect($keyword->jsonSerialize())->toBe(3);
    });
})->covers(MaxContains::class);

describe(class_basename(MinContains::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MinContains::create(1);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MinContains::name())->toBe('minContains');
    });

    it('returns value correctly', function (): void {
        $keyword = MinContains::create(2);

        expect($keyword->value())->toBe(2);
    });

    it('serializes as integer', function (): void {
        $keyword = MinContains::create(0);

        expect($keyword->jsonSerialize())->toBe(0);
    });
})->covers(MinContains::class);

describe(class_basename(MaxProperties::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MaxProperties::create(10);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MaxProperties::name())->toBe('maxProperties');
    });

    it('returns value correctly', function (): void {
        $keyword = MaxProperties::create(50);

        expect($keyword->value())->toBe(50);
    });

    it('serializes as integer', function (): void {
        $keyword = MaxProperties::create(5);

        expect($keyword->jsonSerialize())->toBe(5);
    });
})->covers(MaxProperties::class);

describe(class_basename(MinProperties::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = MinProperties::create(1);

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(MinProperties::name())->toBe('minProperties');
    });

    it('returns value correctly', function (): void {
        $keyword = MinProperties::create(0);

        expect($keyword->value())->toBe(0);
    });

    it('serializes as integer', function (): void {
        $keyword = MinProperties::create(2);

        expect($keyword->jsonSerialize())->toBe(2);
    });
})->covers(MinProperties::class);

describe(class_basename(Required::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Required::create('name');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Required::name())->toBe('required');
    });

    it('returns value as array', function (): void {
        $keyword = Required::create('name', 'email', 'age');

        expect($keyword->value())->toBe(['name', 'email', 'age']);
    });

    it('serializes as array of strings', function (): void {
        $keyword = Required::create('id', 'type');

        expect($keyword->jsonSerialize())->toBe(['id', 'type']);
    });

    it('handles single property', function (): void {
        $keyword = Required::create('id');

        expect($keyword->value())->toBe(['id']);
    });
})->covers(Required::class);
