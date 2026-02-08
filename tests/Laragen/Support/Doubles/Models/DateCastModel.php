<?php

namespace Tests\Laragen\Support\Doubles\Models;

use Illuminate\Database\Eloquent\Model;

class DateCastModel extends Model
{
    protected $casts = [
        'published_at' => 'datetime',
        'created_date' => 'date',
        'expires_at' => 'immutable_datetime',
        'birth_date' => 'immutable_date',
        'unix_time' => 'timestamp',
        'price' => 'decimal:2',
    ];
}
