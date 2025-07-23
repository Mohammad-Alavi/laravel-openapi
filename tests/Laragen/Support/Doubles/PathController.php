<?php

namespace Tests\Laragen\Support\Doubles;

use Workbench\App\Models\User;

class PathController
{
    public function methodWithParams(int $id, User $user, string $slug, $noTypeParam, BodyFormRequest $request, bool $flag, User $user_id)
    {
    }

    public function methodWithFormRequest(BodyFormRequest $request)
    {
    }

    public function methodWithoutFormRequest(int $id, User $user)
    {
    }
}
