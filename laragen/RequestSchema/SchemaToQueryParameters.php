<?php

namespace MohammadAlavi\Laragen\RequestSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\QueryParameter;

final readonly class SchemaToQueryParameters
{
    /**
     * @return Parameter[]
     */
    public function convert(ObjectRestrictor $schema): array
    {
        $compiled = $schema->compile();

        if (!isset($compiled['properties']) || [] === $compiled['properties']) {
            return [];
        }

        /** @var string[] $requiredFields */
        $requiredFields = $compiled['required'] ?? [];

        $parameters = [];

        /** @var array<string, mixed> $properties */
        $properties = $compiled['properties'];

        foreach ($properties as $name => $propertySchema) {
            /** @var array<string, mixed> $propertySchema */
            $param = Parameter::query(
                $name,
                QueryParameter::create(Schema::from($propertySchema)),
            );

            if (in_array($name, $requiredFields, true)) {
                $param = $param->required();
            }

            $parameters[] = $param;
        }

        return $parameters;
    }
}
