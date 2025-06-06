<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Label;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Matrix;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\Simple;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SchemaFields\Style\Styles\SpaceDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Cookie\SchemaCookieStyleRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Header\SchemaHeaderStyleRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Path\SchemaPathStyleRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\Schema\Query\SchemaQueryStyleRule;

interface PossibleType extends SchemaCookieStyleRule, SchemaHeaderStyleRule, SchemaPathStyleRule, SchemaQueryStyleRule
{
    public function style(DeepObject|Form|Label|Matrix|PipeDelimited|Simple|SpaceDelimited $style): static;
}
