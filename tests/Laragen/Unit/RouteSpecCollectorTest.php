<?php

use Illuminate\Foundation\Http\FormRequest;
use MohammadAlavi\Laragen\Support\RouteSpecCollector;

describe(class_basename(RouteSpecCollector::class), function () {
    it('normalizes php types correctly', function () {
        $map = [
            'int' => 'integer',
            'integer' => 'integer',
            'bool' => 'boolean',
            'boolean' => 'boolean',
            'array' => 'array',
            'string' => 'string',
            'mixed' => 'string',
            'OBJECT' => 'string',
        ];
        foreach ($map as $input => $expected) {
            $collector = new RouteSpecCollector();
            $actual = $collector->normalizePhpType($input);

            expect($actual)->toBe($expected);
        }
    });

    it(
        'covers basic ruleSetToSchema type detection',
        function (array $rules, string $type, string|null $format) {
            $collector = new RouteSpecCollector();
            $schema = $collector->ruleSetToSchema($rules);

            expect($schema['type'])->toBe($type);
            if ($format) {
                expect($schema['format'])->toBe($format);
            } else {
                expect(isset($schema['format']))->toBeFalse();
            }
            // expect(isset($schema['example']))->toBeTrue();
        },
    )->with([
        'integer' => [['integer'], 'integer', null],
        'numeric' => [['numeric'], 'integer', null],
        'boolean' => [['boolean'], 'boolean', null],
        'array' => [['array'], 'array', null],
        'date' => [['date'], 'string', 'date'],
        'email' => [['email'], 'string', 'email'],
        'string' => [['string'], 'string', null],
        'file' => [['file'], 'string', 'binary'],
        'image' => [['image'], 'string', 'binary'],
        'mimes' => [['mimes:jpg'], 'string', 'binary'],
    ]);

    it('applies rule modifiers correctly', function () {
        $collector = new RouteSpecCollector();
        $schema = $collector->ruleSetToSchema(
            [
                'string',
                'min:3',
                'max:10',
                'between:4,8',
                'in:foo,bar',
                'regex:/[A-Z]+/',
            ],
        );

        expect($schema['type'])->toBe('string')
            ->and($schema['minimum'])->toBe(4)
            ->and($schema['maximum'])->toBe(8)
            ->and($schema['enum'])->toEqual(['foo', 'bar'])
            ->and($schema['pattern'])->toEqual('/[A-Z]+/');
    });

    it('collects path params correctly', function () {
        $route = Route::get('users/{id}/posts/{slug}', [PathController::class, 'methodWithParams']);
        $collector = new RouteSpecCollector();

        $pathParams = $collector->pathParams($route);

        expect($pathParams)->toBeArray()
            ->and($pathParams)->toHaveKeys(['id', 'slug'])
            ->and($pathParams['id'])->toMatchArray(['type' => 'integer'])
            ->and($pathParams['slug'])->toMatchArray(['type' => 'string']);
    });

    it('skips FormRequest in path params', function () {
        $route = Route::get('test/{foo}', [PathController::class, 'methodWithFormRequest']);
        $collector = new RouteSpecCollector();

        $pathParams = $collector->pathParams($route);

        expect($pathParams)->toBe(
            [
                'foo' => [
                    'type' => 'string',
                    'required' => true,
                ],
            ],
        );
    });

    it('collects body params correctly', function () {
        $route = Route::post('test', [PathController::class, 'methodWithFormRequest']);
        $collector = new RouteSpecCollector();

        $bodyParams = $collector->bodyParams($route);

        expect($bodyParams)->toBeArray()
            ->and($bodyParams)->toHaveKeys(['foo', 'bar'])
            ->and($bodyParams['foo']['type'])->toBe('string')
            ->and($bodyParams['foo']['minimum'])->toBe(3)
            ->and($bodyParams['bar']['type'])->toBe('integer');
    });

    it('returns empty body params when no FormRequest', function () {
        $route = Route::post('test', [PathController::class, 'methodWithParams']);
        $collector = new RouteSpecCollector();

        $bodyParams = $collector->bodyParams($route);

        expect($bodyParams)->toBeArray()->and($bodyParams)->toBeEmpty();
    });
})->covers(RouteSpecCollector::class);

class BodyFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'foo' => ['string', 'min:3'],
            'bar' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function attributes(): array
    {
        return [];
    }
}

class PathController
{
    public function methodWithParams(int $id, string $slug, bool $flag)
    {
    }

    public function methodWithFormRequest(BodyFormRequest $request)
    {
    }
}
