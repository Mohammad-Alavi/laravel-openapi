<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

uses()->beforeEach(function (): void {
    // Prevent Vite manifest errors during testing
    $this->withoutVite();
})->in('Feature');
