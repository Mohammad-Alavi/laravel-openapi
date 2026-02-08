<?php

namespace Tests\Laragen\Support\Doubles\DataObjects;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public int $age,
        public float $score,
        public bool $is_active,
        public string|null $nickname,
        public AddressData $address,
        public UserStatus $status,
        public CarbonImmutable $created_at,
        #[DataCollectionOf(AddressData::class)]
        public array $addresses,
        public string|Optional $bio,
    ) {
    }
}
