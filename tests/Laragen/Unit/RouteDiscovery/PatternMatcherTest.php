<?php

use MohammadAlavi\Laragen\RouteDiscovery\PatternMatcher;

describe(class_basename(PatternMatcher::class), function (): void {
    it('matches a simple wildcard pattern', function (): void {
        $matcher = new PatternMatcher(['api/*'], []);

        expect($matcher->matches('api/users'))->toBeTrue()
            ->and($matcher->matches('api/users/1'))->toBeTrue()
            ->and($matcher->matches('web/users'))->toBeFalse();
    });

    it('matches exact path without wildcard', function (): void {
        $matcher = new PatternMatcher(['api/health'], []);

        expect($matcher->matches('api/health'))->toBeTrue()
            ->and($matcher->matches('api/health/check'))->toBeFalse();
    });

    it('excludes matching patterns', function (): void {
        $matcher = new PatternMatcher(['api/*'], ['api/admin/*']);

        expect($matcher->matches('api/users'))->toBeTrue()
            ->and($matcher->matches('api/admin/settings'))->toBeFalse();
    });

    it('requires at least one include pattern to match', function (): void {
        $matcher = new PatternMatcher(['api/*', 'v2/*'], []);

        expect($matcher->matches('api/users'))->toBeTrue()
            ->and($matcher->matches('v2/users'))->toBeTrue()
            ->and($matcher->matches('web/home'))->toBeFalse();
    });

    it('excludes takes precedence over includes', function (): void {
        $matcher = new PatternMatcher(['api/*'], ['api/telescope/*', 'api/horizon/*']);

        expect($matcher->matches('api/users'))->toBeTrue()
            ->and($matcher->matches('api/telescope/requests'))->toBeFalse()
            ->and($matcher->matches('api/horizon/jobs'))->toBeFalse();
    });

    it('handles leading slashes consistently', function (): void {
        $matcher = new PatternMatcher(['api/*'], []);

        expect($matcher->matches('/api/users'))->toBeTrue()
            ->and($matcher->matches('api/users'))->toBeTrue();
    });

    it('matches with empty exclude list', function (): void {
        $matcher = new PatternMatcher(['*'], []);

        expect($matcher->matches('anything/at/all'))->toBeTrue();
    });

    it('rejects everything with empty include list', function (): void {
        $matcher = new PatternMatcher([], []);

        expect($matcher->matches('api/users'))->toBeFalse();
    });

    it('handles multi-segment wildcards', function (): void {
        $matcher = new PatternMatcher(['api/v1/*'], []);

        expect($matcher->matches('api/v1/users'))->toBeTrue()
            ->and($matcher->matches('api/v1/users/1/posts'))->toBeTrue()
            ->and($matcher->matches('api/v2/users'))->toBeFalse();
    });
})->covers(PatternMatcher::class);
