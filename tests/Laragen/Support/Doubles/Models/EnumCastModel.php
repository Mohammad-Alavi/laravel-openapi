<?php

namespace Tests\Laragen\Support\Doubles\Models;

use Illuminate\Database\Eloquent\Model;

class EnumCastModel extends Model
{
    protected $casts = [
        'status' => StatusEnum::class,
    ];
}
