<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\PathParameters\PathParameterAnalyzer;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use Tests\Laragen\Support\Doubles\ExtractController;

describe(class_basename(PathParameterAnalyzer::class) . ' integration', function (): void {
    it('analyzes path parameters from registered Laravel routes', function (): void {
        $route = RouteFacade::get('test-params/users/{id}', [ExtractController::class, 'simpleRules']);
        $route->whereUuid('id');

        $analyzer = app(PathParameterAnalyzer::class);
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1)
            ->and($params[0])->toBeInstanceOf(Parameter::class)
            ->and($params[0]->getName())->toBe('id')
            ->and($params[0]->isRequired())->toBeTrue();

        $compiled = $params[0]->compile();

        expect($compiled['schema']['type'])->toBe('string')
            ->and($compiled['schema']['format'])->toBe('uuid');
    });

    it('analyzes multiple constrained parameters', function (): void {
        $route = RouteFacade::get('test-params/teams/{team}/members/{member}', [ExtractController::class, 'simpleRules']);
        $route->whereUuid('team');
        $route->whereAlphaNumeric('member');

        $analyzer = app(PathParameterAnalyzer::class);
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(2);

        $teamCompiled = $params[0]->compile();
        $memberCompiled = $params[1]->compile();

        expect($params[0]->getName())->toBe('team')
            ->and($teamCompiled['schema']['format'])->toBe('uuid')
            ->and($params[1]->getName())->toBe('member')
            ->and($memberCompiled['schema']['pattern'])->toBe('[a-zA-Z0-9]+');
    });

    it('handles routes with enum constraints', function (): void {
        $route = RouteFacade::get('test-params/reports/{format}', [ExtractController::class, 'simpleRules']);
        $route->whereIn('format', ['pdf', 'csv', 'xlsx']);

        $analyzer = app(PathParameterAnalyzer::class);
        $params = $analyzer->analyze($route);

        expect($params)->toHaveCount(1);

        $compiled = $params[0]->compile();

        expect($compiled['schema']['enum'])->toBe(['pdf', 'csv', 'xlsx']);
    });
})->covers(PathParameterAnalyzer::class);
