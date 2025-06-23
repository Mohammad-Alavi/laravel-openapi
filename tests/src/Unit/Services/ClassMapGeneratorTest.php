<?php

use MohammadAlavi\LaravelOpenApi\Services\ClassMapGenerator;
use Tests\IntegrationTestCase;
use Tests\src\Support\Doubles\Dummies\Services\ClassToBeFound;
use Tests\UnitTestCase;

describe('ClassMapGenerator', function (): void {
    dataset('expectations', function (): array {
        $div = DIRECTORY_SEPARATOR;

        return [
            [
                'key' => IntegrationTestCase::class,
                'value' => 'tests' . $div . 'IntegrationTestCase.php',
            ],
            [
                'key' => UnitTestCase::class,
                'value' => 'tests' . $div . 'UnitTestCase.php',
            ],
            [
                'key' => ClassToBeFound::class,
                'value' => 'tests' . $div . 'src' . $div . 'Support' . $div . 'Doubles' . $div . 'Dummies' . $div . 'Services' . $div . 'ClassToBeFound.php',
            ],
        ];
    });

    it('creates a class map from a directory', function (string $key, string $value): void {
        $dir = __DIR__ . '/../../../';
        $map = ClassMapGenerator::createMap($dir);

        expect($map)->toBeArray()
            ->and($map)->toHaveKey($key)
            ->and($map[$key])->toEndWith($value);
    })->with('expectations');

    it('creates a class map from an iterator', function (string $key, string $value): void {
        $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../../../'));
        $map = ClassMapGenerator::createMap($dir);

        expect($map)->toBeArray()
            ->and($map)->toHaveKey($key)
            ->and($map[$key])->toEndWith($value);
    })->with('expectations');

    it('finds classes in a given file', function (): void {
        $path = __DIR__ . '/../../../TestCase.php';
        expect(function () use ($path): void {
            ClassMapGenerator::createMap($path);
        })->toThrow(InvalidArgumentException::class, '"' . $path . '" is no directory');
    });
})->covers(ClassMapGenerator::class);
