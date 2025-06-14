<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

interface AllOf
{
    /**
     * An <a href="https://json-schema.org/learn/glossary#instance">instance</a>
     * validates successfully against this keyword if it validates
     * successfully against all schemas defined by this keyword’s value.
     * It’s essentially a logical “AND” operation where all conditions must be met for validation to pass.
     * Remember, if any <a href="https://json-schema.org/learn/glossary#schema">subschema</a>
     * within the <a href="https://www.learnjsonschema.com/2020-12/applicator/allof/">allOf</a>
     * keyword fails validation or has a boolean false value, the entire validation will always fail.
     *
     * @see https://www.learnjsonschema.com/2020-12/applicator/allof/
     */
    public function allOf(JSONSchema ...$builder): static;
}
