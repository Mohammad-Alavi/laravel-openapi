<?php

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\PathParameters\PathParameterAnalyzer;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;

describe(class_basename(PathParameterAnalyzer::class), function (): void {
    it('returns empty array for route without path parameters', function (): void {
        $route = new Route(['GET'], 'users', fn () => 'ok');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toBe([]);
    });

    it('returns string parameter by default', function (): void {
        $route = new Route(['GET'], 'users/{name}', fn () => 'ok');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1)
            ->and($params[0])->toBeInstanceOf(Parameter::class)
            ->and($params[0]->getName())->toBe('name')
            ->and($params[0]->getLocation())->toBe('path')
            ->and($params[0]->isRequired())->toBeTrue();

        $compiled = $params[0]->compile();

        expect($compiled['schema'])->toBe(['type' => 'string']);
    });

    it('detects optional parameter', function (): void {
        $route = new Route(['GET'], 'users/{name?}', fn () => 'ok');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1)
            ->and($params[0]->isRequired())->toBeFalse();
    });

    it('detects integer constraint from whereNumber', function (): void {
        $route = new Route(['GET'], 'users/{id}', fn () => 'ok');
        $route->where('id', '[0-9]+');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema'])->toBe(['type' => 'integer']);
    });

    it('detects UUID constraint from whereUuid', function (): void {
        $route = new Route(['GET'], 'users/{id}', fn () => 'ok');
        $route->whereUuid('id');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['type'])->toBe('string')
            ->and($compiled['schema']['format'])->toBe('uuid');
    });

    it('detects alpha constraint from whereAlpha', function (): void {
        $route = new Route(['GET'], 'categories/{slug}', fn () => 'ok');
        $route->whereAlpha('slug');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['type'])->toBe('string')
            ->and($compiled['schema']['pattern'])->toBe('[a-zA-Z]+');
    });

    it('detects alphanumeric constraint from whereAlphaNumeric', function (): void {
        $route = new Route(['GET'], 'posts/{slug}', fn () => 'ok');
        $route->whereAlphaNumeric('slug');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['type'])->toBe('string')
            ->and($compiled['schema']['pattern'])->toBe('[a-zA-Z0-9]+');
    });

    it('detects enum constraint from whereIn', function (): void {
        $route = new Route(['GET'], 'items/{type}', fn () => 'ok');
        $route->whereIn('type', ['user', 'admin', 'guest']);

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['enum'])->toBe(['user', 'admin', 'guest']);
    });

    it('detects ULID constraint from whereUlid', function (): void {
        $route = new Route(['GET'], 'records/{id}', fn () => 'ok');
        $route->whereUlid('id');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['type'])->toBe('string')
            ->and($compiled['schema']['pattern'])->toBe('[0-7][0-9a-hjkmnp-tv-zA-HJKMNP-TV-Z]{25}');
    });

    it('handles multiple path parameters', function (): void {
        $route = new Route(['GET'], 'teams/{team}/users/{user}', fn () => 'ok');
        $route->whereUuid('team');
        $route->where('user', '[0-9]+');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(2);

        $teamCompiled = $params[0]->compile();
        $userCompiled = $params[1]->compile();

        expect($params[0]->getName())->toBe('team')
            ->and($teamCompiled['schema']['format'])->toBe('uuid')
            ->and($params[1]->getName())->toBe('user')
            ->and($userCompiled['schema']['type'])->toBe('integer');
    });

    it('resolves integer type from model binding with integer key', function (): void {
        $route = new Route(
            ['GET'],
            'items/{basic}',
            ['uses' => 'Tests\Laragen\Support\Doubles\PathParameters\ModelBindingController@showBasic'],
        );

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema'])->toBe(['type' => 'integer']);
    });

    it('resolves string type from model binding with string key', function (): void {
        $route = new Route(
            ['GET'],
            'items/{stringKey}',
            ['uses' => 'Tests\Laragen\Support\Doubles\PathParameters\ModelBindingController@showStringKey'],
        );

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema'])->toBe(['type' => 'string']);
    });

    it('falls back to string when controller parameter has no type hint', function (): void {
        $route = new Route(
            ['GET'],
            'items/{item}',
            ['uses' => 'Tests\Laragen\Support\Doubles\PathParameters\ModelBindingController@showNoTypeHint'],
        );

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema'])->toBe(['type' => 'string']);
    });

    it('prefers explicit where constraint over model binding', function (): void {
        $route = new Route(
            ['GET'],
            'items/{basic}',
            ['uses' => 'Tests\Laragen\Support\Doubles\PathParameters\ModelBindingController@showBasic'],
        );
        $route->whereUuid('basic');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['type'])->toBe('string')
            ->and($compiled['schema']['format'])->toBe('uuid');
    });

    it('falls back to string for unknown constraint patterns', function (): void {
        $route = new Route(['GET'], 'items/{code}', fn () => 'ok');
        $route->where('code', '[A-Z]{3}-\d{4}');

        $analyzer = new PathParameterAnalyzer();
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['type'])->toBe('string')
            ->and($compiled['schema']['pattern'])->toBe('[A-Z]{3}-\d{4}');
    });
})->covers(PathParameterAnalyzer::class);
