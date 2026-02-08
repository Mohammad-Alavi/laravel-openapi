<?php

namespace Tests\Laragen\Support\Doubles;

use Tests\Laragen\Support\Doubles\Collections\UserCollection;
use Tests\Laragen\Support\Doubles\Resources\PostCollection;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

class ResourceCollectionController
{
    public function index(): UserCollection
    {
        return new UserCollection(collect());
    }

    public function posts(): PostCollection
    {
        return new PostCollection(collect());
    }

    public function singleResource(): UserResource
    {
        return new UserResource(null);
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
