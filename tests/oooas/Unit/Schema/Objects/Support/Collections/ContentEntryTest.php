<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\ContentEntry;

describe(class_basename(ContentEntry::class), function (): void {
    it(
        'can be created with predefined media types',
        function (string $method, $expected): void {
            $mediaType = MediaType::create();
            /** @var ContentEntry $contentEntry */
            $contentEntry = ContentEntry::$method($mediaType);

            expect($contentEntry)->key()->toBe($expected)
                ->value()->toBe($mediaType);
        },
    )->with([
        'json' => ['json', 'application/json'],
        'pdf' => ['pdf', 'application/pdf'],
        'jpeg' => ['jpeg', 'image/jpeg'],
        'png' => ['png', 'image/png'],
        'calendar' => ['calendar', 'text/calendar'],
        'plainText' => ['plainText', 'text/plain'],
        'xml' => ['xml', 'text/xml'],
        'formUrlEncoded' => ['formUrlEncoded', 'application/x-www-form-urlencoded'],
    ]);
})->covers(ContentEntry::class);
