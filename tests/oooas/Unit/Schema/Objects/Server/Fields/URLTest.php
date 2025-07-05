<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;

describe(class_basename(URL::class), function (): void {
    it('should create a URL field with a valid value', function (string $url): void {
        $sut = URL::create($url);

        expect($sut->value())->toBe($url);
    })->with([
        '/',
        'https://development.gigantic-server.com/v1',
        'https://{username}.gigantic-server.com:{port}/{basePath}',
        'http://{subdomain}.laragen.io/{path}',
        'https://laragen.io:8080/path/{variable}?query={var}',
        '/path/to/resource',
        '/{username}/{project}/file',
        '/users/{id}/profile',
        '/{basePath}/api/v1',
        'https://laragen.io/path/to/file?name={var}&id={id}',
        'https://laragen.io/path/to/{file}.html#section1',
        'https://{domain}.laragen.io/path',
        'https://{subdomain}.{region}.laragen.io',
        'https://{host}:{port}/api/{endpoint}',
        'https://laragen.io:{port}/data/{userId}',
        'http://development.gigantic-server.com/v1',
        'http://{subdomain}.laragen.io/{path}',
        'http://{subdomain}.laragen.io:{port}/{path}',
    ]);

    it('should throw an exception for an invalid URL', function (): void {
        expect(static fn (): URL => URL::create('invalid-url'))->toThrow(InvalidArgumentException::class);
    });

    it('should throw an exception for an empty URL', function (): void {
        expect(static fn (): URL => URL::create(''))->toThrow(InvalidArgumentException::class);
    });
})->covers(URL::class);
