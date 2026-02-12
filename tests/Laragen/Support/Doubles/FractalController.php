<?php

namespace Tests\Laragen\Support\Doubles;

use Tests\Laragen\Support\Doubles\Transformers\BookTransformer;

class FractalController
{
    public function show()
    {
        $transformer = new BookTransformer();

        return fractal(null, $transformer)->toArray();
    }

    public function showWithClassConst()
    {
        return fractal(null, BookTransformer::class)->toArray();
    }

    public function noTransformer()
    {
        return response()->json([]);
    }
}
