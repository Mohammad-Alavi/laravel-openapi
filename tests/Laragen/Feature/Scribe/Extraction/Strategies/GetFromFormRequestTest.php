<?php

use Knuckles\Scribe\Extracting\Strategies\BodyParameters\GetFromFormRequest as GetFromFormRequestBase;
use MohammadAlavi\Laragen\Scribe\Extraction\Strategies\GetFromFormRequest;
use Tests\Laragen\Feature\Support\Doubles\ExtractController;

describe(class_basename(GetFromFormRequest::class), function () {
    it('extends correct parent', function () {
        expect(
            is_a(
                GetFromFormRequest::class,
                GetFromFormRequestBase::class,
                true,
            ),
        )->toBeTrue();
    });

    it('can extract request rules', function () {
        $route = Route::post('test', [ExtractController::class, 'formRequest']);

        $rules = app(GetFromFormRequest::class)->rules($route);

        expect($rules)->toBe([
            'foo' => ['string', 'min:3'],
            'bar' => ['integer'],
            'name' => ['required_without:email', 'string', 'max:255'],
            'email' => ['required_without:name', 'email', 'max:255'],
            'password' => ['string', 'min:8', 'confirmed'],
            'age' => ['nullable', 'integer', 'between:18,99'],
        ]);
    });

    it('can extract inline rules from request facade', function () {
        $route = Route::post('test', [ExtractController::class, 'fromInlineRequestFacade']);

        $rules = app(GetFromFormRequest::class)->rules($route);

        expect($rules)->toBe([
            'content' => ['string', 'required', 'min:100'],
            'extra' => ['string'],
        ]);
    });

    it('can extract inline rules from request validate method', function () {
        $route = Route::post('test', [ExtractController::class, 'fromInlineRequestValidateMethod']);

        $rules = app(GetFromFormRequest::class)->rules($route);

        expect($rules)->toBe([
            'address' => ['string', 'nullable', 'min:10'],
            'bar' => ['array', 'nullable', 'min:1'],
        ]);
    });

    it('can extract inline rules from validator facade', function () {
        $route = Route::post('test', [ExtractController::class, 'fromInlineValidatorFacade']);

        $rules = app(GetFromFormRequest::class)->rules($route);

        expect($rules)->toBe([
            'foo' => ['string', 'min:3'],
            'bar' => ['integer'],
            'name' => ['required_without:email', 'string', 'max:255'],
            'email' => ['required_without:name', 'email', 'max:255'],
            'password' => ['string', 'min:8', 'confirmed'],
            'age' => ['nullable', 'integer', 'between:18,99'],
            'title' => ['string', 'required', 'max:400'],
            'author_display_name' => ['string'],
        ]);
    });

    it('inline rules override request rules', function () {
        $route = Route::post('test', [ExtractController::class, 'fromRequestAndInline']);

        $rules = app(GetFromFormRequest::class)->rules($route);

        expect($rules)->toBe([
            'foo' => ['string', 'min:3'],
            'bar' => ['array', 'nullable', 'min:1'],
            'name' => ['required_without:email', 'string', 'max:255'],
            'email' => ['required_without:name', 'email', 'max:255'],
            'password' => ['string', 'min:8', 'confirmed'],
            'age' => ['nullable', 'integer', 'between:18,99'],
            'address' => ['string', 'nullable', 'min:10'],
        ]);
    });
})->covers(GetFromFormRequest::class);
