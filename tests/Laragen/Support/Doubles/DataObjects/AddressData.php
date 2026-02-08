<?php

namespace Tests\Laragen\Support\Doubles\DataObjects;

use Spatie\LaravelData\Data;

class AddressData extends Data
{
    public function __construct(
        public string $street,
        public string $city,
        public string $zip,
    ) {
    }
}
