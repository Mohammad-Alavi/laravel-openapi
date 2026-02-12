<?php

namespace MohammadAlavi\Laragen\RequestSchema\SpatieData;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\Laragen\ResponseSchema\SpatieData\SpatieDataSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Compilable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final readonly class SpatieDataRequestSchemaBuilder implements RequestSchemaBuilder
{
    public function __construct(
        private SpatieDataSchemaBuilder $schemaBuilder,
    ) {
    }

    public function build(mixed $detected, Route $route): RequestSchemaResult
    {
        /** @var class-string $detected */
        $jsonSchema = $this->schemaBuilder->build($detected);

        Assert::isInstanceOf($jsonSchema, Compilable::class);

        $compiled = $jsonSchema->compile();

        return new RequestSchemaResult(
            schema: Schema::from($compiled),
            target: RequestTarget::BODY,
            encoding: ContentEncoding::JSON,
        );
    }
}
