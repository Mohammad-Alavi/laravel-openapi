<?php

use Illuminate\Contracts\Console\Kernel;
use MohammadAlavi\Laragen\Providers\LaragenServiceProvider;

describe(class_basename(LaragenServiceProvider::class), function () {
    it('registers generator command', function () {
        $serviceProvider = new LaragenServiceProvider(app());
        $serviceProvider->register();

        $commands = app(Kernel::class)->all();
        expect($commands)->toHaveKey('laragen:generate');
    });
})->covers(LaragenServiceProvider::class);
