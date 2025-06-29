<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Paths\Operations;

class TestController
{
    public function actionWithTypeHintedParams(int $id, $unHinted, \stdClass $unknown): void
    {
    }
}
