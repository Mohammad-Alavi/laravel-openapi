<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\E2E;

class E2EController
{
    // F3: FormRequest extraction + F4: JsonResource response
    public function store(E2EFormRequest $request): E2EResource
    {
        return new E2EResource(null);
    }

    // F4: JsonResource response detection (GET with no request body)
    public function show(): E2EResource
    {
        return new E2EResource(null);
    }

    // No typed return — should not get auto-response
    public function delete(): void
    {
    }
}
