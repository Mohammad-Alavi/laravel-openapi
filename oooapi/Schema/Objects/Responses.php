<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class Responses extends ExtensibleObject
{
    /** @var Response[]|ReusableResponseFactory[] */
    private array $responses = [];

    public static function create(Response|ReusableResponseFactory|self ...$response): self
    {
        $selfResponses = collect($response)
            ->filter(static fn ($param): bool => $param instanceof self)
            ->map(static fn ($param): array => $param->all())
            ->flatten();

        $responses = collect($response)
            ->reject(static fn ($param): bool => $param instanceof self)
            ->merge($selfResponses)
            ->toArray();

        $instance = new self();
        $instance->responses = $responses;

        return $instance;
    }

    public function all(): array
    {
        return $this->responses;
    }

    protected function toArray(): array
    {
        if ([] === $this->responses) {
            // TODO: allow providing default response. maybe somehow via service container?
            $this->responses = [Response::default()];
        }

        $responses = [];
        foreach ($this->responses as $response) {
            if ($response instanceof ReusableResponseFactory) {
                $responseInstance = $response->build();
                $responses[$responseInstance->key()] = $response::ref();

                continue;
            }
            $responses[$response->key()] = $response;
        }

        return Arr::filter($responses);
    }
}
