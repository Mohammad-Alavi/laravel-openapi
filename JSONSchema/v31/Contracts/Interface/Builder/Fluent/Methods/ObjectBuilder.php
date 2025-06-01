<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Methods\Required;

interface ObjectBuilder extends SharedBuilder, AdditionalProperties, Properties, DependentRequired, MaxProperties, MinProperties, Required
{
}
