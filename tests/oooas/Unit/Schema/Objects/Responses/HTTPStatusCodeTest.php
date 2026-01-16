<?php

namespace Tests\oooas\Unit\Schema\Objects\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;

describe(class_basename(HTTPStatusCode::class), function (): void {
    // 2xx Success
    it('can create ok status', function (): void {
        $status = HTTPStatusCode::ok();

        expect($status->value())->toBe('200')
            ->and((string) $status)->toBe('200');
    });

    it('can create created status', function (): void {
        $status = HTTPStatusCode::created();

        expect($status->value())->toBe('201');
    });

    it('can create accepted status', function (): void {
        $status = HTTPStatusCode::accepted();

        expect($status->value())->toBe('202');
    });

    it('can create noContent status', function (): void {
        $status = HTTPStatusCode::noContent();

        expect($status->value())->toBe('204');
    });

    it('can create resetContent status', function (): void {
        $status = HTTPStatusCode::resetContent();

        expect($status->value())->toBe('205');
    });

    it('can create partialContent status', function (): void {
        $status = HTTPStatusCode::partialContent();

        expect($status->value())->toBe('206');
    });

    // 3xx Redirection
    it('can create movedPermanently status', function (): void {
        $status = HTTPStatusCode::movedPermanently();

        expect($status->value())->toBe('301');
    });

    it('can create found status', function (): void {
        $status = HTTPStatusCode::found();

        expect($status->value())->toBe('302');
    });

    it('can create seeOther status', function (): void {
        $status = HTTPStatusCode::seeOther();

        expect($status->value())->toBe('303');
    });

    it('can create notModified status', function (): void {
        $status = HTTPStatusCode::notModified();

        expect($status->value())->toBe('304');
    });

    it('can create temporaryRedirect status', function (): void {
        $status = HTTPStatusCode::temporaryRedirect();

        expect($status->value())->toBe('307');
    });

    it('can create permanentRedirect status', function (): void {
        $status = HTTPStatusCode::permanentRedirect();

        expect($status->value())->toBe('308');
    });

    // 4xx Client Errors
    it('can create badRequest status', function (): void {
        $status = HTTPStatusCode::badRequest();

        expect($status->value())->toBe('400');
    });

    it('can create unauthorized status', function (): void {
        $status = HTTPStatusCode::unauthorized();

        expect($status->value())->toBe('401');
    });

    it('can create forbidden status', function (): void {
        $status = HTTPStatusCode::forbidden();

        expect($status->value())->toBe('403');
    });

    it('can create notFound status', function (): void {
        $status = HTTPStatusCode::notFound();

        expect($status->value())->toBe('404');
    });

    it('can create methodNotAllowed status', function (): void {
        $status = HTTPStatusCode::methodNotAllowed();

        expect($status->value())->toBe('405');
    });

    it('can create notAcceptable status', function (): void {
        $status = HTTPStatusCode::notAcceptable();

        expect($status->value())->toBe('406');
    });

    it('can create requestTimeout status', function (): void {
        $status = HTTPStatusCode::requestTimeout();

        expect($status->value())->toBe('408');
    });

    it('can create conflict status', function (): void {
        $status = HTTPStatusCode::conflict();

        expect($status->value())->toBe('409');
    });

    it('can create gone status', function (): void {
        $status = HTTPStatusCode::gone();

        expect($status->value())->toBe('410');
    });

    it('can create unsupportedMediaType status', function (): void {
        $status = HTTPStatusCode::unsupportedMediaType();

        expect($status->value())->toBe('415');
    });

    it('can create unprocessableEntity status', function (): void {
        $status = HTTPStatusCode::unprocessableEntity();

        expect($status->value())->toBe('422');
    });

    it('can create tooManyRequests status', function (): void {
        $status = HTTPStatusCode::tooManyRequests();

        expect($status->value())->toBe('429');
    });

    // 5xx Server Errors
    it('can create internalServerError status', function (): void {
        $status = HTTPStatusCode::internalServerError();

        expect($status->value())->toBe('500');
    });

    it('can create notImplemented status', function (): void {
        $status = HTTPStatusCode::notImplemented();

        expect($status->value())->toBe('501');
    });

    it('can create badGateway status', function (): void {
        $status = HTTPStatusCode::badGateway();

        expect($status->value())->toBe('502');
    });

    it('can create serviceUnavailable status', function (): void {
        $status = HTTPStatusCode::serviceUnavailable();

        expect($status->value())->toBe('503');
    });

    it('can create gatewayTimeout status', function (): void {
        $status = HTTPStatusCode::gatewayTimeout();

        expect($status->value())->toBe('504');
    });

    // Custom status codes
    it('can create custom status code', function (): void {
        $status = HTTPStatusCode::create('418');

        expect($status->value())->toBe('418');
    });

    it('validates status code format', function (): void {
        expect(fn () => HTTPStatusCode::create('invalid'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => HTTPStatusCode::create('99'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => HTTPStatusCode::create('600'))->toThrow(\InvalidArgumentException::class);
    });

    it('serializes as string', function (): void {
        $status = HTTPStatusCode::ok();

        expect($status->jsonSerialize())->toBe('200');
    });
})->covers(HTTPStatusCode::class);
