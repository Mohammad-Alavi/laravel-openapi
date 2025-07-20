<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Dialect\Draft202012;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Def;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\Dependency;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Items;
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
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Title;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocab;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(Draft202012::class), function (): void {
    it('can create id keyword', function (): void {
        $id = Draft202012::id('https://laragen.io/schema.json');

        expect($id)->toBeInstanceOf(Id::class)
            ->and($id->value())->toBe('https://laragen.io/schema.json');
    });

    it('can create schema keyword', function (): void {
        $schema = Draft202012::schema('https://json-schema.org/draft/2020-12/schema');

        expect($schema)->toBeInstanceOf(Schema::class)
            ->and($schema->value())->toBe('https://json-schema.org/draft/2020-12/schema');
    });

    it('can create type keyword', function (): void {
        $type = Draft202012::type('string');

        expect($type)->toBeInstanceOf(Type::class)
            ->and($type->value())->toBe('string');
    });

    it('can create format keyword', function (): void {
        $format = Draft202012::format(StringFormat::DATE);

        expect($format)->toBeInstanceOf(Format::class)
            ->and($format->value())->toBe('date');
    });

    it('can create minLength keyword', function (): void {
        $minLength = Draft202012::minLength(5);

        expect($minLength)->toBeInstanceOf(MinLength::class)
            ->and($minLength->value())->toBe(5);
    });

    it('can create maxLength keyword', function (): void {
        $maxLength = Draft202012::maxLength(10);

        expect($maxLength)->toBeInstanceOf(MaxLength::class)
            ->and($maxLength->value())->toBe(10);
    });

    it('can create pattern keyword', function (): void {
        $pattern = Draft202012::pattern('^[a-zA-Z0-9]*$');

        expect($pattern)->toBeInstanceOf(Pattern::class)
            ->and($pattern->value())->toBe('^[a-zA-Z0-9]*$');
    });

    it('can create properties keyword', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();
        $property = Property::create('name', $descriptor);
        $properties = Draft202012::properties($property);

        expect($properties)->toBeInstanceOf(Properties::class)
            ->and(\Safe\json_encode($properties))->toBe(\Safe\json_encode(['name' => $descriptor]));
    });

    it('can create ref keyword', function (): void {
        $ref = Draft202012::ref('#/definitions/xyz');

        expect($ref)->toBeInstanceOf(Ref::class)
            ->and($ref->value())->toBe('#/definitions/xyz');
    });

    it('can create comment keyword', function (): void {
        $comment = Draft202012::comment('some comment');

        expect($comment)->toBeInstanceOf(Comment::class)
            ->and($comment->value())->toBe('some comment');
    });

    it('can create defs keyword', function (): void {
        $def = Def::create('foo', LooseFluentDescriptor::withoutSchema());
        $defs = Draft202012::defs($def);

        expect($defs)->toBeInstanceOf(Defs::class)
            ->and(
                Safe\json_encode($defs),
            )->toBe(
                Safe\json_encode(['foo' => $def->value()]),
            );
    });

    it('can create anchor keyword', function (): void {
        $anchor = Draft202012::anchor('anchor1');

        expect($anchor)->toBeInstanceOf(Anchor::class)
            ->and($anchor->value())->toBe('anchor1');
    });

    it('can create dynamicAnchor keyword', function (): void {
        $dynA = Draft202012::dynamicAnchor('da');

        expect($dynA)->toBeInstanceOf(DynamicAnchor::class)
            ->and($dynA->value())->toBe('da');
    });

    it('can create dynamicRef keyword', function (): void {
        $dynR = Draft202012::dynamicRef('#/da');

        expect($dynR)->toBeInstanceOf(DynamicRef::class)
            ->and($dynR->value())->toBe('#/da');
    });

    it('can create vocabulary keyword', function (): void {
        $v1 = Vocab::create('k1', true);
        $v2 = Vocab::create('k2', false);

        $vocab = Draft202012::vocabulary($v1, $v2);

        expect($vocab)->toBeInstanceOf(Vocabulary::class)
            ->and(
                Safe\json_encode($vocab),
            )->toBe(
                Safe\json_encode(['k1' => true, 'k2' => false]),
            );
    });

    it('can create unevaluatedProperties keyword', function (): void {
        $desc = LooseFluentDescriptor::withoutSchema();

        $up = Draft202012::unevaluatedProperties($desc);

        expect($up)->toBeInstanceOf(UnevaluatedProperties::class)
            ->and($up->value())->toBe($desc);
    });

    it('can create unevaluatedItems keyword', function (): void {
        $desc = LooseFluentDescriptor::withoutSchema();

        $ui = Draft202012::unevaluatedItems($desc);

        expect($ui)->toBeInstanceOf(UnevaluatedItems::class)
            ->and($ui->value())->toBe($desc);
    });

    it('can create exclusiveMaximum keyword', function (): void {
        $em = Draft202012::exclusiveMaximum(5.5);

        expect($em)->toBeInstanceOf(ExclusiveMaximum::class)
            ->and($em->value())->toBe(5.5);
    });

    it('can create exclusiveMinimum keyword', function (): void {
        $emn = Draft202012::exclusiveMinimum(1.1);

        expect($emn)->toBeInstanceOf(ExclusiveMinimum::class)
            ->and($emn->value())->toBe(1.1);
    });

    it('can create maximum keyword', function (): void {
        $max = Draft202012::maximum(10.2);

        expect($max)->toBeInstanceOf(Maximum::class)
            ->and($max->value())->toBe(10.2);
    });

    it('can create minimum keyword', function (): void {
        $min = Draft202012::minimum(2.3);

        expect($min)->toBeInstanceOf(Minimum::class)
            ->and($min->value())->toBe(2.3);
    });

    it('can create multipleOf keyword', function (): void {
        $mo = Draft202012::multipleOf(0.5);

        expect($mo)->toBeInstanceOf(MultipleOf::class)
            ->and($mo->value())->toBe(0.5);
    });

    it('can create maxContains, maxItems, minContains, minItems, uniqueItems', function (): void {
        $mc = Draft202012::maxContains(3);
        $mi = Draft202012::minContains(1);
        $mas = Draft202012::maxItems(4);
        $mis = Draft202012::minItems(2);
        $ui = Draft202012::uniqueItems(true);

        expect($mc)->toBeInstanceOf(MaxContains::class)->and($mc->value())->toBe(3)
            ->and($mi)->toBeInstanceOf(MinContains::class)->and($mi->value())->toBe(1)
            ->and($mas)->toBeInstanceOf(MaxItems::class)->and($mas->value())->toBe(4)
            ->and($mis)->toBeInstanceOf(MinItems::class)->and($mis->value())->toBe(2)
            ->and($ui)->toBeInstanceOf(UniqueItems::class)->and($ui->value())->toBe(true);
    });

    it('can create items keyword', function (): void {
        $desc = LooseFluentDescriptor::withoutSchema();

        $items = Draft202012::items($desc);

        expect($items)->toBeInstanceOf(Items::class)
            ->and($items->value())->toBe($desc);
    });

    it('can create allOf, anyOf, oneOf', function (): void {
        $d1 = LooseFluentDescriptor::withoutSchema();
        $d2 = LooseFluentDescriptor::withoutSchema();

        $ao = Draft202012::allOf($d1, $d2);
        $ay = Draft202012::anyOf($d1);
        $oo = Draft202012::oneOf($d2);

        expect($ao)->toBeInstanceOf(AllOf::class)
            ->and($ay)->toBeInstanceOf(AnyOf::class)
            ->and($oo)->toBeInstanceOf(OneOf::class);
    });

    it('can create additionalProperties keyword', function (): void {
        $desc = LooseFluentDescriptor::withoutSchema();

        $ap = Draft202012::additionalProperties($desc);

        expect($ap)->toBeInstanceOf(AdditionalProperties::class)
            ->and($ap->value())->toBe($desc);
    });

    it('can create dependentRequired keyword', function (): void {
        $dep = Dependency::create('key', 'a', 'b');

        $dr = Draft202012::dependentRequired($dep);

        expect($dr)->toBeInstanceOf(DependentRequired::class);
    });

    it('can create maxProperties, minProperties, required', function (): void {
        $mp = Draft202012::maxProperties(5);
        $ip = Draft202012::minProperties(2);
        $req = Draft202012::required('f1', 'f2');

        expect($mp)->toBeInstanceOf(MaxProperties::class)->and($mp->value())->toBe(5)
            ->and($ip)->toBeInstanceOf(MinProperties::class)->and($ip->value())->toBe(2)
            ->and($req)->toBeInstanceOf(Required::class);
    });

    it('can create default, deprecated, description, examples, readOnly, writeOnly, title, const, enum keywords', function (): void {
        $defv = Draft202012::default(100);
        $depd = Draft202012::deprecated();
        $descr = Draft202012::description('desc');
        $ex = Draft202012::examples(1, 2, 3);
        $ro = Draft202012::readOnly();
        $wo = Draft202012::writeOnly();
        $title = Draft202012::title('t');
        $cst = Draft202012::const('v');
        $enm = Draft202012::enum('e1', 'e2');

        expect($defv)->toBeInstanceOf(DefaultValue::class)
            ->and($depd)->toBeInstanceOf(Deprecated::class)
            ->and($descr)->toBeInstanceOf(Description::class)
            ->and($ex)->toBeInstanceOf(Examples::class)
            ->and($ro)->toBeInstanceOf(IsReadOnly::class)
            ->and($wo)->toBeInstanceOf(IsWriteOnly::class)
            ->and($title)->toBeInstanceOf(Title::class)
            ->and($cst)->toBeInstanceOf(Constant::class)
            ->and($enm)->toBeInstanceOf(Enum::class);
    });
})->covers(Draft202012::class);
