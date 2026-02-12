<?php

namespace Tests\Laragen\Support\Doubles\E2E;

use Illuminate\Database\Eloquent\Model;

class E2EArticle extends Model
{
    protected $casts = [
        'title' => 'string',
        'is_published' => 'boolean',
    ];
}
