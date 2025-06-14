<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictor;

interface ObjectRestrictor extends Restrictor, SharedRestrictor, AdditionalProperties, Properties, DependentRequired, MaxProperties, MinProperties, Required
{
}
