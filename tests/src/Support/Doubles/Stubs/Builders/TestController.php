<?php

namespace Tests\src\Support\Doubles\Stubs\Builders;

class TestController
{
    public function actionWithTypeHintedParams(int $id, $unHinted, \stdClass $unknown): void
    {
    }
}
