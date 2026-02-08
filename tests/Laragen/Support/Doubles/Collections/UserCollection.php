<?php

namespace Tests\Laragen\Support\Doubles\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

class UserCollection extends ResourceCollection
{
    public $collects = UserResource::class;
}
