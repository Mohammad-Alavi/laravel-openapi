<?php

namespace Tests\Laragen\Support\Doubles\Transformers;

use League\Fractal\TransformerAbstract;
use Tests\Laragen\Support\Doubles\Models\BasicModel;

class BookTransformer extends TransformerAbstract
{
    public function transform(BasicModel $book): array
    {
        return [
            'id' => $book->id,
            'title' => $book->name,
            'is_published' => true,
        ];
    }
}
