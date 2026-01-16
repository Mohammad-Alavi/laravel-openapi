<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocab;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocabulary;

describe(class_basename(Schema::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Schema::create('https://json-schema.org/draft/2020-12/schema');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Schema::name())->toBe('$schema');
    });

    it('returns value correctly', function (): void {
        $keyword = Schema::create('https://json-schema.org/draft/2020-12/schema');

        expect($keyword->value())->toBe('https://json-schema.org/draft/2020-12/schema');
    });

    it('serializes as string', function (): void {
        $keyword = Schema::create('https://json-schema.org/draft/2020-12/schema');

        expect($keyword->jsonSerialize())->toBe('https://json-schema.org/draft/2020-12/schema');
    });
})->covers(Schema::class);

describe(class_basename(Id::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Id::create('https://example.com/schema');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Id::name())->toBe('$id');
    });

    it('returns value correctly', function (): void {
        $keyword = Id::create('https://example.com/my-schema');

        expect($keyword->value())->toBe('https://example.com/my-schema');
    });

    it('serializes as string', function (): void {
        $keyword = Id::create('https://example.com/schema');

        expect($keyword->jsonSerialize())->toBe('https://example.com/schema');
    });
})->covers(Id::class);

describe(class_basename(Anchor::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Anchor::create('my-anchor');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Anchor::name())->toBe('$anchor');
    });

    it('returns value correctly', function (): void {
        $keyword = Anchor::create('address');

        expect($keyword->value())->toBe('address');
    });

    it('serializes as string', function (): void {
        $keyword = Anchor::create('person');

        expect($keyword->jsonSerialize())->toBe('person');
    });
})->covers(Anchor::class);

describe(class_basename(Ref::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Ref::create('#/$defs/address');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Ref::name())->toBe('$ref');
    });

    it('returns value correctly', function (): void {
        $keyword = Ref::create('#/$defs/person');

        expect($keyword->value())->toBe('#/$defs/person');
    });

    it('serializes as string', function (): void {
        $keyword = Ref::create('https://example.com/schemas/address');

        expect($keyword->jsonSerialize())->toBe('https://example.com/schemas/address');
    });

    it('supports local references', function (): void {
        $keyword = Ref::create('#/$defs/name');

        expect($keyword->jsonSerialize())->toBe('#/$defs/name');
    });

    it('supports remote references', function (): void {
        $keyword = Ref::create('https://example.com/schemas/common.json#/$defs/id');

        expect($keyword->jsonSerialize())->toBe('https://example.com/schemas/common.json#/$defs/id');
    });
})->covers(Ref::class);

describe(class_basename(DynamicAnchor::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = DynamicAnchor::create('node');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(DynamicAnchor::name())->toBe('$dynamicAnchor');
    });

    it('returns value correctly', function (): void {
        $keyword = DynamicAnchor::create('items');

        expect($keyword->value())->toBe('items');
    });

    it('serializes as string', function (): void {
        $keyword = DynamicAnchor::create('extensible');

        expect($keyword->jsonSerialize())->toBe('extensible');
    });
})->covers(DynamicAnchor::class);

describe(class_basename(DynamicRef::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = DynamicRef::create('#node');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(DynamicRef::name())->toBe('$dynamicRef');
    });

    it('returns value correctly', function (): void {
        $keyword = DynamicRef::create('#items');

        expect($keyword->value())->toBe('#items');
    });

    it('serializes as string', function (): void {
        $keyword = DynamicRef::create('#extensible');

        expect($keyword->jsonSerialize())->toBe('#extensible');
    });
})->covers(DynamicRef::class);

describe(class_basename(Comment::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Comment::create('This is a comment');

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Comment::name())->toBe('$comment');
    });

    it('returns value correctly', function (): void {
        $keyword = Comment::create('Important note about this schema');

        expect($keyword->value())->toBe('Important note about this schema');
    });

    it('serializes as string', function (): void {
        $keyword = Comment::create('Schema version 2.0');

        expect($keyword->jsonSerialize())->toBe('Schema version 2.0');
    });
})->covers(Comment::class);

describe(class_basename(Vocab::class), function (): void {
    it('can create a required vocabulary', function (): void {
        $vocab = Vocab::create('https://json-schema.org/draft/2020-12/vocab/core', true);

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/core')
            ->and($vocab->required())->toBeTrue();
    });

    it('can create an optional vocabulary', function (): void {
        $vocab = Vocab::create('https://example.com/vocab/custom', false);

        expect($vocab->id())->toBe('https://example.com/vocab/custom')
            ->and($vocab->required())->toBeFalse();
    });
})->covers(Vocab::class);

describe(class_basename(Vocabulary::class), function (): void {
    it('implements Keyword interface', function (): void {
        $keyword = Vocabulary::create(
            Vocab::create('https://json-schema.org/draft/2020-12/vocab/core', true),
        );

        expect($keyword)->toBeInstanceOf(Keyword::class);
    });

    it('has correct name', function (): void {
        expect(Vocabulary::name())->toBe('$vocabulary');
    });

    it('returns vocabs as value', function (): void {
        $vocab1 = Vocab::create('https://json-schema.org/draft/2020-12/vocab/core', true);
        $vocab2 = Vocab::create('https://json-schema.org/draft/2020-12/vocab/validation', false);

        $keyword = Vocabulary::create($vocab1, $vocab2);

        expect($keyword->value())->toHaveCount(2)
            ->and($keyword->value()[0])->toBe($vocab1)
            ->and($keyword->value()[1])->toBe($vocab2);
    });

    it('serializes as object with URI keys and boolean values', function (): void {
        $keyword = Vocabulary::create(
            Vocab::create('https://json-schema.org/draft/2020-12/vocab/core', true),
            Vocab::create('https://json-schema.org/draft/2020-12/vocab/validation', true),
            Vocab::create('https://json-schema.org/draft/2020-12/vocab/format-annotation', false),
        );

        $serialized = $keyword->jsonSerialize();

        expect($serialized)->toBe([
            'https://json-schema.org/draft/2020-12/vocab/core' => true,
            'https://json-schema.org/draft/2020-12/vocab/validation' => true,
            'https://json-schema.org/draft/2020-12/vocab/format-annotation' => false,
        ]);
    });
})->covers(Vocabulary::class);
