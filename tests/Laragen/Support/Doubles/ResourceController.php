<?php

namespace Tests\Laragen\Support\Doubles;

use Tests\Laragen\Support\Doubles\Resources\PostResource;
use Tests\Laragen\Support\Doubles\Resources\UnwrappedResource;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

class ResourceController
{
    public function show(): UserResource
    {
        return new UserResource(null);
    }

    public function showPost(): PostResource
    {
        return new PostResource(null);
    }

    public function showUnwrapped(): UnwrappedResource
    {
        return new UnwrappedResource(null);
    }

    public function noReturn()
    {
        return response()->json([]);
    }
}
