<?php

namespace Tests\Laragen\Support\Doubles\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

class CustomWrapCollection extends ResourceCollection
{
    public static $wrap = 'users';

    public $collects = UserResource::class;
}
