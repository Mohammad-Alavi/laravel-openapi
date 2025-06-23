<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

describe(class_basename(Keyword::class), function (): void {
    it(
        'can create a keyword',
        function (): void {
            $keyword = new class implements Keyword {
                public static function name(): string
                {
                    return 'keyword';
                }

                public function value(): string
                {
                    return 'value';
                }

                public function jsonSerialize(): string
                {
                    return $this->value();
                }
            };

            expect($keyword::name())->toBe('keyword');
        },
    );

    dataset('keywords', [
        [
            fn (): Keyword => new class implements Keyword {
                public static function name(): string
                {
                    return 'keywordA';
                }

                public function value(): string
                {
                    return 'valueA';
                }

                public function jsonSerialize(): string
                {
                    return $this->value();
                }
            },
            fn (): Keyword => new class implements Keyword {
                public static function name(): string
                {
                    return 'keywordB';
                }

                public function value(): array
                {
                    return ['x' => 'y'];
                }

                public function jsonSerialize(): array
                {
                    return $this->value();
                }
            },
            fn (): Keyword => new class implements Keyword {
                public static function name(): string
                {
                    return 'keywordC';
                }

                public function value(): int
                {
                    return 10;
                }

                public function jsonSerialize(): int
                {
                    return $this->value();
                }
            },
        ],
    ]);
})->covers(Keyword::class);
