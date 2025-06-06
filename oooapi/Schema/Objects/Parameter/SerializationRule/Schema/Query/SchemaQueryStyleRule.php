<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Query;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\SpaceDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\SchemaCommonRule;

interface SchemaQueryStyleRule extends SchemaCommonRule
{
    public function style(DeepObject|Form|PipeDelimited|SpaceDelimited $style): static;
}
