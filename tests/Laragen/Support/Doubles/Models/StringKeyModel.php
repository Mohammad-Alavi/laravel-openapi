<?php

namespace Tests\Laragen\Support\Doubles\Models;

use Illuminate\Database\Eloquent\Model;

class StringKeyModel extends Model
{
    protected $keyType = 'string';
}
