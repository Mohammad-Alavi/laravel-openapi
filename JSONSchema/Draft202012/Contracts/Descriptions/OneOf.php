<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

interface OneOf
{
    /**
     * An <a href="https://json-schema.org/learn/glossary#instance">instance</a>
     * validates successfully against this keyword if it validates
     * successfully against exactly one schema defined by this keyword’s value.
     * It ensures that the instance validates against one and only one of the defined
     * <a href="https://json-schema.org/learn/glossary#schema">subschemas</a> within the oneOf array.
     * This behavior is akin to a logical “XOR” (exclusive OR) operation,
     * where only one condition needs to be met for validation to pass.
     * Remember, if any subschema within the
     * <a href="https://www.learnjsonschema.com/2020-12/applicator/oneof/">oneOf</a>
     * keyword passes validation or has a boolean true value, all the other subschemas
     * within oneOf must fail the validation for the overall validation of the oneOf keyword to be true.
     *
     * @see https://www.learnjsonschema.com/2020-12/applicator/oneof/
     */
    public function oneOf(JSONSchema ...$schema): static;
}
