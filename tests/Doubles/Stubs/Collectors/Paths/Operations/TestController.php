<?php

namespace Tests\Doubles\Stubs\Collectors\Paths\Operations;

class TestController
{
    public function actionWithTypeHintedParams(int $id, $unHinted, \stdClass $unknown): void
    {
    }
}
