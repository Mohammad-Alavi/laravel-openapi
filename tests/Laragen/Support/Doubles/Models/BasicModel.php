<?php

namespace Tests\Laragen\Support\Doubles\Models;

use Illuminate\Database\Eloquent\Model;

class BasicModel extends Model
{
    protected $casts = [
        'name' => 'string',
        'age' => 'integer',
        'score' => 'float',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return 'test';
    }
}
