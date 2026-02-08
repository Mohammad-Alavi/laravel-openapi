<?php

namespace Tests\Laragen\Support\Doubles;

use Tests\Laragen\Support\Doubles\DataObjects\UserData;

class SpatieDataController
{
    public function show(): UserData
    {
        return UserData::from([]);
    }

    public function noReturn()
    {
        return response()->json([]);
    }

    public function stringReturn(): string
    {
        return 'hello';
    }
}
