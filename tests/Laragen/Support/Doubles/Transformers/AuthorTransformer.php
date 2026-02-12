<?php

namespace Tests\Laragen\Support\Doubles\Transformers;

use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    public function transform(array $author): array
    {
        return [
            'name' => $author['name'],
            'bio' => $author['bio'],
        ];
    }
}
