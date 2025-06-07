<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Base;

final class Label extends Base
{
    protected function value(): string
    {
        return 'pipeDelimited';
    }
}
