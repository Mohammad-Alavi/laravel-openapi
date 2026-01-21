<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\ParameterLocation;

final class Parameters extends Generatable
{
    /**
     * @param (Parameter|ParameterFactory)[] $parameters
     */
    private function __construct(
        private readonly array $parameters,
    ) {
    }

    public static function create(Parameter|ParameterFactory|self ...$parameter): self
    {
        $selfInstances = array_filter(
            $parameter,
            static fn (Parameter|ParameterFactory|self $param): bool => $param instanceof self,
        );
        /** @var (Parameter|ParameterFactory)[] $selfParams */
        $selfParams = array_reduce(
            $selfInstances,
            static function (array $carry, self $param): array {
                return array_merge($carry, $param->toArray());
            },
            [],
        );
        $parameters = array_filter(
            $parameter,
            static fn (Parameter|ParameterFactory|self $param): bool => !($param instanceof self),
        );

        $merged = self::removeDuplicate(array_merge($parameters, $selfParams));
        self::validateQuerystringRules($merged);

        return new self($merged);
    }

    /**
     * Validates querystring parameter rules per OAS 3.2 spec.
     *
     * @param (Parameter|ParameterFactory)[] $parameters
     *
     * @throws \InvalidArgumentException if validation fails
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-locations
     */
    private static function validateQuerystringRules(array $parameters): void
    {
        $querystringCount = 0;
        $hasQueryParam = false;

        foreach ($parameters as $parameter) {
            $location = $parameter instanceof Parameter
                ? $parameter->getLocation()
                : $parameter->component()->getLocation();

            if (ParameterLocation::QUERYSTRING->value === $location) {
                ++$querystringCount;
            }

            if (ParameterLocation::QUERY->value === $location) {
                $hasQueryParam = true;
            }
        }

        // Rule: querystring MUST NOT appear more than once
        if ($querystringCount > 1) {
            throw new \InvalidArgumentException('Only one querystring parameter is allowed per operation/path-item. ' . "Found {$querystringCount} querystring parameters. " . 'See: https://spec.openapis.org/oas/v3.2.0#parameter-locations');
        }

        // Rule: querystring MUST NOT appear with query parameters
        if ($querystringCount > 0 && $hasQueryParam) {
            throw new \InvalidArgumentException('querystring and query parameters cannot appear together in the same operation/path-item. See: https://spec.openapis.org/oas/v3.2.0#parameter-locations');
        }

        // Warning: path parameters should have required=true per OAS 3.2
        self::warnAboutNonCompliantPathParams($parameters);
    }

    /**
     * Warns about path parameters without required=true.
     *
     * Per OAS 3.2: "If the parameter location is 'path', this field is REQUIRED
     * and its value MUST be true."
     *
     * This is a warning (not exception) to support frameworks like Laravel that
     * have optional route parameters. Strict OAS compliance requires all path
     * params to have required=true.
     *
     * @param (Parameter|ParameterFactory)[] $parameters
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
     */
    private static function warnAboutNonCompliantPathParams(array $parameters): void
    {
        $nonCompliantParams = [];

        foreach ($parameters as $parameter) {
            if ($parameter instanceof ParameterFactory) {
                $parameter = $parameter->component();
            }

            if ('path' === $parameter->getLocation() && !$parameter->isRequired()) {
                $nonCompliantParams[] = $parameter->getName();
            }
        }

        if ([] !== $nonCompliantParams) {
            @trigger_error(
                sprintf(
                    'OAS 3.2 compliance notice: Path parameter(s) "%s" should have required=true. '
                    . 'Per spec: "If the parameter location is \'path\', this field is REQUIRED and its value MUST be true." '
                    . 'See: https://spec.openapis.org/oas/v3.2.0#parameter-object',
                    implode('", "', $nonCompliantParams),
                ),
                E_USER_NOTICE,
            );
        }
    }

    /**
     * @return (Parameter|ParameterFactory)[]
     */
    public function toArray(): array
    {
        return $this->parameters;
    }

    /**
     * A unique parameter is defined by a combination of a name and location.
     * When duplicates exist, the last occurrence is kept (later items override earlier ones).
     *
     * @param (Parameter|ParameterFactory)[] $parameters
     *
     * @return (Parameter|ParameterFactory)[]
     */
    private static function removeDuplicate(array $parameters): array
    {
        $uniqueParameters = [];
        foreach ($parameters as $parameter) {
            if ($parameter instanceof Parameter) {
                $key = $parameter->getName() . ':' . $parameter->getLocation();
            } elseif ($parameter instanceof ParameterFactory) {
                $key = $parameter->component()->getName() . ':' . $parameter->component()->getLocation();
            } else {
                continue;
            }

            $uniqueParameters[$key] = $parameter;
        }

        return array_values($uniqueParameters);
    }
}
