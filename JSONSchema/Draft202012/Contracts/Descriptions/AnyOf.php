<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

interface AnyOf
{
    /**
     * An <a href="https://json-schema.org/learn/glossary#instance">instance</a>
     * validates successfully against this keyword if it validates
     * successfully against at least one schema defined by this keyword’s value.
     * It allows you to define multiple schemas, and if the data validates against any one of them,
     * the validation passes.
     * Remember, if any <a href="https://json-schema.org/learn/glossary#schema">subschema</a>
     * within the <a href="https://www.learnjsonschema.com/2020-12/applicator/anyof/">anyOf</a>
     * keyword passes validation or has a boolean true value, the overall result of anyOf is considered valid.
     *
     * @see https://www.learnjsonschema.com/2020-12/applicator/anyof/
     */
    public function anyOf(JSONSchema ...$builder): static;
}
