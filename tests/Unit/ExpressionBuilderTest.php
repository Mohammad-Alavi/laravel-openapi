<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\ExpressionBuilder\ExpressionBuilder;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\ExpressionBuilder\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\ExpressionBuilder\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request\RequestBodyExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request\RequestPathExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\URLExpression;

describe(class_basename(ExpressionBuilder::class), function (): void {
    it(class_basename(ExpressionBuilder::class) . ' raw value', function (): void {
        expect(ExpressionBuilder::of('$url')->value())->toBe('$url');
    });

    it('creates ' . class_basename(PathParameter::class), function (): void {
        $param = PathParameter::create('id');

        expect($param->name())->toBe('id');
    });

    it('creates ' . class_basename(QueryParameter::class), function (): void {
        $param = QueryParameter::create('key', 'value');
        expect($param->name())->toBe('key')
            ->and($param->value())->toBe('value');
    });

    it('appends path parameters', function (): void {
        $builder = ExpressionBuilder::of('base')
            ->append(PathParameter::create('id'))
            ->append(PathParameter::create('sub'));

        expect($builder->value())->toBe('base/id/sub');
    });

    it('prepends path parameters', function (): void {
        $builder = ExpressionBuilder::of('base')
            ->append(PathParameter::create('id'))
            ->prepend(PathParameter::create('root'));

        expect($builder->value())->toBe('root/base/id');
    });

    it('appends query parameters', function (): void {
        $builder = ExpressionBuilder::of('base')
            ->append(QueryParameter::create('a', '1'))
            ->append(QueryParameter::create('b', '2'));

        expect($builder->value())->toBe('base?a=1&b=2');
    });

    it('prepends query parameters', function (): void {
        $builder = ExpressionBuilder::of('base')
            ->append(QueryParameter::create('a', '1'))
            ->prepend(QueryParameter::create('b', '2'));

        expect($builder->value())->toBe('base?b=2&a=1');
    });

    it('combines path and query parameters', function (): void {
        $builder = ExpressionBuilder::of('root')
            ->append(PathParameter::create('id'))
            ->append(QueryParameter::create('q', 'test'));

        expect($builder->value())->toBe('root/id?q=test');
    });

    it('appends raw string values', function (): void {
        expect(ExpressionBuilder::of('base')->append('-suffix')->value())->toBe('base-suffix');
    });

    it('prepends raw string values', function (): void {
        expect(ExpressionBuilder::of('base')->prepend('prefix-')->value())->toBe('prefix-base');
    });

    it('trims initial string in of()', function (): void {
        expect(ExpressionBuilder::of('  trimmed  ')->value())->toBe('trimmed');
    });

    it('accepts RuntimeExpressionAbstract as initial value', function (): void {
        $expr = URLExpression::create();
        expect(ExpressionBuilder::of($expr)->value())->toBe('$url');
    });

    //   'https://example.com?transactionId={$request.body#/id}&email={$request.body#/email}':
    it('accepts RuntimeExpressionAbstract as value', function (): void {
        $builder = ExpressionBuilder::of('https://example.com')
            ->append('/data')
            ->append(
                QueryParameter::create(
                    'transactionId',
                    RequestBodyExpression::create('id'),
                ),
            )->append(
                PathParameter::create(
                    RequestPathExpression::create('eventType'),
                ),
            )->append(
                QueryParameter::create(
                    'email',
                    RequestBodyExpression::create('email'),
                ),
            );

        expect($builder->value())
            ->toBe(
                'https://example.com/data/{$request.path.eventType}?transactionId={$request.body#/id}&email={$request.body#/email}',
            );
    });
})->covers(ExpressionBuilder::class);
