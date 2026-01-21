<?php

namespace Tests\oooas\Unit\Support\Rules;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules\ComponentName;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules\JsonPointer;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules\URI;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

describe(class_basename(URI::class), function (): void {
    it('accepts valid absolute URI', function (): void {
        expect(fn () => new URI('https://example.com/path'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URI('http://localhost:8080/api'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URI('ftp://files.example.com'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('accepts valid relative URI', function (): void {
        expect(fn () => new URI('/path/to/resource'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URI('#/components/schemas/Pet'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URI('relative/path'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('accepts URI with query and fragment', function (): void {
        expect(fn () => new URI('https://example.com/path?query=value#fragment'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URI('/path?foo=bar'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('rejects empty URI', function (): void {
        expect(fn () => new URI(''))->toThrow(\InvalidArgumentException::class);
    });
})->covers(URI::class);

describe(class_basename(URL::class), function (): void {
    it('accepts valid URLs', function (): void {
        expect(fn () => new URL('https://example.com'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URL('http://localhost:8080'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URL('https://api.example.com/v1/users'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('rejects invalid URLs', function (): void {
        expect(fn () => new URL('not-a-url'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URL('/relative/path'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new URL(''))->toThrow(\InvalidArgumentException::class);
    });
})->covers(URL::class);

describe(class_basename(ComponentName::class), function (): void {
    it('accepts valid component names', function (): void {
        expect(fn () => new ComponentName('Pet'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('User123'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('my-component'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('my_component'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('my.component'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('X-Rate-Limit'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('rejects invalid component names', function (): void {
        expect(fn () => new ComponentName('Pet/Schema'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('name with spaces'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('name#hash'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName('name?query'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new ComponentName(''))->toThrow(\InvalidArgumentException::class);
    });
})->covers(ComponentName::class);

describe(class_basename(Validator::class), function (): void {
    it('can validate URL via static method', function (): void {
        expect(fn () => Validator::url('https://example.com'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => Validator::url('invalid'))->toThrow(\InvalidArgumentException::class);
    });

    it('can validate URI via static method', function (): void {
        expect(fn () => Validator::uri('https://example.com'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => Validator::uri('#/components/schemas/Pet'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => Validator::uri(''))->toThrow(\InvalidArgumentException::class);
    });

    it('can validate component name via static method', function (): void {
        expect(fn () => Validator::componentName('Pet'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => Validator::componentName('invalid/name'))->toThrow(\InvalidArgumentException::class);
    });

    it('can validate JSON Pointer via static method', function (): void {
        expect(fn () => Validator::jsonPointer(''))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => Validator::jsonPointer('/foo/bar'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => Validator::jsonPointer('invalid'))->toThrow(\InvalidArgumentException::class);
    });
})->covers(Validator::class);

describe(class_basename(JsonPointer::class), function (): void {
    it('accepts empty string (refers to whole document)', function (): void {
        expect(fn () => new JsonPointer(''))->not->toThrow(\InvalidArgumentException::class);
    });

    it('accepts valid JSON Pointers', function (): void {
        expect(fn () => new JsonPointer('/foo'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/foo/bar'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/foo/0'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/a~1b'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/c%d'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/e^f'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/g|h'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/i\\j'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/k"l'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/ '))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/m~0n'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('accepts escaped sequences (~0 for ~ and ~1 for /)', function (): void {
        expect(fn () => new JsonPointer('/~0'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/~1'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/~0~1'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/path~0with~1escapes'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('rejects non-empty pointer not starting with /', function (): void {
        expect(fn () => new JsonPointer('foo'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('foo/bar'))->toThrow(\InvalidArgumentException::class);
    });

    it('rejects invalid escape sequences (~ not followed by 0 or 1)', function (): void {
        expect(fn () => new JsonPointer('/~'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/~2'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/~a'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/foo~'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => new JsonPointer('/foo~bar'))->toThrow(\InvalidArgumentException::class);
    });
})->covers(JsonPointer::class);
