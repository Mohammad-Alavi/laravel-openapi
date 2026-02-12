<?php

namespace Tests\Laragen\Support\Doubles\PathParameters;

use Tests\Laragen\Support\Doubles\Models\BasicModel;
use Tests\Laragen\Support\Doubles\Models\StringKeyModel;

class ModelBindingController
{
    public function showBasic(BasicModel $basic): void
    {
    }

    public function showStringKey(StringKeyModel $stringKey): void
    {
    }

    public function showNoTypeHint($item): void
    {
    }
}
