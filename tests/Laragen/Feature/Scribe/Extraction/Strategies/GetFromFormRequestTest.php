<?php

use Knuckles\Scribe\Extracting\Strategies\BodyParameters\GetFromFormRequest as GetFromFormRequestBase;
use MohammadAlavi\Laragen\Scribe\Extraction\Strategies\GetFromFormRequest;

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
})->covers(GetFromFormRequest::class)->only();
