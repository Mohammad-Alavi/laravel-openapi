<?php

namespace Tests\Laragen\Support\Doubles;

use Tests\Laragen\Support\Doubles\Models\BasicModel;

class EloquentController
{
    public function show(): BasicModel
    {
        return new BasicModel();
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
