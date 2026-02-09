<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\EloquentModel;

use Illuminate\Database\Eloquent\Model;
use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Webmozart\Assert\Assert;

final readonly class EloquentModelSchemaBuilder implements ResponseSchemaBuilder
{
    public function __construct(
        private ModelSchemaInferrer $modelSchemaInferrer,
    ) {
    }

    public function build(mixed $detected): JSONSchema
    {
        Assert::string($detected);
        Assert::isAOf($detected, Model::class);

        return $this->modelSchemaInferrer->infer($detected);
    }
}
