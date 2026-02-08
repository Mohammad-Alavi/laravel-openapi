<?php

namespace Tests\oooas\Unit\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

describe(class_basename(Parameters::class), function (): void {
    it('can create with Parameter instances', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()))->required();
        $param2 = Parameter::query('page', QueryParameter::create(Schema::integer()));

        $parameters = Parameters::create($param1, $param2);

        expect($parameters->toArray())->toHaveCount(2)
            ->and($parameters->toArray()[0])->toBe($param1)
            ->and($parameters->toArray()[1])->toBe($param2);
    });

    it('can create with ParameterFactory instances', function (): void {
        $factory1 = new class extends ParameterFactory {
            public function component(): Parameter
            {
                return Parameter::path('id', PathParameter::create(Schema::string()))->required();
            }
        };

        $factory2 = new class extends ParameterFactory {
            public function component(): Parameter
            {
                return Parameter::query('page', QueryParameter::create(Schema::integer()));
            }
        };

        $parameters = Parameters::create($factory1, $factory2);

        expect($parameters->toArray())->toHaveCount(2);
    });

    it('removes duplicate Parameter instances with same name and location', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()))->required();
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer()))->required(); // Same name+location

        $parameters = Parameters::create($param1, $param2);

        expect($parameters->toArray())->toHaveCount(1);
    });

    it('keeps last occurrence when duplicates exist', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()))->required();
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer()))->required(); // Same name+location, different schema

        $parameters = Parameters::create($param1, $param2);

        // Should keep the last one (param2 with integer schema)
        $result = $parameters->toArray()[0];
        expect($result->compile()['schema']['type'])->toBe('integer');
    });

    it('does not consider parameters with different locations as duplicates', function (): void {
        $pathParam = Parameter::path('id', PathParameter::create(Schema::string()))->required();
        $queryParam = Parameter::query('id', QueryParameter::create(Schema::string())); // Same name, different location

        $parameters = Parameters::create($pathParam, $queryParam);

        expect($parameters->toArray())->toHaveCount(2);
    });

    it('can merge Parameters instances', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()))->required();
        $param2 = Parameter::query('page', QueryParameter::create(Schema::integer()));

        $parameters1 = Parameters::create($param1);
        $parameters2 = Parameters::create($param2);

        $merged = Parameters::create($parameters1, $parameters2);

        expect($merged->toArray())->toHaveCount(2);
    });

    it('removes duplicates when merging Parameters instances', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()))->required();
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer()))->required(); // Duplicate

        $parameters1 = Parameters::create($param1);
        $parameters2 = Parameters::create($param2);

        $merged = Parameters::create($parameters1, $parameters2);

        expect($merged->toArray())->toHaveCount(1);
    });

    it('keeps last occurrence when merging Parameters with duplicates', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()))->required();
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer()))->required();

        $parameters1 = Parameters::create($param1);
        $parameters2 = Parameters::create($param2);

        // parameters2 comes last, so its param should be kept
        $merged = Parameters::create($parameters1, $parameters2);

        $result = $merged->toArray()[0];
        expect($result->compile()['schema']['type'])->toBe('integer');
    });

    describe('querystring validation', function (): void {
        it('throws when more than one querystring parameter is provided', function (): void {
            // Note: querystring name is required per OAS but ignored during matching
            $qs1 = Parameter::querystring(
                '_qs',
                Content::create(ContentEntry::formUrlEncoded(MediaType::create())),
            );
            $qs2 = Parameter::querystring(
                '_qs2',
                Content::create(ContentEntry::formUrlEncoded(MediaType::create())),
            );

            expect(fn () => Parameters::create($qs1, $qs2))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'Only one querystring parameter is allowed',
                );
        });

        it('throws when querystring and query parameters are mixed', function (): void {
            $qs = Parameter::querystring(
                '_qs',
                Content::create(ContentEntry::formUrlEncoded(MediaType::create())),
            );
            $query = Parameter::query('filter', QueryParameter::create(Schema::string()));

            expect(fn () => Parameters::create($qs, $query))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'querystring and query parameters cannot appear together',
                );
        });

        it('allows single querystring parameter', function (): void {
            $qs = Parameter::querystring(
                '_qs',
                Content::create(ContentEntry::formUrlEncoded(MediaType::create())),
            );

            $parameters = Parameters::create($qs);

            expect($parameters->toArray())->toHaveCount(1);
        });
    });

    describe('path parameter required validation', function (): void {
        it('triggers notice for path parameter without required', function (): void {
            $noticeTriggered = false;
            $noticeMessage = '';

            set_error_handler(function (int $errno, string $errstr) use (&$noticeTriggered, &$noticeMessage): bool {
                if (E_USER_NOTICE === $errno) {
                    $noticeTriggered = true;
                    $noticeMessage = $errstr;
                }

                return true;
            });

            $pathParam = Parameter::path('id', PathParameter::create(Schema::string()));
            Parameters::create($pathParam);

            restore_error_handler();

            expect($noticeTriggered)->toBeTrue()
                ->and($noticeMessage)->toContain('OAS 3.2 compliance notice')
                ->and($noticeMessage)->toContain('"id"');
        });

        it('does not trigger notice for path parameter with required', function (): void {
            $noticeTriggered = false;

            set_error_handler(function (int $errno) use (&$noticeTriggered): bool {
                if (E_USER_NOTICE === $errno) {
                    $noticeTriggered = true;
                }

                return true;
            });

            $pathParam = Parameter::path('id', PathParameter::create(Schema::string()))->required();
            Parameters::create($pathParam);

            restore_error_handler();

            expect($noticeTriggered)->toBeFalse();
        });

        it('lists all non-compliant path parameters in notice', function (): void {
            $noticeMessage = '';

            set_error_handler(function (int $errno, string $errstr) use (&$noticeMessage): bool {
                if (E_USER_NOTICE === $errno) {
                    $noticeMessage = $errstr;
                }

                return true;
            });

            $param1 = Parameter::path('id', PathParameter::create(Schema::string()));
            $param2 = Parameter::path('slug', PathParameter::create(Schema::string()));
            Parameters::create($param1, $param2);

            restore_error_handler();

            expect($noticeMessage)->toContain('"id"')
                ->and($noticeMessage)->toContain('"slug"');
        });
    });
})->covers(Parameters::class);
