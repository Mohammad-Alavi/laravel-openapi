<?php

namespace Tests\Laragen\Support\Doubles;

use Tests\Laragen\Support\Doubles\DataObjects\UserData;

class SpatieDataRequestController
{
    public function store(UserData $data): void
    {
    }

    public function show(int $id): UserData
    {
        return UserData::from([]);
    }

    public function noParams(): void
    {
    }

    public function stringParam(string $name): void
    {
    }
}
