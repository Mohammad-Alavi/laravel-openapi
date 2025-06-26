<?php

declare(strict_types=1);

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\MethodExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RequestExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RuntimeExpressionAbstract;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\StatusCodeExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\URLExpression;

describe('MethodExpression', function () {
    it('creates a valid MethodExpression', function (): void {
        $expr = MethodExpression::create();
        expect($expr)->toBeInstanceOf(MethodExpression::class)
            ->and($expr->value())->toBe('$method');
    });

    it('throws on invalid value', function (): void {
        expect(fn () => MethodExpression::create('invalid'))->toThrow(InvalidArgumentException::class);
    });
});

describe('URLExpression', function () {
    it('creates a valid URLExpression', function (): void {
        $expr = URLExpression::create();
        expect($expr)->toBeInstanceOf(URLExpression::class)
            ->and($expr->value())->toBe('$url');
    });

    it('throws on invalid value', function (): void {
        expect(fn () => URLExpression::create('bad'))->toThrow(InvalidArgumentException::class);
    });
});

describe('StatusCodeExpression', function () {
    it('creates a valid StatusCodeExpression', function (): void {
        $expr = StatusCodeExpression::create();
        expect($expr)->toBeInstanceOf(StatusCodeExpression::class)
            ->and($expr->value())->toBe('$statusCode');
    });

    it('throws on invalid value', function (): void {
        expect(fn () => StatusCodeExpression::create('bad'))->toThrow(InvalidArgumentException::class);
    });
});

// describe('RequestExpression', function () {
//    // Create a concrete class for testing the abstract RequestExpression
//    $concrete = new class('$request.header') extends RequestExpression {
//        public static function create(string $value): static
//        {
//            return new static($value);
//        }
//    };
//
//    it('accepts valid prefix', function (): void use ($concrete) {
//        expect($concrete->value())->toBe('$request.header');
//    });
//
//    it('throws on invalid prefix', function (): void {
//        $make = fn () => new class('bad') extends RequestExpression {
//            public static function create(string $value): static
//            {
//                return new static($value);
//            }
//        };
//        expect($make)->toThrow(InvalidArgumentException::class);
//    });
//
//    it('returns correct source', function (): void use ($concrete) {
//        expect($concrete->getSource())->toBe('header');
//    });
// });
//
// describe('RuntimeExpressionAbstract', function () {
//    // Create a concrete class for testing the abstract RuntimeExpressionAbstract
//    $concrete = new class('foo') extends RuntimeExpressionAbstract {
//        protected function validateExpression(string $expression): void {}
//        public static function create(string $value): static
//        {
//            return new static($value);
//        }
//    };
//
//    it('stores and returns value', function () use ($concrete): void {
//        expect($concrete->value())->toBe('foo');
//    });
// });
