<?php

use Illuminate\Foundation\Http\FormRequest;
use MohammadAlavi\Laragen\Scribe\Extraction\Extractor;
use MohammadAlavi\Laragen\Support\RouteSpecCollector;
use Workbench\App\Models\User;

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

    it('collects path params correctly', function () {
        $route = Route::get(
            'users/{user}/posts/{slug}/comments/{id}/{not_in_method_sig}/{not_in_method_sig_opt?}/{noTypeParam}/{user_id?}',
            [PathController::class, 'methodWithParams'],
        );
        $collector = new RouteSpecCollector();

        $endpointData = (new Extractor())->processRoute($route);
        //        dd($endpointData->toArray());
        $pathParams = $endpointData->toArray()['urlParameters'];
        //        $pathParams = $endpointData->toArray()['bodyParameters'];
        //        $pathParams = $collector->pathParams($route);

        expect($pathParams)->toBeArray()
            ->and($pathParams)->toHaveKeys(['id', 'slug'])
//            ->and($pathParams['user'])->toMatchArray(['type' => 'integer', 'required' => true])
            ->and($pathParams['slug'])->toMatchArray(['type' => 'string', 'required' => true])
//            ->and($pathParams['id'])->toMatchArray(['type' => 'integer', 'required' => true])
            ->and($pathParams['not_in_method_sig'])->toMatchArray(['type' => 'string', 'required' => true])
            ->and($pathParams['not_in_method_sig_opt'])->toMatchArray(['type' => 'string', 'required' => false])
            ->and($pathParams['noTypeParam'])->toMatchArray(['type' => 'string', 'required' => true])
            ->and($pathParams['user_id'])->toMatchArray(['type' => 'integer', 'required' => false]);
    })->skip();

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
            ->and($bodyParams['bar']['type'])->toBe('integer');
    });

    it('returns empty body params when no FormRequest', function () {
        $route = Route::post('test', [PathController::class, 'methodWithoutFormRequest']);
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
            'name' => ['required_without:email', 'string', 'max:255'],
            'email' => 'required_without:name|email|max:255',
            'password' => 'string|min:8|confirmed',
            'age' => ['nullable', 'integer', 'between:18,99'],
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
    public function methodWithParams(int $id, User $user, string $slug, $noTypeParam, BodyFormRequest $request, bool $flag, User $user_id)
    {
    }

    public function methodWithFormRequest(BodyFormRequest $request)
    {
    }

    public function methodWithoutFormRequest(int $id, User $user)
    {
    }
}
