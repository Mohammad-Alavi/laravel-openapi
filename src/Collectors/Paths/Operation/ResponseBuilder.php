<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors\Paths\Operation;

use MohammadAlavi\LaravelOpenApi\Attributes\Response as ResponseAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Reusable;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInformation;
use MohammadAlavi\ObjectOrientedOAS\Objects\Response;

class ResponseBuilder
{
    public function build(RouteInformation $routeInformation): array
    {
        return $routeInformation->actionAttributes
            ->filter(static fn (object $attribute) => $attribute instanceof ResponseAttribute)
            ->map(static function (ResponseAttribute $responseAttribute) {
                $factory = app($responseAttribute->factory);
                $response = $factory->build();

                if ($factory instanceof Reusable) {
                    return Response::ref('#/components/responses/' . $response->objectId)
                        ->statusCode($responseAttribute->statusCode)
                        ->description($responseAttribute->description);
                }

                return $response;
            })
            ->values()
            ->toArray();
    }
}
